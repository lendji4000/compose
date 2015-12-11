<?php

/**
 * eisubjecthascampaign actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubjecthascampaign
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjecthascampaignActions extends sfActionsKalifast
{
   //Recherche d'un sujet
    public function checkSubject(sfWebRequest $request, EiProjet $ei_project) {
        $this->subject_id = $request->getParameter('subject_id');
        if ($this->subject_id == null)
            $this->forward404('Missing subject parameters');
        //Recherche de la livraison tout en s'assurant qu'elle corresponde au projet courant 
        $this->ei_subject_with_relation = Doctrine_Core::getTable('EiSubject')
                ->getSubject($ei_project->getProjectId(),$ei_project->getRefId(),$this->subject_id);
        $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneById($this->subject_id);
        if ($this->ei_subject == null)
            $this->forward404('Subject not found');
    } 
    
    public function checkSubjectCampaign(EiSubject $ei_subject , EiCampaign $ei_campaign ){
        $this->subjectCampaign=Doctrine_Core::getTable('EiSubjectHasCampaign')
                ->findOneByCampaignIdAndSubjectId($ei_campaign->getId(),$ei_subject->getId() );
    }
    
  public function executeIndex(sfWebRequest $request)
  {
    $this->checkProject($request);
    $this->checkProfile($request,$this->ei_project);
    $this->checkSubject($request,$this->ei_project); 
        //Campagnes d'un  sujet
      $this->ei_subject_campaigns = $this->ei_subject->getSubjectCampaigns();
  }
 
  public function executeNew(sfWebRequest $request)
  {
    $this->checkProject($request);
    $this->checkProfile($request,$this->ei_project);
    $this->checkSubject($request, $this->ei_project); 
    $subject_has_campaign=new EiSubjectHasCampaign();
    $subject_has_campaign->setSubjectId($this->ei_subject->getId());
    $ei_campaign= new EiCampaign();
    $ei_campaign->setProjectId($this->ei_project->getProjectId());
    $ei_campaign->setProjectRef($this->ei_project->getRefId());
    $ei_campaign->setAuthorId($this->getUser()->getGuardUser()->getId());
    $subject_has_campaign->setEiCampaign($ei_campaign);
    $this->form = new EiSubjectHasCampaignForm($subject_has_campaign); 
    $this->form->embedForm('ei_subject_campaign', new EiCampaignForm($ei_campaign));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->checkProject($request);
    $this->checkProfile($request,$this->ei_project);
    $this->checkSubject($request, $this->ei_project); 
    $subject_has_campaign=new EiSubjectHasCampaign();
    $subject_has_campaign->setSubjectId($this->ei_subject->getId());
    $ei_campaign= new EiCampaign();
    $ei_campaign->setProjectId($this->ei_project->getProjectId());
    $ei_campaign->setProjectRef($this->ei_project->getRefId());
    $ei_campaign->setAuthorId($this->getUser()->getGuardUser()->getId());
    $subject_has_campaign->setEiCampaign($ei_campaign);
    $this->form = new EiSubjectHasCampaignForm($subject_has_campaign); 
    $this->form->embedForm('ei_subject_campaign', new EiCampaignForm($ei_campaign));  
    $this->processForm($request, $this->form); 
     
    $this->setTemplate('new');  
  }

  public function executeEdit(sfWebRequest $request)
  { 
      $this->checkProject($request);
      $this->checkProfile($request,$this->ei_project);
      $this->checkSubject($request, $this->ei_project); 
      $this->ei_campaign=doctrine_core::getTable('EiCampaign')->findOneById($request->getParameter('campaign_id'));
      $this->checkSubjectCampaign($this->ei_subject,$this->ei_campaign);
      $this->form=new EiSubjectHasCampaignForm($this->subjectCampaign);
      $this->form->embedForm('ei_subject_campaign',new EiCampaignForm ($this->ei_campaign));  
  }

  public function executeUpdate(sfWebRequest $request)
  { 
      $this->checkProject($request);
      $this->checkProfile($request,$this->ei_project);
      $this->checkSubject($request, $this->ei_project); 
      $this->ei_campaign=doctrine_core::getTable('EiCampaign')->findOneById($request->getParameter('campaign_id'));
      $this->checkSubjectCampaign($this->ei_subject,$this->ei_campaign);
      $this->form=new EiSubjectHasCampaignForm($this->subjectCampaign);
      $this->form->embedForm('ei_subject_campaign',new EiCampaignForm ($this->ei_campaign));
      
      $this->processForm($request, $this->form); 
     
      $this->setTemplate('edit'); 
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->checkProject($request);
    $this->checkProfile($request,$this->ei_project);
    $this->forward404Unless($ei_subject_has_campaign = Doctrine_Core::getTable('EiSubjectHasCampaign')->find(array($request->getParameter('campaign_id'),
    $request->getParameter('subject_id'))), sprintf('Object ei_subject_has_campaign does not exist (%s).', $request->getParameter('campaign_id'),
    $request->getParameter('subject_id')));
    $ei_subject_has_campaign->delete();

    $this->redirect('eisubjecthascampaign/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_subject_has_campaign = $form->save(); 
      $this->getUser()->setFlash('alert_campaign_form',
                    array('title' => 'Success' ,
                        'class' => 'alert-success' ,
                        'text' => 'Well done ...'));
      $this->redirect($this->generateUrl('subjectCampaignsList', array(
          'project_id' => $this->project_id,
          'project_ref' => $this->project_ref,
          'profile_id' => $this->profile_id,
          'profile_ref' => $this->profile_ref,
          'profile_name' => $this->profile_name,
          'subject_id' => $this->subject_id
      )));
     } 
     else{
            $this->getUser()->setFlash('alert_campaign_form',
                    array('title' => 'Error' ,
                        'class' => 'alert-danger' ,
                        'text' => 'An error occurred while trying to save this intervention\'s campaign. Check requirements'));
        }
  }
}
