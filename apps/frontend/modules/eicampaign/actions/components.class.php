<?php

/**
 *
 * @author Lenine DJOUATSA
 */
class eicampaignComponents extends sfComponentsKalifast {

    //Recherche d'une campagne avec les paramètres de requête
    public function checkEiCampaign(sfWebRequest $request, EiProjet $ei_project) {
        $this->campaign_id = $request->getParameter('campaign_id');
        if ($this->campaign_id == null)
            $this->campaign_id = $request->getParameter('id');
        if (($this->campaign_id) != null) {
            //Recherche de la campagne en base
            $this->ei_campaign = Doctrine_Core::getTable('EiCampaign')->findOneByIdAndProjectIdAndProjectRef(
                    $this->campaign_id, $ei_project->getProjectId(), $ei_project->getRefId());
        } else
            $this->ei_campaign = null;
    }

    //Recherche d'une éventuelle livraison associée à la campagne
    public function checkCampaignDelivery(sfWebRequest $request, EiProjet $ei_project, EiCampaign $ei_campaign = null) {
        $this->delivery_id = $request->getParameter('delivery_id');
        if ($this->delivery_id == null) :
            $this->ei_delivery = null;
        else:
            //Recherche de la livraison tout en s'assurant qu'elle corresponde au projet courant 
            $this->ei_delivery = Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
                    $this->delivery_id, $ei_project->getProjectId(), $ei_project->getRefId());
            if ($this->ei_delivery != null && $ei_campaign != null) :
                //On vérifie l'association entre la campagne et la livraison
                $link = Doctrine_Core::getTable('EiDeliveryHasCampaign')->findOneByDeliveryIdAndCampaignId(
                        $this->delivery_id, $ei_campaign->getId());
                if ($link == null):
                    throw new Exception('This campaign is not associate to the delivery ...');
                endif;
            endif;
        endif;
    } 
            
    //Recherche d'un éventuel sujet associée à la campagne
    public function checkCampaignSubject(sfWebRequest $request, EiProjet $ei_project, EiCampaign $ei_campaign = null) {
        $this->subject_id = $request->getParameter('subject_id');
        if ($this->subject_id == null) :
            $this->ei_subject = null;
        else:
            //Recherche du sujet tout en s'assurant qu'elle corresponde au projet courant 
            $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneByIdAndProjectIdAndProjectRef(
                    $this->subject_id, $ei_project->getProjectId(), $ei_project->getRefId());
            if ($this->ei_subject != null && $ei_campaign != null) :
                //On vérifie l'association entre la campagne et le sujet
                $link = Doctrine_Core::getTable('EiSubjectHasCampaign')->findOneBySubjectIdAndCampaignId(
                        $this->subject_id, $ei_campaign->getId());
                if ($link == null):
                    throw new Exception('This campaign is not associate to the intervention ...');
                endif;
            endif;
        endif;
    }
    //Recherche d'une éventuelle fonction associée à la campagne
    public function checkCampaignFunction(sfWebRequest $request, EiProjet $ei_project, EiCampaign $ei_campaign = null){
        
    }

    public function executeSideBarCampaign(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiCampaign($request, $this->ei_project);
        $this->checkCampaignDelivery($request, $this->ei_project, $this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project, $this->ei_campaign);
        /* Liste des livraisons ouvertes dans la limite de 10 livraisons ordonnées pad date */
        $this->openDeliveries=$this->checkOpenDeliveries($this->ei_project);
    }

    public function executeSideBarHeaderObject(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiCampaign($request, $this->ei_project);
        $this->checkCampaignDelivery($request, $this->ei_project, $this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project, $this->ei_campaign);

        /** @var EiUser $user */
        $user = $this->getUser()->getGuardUser()->getEiUser();

        $this->user_settings = Doctrine_Core::getTable('EiUserSettings')
                ->findOneByUserRefAndUserId($user->getRefId(), $user->getUserId());

        $this->firefox_path = $this->user_settings == null ? : $this->user_settings->getFirefoxPath();

        switch ($request->getParameter('module')):
            case 'eicampaigngraph':
                // Récupération du paramètre exécution s'il existe.
                $execution_id = $request->getParameter("execution_id");
                $this->selectedCampaignExecution = null;
                $this->campaignExecutionGraphs = null;
                $this->campaignExecutionGraphsKeys = null;

                /** @var EiUser $user */
                $user = $this->getUser()->getGuardUser()->getEiUser();

                // On vérifie si l'utilisateur consulte une exécution, si c'est le cas, on la charge.
                if (isset($execution_id) && preg_match("/^([0-9]+)$/", $execution_id)) {
                    /** @var EiCampaignExecution $campaign_execution */
                    $campaign_execution = Doctrine_Core::getTable('EiCampaignExecution')->find($execution_id);

                    if ($campaign_execution->getCampaignId() === $this->ei_campaign->getId()) {
                        $this->selectedCampaignExecution = $campaign_execution;
                        $this->campaignExecutionGraphs = array();
                        $this->campaignExecutionGraphsKeys = array();
                        $tempGraph = Doctrine_Core::getTable('EiCampaignExecutionGraph')->getGraphHasChainedList($this->selectedCampaignExecution);

                        /** @var EiCampaignExecutionGraph $graph */
                        foreach ($tempGraph as $graph) {
                            $this->campaignExecutionGraphs[$graph->getGraphId()] = $graph;
                            $this->campaignExecutionGraphsKeys[] = $graph->getGraphId();
                        }
                    }
                }

                $this->ei_project->createDefaultStepTypeCampaign(); //Création des steps type par défaut s'il n'hexistent pas encore
                $this->user_settings = Doctrine_Core::getTable('EiUserSettings')->findOneByUserRefAndUserId($user->getRefId(), $user->getUserId());
                $this->campaignGraphBlockType = Doctrine_Core::getTable('EiBlockType')->findAll();
//                $this->campaignExecutions = Doctrine_Core::getTable('EiCampaignExecution')->getAllCampaignExecutions($this->ei_campaign->getId());
                break;
            default:
                break;
        endswitch;
    }

    public function executeBreadcrumb(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->breadcrumb = array(); //Tableau destiné à contenir le breadcrumb
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');
        $this->checkEiCampaign($request, $this->ei_project);
        /* Détermination du préfixe de breadcrumb suivant le type de campagne à traiter.
         * (campagne indépendante , campagne de livraison ou campagne d'un sujet )
         */
        $this->checkCampaignDelivery($request, $this->ei_project, $this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project, $this->ei_campaign);
        $this->checkCampaignFunction($request, $this->ei_project, $this->ei_campaign);
        if ($this->ei_delivery != null || $this->ei_subject != null):
            if ($this->ei_delivery != null):
                $this->evaluateSubBreadCampForDelivery();
            endif;
            if ($this->ei_subject != null):
                $this->evaluateSubBreadCampForSubject();
            endif;

        else:
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_campaign','Campaigns',$this->generateUrl('campaign_list', $this->urlParameters),
                    null,null,'AccessProjectCampaignsOnBreadCrumb');  

        endif;


        switch ($mod):
            case "eicampaignexecution":
                //$this->checkCampaignExecution($request, $this->ei_project, $this->ei_campaign);
                $this->campaign_execution=$this->getExecutionDetails($this->campaign_execution_id);
                $this->evaluateSubBreadCampGraph($act);
                break;
            case "eicampaigngraph":
                $this->evaluateSubBreadCampGraph($act);
                break;
            case 'eicampaign':
                $this->evaluateSubBreadCamp($act);
                break;
            case 'eideliveryCampaign':
                switch ($act):
                    case "getDeliveryCampaigns":
                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_list','List',null,true,true);
                    
                        break;
                    case "new":
                    case "create":
                        $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_add','New',null,true,true); 
                        break;
                        break;
                    default:
                        break;
                endswitch;
                break;
            case 'eisubjecthascampaign':
                switch ($act):
                    case "index":
                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_list','List',null,true,true);  
                        break;
                    case "new":
                    case "create":
                        $this->breadcrumb[] =  $this->setBreadcrumbTabItem('ei_add','New',null,true,true); 
                        break;
                        break;
                    case "edit":
                    case "update":
                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_edit','Edit',null,true,true); 
                        break;
                        break;
                    default:
                        break;
                endswitch;
                break;
                break;
            default:
                break;
        endswitch;
    }

    //Détermination du prefixe de breadcrumb dans le cas d'une campagne de livraison
    public function evaluateSubBreadCampForDelivery() {
        $getDeliveryCampaigns = $this->urlParameters;
        $getDeliveryCampaigns['delivery_id'] = $this->ei_delivery->getId();
        $delivery_show = $this->urlParameters;
        $delivery_show['delivery_id'] = $this->ei_delivery->getId();
        $delivery_show['action'] = 'show';
        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_delivery','Deliveries',$this->generateUrl('delivery_list', $this->urlParameters),
                null,null,'AccessProjectDeliveriesOnBreadCrumb'); 

        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_delivery',$this->ei_delivery,$this->generateUrl('delivery_edit', $delivery_show) );  

        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_campaign','Delivery Campaigns',$this->generateUrl('getDeliveryCampaigns', $getDeliveryCampaigns),
                null,null,'AccessDeliveryCampaignsOnBreadCrumb');   
    }

    //Détermination du prefixe de breadcrumb dans le cas d'une campagne de sujet
    public function evaluateSubBreadCampForSubject() {
        $getSubjectCampaigns = $this->urlParameters;
        $getSubjectCampaigns['subject_id'] = $this->ei_subject->getId();
        $subject_show = $this->urlParameters;
        $subject_show['subject_id'] = $this->ei_subject->getId();
        $subject_show['action'] = 'show';
        $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_subject','Bugs',$this->generateUrl('subjects_list', $this->urlParameters),
                null,null,'AccessProjectSubjectsOnBreadCrumb');  

        $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_subject',$this->ei_subject,$this->generateUrl('subject_edit', $subject_show),
                null,null,'AccessProjectSubjectsOnBreadCrumb'); 

        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_subject','Interventions Campaigns',$this->generateUrl('subjectCampaignsList', $getSubjectCampaigns),
                null,null,'AccessSubjectCampaignsOnBreadCrumb');   
    }

    //Détermination du prefixe de breadcrumb dans le cas d'une campagne indépendante
    public function evaluateSubBreadCamp($act) {
        if ($this->ei_campaign != null):
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_campaign',$this->ei_campaign);  
        endif;

        switch ($act):
            case "new":
            case "create":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add','New',null,  true,true); 
                break;
                break;
            case "edit":
                $this->breadcrumb[] =  $this->setBreadcrumbTabItem('ei_edit','Edit',null,  true,true);  
                break;
            case "show":
                $this->breadcrumb[] =  $this->setBreadcrumbTabItem('ei_show','Show',null,  true,true); 
                break;
            case "index":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_list','List',null,  true,true); 
                break;

            default:
                break;
        endswitch;
    }

    //Détermination du prefixe de breadcrumb dans le cas du contenu d'una campagne (steps , execution)
    public function evaluateSubBreadCampGraph($act) {
        if ($this->ei_campaign != null):
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_campaign',$this->ei_campaign);  
        endif;

        switch ($act):
            case "campaignReportsShow":
                $campaignReportsParams = $this->urlParameters;
                $campaignReportsParams["campaign_id"] = $this->ei_campaign->getId();

                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_testset',"Reports",$this->generateUrl('indexCampaignExecutions', $campaignReportsParams),null,null); 
                //$this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_testset',$this->campaign_execution['ce_id'],null,true,true); 
                break;
            case "campaignReportsIndex":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_testset',"Reports",null,  true,true);
                break;
            case "graphHasChainedList":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('fa-anchor',"Content",null,  true,true);
                break;
            case "showCampaignSteps":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('fa-step-forward',"Steps",null,  true,true);   
                break;
            case "editContent":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('fa-step-forward',"Steps",null,  true,true);  
                break;
            case "statistics":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('fa-chart-o',"Statistics",null,  true,true);  
                break;

            default:
                break;
        endswitch;
    }

    /**
     * @param sfWebRequest $request
     */
    public function executePlayerInstanciator(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiCampaign($request, $this->ei_project);

        /** @var EiUser $user */
        $user = $this->getUser()->getGuardUser()->getEiUser();

        $this->user_settings = Doctrine_Core::getTable('EiUserSettings')
            ->findOneByUserRefAndUserId($user->getRefId(), $user->getUserId());

        $this->projetRef = $this->ei_project->getRefId();
        $this->profilRef = $this->profile_ref;

        $this->firefoxPath = $this->user_settings == null ? : $this->user_settings->getFirefoxPath();

        $this->systeme = $request->getUriPrefix() . $request->getRelativeUrlRoot() . $request->getPathInfoPrefix();
    }

}

?>
