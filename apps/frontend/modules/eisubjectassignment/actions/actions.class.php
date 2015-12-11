
<?php

/**
 * eisubjectassignment actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubjectassignment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjectassignmentActions extends sfActionsKalifast
{
    //Recherche du ticket avec vérification de l'appartenance au projet
    public function findSubject(EiProjet $ei_project , $request){
        $this->subject_id = $request->getParameter('subject_id');
        if($this->subject_id==null)
            $this->forward404 ('Subject not found with Id : '.$this->subject_id);
        $this->ei_subject=Doctrine_Core::getTable('EiSubject')->findOneByIdAndProjectIdAndProjectRef(
            $this->subject_id,$ei_project->getProjectId(),$ei_project->getRefId());
        if($this->ei_subject==null)
            $this->forward404 ('Subject does not exist or not belong to the current project');
    }
    //Recherche d'un utilisateur avec vérification de l'appartenance au projet
    public function findUserById(EiProjet $ei_project , $request){
        $this->guard_id = $request->getParameter('guard_id');
        if($this->guard_id==null)
            $this->forward404 ('User not found');
        $this->guard_user=Doctrine_Core::getTable('sfGuardUser')->findOneById($this->guard_id );
        if($this->guard_user==null)
            $this->forward404 ('User not found with giving parameter');
        //Vérification de l'appartenance de l'utilisateur au projet courant
        $this->forward404Unless(Doctrine_Core::getTable('EiProjectUser')->getEiProjet(
            $ei_project->getProjectId(), $ei_project->getRefId(), $this->guard_user->getEiUser()), "Acces denied.");
    }
    //Recherche d'un utilisateur avec vérification de l'appartenance au projet
    public function findUserByUsername(EiProjet $ei_project , $request){
        $this->guard_username = $request->getParameter('guard_username');
        if($this->guard_username==null)
            $this->forward404 ('User not found');
        $this->guard_user=Doctrine_Core::getTable('sfGuardUser')->findOneByUsername($this->guard_username );
        if($this->guard_user==null)
            $this->forward404 ('User not found with giving parameter');
        //Vérification de l'appartenance de l'utilisateur au projet courant
        $this->forward404Unless(Doctrine_Core::getTable('EiProjectUser')->getEiProjet(
            $ei_project->getProjectId(), $ei_project->getRefId(), $this->guard_user->getEiUser()), "Acces denied.");
    }

    public function isUserAlreadyAssignToSubject(EiSubject $ei_subject, sfGuardUser $guard){
        $relation=Doctrine_Core::getTable('EiSubjectAssignment')->findOneBySubjectIdAndGuardId(
            $ei_subject->getId(),$guard->getId());
        if($relation==null) return false;
        return true;
    }

    public function createAssignmentOnSubject(EiSubject $ei_subject, sfGuardUser $guard, sfGuardUser $author){
        $assignment=new EiSubjectAssignment();
        $assignment->setSubjectId($ei_subject->getId());
        $assignment->setGuardId($guard->getId());
        $assignment->setAuthorId($author->getId());
        $assignment->save();
    }
    public function removeAssignmentOnSubject(EiSubject $ei_subject, sfGuardUser $guard, sfGuardUser $author){
        $assignment=Doctrine_Core::getTable('EiSubjectAssignment')->findOneBySubjectIdAndGuardId(
            $ei_subject->getId(),$guard->getId());
        $assignment->delete();
    }

//Ajout d'une assignation de sujet à un utilisateur
    public function executeAdd(sfWebRequest $request)
    {
        $this->success=false;
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du profil
        //Recherche du sujet (avec vérification du sujet au projet)
        $this->findSubject($this->ei_project , $request);
        //Recherche de l'utilisateur (En se rassurant qu'il appartient au projet
        $this->findUserByUsername($this->ei_project , $request);
        $this->connectedGuard=$this->getUser()->getGuardUser();
        //On vérifie que l'utilisateur n'est pas déjà assigné au sujet en question
        if(!$this->isUserAlreadyAssignToSubject($this->ei_subject, $this->guard_user))
        {
            $this->createAssignmentOnSubject($this->ei_subject, $this->guard_user,$this->connectedGuard);
            $this->success=true;
            $eisubjectassignment_assignUser=$this->urlParameters;
            $eisubjectassignment_assignUser['ei_subject']=$this->ei_subject;
            $eisubjectassignment_assignUser['guard_user']=$this->guard_user;
            /* Gestion de l'envoi d'email après l'assignation */ 
            //Changement de l'état de l'utilisateur 
            $message = Swift_Message::newInstance()
              ->setFrom(sfConfig::get('app_sf_guard_plugin_default_from_email', 'admin.kalifast@eisge.com'))
              ->setTo($this->guard_user->getEmailAddress())
              ->setSubject('Hello ! '.$this->connectedGuard->getUsername().' assigned a intervention to you.')
              ->setPriority(1)
              ->setBody($this->getPartial('eisubjectassignment/assignNotification', array(
                  'guard_user' => $this->guard_user,
                  'connectedGuard' => $this->connectedGuard,
                  'ei_subject' => $this->ei_subject,
                  'urlParameters' => $this->urlParameters)))
              ->setContentType('text/html') ;
            $this->getMailer()->send($message); 
            /* Fin du block d'envoi d'email */
            
            //retour de la reponse du process (avec le partiel de la nouvelle assignation)
            return $this->renderText(json_encode(array(
                'html' => $this->getPartial('eisubjectassignment/assignUser' ,$eisubjectassignment_assignUser),
                'success' => $this->success)));
        }
        else{
            //Retour de la reponse json avec notification de l'echecs
            return $this->renderText(json_encode(array(
                'html' => 'User is already assign to subject',
                'success' => $this->success)));
        }

        return sfView::NONE;

    }



    //Retirer l'assignation d'un sujet à un utilisateur
    public function executeRemove(sfWebRequest $request)
    {
        $this->success=false;
        //Récupération du projet
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project); //Récupération du profil
        //Recherche du sujet (avec vérification du sujet au projet)
        $this->findSubject($this->ei_project , $request);
        //Recherche de l'utilisateur (En se rassurant qu'il appartient au projet
        $this->findUserById($this->ei_project , $request);
        $this->connectedGuard=$this->getUser()->getGuardUser();
        //On vérifie que l'utilisateur est déjà assigné au sujet en question
        if($this->isUserAlreadyAssignToSubject($this->ei_subject, $this->guard_user))
        {
            $this->removeAssignmentOnSubject($this->ei_subject, $this->guard_user,$this->connectedGuard);
            $eisubjectassignment_removedUser=$this->urlParameters;
            $eisubjectassignment_removedUser['ei_subject']=$this->ei_subject;
            $eisubjectassignment_removedUser['guard_user']=$this->guard_user;
            $this->success=true;
            //retour de la reponse du process (avec le partiel de la nouvelle assignation)
            return $this->renderText(json_encode(array(
                'html' => $this->getPartial('eisubjectassignment/removedUser' ,$eisubjectassignment_removedUser),
                'success' => $this->success)));
        }
        else{
            //Retour de la reponse json avec notification de l'echecs
            return $this->renderText(json_encode(array(
                'html' => 'User is not assign to subject',
                'success' => $this->success)));
        }

        return sfView::NONE;
    }

    public function executeNew(sfWebRequest $request)
    {
        $this->form = new EiSubjectAssignmentForm();
    }

    public function executeCreate(sfWebRequest $request)
    {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->form = new EiSubjectAssignmentForm();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request)
    {
        $this->forward404Unless($ei_subject_assignment = Doctrine_Core::getTable('EiSubjectAssignment')->find(array($request->getParameter('guard_id'),
            $request->getParameter('subject_id'))), sprintf('Object ei_subject_assignment does not exist (%s).', $request->getParameter('guard_id'),
            $request->getParameter('subject_id')));
        $this->form = new EiSubjectAssignmentForm($ei_subject_assignment);
    }

    public function executeUpdate(sfWebRequest $request)
    {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($ei_subject_assignment = Doctrine_Core::getTable('EiSubjectAssignment')->find(array($request->getParameter('guard_id'),
            $request->getParameter('subject_id'))), sprintf('Object ei_subject_assignment does not exist (%s).', $request->getParameter('guard_id'),
            $request->getParameter('subject_id')));
        $this->form = new EiSubjectAssignmentForm($ei_subject_assignment);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request)
    {
        $request->checkCSRFProtection();

        $this->forward404Unless($ei_subject_assignment = Doctrine_Core::getTable('EiSubjectAssignment')->find(array($request->getParameter('guard_id'),
            $request->getParameter('subject_id'))), sprintf('Object ei_subject_assignment does not exist (%s).', $request->getParameter('guard_id'),
            $request->getParameter('subject_id')));
        $ei_subject_assignment->delete();

        $this->redirect('eisubjectassignment/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid())
        {
            $ei_subject_assignment = $form->save();

            $this->redirect('eisubjectassignment/edit?guard_id='.$ei_subject_assignment->getGuardId().'&subject_id='.$ei_subject_assignment->getSubjectId());
        }
    }
}
