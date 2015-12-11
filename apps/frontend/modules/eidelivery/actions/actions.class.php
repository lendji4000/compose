<?php

/**
 * eidelivery actions.
 *
 * @package    kalifastRobot
 * @subpackage eidelivery
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eideliveryActions extends sfActionsKalifast {
  
    public function preExecute() {
        parent::preExecute();

        // Récupération de l'utilisateur.
        $this->guard_user = $this->getUser()->getGuardUser();
        $this->ei_user = $this->guard_user->getEiUser();
    }
    /* Récupération de la campagne courante en cas d'édition d'une step */
    //On recherche la  campagne 
    public function checkCurrentCampaign(sfWebRequest $request, $campaign_id,EiProjet $ei_project) {
        $this->current_campaign_id =$campaign_id; 

        if ($this->current_campaign_id == null):
            $this->forward404('Campaign parameter not found');
        else:
            $this->ei_current_campaign = Doctrine_Core::getTable('EiCampaign')
                    ->findOneByIdAndProjectIdAndProjectRef(
                    $this->current_campaign_id, $ei_project->getProjectId(), $ei_project->getRefId());
            if ($this->ei_current_campaign == null):
                $this->forward404('Campaign not found');
            endif;
        endif;
    }
    /* Récupération des impacts d'une livraison */
    
    public function executeImpacts(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkDelivery($request, $this->ei_project);//Recherche de la livraison 
        $this->addDeliveryInUserSession($this->ei_delivery);
       
        if(  !$request->getParameter('exec')): 
            $this->exec=false ;   
        else:
            $this->exec=true;
        endif;        
        /* Recherche pour la livraison des fonctions exécutées */
        $this->exFunctions=$this->ei_delivery->getExFunctions($this->exec);  
    } 
    
    /* Initialisation du formulaire de recherche des itérations pour le tri des statistiques */

    public function initializeIterationStatsSearchForm(EiProjet $ei_project, EiDelivery $ei_delivery = null) { 
        $this->iterationsAuthors = Doctrine_Core::getTable('EiIteration')->getIterationAuthorsForProject($ei_project);  
        $this->ei_profiles = Doctrine_Core::getTable('EiProfil')->getProjectProfilesAsArrayWithNull($ei_project);  
        $this->deliveries = Doctrine_Core::getTable('EiDelivery')->getAllProjectDeliveriesForSearchBox($ei_project);
        $this->iterationSearchStatsForm = new iterationSearchStatsForm(array(), array( 
            'deliveries' => $this->deliveries,
            'ei_delivery' => $ei_delivery, 
            'ei_profiles' => $this->ei_profiles,
            'default_profile' => $this->ei_profile
        ));
    }
    /* Récupération des statistiques d'une livraison */
    
    public function executeStatistics(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkDelivery($request, $this->ei_project); //Recherche de la livraison 
        $this->addDeliveryInUserSession($this->ei_delivery);
        $this->initializeIterationStatsSearchForm($this->ei_project);
        /* Bugs ayant des impacts/ou pas */
        $this->bugsWithImpacts = $this->ei_delivery->getBugsWithImpacts(); 
        $this->bugsWithoutImpacts = $this->ei_delivery->getBugsWithoutImpacts();
        
        /* Récupération de l'itération courante */
        $this->current_iteration = $this->ei_profile->getCurrentIteration(); 
        /* Ici ei_iterations contient la dernière itération de la livraison . */
        $this->ei_iterations=$this->ei_delivery->getLastIterationForProfile($this->ei_profile); 
        $this->ei_impacted_functions_stats_with_params = array();
        if (count($this->ei_iterations)>0): //throw new Exception($this->current_iteration);
            $this->ei_impacted_functions_stats_with_params = $this->ei_delivery->getImpactedFunctionsStatsWithParams($this->ei_iterations[0]['id']);
        endif;
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
        
        
    }
 

    /*
     * Récupération des campagnes et sujets d'une livraison pour l'édition des steps d'une campagne
     */
    public function executeShowDeliveryCampaigns(sfWebRequest $request){
      $this->forward404unless($request->isXmlHttpRequest()); 
      $this->checkProject($request); //Récupération du projet
      $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
      $this->checkDelivery($request, $this->ei_project);//Recherche de la livraison 
      $this->addDeliveryInUserSession($this->ei_delivery);
      $this->checkCurrentCampaign($request, $request->getParameter('current_campaign_id'), $this->ei_project);//Campagne courante
      //Sujets de la livraison
      $this->ei_delivery_subjects = $this->ei_delivery->getDeliverySubjects();
      //Campagnes de la livraison
      $this->ei_delivery_campaigns = $this->ei_delivery->getDeliveryCampaigns();
      /*Construction des tableaux de paramètres de partiel */
      //Paramètres du partiel eicampaigngraph/rightSideDeliveryBloc 
      $rightSideDeliveryBloc=$this->urlParameters;
      $rightSideDeliveryBloc['ei_delivery']=$this->ei_delivery;
      $rightSideDeliveryBloc['ei_subjects']=$this->ei_delivery_subjects;
      $rightSideDeliveryBloc['ei_campaigns']=$this->ei_delivery_campaigns;
      $rightSideDeliveryBloc['ei_current_campaign']=$this->ei_current_campaign;
      //Paramètres du partiel eicampaigngraph/stepSearchGlobalBox 
      $stepSearchGlobalBox=$this->urlParameters;
      $stepSearchGlobalBox['ei_delivery']=$this->ei_delivery;
      $stepSearchGlobalBox['ei_subjects']=null;
      $stepSearchGlobalBox['ei_campaign']=null; 
       return  $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eicampaigngraph/rightSideDeliveryBloc',$rightSideDeliveryBloc),
                     'searchBox' =>  $this->getPartial('eicampaigngraph/stepSearchGlobalBox', $stepSearchGlobalBox),
                    'success' => true))); 
        return sfView::NONE; 
        
    }
    
    /* Initialisation du formulaire de recherche des livraisons */

    public function initializeDeliverySearchForm(EiProjet $ei_project) {
        //Récupération des statuts de livraison pour un projet
        $this->deliveryStates = Doctrine_Core::getTable('EiDeliveryState')->getDeliveryStateForSearchBox($ei_project);
        $this->deliveryAuthors = Doctrine_Core::getTable('EiDelivery')->getDeliveryAuthorsForProject($ei_project);
        $this->deliveryTitles = Doctrine_Core::getTable('EiDelivery')->getDeliveryTitlesForProject($ei_project);
        $this->deliverySearchForm = new deliverySearchForm(array(), array('deliveryStates' => $this->deliveryStates));
    }
    

    //Récupération des critères de recherche d'un livraison renseignés dans le formulaire de recherche
    public function getDeliverySearchCriteriaForm(sfWebRequest $request, deliverySearchForm $deliverySearchForm) {
        $this->searchDeliveryCriteria =
                array('title' => null,
                    'author' => null,
                    'state' => null,
                    'start_date' => null,
                    'end_date' => null);
        $criterias = $request->getParameter($deliverySearchForm->getName());
        if (is_array($criterias)):
            if (isset($criterias['title'])): $this->searchDeliveryCriteria['title'] = $criterias['title'];
            endif;
            if (isset($criterias['author'])): $this->searchDeliveryCriteria['author'] = $criterias['author'];
            endif;
            if (isset($criterias['state'])): $this->searchDeliveryCriteria['state'] = $criterias['state'];
            endif;
            if (isset($criterias['start_date'])): $this->searchDeliveryCriteria['start_date'] = $criterias['start_date'];
            endif;
            if (isset($criterias['end_date'])): $this->searchDeliveryCriteria['end_date'] = $criterias['end_date'];
            endif; 
        endif;
    }
    //Recupération des critères de recherche d'une livraison se trouvant dans l'url
    public function getDeliverySearchCriteria(sfWebRequest $request) {
        $this->searchDeliveryCriteria =
                array('title' => null,
                    'author' => null,
                    'state' => null,
                    'start_date' => null,
                    'end_date' => null);  
            if ($request->getParameter('title')!=null): $this->searchDeliveryCriteria['title'] = $request->getParameter('title');
            endif;
            if ($request->getParameter('author')!=null): $this->searchDeliveryCriteria['author'] =$request->getParameter('author') ;
            endif;
            if ($request->getParameter('state')!=null): $this->searchDeliveryCriteria['state'] = $request->getParameter('state');
            else: $stateTab=array();
                if($this->openStates && count($this->openStates)>0):
                    //On construit le tableau des IDs de statut de livraison ouverte 
                    $stateTab=array();
                    foreach($this->openStates as $openState): 
                    $stateTab[]=$openState->getId();
                    endforeach; 
                endif; 
                $this->searchDeliveryCriteria['state']=$stateTab;
            endif; 
            if ($request->getParameter('start_date')!=null): $this->searchDeliveryCriteria['start_date'] = $request->getParameter('start_date');
            endif;
            if ($request->getParameter('end_date')!=null): $this->searchDeliveryCriteria['end_date'] = $request->getParameter('end_date');
            endif; 
    }
 
    /* Vérifier si une livraison est close ou non */
    public function executeIsDeliveryClosed(sfWebRequest $request){
        if(!$request->isXmlHttpRequest()) $this->forward404 ("Can't call this action...");
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkDelivery($request, $this->ei_project); 
        $state=Doctrine_core::getTable('EiDeliveryState')->findOneById($this->ei_delivery->getDeliveryStateId());
        if($state->getCloseState()):
            return $this->renderText(json_encode(array(
                    'html' => "Delivery is closed",
                    'success' => true)));
        else:
            return $this->renderText(json_encode(array(
                    'html' => "Delivery is open",
                    'success' => false)));
        endif;
        return sfView::NONE;
    }
    //Administration de la migration d'une livraison
    public function executeAdminMigration(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkDelivery($request, $this->ei_project); 
        $this->addDeliveryInUserSession($this->ei_delivery);
        $this->ei_profiles=$this->ei_project->getProfils(); //Récupération des profils
        /* Gestion des fonctions de la livraison à migrer */
        $this->migrateFuncts=$this->ei_delivery->getFunctionsToMigrate("SELECT COUNT(t_id) as nb_occurences,df_vw.*");       
        $this->migrateFunctsWithoutCount=$this->ei_delivery->getFunctionsToMigrate("SELECT df_vw.*",false);
        $implicatedTikets='('; $k=count($this->migrateFuncts);$i=1;
        if(count($this->migrateFuncts)>0):
        foreach($this->migrateFuncts as $migrateFunc):
            $implicatedTikets.="(".$migrateFunc['sc_ticket_id'].",".$migrateFunc['sc_ticket_ref'].")";
            if($k!=$i):
                $implicatedTikets.=","; 
            endif;
            $i++;
        endforeach;
        endif;
        $implicatedTikets.=")";  
        //Récupération des relations script-profil impliquant le ticket en question
        $this->scriptProfiles=array();
      if(count($this->migrateFuncts)>0):  
      $this->TabScriptProfiles=Doctrine_Core::getTable('EiTicket')->getAssociatedProfilesForDelivery($implicatedTikets); 
      if(!empty($this->TabScriptProfiles)):
          foreach($this->TabScriptProfiles as $scriptProfile):
              $tab[$scriptProfile['function_id'].'_'.$scriptProfile['function_ref'].'_'.
                  $scriptProfile['profile_id'].'_'.$scriptProfile['profile_ref']]=$scriptProfile['script_id'];
          endforeach; 
          $this->scriptProfiles=$tab;
      endif;
      endif;
      //Récupération des conflits résolus sur la livraison 
      $this->resolved_conflicts_list = $this->ei_delivery->getResolvedConflictsOnFunctions();
        $resolved_conflicts = array();
        if (count($this->resolved_conflicts_list) > 0):
            foreach ($this->resolved_conflicts_list as $conflict):
                if (array_key_exists($conflict['function_id'] . '_' . $conflict['function_ref'] . '_' . $conflict['delivery_id'], $resolved_conflicts)):
                    $resolved_conflicts[$conflict['function_id'] . '_' . $conflict['function_ref'] . '_' . $conflict['delivery_id']]['profile'][] = array(
                        'profile_id' => $conflict['profile_id'],
                        'profile_ref' => $conflict['profile_ref']
                    );
                else:
                    $resolved_conflicts[$conflict['function_id'] . '_' . $conflict['function_ref'] . '_' . $conflict['delivery_id']] = array("package_id" => $conflict['package_id'], 'package_ref' => $conflict['package_ref'], 'profile' => array(0 => array('profile_id' => $conflict['profile_id'],
                                        'profile_ref' => $conflict['profile_ref'])
                    ));
                endif;

            endforeach;
        endif;
        $this->resolved_conflicts=isset($resolved_conflicts)?$resolved_conflicts:array();
    /* Gestion des scenarios de la livraison à migrer */  
      
      $this->scenariosToMigrate = $this->ei_delivery->getScenariosToMigrate("SELECT COUNT(sc_id) as nb_occurences,ds_vw.*");
        $this->scenariosToMigrateWithoutCount = $this->ei_delivery->getScenariosToMigrate("SELECT ds_vw.*", false);
        
      
        if(count($this->scenariosToMigrate)>0): 
            $implicatedTiketsScenario='('; $k=count($this->scenariosToMigrate);$i=1;
            foreach($this->scenariosToMigrate as $migrateScen):
                $implicatedTiketsScenario.="(".$migrateScen['s_package_id'].",".$migrateScen['s_package_ref'].")";
                if($k!=$i):
                    $implicatedTiketsScenario.=","; 
                endif;
                $i++;
            endforeach;
            $implicatedTiketsScenario.=")";  
        endif;
        $this->TabScenarioProfiles =array();
        if(isset($implicatedTiketsScenario)):
            $this->TabScenarioProfiles = Doctrine_Core::getTable('EiTicket')->getVersionsScenarioProfiles($implicatedTiketsScenario); 
        endif;

        if (!empty($this->TabScenarioProfiles)) {
            foreach ($this->TabScenarioProfiles as $versionProfile):
                $tab2[$versionProfile['ei_scenario_id'] . '_' . $versionProfile['profile_id'] . '_' . $versionProfile['profile_ref']] = $versionProfile['ei_version_id'];
            endforeach;
            $this->versionsProfiles = $tab2;
        }
        
        //Récupération des conflits de scenarios résolus sur la livraison 
      $this->resolved_conflicts_scenario_list = $this->ei_delivery->getResolvedConflictsOnScenarios();
        $resolved_conflicts_scenarios = array();
        if (count($this->resolved_conflicts_scenario_list) > 0):
            foreach ($this->resolved_conflicts_scenario_list as $conflict):
                if (array_key_exists($conflict['ei_scenario_id'] .'_'. $conflict['delivery_id'], $resolved_conflicts_scenarios)):
                    $resolved_conflicts_scenarios[$conflict['ei_scenario_id'] . '_' . $conflict['delivery_id']]['profile'][] = array(
                        'profile_id' => $conflict['profile_id'],
                        'profile_ref' => $conflict['profile_ref']
                    );
                else:
                    $resolved_conflicts_scenarios[$conflict['ei_scenario_id'] . '_' . $conflict['delivery_id']] = array(
                        "package_id" => $conflict['package_id'], 
                        'package_ref' => $conflict['package_ref'], 
                        'profile' => array(0 => array('profile_id' => $conflict['profile_id'], 'profile_ref' => $conflict['profile_ref'])
                    ));
                endif;

            endforeach;
        endif;
        $this->resolved_conflicts_scenarios=isset($resolved_conflicts_scenarios)?$resolved_conflicts_scenarios:array();
        
    }
    public function executeChangeMigrationProfile(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); // Récupération du profil  
        $this->checkDelivery($request, $this->ei_project);
        $migrationCase = $request->getParameter('migrationCase');
        $this->ei_profiles = $this->ei_project->getProfils(); //Récupération des profils
        //Retour du partiel avec la reponse json 
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eidelivery/profilesForMigration', array('ei_profiles' => $this->ei_profiles,
                        'ei_project' => $this->ei_project, 
                        'current_profile' => $this->ei_profile,
                    'ei_delivery' => $this->ei_delivery,
                    'migrationCase' => ($migrationCase != null ? $migrationCase : null))),
                    'success' => true)));
        return sfView::NONE;
    }
    /* Procedure de migration applicative d'une livraison (delivery process) */
    public function executeDeliveryProcess(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkDelivery($request, $this->ei_project);
        /* Récupération des procédures de migration applicative des bugs de la livraison */
        $this->delivery_process_lines=$this->ei_delivery->getDeliveryProcess();
        
    }
    //Recherche rapide d'une livraison par son numero ou son nom
    public function executeSearchDeliveryByIdOrName(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->nameOrId=$request->getParameter('nameOrId');
        $this->ei_delivery=Doctrine_Core::getTable('EiDelivery')->searchDeliveryByIdOrName(
                $this->nameOrId,$this->project_id,$this->project_ref); 
        if($this->ei_delivery!=null)
            $this->form = new EiDeliveryForm($this->ei_delivery);
        else{
            $this->getUser()->setFlash('delivery_not_found', 'Delivery not found');
            $this->checkDelivery($request, $this->ei_project); 
            $this->form = new EiDeliveryForm($this->ei_delivery);
        }
        
        $this->setTemplate('edit');
    }
    //Récupération des livraisons relativement aux différents critères 
    public function getDeliveriesByCriteria(sfWebRequest $request){
        /* Controle de l'offset pour la détermination du nombre d'enregistrement à afficher */
        //Si l'offset est spécifié, alors il prime sur le nombre d'enregistrement par défaut à afficher.
        $this->offset=intval($request->getParameter('offset'));
        if($this->offset !=15 && $this->offset !=30 && $this->offset !=50 && $this->offset !=100 ) $this->offset=15; 
        $this->max_delivery_per_page = sfConfig::get('app_max_delivery_per_page');
        if ($request->getParameter('offset')) {
            $this->max_delivery_per_page = $this->offset;
        }
        else{
            $this->offset=$this->max_delivery_per_page;
        }
        /*
         * Gestion de la pagination des résultats 
         */
        //Calcul du nombre de pages 
        $q = Doctrine_Core::getTable('EiDelivery')
                        ->sortDeliveriesByCriterias(Doctrine_Core::getTable('EiDelivery')
                                ->getProjectDeliveries($this->project_id, $this->project_ref), $this->searchDeliveryCriteria)->execute();
        $this->nbEnr = count($q);

        //La fonction ceil retourne l'arrondi d'ordre supérieur (0.5 => 1 , 0.3 => 1 , 0.7 =>1)
        $this->nb_pages = ceil($this->nbEnr / $this->max_delivery_per_page);

        //Récupération de la variable page représentant la page courante

        if ($request->getParameter('page')) { // Si le nombre de page à afficher à été spécifié...
            //On récupère le numéro de la page à afficher 
            $this->current_page = intval($request->getParameter('page'));
            // Si la valeur de $current_page (le numéro de la page) est plus grande que $nbpages(nombre de pages totale)...
            if ($this->current_page > $this->nb_pages) {
                $this->current_page = $this->nb_pages;
            }
        } else { // Sinon la page n'a pas été spécifiée et dans ce cas , on retourne la première page
            $this->current_page = 1; // La page actuelle est la n°1    
        }
        //A cette étape, le numéro de page à afficher est connu.
        //Il reste à déterminer la plage de données à retourner . (LIMIT debut , nombre d'enregistrements )

        /* l'enregistrement à partir duquel on va éffectuer notre requête est déterminé
         *  par le numéro de page et le nombre d'enregistrements par page soit :
         */
        if($this->nb_pages==0  || $this->current_page==0): 
            $this->current_page=1;  $this->nb_pages=1; 
            endif; 
        
        $this->first_entry = ($this->current_page - 1) * $this->max_delivery_per_page;

        //À cette étape , on a tous les éléments pour éffectuer notre requête 
        //Récupération de la liste des objets
        $this->deliverys = $this->ei_project->getPaginateDelivery(
                        $this->first_entry, $this->max_delivery_per_page, $this->searchDeliveryCriteria)
                ->execute();
        
        
    }
     //on remplit le formulaire de recherche avec les critères précedemment renseignés
    public function setDefaultValuesForSearchForm (){
       
        $this->deliverySearchForm->setDefault('title', $this->searchDeliveryCriteria['title']); 
        $this->deliverySearchForm->setDefault('author', $this->searchDeliveryCriteria['author']);  
        $this->deliverySearchForm->setDefault('state', $this->searchDeliveryCriteria['state']);
        $this->deliverySearchForm->setDefault('start_date', $this->searchDeliveryCriteria['start_date']);
        $this->deliverySearchForm->setDefault('end_date', $this->searchDeliveryCriteria['end_date']);
    }
    
    //Fonction de recherche des livraisons à partir de la searchBox (formulaire de recherche)
    public function executeSearchDeliveries(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        //Initilisation du formulaire de recherche d'une livraison
        $this->initializeDeliverySearchForm($this->ei_project);
        //Récupération des critères de recherche
        $this->getDeliverySearchCriteriaForm($request, $this->deliverySearchForm);
        //on remplit le formulaire de recherche avec les critères précedemment renseignés
        $this->setDefaultValuesForSearchForm();
        //Récupération des livraisons relativement aux différents critères 
        $this->getDeliveriesByCriteria($request);
        //var_dump($this->searchDeliveryCriteria);
        //Retour de template
        if($request->isXmlHttpRequest())  $this->setTemplate('searchBoxForSteps');  
        else $this->setTemplate('index');
    }
    
    
    //Listing des livraisons d'un projet (avec prise en compte d'éventuels critères)
    public function executeIndex(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        //récupération  de l'id des statuts de livraison ouverte du projet 
        $this->openStates=Doctrine_Core::getTable('EiDeliveryState')->findByProjectIdAndProjectRefAndCloseState(
                $this->project_id, $this->project_ref,0);
        //Initilisation du formulaire de recherche d'une livraison
        $this->initializeDeliverySearchForm($this->ei_project);
         
        //Récupération des critères de recherche
        $this->getDeliverySearchCriteria($request, $this->deliverySearchForm);
        //on remplit le formulaire de recherche avec les critères précedemment renseignés
        $this->setDefaultValuesForSearchForm(); 
        //Récupération des livraisons relativement aux différents critères 
        $this->getDeliveriesByCriteria($request); 
        
        /* Si c'est une action ajax (recherche des livraisons pour les steps de campagne par ex ) */
        if($request->isXmlHttpRequest())  $this->setTemplate('searchBoxForSteps');   
    }
    
    //Récupération des sujets d'une livraison
    public function executeGetDeliverySubjects(sfWebRequest $request){ 
        
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkDelivery($request,$this->ei_project);
        $this->addDeliveryInUserSession($this->ei_delivery);
//      Sujets d'une livraison
        $request->setParameter("delivery_id", $this->ei_delivery->getId());
        $request->setParameter('delivery',$this->ei_delivery->getId());
        $request->setParameter("contextRequest", 'EiDelivery'); //On injecte le contexte à la demande pour pouvoir le garder par la suite 
        $content = $this->getController()->getPresentationFor("eisubject", "index");
        return $this->renderText($content);
//      $this->ei_delivery_subjects = $this->ei_delivery->getDeliverySubjects();
    }
    //Recherche des sujets d'une livraison
    public function executeSearchDeliverySubjects(sfWebRequest $request){ 
        
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkDelivery($request,$this->ei_project);
        $this->addDeliveryInUserSession($this->ei_delivery);
        
//      Sujets d'une livraison
        $request->setParameter("delivery_id", $this->ei_delivery->getId());
        $request->setParameter('delivery',$this->ei_delivery->getId());
        $request->setParameter("contextRequest", 'EiDelivery'); //On injecte le contexte à la demande pour pouvoir le garder par la suite 
        $content = $this->getController()->getPresentationFor("eisubject", "searchSubjects");
        return $this->renderText($content);
//      $this->ei_delivery_subjects = $this->ei_delivery->getDeliverySubjects();
    } 

    public function executeNew(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $delivery = new EiDelivery();
        $delivery->setProjectId($this->ei_project->getProjectId());
        $delivery->setProjectRef($this->ei_project->getRefId());
        $delivery->setAuthorId($this->getUser()->getGuardUser()->getId());
        $this->form = new EiDeliveryForm($delivery);
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $delivery = new EiDelivery();
        $delivery->setProjectId($this->ei_project->getProjectId());
        $delivery->setProjectRef($this->ei_project->getRefId());
        $delivery->setAuthorId($this->getUser()->getGuardUser()->getId());
        $this->form = new EiDeliveryForm($delivery);

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkDelivery($request,$this->ei_project);
        $this->addDeliveryInUserSession($this->ei_delivery);
        $this->form = new EiDeliveryForm($this->ei_delivery);
    }
    public function executeShow(sfWebRequest $request) { 
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkDelivery($request,$this->ei_project);
        $this->addDeliveryInUserSession($this->ei_delivery);
        $this->form = new EiDeliveryForm($this->ei_delivery);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkDelivery($request,$this->ei_project);
        $this->form = new EiDeliveryForm($this->ei_delivery);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($ei_delivery = Doctrine_Core::getTable('EiDelivery')->find(array($request->getParameter('id'))), sprintf('Object ei_delivery does not exist (%s).', $request->getParameter('id')));
        $this->cleanDeliverySessionVar();
        $ei_delivery->delete();
        
        $this->redirect('eidelivery/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $ei_delivery = $form->save();
            $this->getUser()->setFlash('alert_form',
                    array('title' => 'Success' ,
                            'class' => 'alert-success' ,
                            'text' => 'Well done ...'));
            //Construction de l'url de retour
            $delivery_edit=$this->urlParameters;
            $delivery_edit['delivery_id']=$ei_delivery->getId();
            $delivery_edit['action']='edit'; 
            $this->redirect($this->generateUrl('delivery_edit',$delivery_edit));
            
        }
        else{
            $this->getUser()->setFlash('alert_form',
                    array('title' => 'Error' ,
                        'class' => 'alert-danger' ,
                        'text' => 'An error occurred while trying to save this delivery. Check requirements'));
        }
    }

}
