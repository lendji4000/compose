<?php

/**
 * eisubjectattachment actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubjectattachment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjectattachmentActions extends sfActionsKalifast {

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

    //Recherche du fichier attaché 
    public function checkAttachment(sfWebRequest $request){
        $this->attachment_id = $request->getParameter('id');
        if ($this->attachment_id == null)
            $this->forward404('Missing Attachment  parameters');
        //Recherche du fichier attaché
        $this->ei_subject_attachment = Doctrine_Core::getTable('EiSubjectAttachment')->findOneById($this->attachment_id);
        if ($this->ei_subject_attachment == null)
            $this->forward404('Attachment  not found');
    }
    
    public function getAssignAndNonAssignUserToSubject(EiSubject $ei_subject) {
        $this->alreadyAssignUsers = $ei_subject->getAssignUsers();
        $this->usersToAssignToSubject = $ei_subject->getNonAssignUsers($this->alreadyAssignUsers);
    }
    
    public function executeIndex(sfWebRequest $request) {
        $this->ei_subject_attachments = Doctrine_Core::getTable('EiSubjectAttachment')
                ->createQuery('a')
                ->execute();
    }

    public function executeDownload(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkAttachment($request);        //throw new Exception($this->ei_subject_attachment->getPath());
        $filePath = sfConfig::get('sf_upload_dir').'/subjectAttachements/'.$this->ei_subject_attachment->getPath(); 
        //$mimeType = mime_content_type($this->ei_subject_attachment->getPath());

        /** @var $response sfWebResponse */
        $response = $this->getResponse();
        $response->clearHttpHeaders();
        //$response->setContentType($mimeType);
        $response->setHttpHeader('Content-Disposition', 'attachment; filename="' . $this->ei_subject_attachment->getFileName() . '"');
        $response->setHttpHeader('Content-Description', 'File Transfer');
        $response->setHttpHeader('Content-Transfer-Encoding', 'binary');
        $response->setHttpHeader('Content-Length', filesize($filePath));
        $response->setHttpHeader('Cache-Control', 'public, must-revalidate');
        // if https then always give a Pragma header like this  to overwrite the "pragma: no-cache" header which
        // will hint IE8 from caching the file during download and leads to a download error!!!
        $response->setHttpHeader('Pragma', 'public');
        //$response->setContent(file_get_contents($filePath)); # will produce a memory limit exhausted error
        $response->sendHttpHeaders();

        ob_end_flush();
        return $this->renderText(readfile($filePath));
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $this->guardUser=$this->getUser()->getGuardUser();
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkSubject($request, $this->ei_project);
        
        $attach = new EiSubjectAttachment();
        $attach->setSubjectId($this->ei_subject->getId());
//        $attach->setType(sfConfig::get('app_bug_attachment_description'));
        $attach->setAuthorId($this->guardUser->getId()); 
        $this->form = new EiSubjectAttachmentForm($attach); 

        $this->processForm($request, $this->form);
        
        $this->subjectAttachments=Doctrine_Core::getTable('EiSubjectAttachment')->findBySubjectIdAndType(
                    $this->ei_subject->getId(), sfConfig::get('app_bug_attachment_description'));
        
        //Recherche des utilisateurs assignés et non-assignés au subjet
        $this->getAssignAndNonAssignUserToSubject($this->ei_subject);
        $this->projectUsers = Doctrine_Core::getTable('sfGuardUser')->getProjectUsers($this->ei_project);
        
        $this->newAttachForm =$this->form;
        //Redirection en cas d'echec de l'opération
        $tainted=$request->getParameter($this->form->getName()); 
        
        //On réccupère éventuellement le contexte de création du bug
        $this->ei_context=$this->ei_subject->getBugContextSubject()->getFirst();
        
        //Méssages sur la description du sujet
        $this->subjectMessages=Doctrine_Core::getTable('EiSubjectMessage')
                ->getMessages($this->subject_id,sfConfig::get("app_bug_description_message"));
        
        switch ($tainted['type']) {
                    case sfConfig::get('app_bug_attachment_description'): 
                        $this->setTemplate('show','eisubject');
                        break;
                    case sfConfig::get('app_bug_attachment_details'): 
                        $this->ei_subject_details=$this->ei_subject->getSubjectDetails();
                        $this->setTemplate('show','eisubjectdetails');
                        break;
                    case sfConfig::get('app_bug_attachment_solution'): 
                        $this->ei_subject_solution=$this->ei_subject->getSubjectSolution();
                        $this->setTemplate('show','eisubjectsolution');
                        break;
                    case sfConfig::get('app_bug_attachment_migration'):  
                        $this->ei_subject_migration=$this->ei_subject->getSubjectMigration();
                        $this->setTemplate('show','eisubjectmigration'); 
                        break;
                    default: 
                        $this->setTemplate('show','eisubject');
                        break;
                }  
    }

    public function executeEdit(sfWebRequest $request) {

        $this->forward404Unless($ei_subject_attachment = Doctrine_Core::getTable('EiSubjectAttachment')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_attachment does not exist (%s).', $request->getParameter('id')));
        $this->form = new EiSubjectAttachmentForm($ei_subject_attachment);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($ei_subject_attachment = Doctrine_Core::getTable('EiSubjectAttachment')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_attachment does not exist (%s).', $request->getParameter('id')));
        $this->form = new EiSubjectAttachmentForm($ei_subject_attachment);

        $this->processForm($request, $this->form);

        $this->setTemplate('show','eisubject'); 
    }

    public function executeDelete(sfWebRequest $request) {
//        $request->checkCSRFProtection(); 
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkAttachment($request);
        $filePath = sfConfig::get('sf_upload_dir').'/subjectAttachements/'.$this->ei_subject_attachment->getPath();
        if( file_exists($filePath))  unlink($filePath);
        $this->ei_subject_attachment->delete();
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eisubjectattachment/alertMsg', array(
                        'msg' => 'File has been removed successfully',
                        'msgClass' => 'alert-success alert_subject_attachment',
                        'msgTitle' => 'Well done ! ')),
                    'success' => true)));
        return sfView::NONE;
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) { 
            $file=$form->getValue('path'); 
            if($file!=null):  
                $form->updateObject();
                $form->getObject()->setFilename($file->getOriginalName());
                $form->getObject()->save();  
                $ei_subject_attachment= $form->getObject();
                //$ei_subject_attachment = $form->save(); 
                $this->getUser()->setFlash('alert_form',
                    array('title' => 'Success' ,
                            'class' => 'alert-success' ,
                            'text' => 'Attachment has been add sucessfully ...'));
                //Suivant le type du fichier attaché , on éffectue la bonne redirection  
                else: 
            $this->getUser()->setFlash('alert_form', array('title' => 'Error',
                    'class' => 'alert-danger',
                    'text' => 'Please select a file ...'));  
            endif;
            $this->redirectWithinAttachmentType($form->getValue('type')); 
        }
                    
    }
    public function redirectWithinAttachmentType($attachment_type){   //throw new Exception($attachment_type);
                switch ($attachment_type) {
                                        
                    case sfConfig::get('app_bug_attachment_description'):
                        $subject_show=$this->urlParameters; $subject_show['subject_id']=$this->subject_id;
                        $this->redirect($this->generateUrl('subject_show',$subject_show)) ;

                        break;
                    case sfConfig::get('app_bug_attachment_details'):
                        $subject_details_edit=$this->urlParameters; $subject_details_edit['subject_id']=$this->subject_id;
                        $subject_details_edit['id']=$this->ei_subject->getSubjectDetails();$subject_details_edit['action']='show';
                        $this->redirect($this->generateUrl('subject_details_edit', $subject_details_edit)) ;

                        break;
                    case sfConfig::get('app_bug_attachment_solution'):
                        $subject_solution_edit=$this->urlParameters; $subject_solution_edit['subject_id']=$this->subject_id;
                        $subject_solution_edit['id']=$this->ei_subject->getSubjectSolution();$subject_solution_edit['action']='show';
                        $this->redirect($this->generateUrl('subject_solution_edit',$subject_solution_edit)) ;

                        break;
                    case sfConfig::get('app_bug_attachment_migration'):
                        $subject_migration_edit=$this->urlParameters; $subject_migration_edit['subject_id']=$this->subject_id;
                        $subject_migration_edit['id']=$this->ei_subject->getSubjectMigration();$subject_migration_edit['action']='show';
                        $this->redirect($this->generateUrl('subject_migration_edit', $subject_migration_edit)) ;

                        break;

                    default:
                        $subject_show=$this->urlParameters; $subject_show['subject_id']=$this->subject_id;
                        $this->redirect($this->generateUrl('subject_show',$subject_show)) ;
                        break;
                }
        }

}
