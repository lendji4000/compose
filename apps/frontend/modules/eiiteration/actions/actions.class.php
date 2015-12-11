<?php

/**
 * eiiteration actions.
 *
 * @package    kalifastRobot
 * @subpackage eiiteration
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiiterationActions extends sfActionsKalifast
{
  public function preExecute() {
        parent::preExecute();

        // Récupération de l'utilisateur.
        $this->guard_user = $this->getUser()->getGuardUser();
        $this->ei_user = $this->guard_user->getEiUser();
    }
    // Recherche du profil  de création de l'itération

    public function checkItProfile(sfWebRequest $request,  EiProjet $ei_project) {
        $this->it_profile_id = $request->getParameter('it_profile_id');
        $this->it_profile_ref = $request->getParameter('it_profile_ref');

        if ($this->it_profile_id != null && $this->it_profile_ref != null) {
            //Recherche du profil en base
            $this->ei_it_profile = Doctrine_Core::getTable('EiProfil')
                    ->findOneByProfileIdAndProfileRefAndProjectIdAndProjectRef(
                            $this->it_profile_id, $this->it_profile_ref,$ei_project->getProjectId(),$ei_project->getRefId());
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->ei_profile == null) throw new Exception("Iteration profile not found ..."); 
        }
        else {
            throw new Exception("Missing iteration profile parameters ...");
        }
    } 
    
    /* Récupération des statistiques d'une livraison pour plusieurs itérations à la fois */
    public function executeGetDelStatsForManyIterations(sfWebRequest $request){
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request); // Projet courant
        $this->checkProfile($request, $this->ei_project); // Profil courant
        $this->checkDelivery($request, $this->ei_project); // Livraison de l'itération 
        $this->ei_iterations_params = $request->getParameter("ei_iterations");
        if ($this->ei_iterations_params != null && count($this->ei_iterations_params) > 0):
            $this->ei_impacted_functions_stats_with_params = $this->ei_delivery->getDelStatsForManyIterations($this->ei_iterations_params);
            /* Récupération de l'itération courante */
            $this->current_iteration = $this->ei_profile->getCurrentIteration();
            /* Liste des itérations choisies . */
            $this->ei_iterations =Doctrine_Core::getTable("EiIteration")->getManyIterations(
                    $this->ei_project->getProjectId(),$this->ei_project->getRefId(),$this->ei_iterations_params)  ;

            $iterationBlocStats = $this->urlParameters;
            $iterationBlocStats['current_iteration'] = $this->current_iteration;
            $iterationBlocStats['ei_delivery'] = $this->ei_delivery;
            $iterationBlocStats['ei_iterations'] = $this->ei_iterations;
            $iterationBlocStats['ei_impacted_functions_stats_with_params'] = $this->ei_impacted_functions_stats_with_params;
            $this->html = $this->getPartial("eiiteration/iterationBlocStats", $iterationBlocStats);

        else:
            $this->success = false;
            $this->html = "no iteration";
        endif;
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'success' => true)));
        return sfView::NONE;
    }

    //Récupération des critères renseignés dans le formulaire de recherche
    public function getIterationSearchCriteriaForm(sfWebRequest $request, iterationSearchStatsForm $iterationSearchStatsForm) {
        $this->searchIterationCriteria = array('environment' => null, 'author' => null,  'delivery'=>null, "is_active"=> false);
        $criterias = $request->getParameter($iterationSearchStatsForm->getName());
        $this->searchIterationCriteria=array();
        if ($criterias): 
            if (isset($criterias['author']))
                $this->searchIterationCriteria['author'] = $criterias['author'];  
            if (isset($criterias['is_active']))
                $this->searchIterationCriteria['is_active'] = $criterias['is_active'];  
            if (isset($criterias['delivery']))
                $this->searchIterationCriteria['delivery'] = $criterias['delivery'];
            if (isset($criterias['environment']) && $criterias['environment']!=null && $criterias['environment']!=0): 
                /*Récupération de l'id et du ref du profil */
                $tabProf=explode('_', $criterias['environment']);
                $this->searchIterationCriteria['environment']['profile_id'] =$tabProf[0];
                $this->searchIterationCriteria['environment']['profile_ref'] =$tabProf[1];
            endif;
                
        endif;
    }  
    
  /* Action de recherche des itérations pour la génération des statistiques sur une livraison */
    
  public function executeSearchItForDelStats(sfWebRequest $request){
      $this->forward404unless($request->isXmlHttpRequest()); 
      $this->checkProject($request); // Projet courant
      $this->checkProfile($request, $this->ei_project); // Profil courant
      /* Récupération des variables de formulaire pour la recherche des itérations */  
        $this->ei_profiles = Doctrine_Core::getTable('EiProfil')->getProjectProfilesAsArrayWithNull($this->ei_project);  
        $this->deliveries = Doctrine_Core::getTable('EiDelivery')->getAllProjectDeliveriesForSearchBox($this->ei_project);
      $this->iterationSearchStatsForm = new iterationSearchStatsForm(array(), array( 
            'deliveries' => $this->deliveries, 
            'ei_profiles' => $this->ei_profiles, 
        ));
      $this->getIterationSearchCriteriaForm($request,$this->iterationSearchStatsForm);
      /* Recherche des itérations par rapport au données renseignées dans le formulaire */
      $ei_iterations=Doctrine_Core::getTable("EiIteration")->getManyIterationsByCriterias(
              $this->ei_project->getProjectId(),$this->ei_project->getRefId(),$this->searchIterationCriteria);
       
      /* Préparation du partiel à retourner à la box de recherche */
      $listForSearchBoxParams=$this->urlParameters;
      $listForSearchBoxParams['ei_iterations']=$ei_iterations;
      $this->html=$this->getPartial('listForSearchBox',$listForSearchBoxParams);
      return  $this->renderText(json_encode(array( 
                    'html' =>$this->html, 
                    'success' => true))); 
      return sfView::NONE;
  }
  public function executeIndex(sfWebRequest $request)
  {
      $this->checkProject($request); // Projet courant
      $this->checkProfile($request, $this->ei_project); // Profil courant
      $this->checkDelivery($request, $this->ei_project); // Livraison de l'itération 
      $this->iterations_by_profiles=Doctrine_Core::getTable('EiIteration')->getDeliveryIterationsGroupByProfiles($this->ei_project,$this->ei_delivery);  
  }

  /* Définir une itération comme itération par défaut pour un profil donné et pour une livraison donnée*/
  public function executeSetAsActiveIteration(sfWebRequest $request){
      $this->forward404unless($request->isXmlHttpRequest()); 
      $this->checkProject($request); // Projet courant
      $this->checkProfile($request, $this->ei_project); // Profil courant
      $this->checkDelivery($request, $this->ei_project); // Livraison de l'itération 
      $this->checkIteration($request); 
      $res = $this->ei_iteration->setAsDefault();  

      return  $this->renderText(json_encode(array( 
                    'html' =>   ($res?' Active iteration':' Set as active'),
                    'resultClass' => ($res?"btn btn-sm btn-success activeIteration":"btn btn-sm btn-default setIterationAsDefault"),
                    'resultTitle' => ($res?"Active iteration":"Set as active?"),
                    'success' => ($res?true:false))));
      
      return sfView::NONE;
  }
  /* Récupération des statistiques d'une itération */
    
    public function executeStatistics(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkIteration($request); 
        $this->ei_delivery=$this->ei_iteration->getEiDelivery();
        $this->delivery_id=$this->ei_delivery->getId();
        /* Bugs ayant des impacts/ou pas */
        $this->bugsWithImpacts = $this->ei_delivery->getBugsWithImpacts(); 
        $this->bugsWithoutImpacts = $this->ei_delivery->getBugsWithoutImpacts();
        /* Récupération de l'itération courante */
        $this->current_iteration = $this->ei_profile->getCurrentIteration();  
        $this->ei_impacted_functions_stats_with_params = $this->ei_delivery->getImpactedFunctionsStatsWithParams($this->ei_iteration->getId()); 
        $this->bugsByStates = $this->ei_delivery->getBugsByStates();
        /* On récupère les bugs de la livraison avec toutes les relations du bug (statuts, priorites,types,assignments , etc ...) */
        $res=Doctrine_Core::getTable('EiSubject')->getSubjectWithRel($this->ei_project,$this->ei_delivery->getId());
        $stateBugs=array();
        if(count($res)>0):  
            foreach($res  as $ei_subject):
            if(!isset($stateBugs[$ei_subject['state_id']])):
                $stateBugs[$ei_subject['state_id']]=array();
                $stateBugs[$ei_subject['state_id']]['ei_subjects']=array();
                $stateBugs[$ei_subject['state_id']]['ei_subjects'][$ei_subject['id']]=$ei_subject;
                else:
                $stateBugs[$ei_subject['state_id']]['ei_subjects'][$ei_subject['id']]=$ei_subject;
            endif; 
            endforeach;
        endif;
        $this->stateBugs=$stateBugs;
        $this->setTemplate("statistics", "eidelivery");
    }
  public function executeShow(sfWebRequest $request)
  {
    $this->checkProject($request); // Projet courant
      $this->checkProfile($request, $this->ei_project); // Profil courant  
      $this->checkIteration($request); 
      $this->ei_delivery=$this->ei_iteration->getEiDelivery();
      $this->delivery_id=$this->ei_delivery->getId();
      $this->ei_impacted_functions_stats_with_params=$this->ei_delivery->getImpactedFunctionsStatsWithParams($this->ei_iteration->getId());
  }

  /* Mise à jour rapide de litération d'une livraison */
  public function executeUpdateQuicklyDesc(sfWebRequest $request){
      $this->checkProject($request); // Projet courant
      $this->checkProfile($request, $this->ei_project); // Profil courant
      $this->checkDelivery($request, $this->ei_project); // Livraison de l'itération  
      $this->checkIteration($request);
      $this->ei_iteration->setDescription($request->getParameter("iteration_description"));
      $this->ei_iteration->save();
      return  $this->renderText(json_encode(array( 
                    'html' =>   "Updated successfully ... ",
                    'success' => true)));
      
      return sfView::NONE;
  } 
  
  public function executeNew(sfWebRequest $request)
  {
      $this->checkProject($request); // Projet courant
      $this->checkProfile($request, $this->ei_project); // Profil courant
      $this->checkItProfile($request, $this->ei_project); // Recherche du profil  de création de l'itération
      $this->checkDelivery($request, $this->ei_project); // Livraison de l'itération 
      $ei_iteration=new EiIteration();
      $ei_iteration->setAuthorId($this->guard_user->getId());
      $ei_iteration->setEiProject($this->ei_project); 
      $ei_iteration->setEiProfile($this->ei_it_profile);
      $ei_iteration->setDeliveryId($this->ei_delivery->getId());
      $this->form = new EiIterationForm($ei_iteration);
      $form_uri=$this->urlParameters;
      $form_uri['it_profile_id']=$this->it_profile_id;
      $form_uri['it_profile_ref']=$this->it_profile_ref;
      $form_uri['delivery_id']=$this->delivery_id;
      $form_uri['action']="create";
      $uri_form=$this->generateUrl("ei_iteration_create" ,$form_uri );
      return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('form', array(
                        "form" =>$this->form,
                        "uri_form" => $uri_form)),
                    'success' => true)));
        return sfView::NONE;
  }

  public function executeCreate(sfWebRequest $request)
  {
       $this->forward404Unless($request->isXmlHttpRequest());
      $this->checkProject($request); // Projet courant
      $this->checkProfile($request, $this->ei_project); // Profil courant
      $this->checkItProfile($request, $this->ei_project); // Recherche du profil  de création de l'itération
      $this->checkDelivery($request, $this->ei_project); // Livraison de l'itération 
      $ei_iteration=new EiIteration();
      $ei_iteration->setAuthorId($this->guard_user->getId());
      $ei_iteration->setEiProject($this->ei_project); 
      $ei_iteration->setEiProfile($this->ei_it_profile);
      $ei_iteration->setDeliveryId($this->ei_delivery->getId());
      $this->form = new EiIterationForm($ei_iteration);

    $this->processForm($request, $this->form);
    $form_uri=$this->urlParameters;
    /* Traitement des résultats */
    if($this->success):
         
        $iterationLineParams=$this->urlParameters;
        $iterationLineParams['iteration']=$this->ei_iteration->asArray(); 
        $this->html=$this->getPartial('iterationLine',$iterationLineParams); 
        else: 
        $form_uri['it_profile_id']=$this->it_profile_id;
        $form_uri['it_profile_ref']=$this->it_profile_ref;
        $form_uri['delivery_id']=$this->delivery_id;
        $form_uri['action']="create";
        $uri_form=$this->generateUrl("ei_iteration_create" ,$form_uri );
        $this->html=$this->getPartial('form', array(
                        "form" =>$this->form,
                        "uri_form" => $uri_form ));
    endif;
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    "iteration_num" => 0,
                    "iteration_profile_class"=> '.iteration_profile_num'.$this->it_profile_id.$this->it_profile_ref,
                    'success' => $this->success)));

    return sfView::NONE;
  }

  public function executeEdit(sfWebRequest $request)
  {
      $this->forward404Unless($request->isXmlHttpRequest());
      $this->checkProject($request); // Projet courant
      $this->checkProfile($request, $this->ei_project); // Profil courant 
      $this->checkIteration($request); 
     $this->form = new EiIterationForm($this->ei_iteration);
     $form_uri=$this->urlParameters;
     $form_uri['iteration_id']=$this->ei_iteration->getId();
        $form_uri['action']="update";
        $this->form = new EiIterationForm($this->ei_iteration);
        $uri_form=$this->generateUrl("ei_iteration_actions" ,$form_uri ); 
        
     return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('form', array(
                        "form" =>$this->form,
                        "uri_form" => $uri_form  )),
                    'success' => true)));
     return sfView::NONE;
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isXmlHttpRequest());
      $this->checkProject($request); // Projet courant
      $this->checkProfile($request, $this->ei_project); // Profil courant 
      $this->checkIteration($request); 
     $this->form = new EiIterationForm($this->ei_iteration); 
    $this->processForm($request, $this->form);

    $form_uri=$this->urlParameters;
    /* Traitement des résultats */
    if($this->success):
        
        $iterationLineParams=$this->urlParameters;
        $iterationLineParams['iteration']=$this->ei_iteration->asArray(); 
        $this->html=$this->getPartial('iterationLine',$iterationLineParams); 
        else: 
        $form_uri['iteration_id']=$this->ei_iteration->getId();
        $form_uri['action']="update"; 
        $uri_form=$this->generateUrl("ei_iteration_actions" ,$form_uri ); 
        $this->html=$this->getPartial('form', array(
                        "form" =>$this->form,
                        "uri_form" => $uri_form )); 
    endif;
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    "iteration_num" => $this->ei_iteration->getId(),
                    'success' => $this->success)));

    return sfView::NONE;
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_iteration = Doctrine_Core::getTable('EiIteration')->find(array($request->getParameter('id'))), sprintf('Object ei_iteration does not exist (%s).', $request->getParameter('id')));
    $ei_iteration->delete();

    $this->redirect('eiiteration/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $this->ei_iteration = $form->save();
      $this->success=true;
      //$this->redirect('eiiteration/edit?id='.$ei_iteration->getId());
    }
    else{
        $this->success=false;
    }
  }
}
