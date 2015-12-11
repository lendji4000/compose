<?php

/**
 * eisubjectmigration actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubjectmigration
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjectmigrationActions extends sfActionsKalifast {
     

    //Recherche d'un sujet
    public function checkSubject(sfWebRequest $request, EiProjet $ei_project) {
        $this->subject_id = $request->getParameter('subject_id');
        if ($this->subject_id == null)
            $this->forward404('Missing subject parameters');
        //Recherche du sujet tout en s'assurant qu'elle corresponde au projet courant 
        $this->ei_subject_with_relation = Doctrine_Core::getTable('EiSubject')
                ->getSubject($ei_project->getProjectId(),$ei_project->getRefId(),$this->subject_id);
        $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneById($this->subject_id);
        if ($this->ei_subject == null)
            $this->forward404('Subject not found');
    }

    public function executeIndex(sfWebRequest $request) {
        $this->ei_subject_migrations = Doctrine_Core::getTable('EiSubjectMigration')
                ->createQuery('a')
                ->execute();
    }

    public function executeShow(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkSubject($request, $this->ei_project); 
        $this->ei_subject_migration = Doctrine_Core::getTable('EiSubjectMigration')->find(array($request->getParameter('id')));
        $this->forward404Unless($this->ei_subject_migration);
        
        //Gestion des fichiers attachés
        $this->guardUser=$this->getUser()->getGuardUser();
        $this->subjectAttachments=Doctrine_Core::getTable('EiSubjectAttachment')->findBySubjectIdAndType(
                    $this->ei_subject->getId(), sfConfig::get('app_bug_attachment_migration')); 
        //Formulaire d'ajout d'un nouveau attachment 
        $attach = new EiSubjectAttachment();
        $attach->setSubjectId($this->ei_subject->getId());
        $attach->setType(sfConfig::get('app_bug_attachment_migration'));
        $attach->setAuthorId($this->guardUser->getId()); 
        $this->newAttachForm = new EiSubjectAttachmentForm($attach); 
    }

    public function executeNew(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkSubject($request, $this->ei_project); 
        //On vérifie qu'il n'existe pas déjà une migration définie sur le sujet 
        $exist_migration=$this->ei_subject->getSubjectMigration();
        if($exist_migration!=null)
        $this->redirect($this->generateUrl('subject_migration_edit', array(
                        'subject_id' => $this->subject_id,
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'id' => $exist_migration->getId(),
                        'action' => 'show'
            )));
        //Sinon on continue le process
        $subjectMigration = new EiSubjectMigration();
        $subjectMigration->setSubjectId($this->ei_subject->getId());
        $this->form = new EiSubjectMigrationForm($subjectMigration);
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkSubject($request, $this->ei_project); 
        //On vérifie qu'il n'existe pas déjà une migration définie sur le sujet 
        $exist_migration=$this->ei_subject->getSubjectMigration();
        if($exist_migration!=null)
        $this->redirect($this->generateUrl('subject_migration_edit', array(
                        'subject_id' => $this->subject_id,
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'id' => $exist_migration->getId(),
                        'action' => 'show'
            )));
        //Sinon on continue le process
        $subjectMigration = new EiSubjectMigration();
        $subjectMigration->setSubjectId($this->ei_subject->getId());
        $this->form = new EiSubjectMigrationForm($subjectMigration);

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($ei_subject_migration = Doctrine_Core::getTable('EiSubjectMigration')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_migration does not exist (%s).', $request->getParameter('id')));
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkSubject($request, $this->ei_project); 
        $this->form = new EiSubjectMigrationForm($ei_subject_migration);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($ei_subject_migration = Doctrine_Core::getTable('EiSubjectMigration')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_migration does not exist (%s).', $request->getParameter('id')));
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkSubject($request, $this->ei_project); 
        $this->form = new EiSubjectMigrationForm($ei_subject_migration);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkSubject($request, $this->ei_project);
        $this->forward404Unless($ei_subject_migration = Doctrine_Core::getTable('EiSubjectMigration')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_migration does not exist (%s).', $request->getParameter('id')));
        $ei_subject_migration->delete();

        $this->redirect('eisubjectmigration/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $ei_subject_migration = $form->save();
            $this->getUser()->setFlash('alert_migration_form',
                    array('title' => 'Success' ,
                            'class' => 'alert-success' ,
                            'text' => 'Well done ...'));
            $subject_migration_edit = $this->urlParameters;
            $subject_migration_edit['subject_id']=$this->subject_id;
            $subject_migration_edit['id']=$ei_subject_migration->getId();
            $subject_migration_edit['action']='show';
            $this->redirect($this->generateUrl('subject_migration_edit', $subject_migration_edit)); 
        }
        else{
            $this->getUser()->setFlash('alert_migration_form', array('title' => 'Error',
                    'class' => 'alert-danger',
                    'text' => 'Please select a file ...'));
                $subject_show = $this->urlParameters;
                $subject_show['subject_id'] = $this->subject_id;
                $this->redirect($this->generateUrl('subject_show', $subject_show)); 
    }
    }

}
