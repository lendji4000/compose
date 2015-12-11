<?php

/**
 * eicampaigngraph actions.
 *
 * @package    kalifastRobot
 * @subpackage eicampaigngraph
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eicampaigngraphActions extends sfActionsKalifast {
    //Recherche d'une éventuelle livraison associée à la campagne
    public function checkCampaignDelivery(sfWebRequest $request, EiProjet $ei_project,EiCampaign $ei_campaign=null) {
        $this->delivery_id = $request->getParameter('delivery_id');
        if ($this->delivery_id == null) :
            $this->ei_delivery=null;
            else:
            //Recherche de la livraison tout en s'assurant qu'elle corresponde au projet courant 
            $this->ei_delivery = Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
                $this->delivery_id, $ei_project->getProjectId(), $ei_project->getRefId());
                if ($this->ei_delivery != null && $ei_campaign!=null) :
                    //On vérifie l'association entre la campagne et la livraison
                    $link=Doctrine_Core::getTable('EiDeliveryHasCampaign')->findOneByDeliveryIdAndCampaignId(
                           $this->delivery_id,$ei_campaign->getId() );
                if($link==null):
                    throw new Exception ('This campaign is not associate to the delivery ...');
                endif;
                endif;
        endif; 
    }
    //Recherche d'un éventuel sujet associée à la campagne
    public function checkCampaignSubject(sfWebRequest $request, EiProjet $ei_project,EiCampaign $ei_campaign=null) {
        $this->subject_id = $request->getParameter('subject_id');
        if ($this->subject_id == null) :
            $this->ei_subject=null;
            else:
            //Recherche du sujet tout en s'assurant qu'elle corresponde au projet courant 
            $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneByIdAndProjectIdAndProjectRef(
                $this->subject_id, $ei_project->getProjectId(), $ei_project->getRefId());
            
                if ($this->ei_subject != null && $ei_campaign!=null) :
                    //On vérifie l'association entre la campagne et le sujet
                    $link=Doctrine_Core::getTable('EiSubjectHasCampaign')->findOneBySubjectIdAndCampaignId(
                           $this->subject_id,$ei_campaign->getId() );
                if($link==null):
                    throw new Exception ('This campaign is not associate to the intervention ...');
                endif;
                endif;
        endif; 
    }

    //On recherche le noeud de campagne du graphe
    public function checkCampaignGraph(sfWebRequest $request) {
        $this->campaign_graph_id = $request->getParameter('id');

        if ($this->campaign_graph_id == null):
            $this->forward404('Campaign parameter not found');
        else:
            $this->ei_campaign_graph = Doctrine_Core::getTable('EiCampaignGraph')
                    ->getCampaignGraphStep($this->campaign_graph_id);
            if (count($this->ei_campaign_graph) == null):
                $this->forward404('Campaign node not found');
            endif;
        endif;
    }

    //Récupération de la racine d'une campagne
    public function getCampaignRoot(EiCampaign $ei_campaign) {
        $this->ei_campaign_root = $ei_campaign->getRootCampaign();
    }

    //Insertion d'un step en temps que root d'une campagne
    public function addStepAsRoot(EiCampaignGraph $step, EiCampaign $current_campaign, EiCampaignGraph $new_step) {
        return $step->createStepAsRoot($current_campaign, $new_step);
    }

    //Recherche d'un noeud de graphe
    public function getNodeGraph($node_id) {
        return Doctrine_Core::getTable('EiCampaignGraph')->findOneById($node_id);
    }

    //Recherche du noeud parent d'un noeud du graphe
    public function getNodeParent(sfWebRequest $request) {
        //Paramètre parent 
        $this->parent_id = $request->getParameter('parent_id');
        if ($this->parent_id == null):
            $this->forward404('Node parent parameter not found');
        endif;
        if ($this->parent_id == 0)
            $this->nodeParent = null;
        else
            $this->nodeParent = $this->getNodeGraph($this->parent_id);
    }

    /* Vérifier si un jeu de données appartient bien à un scénario */
    public function verifyMatchingBetweenJddAndScenario($ei_scenario_id,$data_set_id){
        if($ei_scenario_id==null || $data_set_id==null) return false;
        //Recherche du scénario en base
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->findOneById($ei_scenario_id);
            //Si le scénario n'existe pas , alors on retourne un erreur 404
            if ($this->ei_scenario == null) return false;
        // Recherche du jeux de données en base   
        $ei_data_set=Doctrine_Core::getTable('EiDataSet')->findOneById($data_set_id);
        if($ei_data_set==null) return false;
        if(Doctrine_Core::getTable('EiDataSet')->verifyMatchingBetweenJddAndScenario(
                $ei_data_set,$this->ei_scenario)) return true;
        return false;
    }
    /* Mise à jour d'une step de campagne (changement de la description associée) */

    public function executeMajStepDesc(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkCampaignGraph($request);
        $this->success = false;
        $this->html = 'Error';
        $this->step_desc = $request->getParameter('step_desc');   
            $this->ei_campaign_graph->setDescription($this->step_desc);
            $this->success=$this->ei_campaign_graph->save();
            //$this->success = true;
            if($this->success) $this->html = 'Success';
         
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'campaign_graph_id' => $this->ei_campaign_graph->getId(),
                    'success' => $this->success)));
        return sfView::NONE;
    }
    /* Mise à jour d'une step de campagne (changement du dataset associé) */

    public function executeMajStepDataSet(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkCampaignGraph($request);
        $this->success = false;
        $this->html = 'Error';
        $this->data_set_id = $request->getParameter('data_set_id');
        $this->forward404If(!$this->data_set_id);
        $data_set = Doctrine_Core::getTable('EiDataSet')->findOneById($this->data_set_id);
        if ($data_set != null || $this->data_set_id != $this->ei_campaign_graph->getDataSetId()):
            /* On vérifie que le jeu de données appartient bien au scénario en question du step */
            if($this->verifyMatchingBetweenJddAndScenario($this->ei_campaign_graph->getScenarioId(),$this->data_set_id)):
                $this->ei_campaign_graph->setDataSetId($this->data_set_id);
                $this->ei_campaign_graph->save();
                $this->success = true;
                //Construction de l'url retour
                $eidataset_edit=$this->urlParameters;
                $eidataset_edit['ei_scenario_id']=$this->ei_campaign_graph->getScenarioId();
                $eidataset_edit['ei_data_set_id']=$this->data_set_id;
                $this->html = $this->generateUrl('eidataset_edit', $eidataset_edit, 
                    array('class' => 'btn btn-link btn-xs stepLineInContentDataSetTitle'));
            endif;
            
        endif;
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'data_set_id'=>$this->data_set_id,
                    'campaign_graph_id' => $this->ei_campaign_graph->getId(),
                    'success' => $this->success)));
        return sfView::NONE;
    }

    /* Mise à jour des positions des steps de campagne */

    public function executeMajStepInBase(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne
        $this->success = false;
        $ids = $request->getParameter('stepTab');
        if (Doctrine_Core::getTable('EiCampaignGraphHasGraph')
                        ->updatePosition(MyFunction::parseStringToTab($ids), $this->campaign_id) != 1):
            $this->html = 'Error';
        else:
            $this->html = 'well done';
            $this->success = true;
        endif;

        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'success' => $this->success)));
        return sfView::NONE;
    }

    /* Recherche d'une livraison et de ses campagnes */

    public function deliveryAndCampaigns($delivery_id, EiProjet $ei_project) {
        $this->delivery_id = $delivery_id;
        if ($this->delivery_id == null) : $this->ei_delivery = null;
            return null;
        endif;
        //Recherche de la livraison tout en s'assurant qu'elle corresponde au projet courant 
        $this->ei_delivery = Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
                $this->delivery_id, $ei_project->getProjectId(), $ei_project->getRefId());
        if ($this->ei_delivery == null) : $this->ei_delivery_campaigns = array();
            return null;
        endif;
        $this->ei_delivery_campaigns = $this->ei_delivery->getDeliveryCampaigns();
        $this->ei_delivery_subjects = $this->ei_delivery->getDeliverySubjects();
    }

    /* Recherche d'un sujet et de ses campagnes */

    public function subjectAndCampaigns($subject_id, EiProjet $ei_project) {
        $this->subject_id = $subject_id;
        if ($this->subject_id == null) : $this->ei_subject = null;
            return null;
        endif;
        //Recherche du subject tout en s'assurant qu'elle corresponde au projet courant 
        $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneByIdAndProjectIdAndProjectRef(
                $this->subject_id, $ei_project->getProjectId(), $ei_project->getRefId());
        $this->ei_subject_with_relation = Doctrine_Core::getTable('EiSubject')
                ->getSubject($ei_project->getProjectId(),$ei_project->getRefId(),$this->subject_id);
        if ($this->ei_subject == null) : $this->ei_subject_campaigns = array();
            return null;
        endif;
        //Campagnes d'un  sujet
        $this->ei_subject_campaigns = $this->ei_subject->getSubjectCampaigns();
    }

    /*
     * Ouvrir une campagne pour voir ses steps dans le menu de droite d'édition
     */

    public function executeShowCampaignSteps(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request); //Recherche d'un projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne
        $this->checkCampaignDelivery($request, $this->ei_project,$this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project,$this->ei_campaign); 
        
        $this->ei_project->createDefaultStepTypeCampaign(); //Création des steps type par défaut s'il n'existent pas encore
        $this->ei_campaign_graphs = Doctrine_Core::getTable('EiCampaignGraph')->getGraphHasChainedList($this->ei_campaign);
        
        $rightSideCampaignSteps = $this->urlParameters;
        $rightSideCampaignSteps['ei_campaign'] = $this->ei_campaign;
        $rightSideCampaignSteps['steps'] =$this->ei_campaign_graphs;
        $stepSearchGlobalBox = $this->urlParameters;
        $stepSearchGlobalBox['ei_delivery'] = null;
        $stepSearchGlobalBox['ei_subject'] = null;
        $stepSearchGlobalBox['ei_campaign'] = $this->ei_campaign;
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eicampaigngraph/rightSideCampaignSteps', $rightSideCampaignSteps),
                    'searchBox' => $this->getPartial('eicampaigngraph/stepSearchGlobalBox', $stepSearchGlobalBox),
                    'success' => true)));
        
        return sfView::NONE;
    }

    /* Action a executer au début du process */

    public function preAddStepInContent(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->before_step_id = $request->getParameter('before_step_id');
        $this->success = false;
        if ($this->before_step_id == null):
            $this->html = "Select position to add before ... ";
            return -1;
        endif;
        //Recherche de la campagne courante pour insertion
        $this->current_campaign_id = $request->getParameter('current_campaign_id');
        if ($this->current_campaign_id == null):
            $this->html = "Missing current campaign parameter ...";
            return -1;
        endif;

        $this->current_campaign = Doctrine_Core::getTable('EiCampaign')->findOneByIdAndProjectIdAndProjectRef(
                $this->current_campaign_id, $this->ei_project->getProjectId(), $this->ei_project->getRefId());
        if ($this->current_campaign == null):
            $this->html = "Campaign not found with theses parameters ...";
            return -1;
        endif;
        return 1;
    }

    public function addScenarioWithJddInContent(sfWebRequest $request) {
        //Ajout d'un scénario avec spécification du jeu de données
        $this->data_set_id = $request->getParameter('data_set_id');

        if ($this->data_set_id == 0 || $this->data_set_id == null):
        //$this->ei_campaign_graph->setDataSetId(null); 
        else:
            $this->ei_campaign_graph->setDataSetId($this->data_set_id);
        endif;
        return 1;
    }

    public function addScenarioInContent(sfWebRequest $request) {
        $campaign_graph_type = Doctrine_Core::getTable('EiCampaignGraphType')->findOneByProjectIdAndProjectRefAndName(
                $this->project_id, $this->project_ref, 'Test Suite');
        if ($campaign_graph_type == null):
            $this->html = "Fatal error , step type not found . contact administrator ...";
            return -1;
        endif;
        //Ajout d'un scénario sans spécification du jeu de données
        $this->ei_campaign_graph = new EiCampaignGraph();
        $this->ei_campaign_graph->setScenarioId($request->getParameter('ei_scenario_id'));
        $this->ei_campaign_graph->setCampaignId($this->current_campaign->getId());
        $this->ei_campaign_graph->setStepTypeId($campaign_graph_type->getId());
        $this->ei_campaign_graph->setState('Blank');
        //Si un jeu de données est renseigné , on l'ajout au step avant la sauvegarde
        if ($this->addScenarioWithJddInContent($request) == -1):
            $this->html = "Data set not found with giving parameter...";
            return -1;
        endif;
        $this->ei_campaign_graph->save($this->conn);
        $this->new_step = $this->ei_campaign_graph;
    }

    public function addStepInContent(sfWebRequest $request) {
        //Ajout d'une step de campagne au contenu
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne
        $this->checkCampaignGraph($request); //Step à inserer
        //Copie de l'étape à insérer

        $this->new_step = $this->ei_campaign_graph->copy();
        $this->new_step->setCampaignId($this->current_campaign->getId());
        //$this->new_step->save($this->conn);
        return 1;
    }

    public function addStepProcess() {
        /* A ce niveau on a tous les éléments permettant l'insertion du step */
        if ($this->before_step_id == 0):   //Ajout du step à la racine ou en première position 
            //On ajoute le step comme racine de la campagne
            if (($new_step = $this->addStepAsRoot($this->ei_campaign_graph, $this->current_campaign, $this->new_step)) != null):
                $stepLineInContent = $this->urlParameters;
                $stepLineInContent['ei_campaign_graph']=$new_step;
                $stepLineInContent['is_lighter']=true;
                $this->html = $this->getPartial('eicampaigngraph/stepLineInContent',$stepLineInContent);
                $this->success = true;
            else:
                $this->html = "Error when trying to add step ...";
                throw new Exception('error');
            endif;

        else: //Ajout du step après un élément bien identifé
            $this->step_before = Doctrine_Core::getTable('EiCampaignGraph')
                    ->getCampaignGraphStep($this->before_step_id);
            if ($this->step_before == null):
                $this->html = "Node before not found. Refresh the page ...";
                throw new Exception('error');
            else:

                if (($new_step = $this->ei_campaign_graph->addStepAsNextOf($this->step_before, $this->new_step)) != null):
                    $stepLineInContent = $this->urlParameters;
                    $stepLineInContent['ei_campaign_graph']=$new_step;
                    $stepLineInContent['is_lighter']=true;
                    $this->html = $this->getPartial('eicampaigngraph/stepLineInContent',$stepLineInContent);
                    $this->success = true;
                else:
                    $this->html = "Error when trying to add step ... ";
                    throw new Exception('error');
                endif;
            endif;
        endif;
    }

    /*  Insertion d'une step le contenu d'une campagne */

    public function executeAddStepInContent(sfWebRequest $request) {
        $this->conn = Doctrine_Manager::connection();
        //Récupération des paramètres indispensables au process 
        if ($this->preAddStepInContent($request) == -1)
            throw new Exception('error');
        //return  $this->renderText(json_encode(array( 'html' => $this->html, 'success' => $this->success)));
        try {
            $this->conn->beginTransaction();
            //Cas d'ajout d'un step de campagne
            if ($request->getParameter('campaign_id') != null && $request->getParameter('campaign_id') != 0 && $request->getParameter('id') != null && $request->getParameter('id') != 0):
                if ($this->addStepInContent($request) == -1)
                    throw new Exception('error');
            //return  $this->renderText(json_encode(array( 'html' => $this->html, 'success' => $this->success))); 
            endif;
            if ($request->getParameter('ei_scenario_id') != null && $request->getParameter('ei_scenario_id') != 0):
                if ($this->addScenarioInContent($request) == -1)
                    throw new Exception('error');
            //return  $this->renderText(json_encode(array( 'html' => $this->html, 'success' => $this->success)));
            endif;

            /* A ce niveau on a tous les éléments permettant l'insertion du step */
            $this->addStepProcess();

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollback();
            throw ($e);
        }
        return $this->renderText(json_encode(array(
                    'html' => $this->html, 'success' => $this->success)));
    }
    
    /* Ajout de plusieurs steps à la fois dans une campagne */
    public function executeAddManyStepsInContent(sfWebRequest $request){ 
        $this->conn = Doctrine_Manager::connection(); 
        $this->success=false; $this->html='';
        $selectStepTab=  MyFunction::parseSimpleStringToTab($request->getParameter('selectStepTab'));
        if(count($selectStepTab)==0) 
            return $this->renderText(json_encode(array(
                    'html' => "No step has been selected ...", 'success' => $this->success))); 
         try {
            //Récupération des paramètres indispensables au process 
            if ($this->preAddStepInContent($request) == -1)
                throw new Exception('error'); 
            $this->conn->beginTransaction();
            /* Recherche des éléments entre lesquelles on va insérer les steps */
            $this->lastStep=null;
            if($this->before_step_id==0):
                $this->firstStep=Doctrine_Core::getTable('EiCampaignGraph')->findOneById($selectStepTab[0]);
                $this->lastStep=$this->current_campaign->getRootCampaign();
                else:
                  $this->firstStep=Doctrine_Core::getTable('EiCampaignGraph')->findOneById($this->before_step_id);
                  $nextStep=$this->firstStep->getNextStep($this->conn);
                  if($nextStep!=null):
                      $this->lastStep=$nextStep; 
                  endif;
            endif;

            /* A ce niveau on a tous les éléments permettant l'insertion des steps */
            $this->addManyStepProcess($this->current_campaign,$selectStepTab,$this->firstStep,$this->lastStep);
            /* On tente de récupérer le root de la campagne en fin de process.
             * S'il y'a eu des soucis, cette fonction lèvera une exception et annulera la transaction */
            $this->current_campaign->getRootCampaign();
            /* Validation globale de la transaction */
            $this->conn->commit();
            $manyStepsLineContent = $this->urlParameters;
            $manyStepsLineContent['ei_campaign'] =$this->ei_campaign;
            $manyStepsLineContent['steps'] = $this->collection_step;
            $this->html=$this->getPartial('eicampaigngraph/manyStepsLineContent', $manyStepsLineContent) ;
            $this->success=true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw ($e);
        }
        return $this->renderText(json_encode(array(
                    'html' => $this->html, 'success' => $this->success)));

        return sfView::NONE;
    }
    /* Process d'ajout de plusieurs steps à une campagne */
    public function addManyStepProcess(EiCampaign $ei_campaign,array $selectStepTab,EiCampaignGraph $firstStep,EiCampaignGraph $lastStep=null ){
        $bef=$firstStep;     
        $this->collection_step=new Doctrine_Collection('EiCampaignGraph'); 
        foreach($selectStepTab as $i => $stepId):
        if($this->before_step_id==0 && $i==0):
            $step=$bef->copy();
            $step->setCampaignId($ei_campaign->getId());
            $step->save($this->conn); 
            $this->collection_step->add($step); //Enregistrement du step dans la collection à retourner 
            $bef=$step;
            else:
                $step=Doctrine_Core::getTable('EiCampaignGraph')->findOneById($stepId);
                $step=$step->copy();
                $step->setCampaignId($ei_campaign->getId());
                $step->save($this->conn);
                $this->collection_step->add($step);//Enregistrement du step dans la collection à retourner 
                //Création de la relation
                $new_relation=new EiCampaignGraphHasGraph();
                $new_relation->setParentId($bef->getId());
                $new_relation->setChildId($step->getId());
                $new_relation->setCampaignId($ei_campaign->getId());  
                $new_relation->save($this->conn);
                //Changement du bef
                $bef=$step;
        endif;
        
        endforeach;
        //On vérifie s'il existait un élément après le curseur  
        if($lastStep!=null):
            //Création de la relation
                $new_relation=new EiCampaignGraphHasGraph();
                $new_relation->setParentId($bef->getId());
                $new_relation->setChildId($lastStep->getId());
                $new_relation->setCampaignId($ei_campaign->getId()); 
                $new_relation->save($this->conn);
        endif;  
    }
    
    /* Ajout d'une action manuelle */

    public function executeAddManualStepInContent(sfWebRequest $request) {
        $this->conn = Doctrine_Manager::connection();
        //Récupération des paramètres indispensables au process 
        if ($this->preAddStepInContent($request) == -1)
            throw new Exception('error');
        try {
            $this->conn->beginTransaction();
            $campaign_graph_type = Doctrine_Core::getTable('EiCampaignGraphType')->findOneByProjectIdAndProjectRefAndName(
                    $this->project_id, $this->project_ref, 'Manual Action');
            if ($campaign_graph_type == null):
                $this->html = "Fatal error , step type not found . contact administrator ...";
                return -1;
            endif;
            //Ajout d'un scénario sans spécification du jeu de données
            $this->ei_campaign_graph = new EiCampaignGraph();
            $this->ei_campaign_graph->setCampaignId($this->current_campaign->getId());
            $this->ei_campaign_graph->setStepTypeId($campaign_graph_type->getId());
            $this->ei_campaign_graph->setState('Blank');

            $this->ei_campaign_graph->save($this->conn);
            $this->new_step = $this->ei_campaign_graph;

            $this->addStepProcess();

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollback();
            throw ($e);
        }
        return $this->renderText(json_encode(array(
                    'html' => $this->html, 'success' => $this->success)));

        return sfView::NONE;
    }

    /* Edition du contenu d'une campagne (ses steps ) */

    public function executeEditContent(sfWebRequest $request) {
        //Le graphe est déterminé par rapport à une campagne de tests 
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne
        $this->checkCampaignDelivery($request, $this->ei_project,$this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project,$this->ei_campaign);
        
        $this->ei_project->createDefaultStepTypeCampaign(); //Création des steps type par défaut s'il n'existent pas encore
        $this->ei_campaign_graphs = Doctrine_Core::getTable('EiCampaignGraph')->getGraphHasChainedList($this->ei_campaign);
        /* On vérifie si une livraison ou un sujet est spécifié dans l'url */
        if (($this->subject_id = $request->getParameter('subject_id')) != null):
            $this->subjectAndCampaigns($this->subject_id, $this->ei_project);
        else ://On recherche si la campagne est associée à un sujet ; auquel cas on récupère ce sujet par défaut
            $this->subjectCampaign = Doctrine_Core::getTable('EiSubjectHasCampaign')
                    ->findByCampaignId($this->campaign_id);
            if (count($this->subjectCampaign) == 1):
                $this->subjectAndCampaigns($this->subjectCampaign->getFirst()->getSubjectId(), $this->ei_project);
            endif;
        endif;
        if (($this->delivery_id = $request->getParameter('delivery_id')) != null):
            $this->deliveryAndCampaigns($this->delivery_id, $this->ei_project);
        else://On recherche si la campagne est associée à une livraison ; auquel cas on récupère cette livraison par défaut
            $this->ei_delivery_has_campaign = Doctrine_Core::getTable('EiDeliveryHasCampaign')
                    ->findByCampaignId($this->campaign_id);
            if (count($this->ei_delivery_has_campaign) == 1):
                $this->deliveryAndCampaigns($this->ei_delivery_has_campaign->getFirst()->getDeliveryId(), $this->ei_project);
            endif;
        endif;
        //Si aucun critère n'est renseigné , on récupère les dernières campagnes ajoutées
        //On récupère également les flags sur la campagne
        if ($this->ei_subject == null && $this->ei_delivery == null):
            $this->lonelyCampaigns = Doctrine_Core::getTable('EiCampaign')->
                    getProjectCampaignsList($this->project_id, $this->project_ref,$this->ei_campaign)
                    ->orderBy('c.created_at , c.updated_at')
                    ->limit(20)
                    ->execute();
            $this->nbLonelyCampaigns = Doctrine_Core::getTable('EiCampaign')->
                    getNbLonelyCampaigns($this->project_id, $this->project_ref);
        endif;

        /* Récupération de l'arbre des scénarios pour l'ajout dans les steps */
        $this->root_node = $this->ei_project->getRootFolder(); 
         //Si le profil n'est pas retrouvé , on redirige l'utilisateur vers la connexion
        if($this->ei_profile==null) $this->forward ('connexion', 'signin');
        //Réccupération des noeuds ouverts
        $this->opened_ei_nodes = Doctrine_Core::getTable('EiTreeOpenedBy')
                ->getOpenedNodes($this->getUser()->getGuardUser()->getEiUser(), $this->project_ref, $this->project_id);
    }

    /* Récupération du graphe d'une campagne sous forme de tableau.
     * Première version : Chaque noeud possède au plus un fils et au plus un père.
     * On adapate la structure du graphe pour cette utilisation
     */

    public function executeGraphHasChainedList(sfWebRequest $request) {
        //Le graphe est déterminé par rapport à une campagne de tests
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne
        $this->checkCampaignDelivery($request, $this->ei_project,$this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project,$this->ei_campaign);
        
        // Récupération du paramètre exécution s'il existe.
        $execution_id = $request->getParameter("execution_id");

        $this->selectedCampaignExecution = null;
        $this->campaignExecutionGraphs = null;
        $this->campaignExecutionGraphsKeys = null;

        /** @var EiUser $user */
        $user = $this->getUser()->getGuardUser()->getEiUser(); 

        $this->user_settings = Doctrine_Core::getTable('EiUserSettings')
                ->findOneByUserRefAndUserId($user->getRefId(), $user->getUserId());

        $this->firefox_path = $this->user_settings == null ? : $this->user_settings->getFirefoxPath();
        
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
        $this->ei_campaign_graphs = Doctrine_Core::getTable('EiCampaignGraph')->getGraphHasChainedList($this->ei_campaign);
    }

    public function executeDownload(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkCampaignGraph($request);
        $filePath = sfConfig::get('sf_upload_dir') . $this->ei_campaign_graph->getPath();
        //$mimeType = mime_content_type($filePath);

        /** @var $response sfWebResponse */
        $response = $this->getResponse();
        $response->clearHttpHeaders();
        //$response->setContentType($mimeType);
        $response->setHttpHeader('Content-Disposition', 'attachment; filename="' . basename($filePath) . '"');
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

    public function executeIndex(sfWebRequest $request) {
        //Le graphe est déterminé par rapport à une campagne de tests 
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne 
        $this->checkCampaignDelivery($request, $this->ei_project,$this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project,$this->ei_campaign);
        
        $this->ei_campaign_graphs =
                Doctrine_Core::getTable('EiCampaignGraph')->getCampaignGraphs($this->ei_campaign);
        //Récupération du noeud racine du graphe
        $this->getCampaignRoot($this->ei_campaign);
        $this->campaignGraphBlockType = Doctrine_Core::getTable('EiBlockType')->findAll();
    }

    public function executeNew(sfWebRequest $request) {
        //Recherche du projet
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        //Recherche de la campagne concernée 
        $this->checkCampaign($request, $this->ei_project);
        $this->getNodeParent($request); //Récupération du noeud parent 
        $this->position = $request->getParameter('position'); //Récupération  de la position 
        //Initialisation du formulaire 
        $ei_campaign_graph = new EiCampaignGraph();
        $ei_campaign_graph->setEiCampaign($this->ei_campaign);
        $this->form = new EiCampaignGraphForm($ei_campaign_graph, array('ei_project' => $this->ei_project));
        $graphRelation = new EiCampaignGraphHasGraph();
        $graphRelation->setParentId($this->parent_id);
        $graphRelation->setCampaignChild($ei_campaign_graph);
        $graphRelation->setEiCampaign($this->ei_campaign);
        $graphRelation->setPosition($this->position);
        $this->form->embedForm('graphParent', new EiCampaignGraphHasGraphForm($graphRelation));


        //Récupération du root folder du projet 
        $this->root_folder = Doctrine_Core::getTable('EiNode')
                ->getRootFolder($this->project_ref, $this->project_id);
        //Récupération des noeuds enfants du dossier
        $this->ei_nodes = $this->root_folder->getNodes(false, false);

        //retour de la reponse du process (avec le partiel de la nouvelle assignation)
        $uri_form = $this->urlParameters;
            $uri_form['form'] =$this->form;
            $uri_form['ei_nodes'] = $this->ei_nodes;
            $uri_form['campaign_id'] = $this->campaign_id;
            $uri_form['root_folder'] = $this->root_folder;
            $uri_form['parent_id'] = $this->parent_id;
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eicampaigngraph/form', $uri_form),
                    'success' => true)));
        return sfView::NONE;
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST)); 
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        //Recherche de la campagne concernée 
        $this->checkCampaign($request, $this->ei_project);
        $this->getNodeParent($request); //Récupération du noeud parent
        $this->position = $request->getParameter('position'); //Récupération  de la position
        //Initialisation du formulaire 
        $ei_campaign_graph = new EiCampaignGraph();
        $ei_campaign_graph->setEiCampaign($this->ei_campaign);
        $this->form = new EiCampaignGraphForm($ei_campaign_graph, array('ei_project' => $this->ei_project));
        $graphRelation = new EiCampaignGraphHasGraph();
        $graphRelation->setParentId($this->parent_id);
        $graphRelation->setCampaignChild($ei_campaign_graph);
        $graphRelation->setEiCampaign($this->ei_campaign);
        $graphRelation->setPosition($this->position);
        $this->form->embedForm('graphParent', new EiCampaignGraphHasGraphForm($graphRelation));

        $this->processForm($request, $this->form);

        //Récupération du root folder du projet 
        $this->root_folder = Doctrine_Core::getTable('EiNode')
                ->getRootFolder($this->project_ref, $this->project_id);
        //Récupération des noeuds enfants du dossier
        $this->ei_nodes = $this->root_folder->getNodes(false, false);
        //Récupération du chemin firefox dans les settings utilisateur
        /** @var EiUser $user */
        $user = $this->getUser()->getGuardUser()->getEiUser();

        $this->user_settings = Doctrine_Core::getTable('EiUserSettings')
                ->findOneByUserRefAndUserId($user->getRefId(), $user->getUserId());


        $firefoxPath = $this->user_settings == null ? : $this->user_settings->getFirefoxPath();

        if ($this->success):
            $this->campaignGraphBlockType = Doctrine_Core::getTable('EiBlockType')->findAll();
            $campaignGraphLine = $this->urlParameters;
            $campaignGraphLine['ei_campaign_graph'] =Doctrine_Core::getTable('EiCampaignGraph')->getCampaignGraphStep($this->ei_campaign_graph->getId());
            $campaignGraphLine['project_name'] = $this->ei_project->getName();
            $campaignGraphLine['firefox_path'] = $firefoxPath;
            $campaignGraphLine['campaignGraphBlockType'] = $this->campaignGraphBlockType; 
            $campaign_graph_new = $this->urlParameters;
            $campaign_graph_new['campaign_id'] =$this->ei_campaign_graph->getId();
            $campaign_graph_new['parent_id'] = $this->ei_campaign_graph->getId(); 
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eicampaigngraph/campaignGraphLine',$campaignGraphLine),
                        'new_create_url' => $this->generateUrl('campaign_graph_new', $campaign_graph_new),
                        'updateMode' => false,
                        'success' => true)));
        else:
            $uri_form = $this->urlParameters;
            $uri_form['form'] =$this->form;
            $uri_form['ei_nodes'] = $this->ei_nodes;
            $uri_form['campaign_id'] = $this->campaign_id;
            $uri_form['root_folder'] = $this->root_folder;
            $uri_form['parent_id'] = $this->parent_id;
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eicampaigngraph/form', $uri_form),
                        'success' => false)));

        endif;
        //Retour de la réponse json en cas de succès 

        return sfView::NONE;
    }

    //Vérification du type (automatisable ou pas ) d'un step type de campagne
    public function executeIsAutomate(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->step_type_id = intval($request->getParameter('step_type_id'));
        if ($this->step_type_id == null)
            $this->forward404('Step type parameter is NULL');
        $stepType = Doctrine_core::getTable('EiCampaignGraphType')->findOneByProjectIdAndProjectRefAndId(
                $this->project_id, $this->project_ref, $this->step_type_id);
        if ($stepType == null)
            $this->forward404('Step type not found');
        if ($stepType->getAutomate()):
            return $this->renderText(json_encode(array(
                        'html' => "Is automatizable",
                        'success' => true)));
        else :
            return $this->renderText(json_encode(array(
                        'html' => "Is not automatizable",
                        'success' => false)));
        endif;
    }

    public function executeEdit(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkCampaignGraph($request);
        $this->ei_campaign = $this->ei_campaign_graph->getCampaign($this->project_id, $this->project_ref);
        if ($this->ei_campaign == null)
            $this->forward404('Campaign not found');
        $this->form = new EiCampaignGraphForm($this->ei_campaign_graph, array('ei_project' => $this->ei_project));
        //Récupération du root folder du projet 
        $this->root_folder = Doctrine_Core::getTable('EiNode')
                ->getRootFolder($this->project_ref, $this->project_id);
        //Récupération des noeuds enfants du dossier
        $this->ei_nodes = $this->root_folder->getNodes(false, false);

        $uri_form = $this->urlParameters;
            $uri_form['form'] =$this->form;
            $uri_form['ei_nodes'] = $this->ei_nodes;
            $uri_form['campaign_id'] = $this->campaign_id;
            $uri_form['root_folder'] = $this->root_folder;
            $uri_form['parent_id'] = $this->parent_id;
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eicampaigngraph/form', $uri_form),
                    'success' => true)));

        return sfView::NONE;
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkCampaignGraph($request);        
        $this->ei_campaign = $this->ei_campaign_graph->getCampaign($this->project_id, $this->project_ref);
        if ($this->ei_campaign == null)
            $this->forward404('Campaign not found');
        $this->form = new EiCampaignGraphForm($this->ei_campaign_graph, array(
            'ei_project' => $this->ei_project));
 

        $this->processForm($request, $this->form); 

        if ($this->success):
            $this->campaignGraphBlockType = Doctrine_Core::getTable('EiBlockType')->findAll();
            $downloadCampaignNodeAttachment = $this->urlParameters;
            $downloadCampaignNodeAttachment['campaign_id'] =$this->ei_campaign_graph->getCampaignId();
            $downloadCampaignNodeAttachment['id'] = $this->campaign_graph_id; 
            return $this->renderText(json_encode(array( 
                        'html' =>  (($this->ei_campaign_graph->getFilename()!=null)? 
                                $this->generateUrl('downloadCampaignNodeAttachment',$downloadCampaignNodeAttachment): '#'),
                        'filename' => (($this->ei_campaign_graph->getFilename()!=null)?
                                        MyFunction::troncatedText($this->ei_campaign_graph->getFilename(),17):''),
                        'updateMode' => true,
                        'step_id' => $this->campaign_graph_id,
                        //'campaignGraphStepId' => 'campaignGraphStep' . $this->campaign_graph_id,
                        'success' => true)));
        else:
            $uri_form = $this->urlParameters;
            $uri_form['form'] =$this->form;
            $uri_form['ei_nodes'] = $this->ei_nodes;
            $uri_form['campaign_id'] = $this->campaign_id;
            $uri_form['root_folder'] = $this->root_folder;
            $uri_form['parent_id'] = $this->parent_id; 
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eicampaigngraph/form', $uri_form),
                        'success' => false)));

        endif;

        return sfView::NONE;
    }

    public function executeDelete(sfWebRequest $request) {
        //$request->checkCSRFProtection();
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkCampaignGraph($request);
        $this->ei_campaign = $this->ei_campaign_graph->getCampaign($this->project_id, $this->project_ref);
        $deleteResult = $this->ei_campaign_graph->deleteNode();
        $this->getUser()->setFlash('delete_step_success', 'Step has been deleted successfully ... ');
        if ($request->getParameter('redirect') == 0 && $request->isXmlHttpRequest()):
            return $this->renderText(json_encode(array(
                        'html' => 'Success',
                        'success' => true)));
        endif;
        $graphHasChainedList = $this->urlParameters; 
            $graphHasChainedList['campaign_id'] =$this->ei_campaign->getId();
        $this->redirect($this->generateUrl('graphHasChainedList', $graphHasChainedList));
        return sfView::NONE;
    }

    /* Récupération des fils d'un noeud de l'arbre des scénarios .
     * Utilisation pour les noeuds de graphe de campagne.
     */

    public function executeGetNodeChildsForCampaignGraph(sfWebRequest $request) {
        $this->success = false;
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->ei_node_id = $request->getParameter('ei_node_id');
        $this->ei_node_type = $request->getParameter('ei_node_type');

        $this->ei_node = Doctrine_Core::getTable('EiNode')
                ->findOneByIdAndProjectIdAndProjectRefAndType(
                $this->ei_node_id, $this->project_id, $this->project_ref, $this->ei_node_type);
        /* Si le type de noeud est 'EiDataSet ou EiDataSetFolder , 
         * on ramene les noeuds de type EiDataSet et  EiDataSetFolder en plus 
         */
        if ($this->ei_node_type == "EiDataSet" || $this->ei_node_type == "EiDataSetFolder")
            $this->ei_nodes = $this->ei_node->getNodes(true, true);
        else
            $this->ei_nodes = $this->ei_node->getNodes(false, false);

        $this->success = true;
        //retour de la reponse du process (avec le partiel de la nouvelle assignation)
        $nodeChilds = $this->urlParameters; 
            $nodeChilds['ei_nodes'] =$this->ei_nodes;
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eicampaigngraph/nodeChilds',$nodeChilds),
                    'success' => $this->success)));
        return sfView::NONE;
    }

    //Evenement sur choix d'un scénario comme pour un noeud de graphe de campagne
    public function executeChooseTestSuiteForCampaignGraphNode(sfWebRequest $request) {
        $this->success = false;
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->ei_node_id = $request->getParameter('ei_node_id');
        $this->ei_node_type = $request->getParameter('ei_node_type');
        //Récupération du noeud du scénario
        $this->ei_node = Doctrine_Core::getTable('EiNode')
                ->findOneByIdAndProjectIdAndProjectRefAndType(
                $this->ei_node_id, $this->project_id, $this->project_ref, $this->ei_node_type);
        //Récupération du noeud du jeux de données racine du scénario
        $this->ei_data_set_root_folder = Doctrine_Core::getTable('EiNode')
                ->findOneByRootIdAndType($this->ei_node->getId(), 'EiDataSetFolder');

        $this->forward404Unless($this->ei_data_set_root_folder, "Système error . Test suite must contain root folder for data sets");

        $this->ei_data_set_children = Doctrine_Core::getTable('EiNode')
                ->findByRootId($this->ei_data_set_root_folder->getId());

        $this->success = true;
        //retour de la reponse du process (avec le partiel de la nouvelle assignation)
        $testSuiteJddRootFolder = $this->urlParameters; 
            $testSuiteJddRootFolder['ei_data_set_children'] =$this->ei_data_set_children;
            $testSuiteJddRootFolder['ei_data_set_root_folder'] =$this->ei_data_set_root_folder;
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eicampaigngraph/testSuiteJddRootFolder',$testSuiteJddRootFolder),
                    'test_suite_id' => $this->ei_node->getObjId(),
                    'test_suite_name' => $this->ei_node->getName(),
                    'success' => $this->success)));
        return sfView::NONE;
    }

    //Evenement sur choix d'un jeu de données 
    public function executeChooseDataSetForCampaignGraphNode(sfWebRequest $request) {
        $this->success = false;
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->ei_node_id = $request->getParameter('ei_node_id');
        $this->ei_node_type = $request->getParameter('ei_node_type');
        $this->ei_node = null;

        // Récupération du noeud du jeu de données
        if( $this->ei_node_type == "EiDataSetTemplate" ){
            $this->ei_node = Doctrine_Core::getTable('EiDataSetTemplate')->findOneByEiNodeId($this->ei_node_id);

            if( $this->ei_node != null && $this->ei_node->getEiDataSet() != null ){
                $this->ei_node = $this->ei_node->getEiDataSet()->getEiNode();
            }
        }

        if( $this->ei_node == null ){
            $this->ei_node = Doctrine_Core::getTable('EiNode')
                ->findOneByIdAndProjectIdAndProjectRefAndType(
                    $this->ei_node_id, $this->project_id, $this->project_ref, $this->ei_node_type);
        }

        /* Récupération du scénario du jeux de données pour se rassurer
         *  que le scénario et le jeux de données se correspondent
         */
        $this->ei_scenario_node = $this->ei_node->getEiScenarioNode();
        $this->success = true;
        //retour de la reponse du process (avec le partiel de la nouvelle assignation)
        
        return $this->renderText(json_encode(array(
                    'html' => "Choix du jeu de données éffectué avec succès",
                    'data_set_id' => $this->ei_node->getObjId(),
                    'data_set_name' => $this->ei_node->getName(),
                    'test_suite_id' => $this->ei_scenario_node->getObjId(),
                    'test_suite_name' => $this->ei_scenario_node->getName(),
                    'success' => $this->success)));
        return sfView::NONE;
    }

    /**
     * Action permettant de mettre à jour les status des étapes d'une campagne.
     * Appelable uniquement via ajax.
     *
     * Nécessite l'id de la campagne en paramètre.
     *
     * @param sfWebRequest $request
     */
    public function executeResettingStatusCampaign(sfWebRequest $request) {
        // On définit le statut par défaut de l'action.
        $this->success = false;

        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        if ($request->isXmlHttpRequest()) {
            // On vérifie les dépendances avec le projet.
            $this->checkProject($request);

            // On recherche la campagne et on vérifie la cohérence par rapport au projet.
            $this->checkCampaign($request, $this->ei_project);

            // Récupération de l'état vide.
            $etatVide = sfConfig::get("app_campaigngraphstateblank");

            // Récupération da la connexion.
            $connexion = Doctrine_Manager::connection();

            try {
                $connexion->beginTransaction();
                $etats = array();

                /** @var EiCampaign $ei_campaign */
                $ei_campaign = $this->ei_campaign;

                if ($request->getParameter("execution_id") != 0) {

                    $execution = Doctrine_Core::getTable("EiCampaignExecution")->find($request->getParameter("execution_id"));
                    $executionsGraph = Doctrine_Core::getTable('EiCampaignExecutionGraph')->getGraphHasChainedList($execution);

                    /** @var EiCampaignExecutionGraph $graph */
                    foreach ($executionsGraph as $graph) {
                        $graph->setState($etatVide);
                        $graph->save();

                        $etats[$graph->getGraphId()] = $etatVide;
                    }
                } else {
                    // On récupère le graphe de la campagne pour mettre à jour le statut.
                    $this->ei_campaign_graphs = $ei_campaign->getGraphCampaign();

                    /** @var EiCampaignGraph $graph */
                    foreach ($this->ei_campaign_graphs as $graph) {
                        $graph->setState($etatVide);
                        $graph->save();

                        $etats[$graph->getId()] = $etatVide;
                    }
                }

                $connexion->commit();

                return $this->renderText(json_encode(array("resultats" => $etats, 'success' => true)));
            } catch (Exception $e) {
                if ($connexion !== null)
                    $connexion->rollback();

                throw $e;
            }
        }
        else {
            $this->forward404("Not Found.");
        }
    }

    /**
     * Action permettant de mettre à jour les status des étapes d'une campagne.
     * Appelable uniquement via ajax.
     *
     * Nécessite l'id de la campagne en paramètre.
     *
     * @param sfWebRequest $request
     */
    public function executeRefreshStatusCampaign(sfWebRequest $request) {
        // On définit le statut par défaut de l'action.
        $this->success = false;
        $stop = false;

        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        if ($request->isXmlHttpRequest() || 1 === 1) {
            // On vérifie les dépendances avec le projet.
            $this->checkProject($request); //Récupération du projet
            $this->checkProfile($request, $this->ei_project); //Récupération du profil courant

            // On recherche la campagne et on vérifie la cohérence par rapport au projet.
            $this->checkCampaign($request, $this->ei_project);

            // Tableau contenant les états de chaque étape.
            $etats = array();
            // Tableau contenant les liens vers les oracles de chaque étape terminée.
            $oracles = array();

            /** @var EiCampaign $ei_campaign */
            $ei_campaign = $this->ei_campaign;

            if ($request->getParameter("execution_id") != 0) {

                /** @var EiCampaignExecution $execution */
                $execution = Doctrine_Core::getTable("EiCampaignExecution")->find($request->getParameter("execution_id"));
                $executionsGraph = Doctrine_Core::getTable('EiCampaignExecutionGraph')->getGraphHasChainedList($execution);
                $stop = true;

                /** @var EiCampaignExecutionGraph $graph */
                foreach ($executionsGraph as $graph) {
                    $etats[$graph->getGraphId()] = $graph->getState();

                    if ($graph->getEiTestSetId() == null) {
                        $stop = false;
                        $oracles[$graph->getGraphId()] = false;
                    } else {
                        $oracles[$graph->getGraphId()] = $this->generateUrl("eitestset_oracle", array(
                            'project_id' => $execution->getProjectId(),
                            'project_ref' => $execution->getProjectRef(),
                            'ei_scenario_id' => $graph->getScenarioId(),
                            'ei_test_set_id' => $graph->getEiTestSetId(),
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name
                        ));
                    }

                    if( $graph->getState() == StatusConst::STATUS_CAMP_PROCESSING_DB ){
                        $stop = false;
                    }
                }
            } else {
                // On récupère le graphe de la campagne pour mettre à jour le statut.
                $this->ei_campaign_graphs = $ei_campaign->getGraphCampaign();

                /** @var EiCampaignGraph $graph */
                foreach ($this->ei_campaign_graphs as $graph) {
                    $etats[$graph->getId()] = $graph->getState();
                }
            }

            return $this->renderText(json_encode(array("resultats" => $etats, 'success' => true, 'stop' => $stop, 'oracles' => $oracles)));
        } else {
            $this->forward404("Not Found.");
        }
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $tab = $request->getParameter('ei_campaign_graph');
            if ($tab['tmpPath'] != null && $tab['filename'] != null):
                copy($tab['tmpPath'], sfConfig::get('sf_upload_dir') . '/campaignGraphAttachments/' . time() . session_id() . $tab['filename']);
            else:
                $form->getObject()->setMimeType(null);
            endif;
            $this->ei_campaign_graph = $form->save();
            $this->success = true;

            //On vide le repertoire temporaire 
            if (isset($tab['tmpPath']) && $tab['tmpPath'] != null)
                unlink($tab['tmpPath']);
        }
        else {
            $this->success = false;
        }
    }

    /**
     * @return sfView
     */
//    private function returnJsonCampaignGraphLine() {
//
//        //Récupération du chemin firefox dans les settings utilisateur
//        /** @var EiUser $user */
//        $user = $this->getUser()->getGuardUser()->getEiUser();
//
//        $this->user_settings = Doctrine_Core::getTable('EiUserSettings')
//                ->findOneByUserRefAndUserId($user->getRefId(), $user->getUserId());
//
//
//        $firefoxPath = $this->user_settings == null ? : $this->user_settings->getFirefoxPath();
//
//        
//        return $this->renderText(json_encode(array(
//                    'html' => $this->getPartial('eicampaigngraph/campaignGraphLine', array(
//                        'ei_campaign_graph' => Doctrine_Core::getTable('EiCampaignGraph')->getCampaignGraphStep($this->ei_campaign_graph->getId()),
//                        'project_id' => $this->project_id,
//                        'project_ref' => $this->project_ref,
//                        'project_name' => $this->ei_project->getName(),
//                        'profile_id' => $this->profile_id,
//                        'profile_ref' => $this->profile_ref,
//                        'profile_name' => $this->profile_name ,
//                        'firefox_path' => $firefoxPath)),
//                    'new_create_url' => $this->generateUrl('campaign_graph_new', array(
//                        'project_id' => $this->project_id,
//                        'project_ref' => $this->project_ref,
//                        'profile_id' => $this->profile_id,
//                        'profile_ref' => $this->profile_ref,
//                        'profile_name' => $this->profile_name ,
//                        'campaign_id' => $this->campaign_id,
//                        'parent_id' => $this->ei_campaign_graph->getId()
//                    )),
//                    'updateMode' => false,
//                    'success' => true)));
//    }

}
