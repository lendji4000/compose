<?php

/**
 * @author  Lenine DJOUATSA
 */
class kalfonctionComponents extends sfComponentsKalifast {
    /* Cette fonction permet de rechercher la fonction (KalFunction) avec les paramètres renseignés.  */

    public function checkFunction(sfWebRequest $request, EiProjet $ei_project) {
        $this->function_id = $request->getParameter('function_id');
        $this->function_ref = $request->getParameter('function_ref');

        if ($this->function_id != null || $this->function_ref != null) {
            //Recherche de la fonction en base
            $this->kal_function = Doctrine_Core::getTable('KalFunction')
                    ->findOneByFunctionIdAndFunctionRefAndProjectIdAndProjectRef(
                    $this->function_id, $this->function_ref, $ei_project->getProjectId(), $ei_project->getRefId());
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->kal_function == null)
                $this->kal_function = null;
        }
        else {
            $this->function_id = null;
            $this->function_ref = null;
        }
    }

    public function executeSideBarFunction(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        /* Liste des livraisons ouvertes dans la limite de 10 livraisons ordonnées pad date */
        $this->openDeliveries=$this->checkOpenDeliveries($this->ei_project);
    }

    /* Composant permettant de retourner le chemin vers l'objet */

    public function executeBreadcrumb(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);

        $this->breadcrumb = array(); //Tableau destiné à contenir le breadcrumb
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');
        $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_function', 'Functions', $this->generateUrl('functionList', $this->urlParameters) ,
                false,false,"AccessProjectFunctionsOnBreadCrumb"); 
        switch ($mod):
            case "kalfonction":
                $this->evaluateSubBreadKalFonction($act);
                break;
            case "functionCampaigns":
                $this->evaluateSubBreadFunctionCampaigns($act, 'Details');
                break;
            case "eisubjectsolution":
                $this->evaluateSubBreadSubject($act, 'Solution');
                break;
            case "eisubjectmigration":
                $this->evaluateSubBreadSubject($act, 'Migration');
                break;
            case "bugContext":
                $this->evaluateSubBreadContext($act);
                break;
            case "subjectfunction":
                $this->evaluateSubBreadFunctions($act);
                break;
            default:
                break;
        endswitch;
    }

    public function executeSideBarHeaderObject(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');
        if ($mod == 'kalfonction' && $act == 'show')
            $this->activeItem = 'show';
        if ($mod == 'functionCampaigns' && $act == 'index')
            $this->activeItem = 'functionCampaigns';
        if ($mod == 'kalfonction' && ($act == 'functionSubjects' || $act == 'getFunctionSubjects' || $act == 'searchFunctionSubjects'))
            $this->activeItem = 'functionSubjects'; 
        if ($mod == 'kalfonction' && ($act == 'scenariosFunction'))
            $this->activeItem = 'scenariosFunction'; 
        if($mod=="kalfonction" && $act=="statistics")
                $this->activeItem = 'statisticsFunction';  
        if($mod=="eifunctionparams" && $act=="index")
                $this->activeItem = 'functionParameters';  
        if($mod=="eiversionnotice" && $act=="index")
                $this->activeItem = 'functionNotices';  
    }

    //Détermination du prefixe de breadcrumb dans le cas d'une fonction 
    public function evaluateSubBreadKalFonction($act) {
        if ($this->kal_function != null):
            $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_function', $this->kal_function);  
        endif;

        switch ($act):
            case "index":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_list', 'List', null, true,true); 
            
                break;
            case "new":
            case "create":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_add', 'New', null, true,true);  
                break;
                break;
            case "edit":
            case "update":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_edit', 'Edit', null, true,true);  
                break;
                break;
            case "show":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_show', 'Show', null, true,true);   
                break;
            default:
                break;
        endswitch;
    }

    //Détermination du prefixe de breadcrumb dans le cas d'une fonction 
    public function evaluateSubBreadFunctionCampaigns($act) {
        if ($this->kal_function != null):
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_function', $this->kal_function); 

        endif;

        $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_campaign', 'Function campaigns');   

        switch ($act):
            case "index":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_list', 'List', null, true,true); 
                break;
            case "new":
            case "create":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem(' ei_add', 'New', null, true,true);  
                break;
                break;
            case "edit":
            case "update":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem(' ei_edit', 'Edit', null, true,true);    
                break;
                break;
            case "show":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem(' ei_show', 'Show', null, true,true);  
                break;
            default:
                break;
        endswitch;
    }

}

?>
