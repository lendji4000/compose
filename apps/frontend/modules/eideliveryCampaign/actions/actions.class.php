<?php

/**
 * eideliveryCampaign actions.
 *
 * @package    kalifastRobot
 * @subpackage eideliveryCampaign
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eideliveryCampaignActions extends sfActionsKalifast
{  
  //Recherche de la livraison
  public function checkDelivery(sfWebRequest $request,EiProjet $ei_project){
      $this->delivery_id = $request->getParameter('delivery_id');
      if($this->delivery_id==null) $this->forward404 ('Missing delivery parameters');
      //Recherche de la livraison tout en s'assurant qu'elle corresponde au projet courant 
      $this->ei_delivery=Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
              $this->delivery_id,$ei_project->getProjectId(),$ei_project->getRefId());
      if($this->ei_delivery==null) $this->forward404 ('Delivery not found');
  }
  
  //Recherche d'une relation delivery-campaign
  public function checkDeliveryCampaign(EiDelivery $ei_delivery, EiCampaign $ei_campaign){
      $this->ei_delivery_has_campaign=Doctrine_Core::getTable('EiDeliveryHasCampaign')
              ->findOneByDeliveryIdAndCampaignId($ei_delivery->getId(),$ei_campaign->getId());
  }
  
  //Création de la relation livraison - campagne
  public function createRelation(EiDelivery $ei_delivery, EiCampaign $ei_campaign) {
      $delivery_has_campaign=new EiDeliveryHasCampaign();
      $delivery_has_campaign->setEiCampaign($ei_campaign);
      $delivery_has_campaign->setEiDelivery($ei_delivery);
      return $delivery_has_campaign->save();
  }
  //Récupération des campagnes d'une livraison
  public function executeGetDeliveryCampaigns(sfWebRequest $request)
  {
      $this->checkProject($request);
      $this->checkProfile($request,$this->ei_project);
      $this->checkDelivery($request,$this->ei_project);
      $this->addDeliveryInUserSession($this->ei_delivery);
      //Campagnes d'une livraison
      $this->ei_delivery_campaigns = $this->ei_delivery->getDeliveryCampaigns();
      //Campagnes  du projet pour l'ajout d'une campagne à la livraison
      $this->ei_campaigns=Doctrine_Core::getTable('EiCampaign')
              ->getProjectCampaigns($this->project_id,$this->project_ref)
              ->execute();
  }

  //Ajout d'une campagne de tests à une livraison
  public function executeAddCampaignToDeliverys(sfWebRequest $request)
  {
      $this->success=false;
      $this->checkProject($request);//Recherche du projet
      $this->checkProfile($request,$this->ei_project);
      $this->checkDelivery($request,$this->ei_project); //Recherche de la livraison
      $this->checkCampaign($request,$this->ei_project); //Recherche de la campagne
      //Recherche de la relation 
      $this->checkDeliveryCampaign($this->ei_delivery, $this->ei_campaign); 
      //Si l'object n'est pas trouvé alors la relation n'hexiste pas et dans ce cas , on la crée
      if($this->ei_delivery_has_campaign==null):
          $this->createRelation($this->ei_delivery, $this->ei_campaign);
      $this->success=true;
          //retour de la reponse du process (avec le partiel de la nouvelle assignation)
          return $this->renderText(json_encode(array(
                'html' => $this->getPartial('eideliveryCampaign/deliveryCampaign' , 
                               array('ei_campaign'=> $this->ei_campaign,
                                     'ei_delivery' => $this->ei_delivery,
                                     'project_id' => $this->project_id,
                                     'project_ref' => $this->project_ref)), 
                'success' => $this->success))); 
          else:
            return $this->renderText(json_encode(array(
                'html' => 'Relation already exist', 
                'success' => $this->success)));   
      endif;
      return sfView::NONE;
  }

  //Formulaire d'ajout d'une campagne à une livraison
  public function executeNew(sfWebRequest $request){
      $this->checkProject($request);
      $this->checkProfile($request,$this->ei_project);
    $this->checkDelivery($request, $this->ei_project);
    $delivery_has_campaign=new EiDeliveryHasCampaign();
    $delivery_has_campaign->setDeliveryId($this->ei_delivery->getId());
    $ei_campaign= new EiCampaign();
    $ei_campaign->setProjectId($this->ei_project->getProjectId());
    $ei_campaign->setProjectRef($this->ei_project->getRefId());
    $ei_campaign->setAuthorId($this->getUser()->getGuardUser()->getId());
    $delivery_has_campaign->setEiCampaign($ei_campaign);
    $this->form = new EiDeliveryHasCampaignForm($delivery_has_campaign); 
    $this->form->embedForm('ei_delivery_campaign', new EiCampaignForm($ei_campaign));
  }
 //Créatio d'une campagne de test pour une livraison
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));  
    $this->checkProject($request);
    $this->checkProfile($request,$this->ei_project);
    $this->checkDelivery($request, $this->ei_project);
    $delivery_has_campaign=new EiDeliveryHasCampaign();
    $delivery_has_campaign->setDeliveryId($this->ei_delivery->getId());
    $ei_campaign= new EiCampaign();
    $ei_campaign->setProjectId($this->ei_project->getProjectId());
    $ei_campaign->setProjectRef($this->ei_project->getRefId());
    $ei_campaign->setAuthorId($this->getUser()->getGuardUser()->getId());
    $delivery_has_campaign->setEiCampaign($ei_campaign);
    $this->form = new EiDeliveryHasCampaignForm($delivery_has_campaign); 
    $this->form->embedForm('ei_delivery_campaign', new EiCampaignForm($ei_campaign));  
    $this->processForm($request, $this->form); 
     
    $this->setTemplate('new'); 
  }

  public function executeEdit(sfWebRequest $request)
  { 
  }

  public function executeUpdate(sfWebRequest $request)
  { 
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_delivery_has_campaign = Doctrine_Core::getTable('EiDeliveryHasCampaign')->find(array($request->getParameter('delivery_id'),
$request->getParameter('campaign_id'))), sprintf('Object ei_delivery_has_campaign does not exist (%s).', $request->getParameter('delivery_id'),
$request->getParameter('campaign_id')));
    $ei_delivery_has_campaign->delete();

    $this->redirect('eideliveryCampaign/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_delivery_has_campaign = $form->save();
      $getDeliveryCampaigns=$this->urlParameters;
      $getDeliveryCampaigns['delivery_id']=$this->delivery_id;
      $this->getUser()->setFlash('alert_campaign_form',
                    array('title' => 'Success' ,
                        'class' => 'alert-success' ,
                        'text' => 'Well done ...'));
      $this->redirect($this->generateUrl('getDeliveryCampaigns', $getDeliveryCampaigns));
    }
    else{
            $this->getUser()->setFlash('alert_campaign_form',
                    array('title' => 'Error' ,
                        'class' => 'alert-danger' ,
                        'text' => 'An error occurred while trying to save this delivery\'s campaign. Check requirements'));
        }
  }
}
