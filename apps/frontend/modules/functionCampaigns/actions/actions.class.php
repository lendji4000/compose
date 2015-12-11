<?php

/**
 * functionCampaigns actions.
 *
 * @package    kalifastRobot
 * @subpackage functionCampaigns
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class functionCampaignsActions extends sfActionsKalifast
{ 
     
    
    /* Cette fonction permet de rechercher la fonction (KalFunction) avec les paramètres renseignés.  */

    public function checkFunction(sfWebRequest $request,  EiProjet $ei_project) {
        $this->function_id = $request->getParameter('function_id');
        $this->function_ref = $request->getParameter('function_ref');

        if ($this->function_id != null || $this->function_ref != null) {
            //Recherche de la fonction en base
            $this->kal_function = Doctrine_Core::getTable('KalFunction')
                    ->findOneByFunctionIdAndFunctionRefAndProjectIdAndProjectRef(
                            $this->function_id,$this->function_ref,$ei_project->getProjectId(),$ei_project->getRefId());
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->kal_function == null)
                $this->kal_function = null;
        }
        else {
            $this->function_id = null;
            $this->function_ref = null;
        }
    }
 
  public function executeIndex(sfWebRequest $request)
  {
    $this->checkProject($request);
    $this->checkProfile($request, $this->ei_project); $this->forward404If($this->ei_profile==null, 'Environment not found ...');
    $this->checkFunction($request, $this->ei_project);$this->forward404If($this->kal_function==null, 'Function not found ...');
    $this->profile_name=$this->ei_profile->getName();
    /* Campagnes d'une fonction */ 
      $this->ei_function_campaigns = $this->kal_function->getFunctionCampaigns(); 
    /* Campagnes de test du projet */
    $conn = Doctrine_Manager::connection();
    $this->ei_project_campaigns=$this->ei_project->getAllProjectCampaigns($conn);
    
    $q="select distinct(campaign_id) from ei_campaign_graph where scenario_id 
            in (select  s_id from ei_scenarios_function_vw  Where kf_project_id =".$this->ei_project->getProjectId()." And kf_project_ref=".$this->ei_project->getRefId().
            " And kf_function_id =".$this->kal_function->getFunctionId()." And kf_function_ref=".$this->kal_function->getFunctionRef().')';
    $this->ei_occurences_function=$conn->fetchAll($q);
    
    
  }

  /* Actions avant la gestion des formulaires de traitement de campagnes d'une fonction */
  public function preForm(sfWebRequest $request){
      /* Paramètres de la requêtes*/
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);  $this->forward404If($this->ei_profile==null, 'Environment not found ...');
        $this->checkFunction($request, $this->ei_project); $this->forward404If($this->kal_function==null, 'Function not found ...');
        $this->profile_name=$this->ei_profile->getName();
  }
  /* Création des objets en ajout ou création d'une campagne de fonction */
  public function middleForm(){
    /* Création des objets*/
    $this->function_has_campaign=new EiFunctionCampaigns();
    $this->function_has_campaign->setFunctionId($this->kal_function->getFunctionId());
    $this->function_has_campaign->setFunctionRef($this->kal_function->getFunctionRef());
    $this->ei_campaign= new EiCampaign();
    $this->ei_campaign->setProjectId($this->ei_project->getProjectId());
    $this->ei_campaign->setProjectRef($this->ei_project->getRefId());
    $this->ei_campaign->setAuthorId($this->getUser()->getGuardUser()->getId());
    $this->function_has_campaign->setEiCampaign($this->ei_campaign);  
  }
  public function executeNew(sfWebRequest $request)
  {
    $this->preForm($request); /* Récupération des objets indispensables au traitement*/
    $this->middleForm();/* Création des objets*/ 
    /* Initialisation du formulaire*/
    $this->form = new EiFunctionCampaignsForm($this->function_has_campaign); 
    $this->form->embedForm('ei_function_campaign', new EiCampaignForm($this->ei_campaign));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->preForm($request); /* Récupération des objets indispensables au traitement*/
    $this->middleForm();/* Création des objets*/     
    /* Initialisation du formulaire*/
    $this->form = new EiFunctionCampaignsForm($this->function_has_campaign); 
    $this->form->embedForm('ei_function_campaign', new EiCampaignForm($this->ei_campaign)); 
    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_function_campaigns = Doctrine_Core::getTable('EiFunctionCampaigns')->find(array($request->getParameter('id'))), sprintf('Object ei_function_campaigns does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiFunctionCampaignsForm($ei_function_campaigns);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_function_campaigns = Doctrine_Core::getTable('EiFunctionCampaigns')->find(array($request->getParameter('id'))), sprintf('Object ei_function_campaigns does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiFunctionCampaignsForm($ei_function_campaigns);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_function_campaigns = Doctrine_Core::getTable('EiFunctionCampaigns')->find(array($request->getParameter('id'))), sprintf('Object ei_function_campaigns does not exist (%s).', $request->getParameter('id')));
    $ei_function_campaigns->delete();

    $this->redirect('functionCampaigns/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_function_campaigns = $form->save(); 
      $this->redirect($this->generateUrl('showFunctionCampaigns', array(
          'project_id' => $this->project_id,
          'project_ref' => $this->project_ref,
          'profile_id' => $this->profile_id,
          'profile_ref' => $this->profile_ref,
          'profile_name' => $this->profile_name,
          'function_id' => $this->function_id,
          'function_ref' => $this->function_ref,
          'action' => 'index'
      ))); 
    }
  }
}
