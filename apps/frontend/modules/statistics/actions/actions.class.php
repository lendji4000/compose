<?php

/**
 * statistics actions.
 *
 * @package    kalifastRobot
 * @subpackage statistics
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statisticsActions extends sfActionsKalifast {
  
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->getUrlParameters($request);
        //récupération du nombre de scénario existants pour le projet.
        $this->scenarios = Doctrine_Core::getTable('EiProjet')
                ->getEiScenariosQuery($this->project_id, $this->project_ref)
                ->count();
        $this->nbScenarios = Doctrine_Core::getTable('EiProjet')
                ->countEiScenario($this->project_id, $this->project_ref);
    }

    /**
     * Retourne les statistiques pour une fonction donnée.
     * @param sfWebRequest $request
     * @throws Exception
     */
    public function executeGetFunctionStats(sfWebRequest $request) {
        $this->getUrlParameters($request);
        $this->fct_id = $request->getParameter('function_id');
        $this->fct_ref = $request->getParameter('function_ref');
        $this->node = Doctrine_Core::getTable('EiTree')
                ->findOneByRefObjAndObjIdAndType($this->fct_ref, $this->fct_id, 'Function');
        $this->times = Doctrine_Core::getTable('EiFonction')
                ->getTimeStats($this->project_id, $this->project_ref, $this->fct_id, $this->fct_ref);

        return $this->renderPartial('functionStats');
    }

    public function executeGetFunctionGraph(sfWebRequest $request) {
        $this->getUrlParameters($request);
        $this->fct_id = $request->getParameter('function_id');
        $this->fct_ref = $request->getParameter('function_ref');
        //récupération du nom pour les affichages.
        $this->node = Doctrine_Core::getTable('EiTree')
                ->findOneByRefObjAndObjIdAndType($this->fct_ref, $this->fct_id, 'Function');
        //récupération des temps d'execution
        $this->times = Doctrine_Core::getTable('EiFonction')
                ->getTimeStats($this->project_id, $this->project_ref, $this->fct_id, $this->fct_ref);

        // Width and height of the graph
        $width = 700;
        $height = 300;

        // Create a graph instance
        $graph = new Graph($width, $height);
        $graph->SetScale('intint');
        $graph->title->Set($this->node->getName() . " 's execution time evolution.");

        $graph->xaxis->title->Set('Execution');
        $graph->yaxis->title->Set('Time (ms)');
        $exec = array();

        //récupération des durées uniquement
        foreach ($this->times as $t => $time) {
            $exec[] = $time['l_duree'];
        }

        $lineplot = new LinePlot($exec);
        $graph->Add($lineplot);

        $graph->Stroke();

        return sfView::NONE;
    }

    /* Index de génération des statistiques générales de l'application */
    
    public function executeStats(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil
        /* Récupération des bugs de tous les users par statuts */
        $user_bugs=$this->ei_project->getUserBugs();
        $conn = Doctrine_Manager::connection(); 
        
        $state_bugs_count= $conn->fetchAll("select  SUM(nbBugs) as nbTotalBugs, st_id
                  from ei_user_bugs_vw vw where st_project_id=".$this->ei_project->getProjectId()." and st_project_ref=".$this->ei_project->getRefId()." group by st_id" );
        $tabStateCount=array();
         foreach($state_bugs_count as $state_bug_count):
             $tabStateCount[$state_bug_count['st_id']]=$state_bug_count['nbTotalBugs'];
         endforeach;
         $this->tabStateCount=$tabStateCount;
        
        $user_bugs_count= $conn->fetchAll("select  SUM(nbBugs) as nbTotalBugs, g_id
                  from ei_user_bugs_vw vw where st_project_id=".$this->ei_project->getProjectId()." and st_project_ref=".$this->ei_project->getRefId()." group by g_id" );
        $tabUserCount=array(); 
        foreach($user_bugs_count as $user_total_bug):
             $tabUserCount[$user_total_bug['g_id']]=$user_total_bug['nbTotalBugs'];
         endforeach;
         $this->tabUserCount=$tabUserCount;
         
        $this->bugs_states=Doctrine_Core::getTable('EiSubjectState')->getSubjectStateForSearchBox($this->ei_project); // Récupération des différents statuts de bugs sur le projet 
        $this->ei_project_users=$this->ei_project->getProjectUsers(); //Récupération des utilisateurs du projet  
        /* On parse les résultats obtenu pour ranger les résultats en tableau de clés user-state */
        $tab=array();
        if(isset($user_bugs) && count($user_bugs) > 0):
            foreach($user_bugs as $user_bug):
            if($user_bug['g_id']!=null): 
                $tab[$user_bug['user_bug_id']]=$user_bug;
                else:
                $tab[$user_bug['st_id']]=$user_bug;
            endif; 
            endforeach;
        endif;
        $this->user_bugs=$tab; 
    }
    /* Statistiques des fonctions executées  */
    
    public function executeFunctionsStats(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil
        $this->getExecutionsParams($request); 
        /* Récupération des fonctions exécutées sur le projet */
        
        if($request->isXmlHttpRequest()):  
            $this->exFunctions=Doctrine_Core::getTable("KalFunction")->getExFunctions($this->ei_project,$this->criterias);
            $partialParams=$this->urlParameters;
            $partialParams['exFunctions']=$this->exFunctions;
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('statistics/functionStatsList',$partialParams),
                        'success' => true)));
            return sfView::NONE;
            else:
            $this->listNone=true;
        endif;
        
    }
    
    /* Récupération des paramètres d'exécutions de fonctions */
    public function getExecutionsParams(sfWebRequest $request){
        $tabForm=$request->getParameter('searchStatsFunctionForm'); 
        $this->all=(isset($tabForm["all"]) && $tabForm["all"])?true:false;
        $this->success=(isset($tabForm["success"]) && $tabForm["success"])?true:false;
        $this->failed=(isset($tabForm["failed"]) && $tabForm["failed"])?true:false;
        $this->never_plan=(isset($tabForm['never_plan']) && $tabForm['never_plan'])?true:false;
        $this->aborted=(isset($tabForm['aborted']) && $tabForm['aborted'] )?true:false;
        $this->min_date=isset($tabForm['min_date'])?$tabForm['min_date']:null;
        $this->max_date=isset($tabForm['max_date'])?$tabForm['max_date']:null; 
        /* Récupération des criticités */
        $criticity=array(); 
        if(isset($tabForm['criticity_blank']) && $tabForm['criticity_blank'] ): $criticity['blank']="Blank"; endif;
        if(isset($tabForm['criticity_low']) && $tabForm['criticity_low'] ): $criticity['low']="Low"; endif;
        if(isset($tabForm['criticity_medium']) && $tabForm['criticity_medium'] ): $criticity['medium']="Medium"; endif;
        if(isset($tabForm['criticity_high']) && $tabForm['criticity_high'] ): $criticity['high']="High"; endif;
        $this->criterias=array(
            "all" => $this->all,
            "success" =>$this->success,
            "failed" =>$this->failed,
            "never_plan" =>$this->never_plan,
            "aborted" =>$this->aborted,
            "min_date" =>$this->min_date,
            "max_date" =>$this->max_date, 
            "criticity" => $criticity
        );
    }
     
}
