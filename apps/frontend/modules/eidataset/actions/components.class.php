<?php

/**
 *
 * @author Lenine DJOUATSA
 */
class eidatasetComponents extends sfComponentsKalifast {
    
    //Recherche d'un scénario avec les paramètres de requête
    public function checkEiScenario(sfWebRequest $request,EiProjet $ei_project) {
        if (($this->ei_scenario_id = $request->getParameter('ei_scenario_id')) != null ) {
            //Recherche du scénario en base
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->findOneByIdAndProjectIdAndProjectRef(
                    $this->ei_scenario_id,$ei_project->getProjectId(),$ei_project->getRefId()); 
        }

        else $this->ei_scenario=null; 
    }  
    
    public function executeSideBarScenario(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); 
        $this->checkEiScenario($request,$this->ei_project) ; 
        //Récupération des versions du scénario
                $this->ei_versions = Doctrine_Core::getTable('EiVersion')
                    ->findByEiScenarioId($this->ei_scenario->getId());
    }
    /* Composant permettant de retourner le chemin vers l'objet */
    public function executeBreadcrumb(sfWebRequest $request){
         $this->checkProject($request); //Récupération du projet
         $this->checkProfile($request, $this->ei_project); 
         $this->checkEiScenario($request,$this->ei_project) ; 
         //On n'oubli pas de renvoyer le chemin vers le scénario
         $this->evaluatePathToScenario($this->ei_scenario);
    }
    public function executeSideBarHeaderObject(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); 
        $this->checkEiScenario($request,$this->ei_project) ;

        $this->mod = $request->getParameter('module');
        $this->act = $request->getParameter('action');
        $this->objMenu = array();

        switch ($this->mod):
            case 'eidataset':
                switch ($this->act):
                    case 'edit':
                    case 'update':

                        $this->urlExcelRequest = url_for2("api_generate_excel_request_api", array(
                            'project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name
                        ));

                        $urlOpenDataSetXml = url_for2("eidataset_download", array(
                            'project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name,
                            'ei_data_set_id' => $this->ei_data_set->getId(),
                            'sf_format' => "xml"
                        ));

                        $this->objTitle = $this->ei_data_set->getEiDataSetTemplate()->getName();
                        $this->logoTitle =  ei_icon('ei_dataset') ;
                        $this->objMenu[] = array(
                            'logo' => '<i class="fa fa-wrench"></i>',
                            'title' => 'Properties',
                            'uri' => '#datasetProperties',
                            'active' => true,
                            'class' => "",
                            'tab' => 'tab',
                            'id' => "datasetPropertiesTab",
                            'titleAttr' => "Data set properties"
                        );

                        $this->objMenu[] = array(
                            'logo' =>     '<i class="fa fa-code"></i>' ,
                            'title' => 'Sources',
                            'uri' => '#datasetSource',
                            'active' => false,
                            'class' => "",
                            'id' => "datasetSourceTab",
                            'tab' => 'tab',
                            'titleAttr' => "Data set XML source");

                        $this->objMenu[] = array(
                            'logo' =>     ei_icon('ei_version'),
                            'title' => 'Versions',
                            'uri' => '#datasetVersions',
                            'active' => false,
                            'class' => "",
                            'id' => "datasetVersionsTab",
                            'tab' => 'tab',
                            'titleAttr' => "Data set versions");

//                        $this->objMenu[] = array(
//                            'logo' =>     ei_icon("ei_testset"),
//                            'title' => 'Reports',
//                            'uri' => '#datasetReports',
//                            'active' => false,
//                            'class' => "",
//                            'id' => "datasetReportsTab",
//                            'tab' => 'tab',
//                            'titleAttr' => "Data set reports");

                        $this->objMenu[] = array(
                            'logo' => '<i class="fa fa-download"></i>',
                            'title' => '',
                            'uri' => $urlOpenDataSetXml,
                            'active' => false,
                            'class' => "",
                            'id' => "datasetDownloadTab",
                            'titleAttr' => "Download data set");

                        $this->objMenu[] = array(
                            'logo' => '<img src="'.sfConfig::get("app_icone_excel_24x24_path").'" alt="" width="20" title="Open data set in Excel" class="excel-icon-img disabledOracle" />',
                            'title' => '',
                            'uri' => $this->urlExcelRequest,
                            'active' => false,
                            'class' => "excel-open-jdd excelIcon",
                            'id' => "datasetOpenInExcelTab",
                            'titleAttr' => "Open in Excel",
                            'data-id' => $this->ei_data_set->getId()
                        );

                        $this->objMenu[] = array(
                            'logo' =>     '<i class="fa fa-times"></i>',
                            'title' => '',
                            'uri' => '#',
                            'active' => false,
                            'class' => "btn-close",
                            'id' => "datasetCloseTab",
                            'tab' => 'tab',
                            'titleAttr' => "Close Data set");
                        break;
                    default :
                        break;
                endswitch;

                break;

            default :
                break;
        endswitch;
    }

    /**
     * TODO: Optimiser visionneuse de JDD.
     *
     * @param sfWebRequest $request
     */
    public function executeManager(sfWebRequest $request)
    {
        $memoryLimit = ini_get("memory_limit");
        ini_set("memory_limit", "-1");

        /** @var EiDataSet $ei_data_set */
        $ei_data_set = $this->ei_data_set->getEiDataSet();

        $countLines = $ei_data_set->getCountOfLines();

        if( $countLines < 1000 ){
            // Réupération de la structure du jeu de données.
            $structures = $ei_data_set->getTreeArrayForITree();

            // Création du TreeViewer.
            $treeViewer = new TreeViewer("EiDataSet");

            $treeViewer->import($structures);

            $this->treeDisplay = new TreeView($treeViewer, new ModeEditTreeStrategy(), array(
                "id" => "dataset_source_tree_".time(),
                "formats" => array(
                    "node" => EiNodeDataSet::getFormNameFormat(),
                    "leaf" => EiLeafDataSet::getFormNameFormat()
                ),
                "types" => array(
                    "root" => array(
                        "icon" => TreeView::$TYPE_XML,
                        "name" => "root"
                    ),
                    "node" => array(
                        "icon" => TreeView::$TYPE_TAG,
                        "name" => "node"
                    ),
                    "attr" => array(
                        "icon" => TreeView::$TYPE_XSL_VALUE,
                        "name" => "attribute"
                    ),
                    "leaf" => array(
                        "icon" => TreeView::$TYPE_VALUE,
                        "name" => "value"
                    )
                ),
                "authorizations" => array(
                    "rename" => array(
                        "leaf"
                    ),
                    "new" => array(),
                    "remove" => array(),
                    "dragndrop" => array()
                ),
                "init" => array(
                    "openAll" => true
                ),
                "styleMessageResultat" => 2,
                "styleMessageResultatObject" => '#ei_data_set_content',
                "actions" => array(
                    "rename" => array(
                        "route" => "eidataset_rename_value_dataline",
                        "parameters" => array(
                            'project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'ei_scenario_id' => $this->ei_scenario->getId(),
                            'profile_name' => $this->profile_name,
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'ei_data_set_id' => $ei_data_set->getId(),
                            'ei_data_line_id' => 'ei_node_id'
                        ),
                        "target" => "ei_node_id"
                    )
                )
            ));
        }
        else{
            $this->treeDisplay = null;
        }
        ini_set("memory_limit", $memoryLimit);
    }

}

?>
