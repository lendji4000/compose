<?php

/**
 *
 * @author Lenine DJOUATSA
 */
class eisubjectComponents extends sfComponentsKalifast {

    //Recherche d'un sujet avec les paramètres de requête
    public function checkEiSubject(sfWebRequest $request, EiProjet $ei_project) {
        $this->subject_id = $request->getParameter('subject_id');
        if ($this->subject_id == null)
            $this->subject_id = $request->getParameter('id');
        if (($this->subject_id) != null) {
            //Recherche du sujet en base
            $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneByIdAndProjectIdAndProjectRef(
                    $this->subject_id, $ei_project->getProjectId(), $ei_project->getRefId());
        } else
            $this->ei_subject = null;
    }

    public function executeSideBarSubject(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiSubject($request, $this->ei_project);
        /* Liste des livraisons ouvertes dans la limite de 10 livraisons ordonnées pad date */
        $this->openDeliveries=$this->checkOpenDeliveries($this->ei_project);
    }
    //Récupération des livraisons ouvertes
    public function checkOpenDeliveries(EiProjet $ei_project){
        return Doctrine_Core::getTable('EiDelivery')->getOpenDeliveries($ei_project,10);
    }
            
    /* Composant permettant de retourner le chemin vers l'objet */

    public function executeBreadcrumb(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiSubject($request, $this->ei_project);

        $this->breadcrumb = array(); //Tableau destiné à contenir le breadcrumb
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');
        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_subject', 'Interventions', $this->generateUrl('subjects_list', $this->urlParameters) ,
                null,null,"AccessProjectSubjectsOnBreadCrumb"); 
        switch ($mod):
            case "eisubject":
                $this->evaluateSubBreadSubject($act);
                break;
            case "eisubjectstate":
                $this->evaluateSubBreadBugsState($act);
                break;
            case "eisubjectdetails":
                $this->evaluateSubBreadSubject($act, 'Details');
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
        $this->checkEiSubject($request, $this->ei_project);
        if ($this->ei_subject != null)//On réccupère éventuellement le contexte de création du bug
            $this->ei_context = $this->ei_subject->getBugContextSubject()->getFirst();
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');

        if ($mod == 'eisubject' && $act == 'show') $this->activeItem = 'Show';
        if ($mod == 'eisubject' && ($act == 'index' || $act=='searchSubjects')) $this->activeItem = 'bugsList';
        if ($mod=='eisubjectstate') $this->activeItem='stateList';
        if ($mod == 'eisubject' && $act == 'edit')  $this->activeItem = 'Edit';
        if ($mod == 'eisubject' && $act == 'adminMigration')  $this->activeItem = 'adminMigration';
        if ($mod == 'eisubjectdetails')  $this->activeItem = 'Details';
        if ($mod == 'eisubjectsolution') $this->activeItem = 'Solution';
        if ($mod == 'eisubjectmigration')  $this->activeItem = 'Migration';
        if ($mod == 'eisubjecthascampaign')  $this->activeItem = 'Campaigns';
        if ($mod == 'bugContext')  $this->activeItem = 'Context';
        if ($mod == 'subjectfunction') $this->activeItem = 'Functions';
    }

    //Détermination du prefixe de breadcrumb dans le cas des statuts de bug
    public function evaluateSubBreadBugsState($act) { 
        switch ($act):
            case "index":
                case "index":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_list','List',null,true,true); 
            
                    break;
                break; 
            case "new":
            case "create":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add','New',null,true,true); 
            
                break;
                break;
            case "edit":
            case "update":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_edit','Edit',null,true,true); 
            
                break;
                break;
            case "show":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_show','Show',null,true,true); 
            
                break;
            default:
                break;
        endswitch;
    }
    //Détermination du prefixe de breadcrumb dans le cas d'un sujet 
    public function evaluateSubBreadSubject($act, $subjTyp = null) {
        if ($this->ei_subject != null):
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_subject',$this->ei_subject); 
        endif;
        if ($subjTyp != null):
            if ($subjTyp == 'Details'):$subjTypeLogo=  ' ei_list';
            endif;
            if ($subjTyp == 'Solution'):$subjTypeLogo =   'fa-lightbulb-o';
            endif;
            if ($subjTyp == 'Migration'):$subjTypeLogo =  'fa-globe';
            endif; 
            $this->breadcrumb[] = $this->setBreadcrumbTabItem($subjTypeLogo,$subjTyp); 
        endif;

        switch ($act):
            case "index":
                case "searchSubjects":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_list','List',null,true,true); 
            
                    break;
                break; 
            case "new":
            case "create":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add','New',null,true,true); 
            
                break;
                break;
            case "edit":
            case "update":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_edit','Edit',null,true,true); 
            
                break;
                break;
            case "show":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_show','Show',null,true,true); 
            
                break;
            default:
                break;
        endswitch;
    }

    //Détermination du prefixe de breadcrumb dans le cas d'un contexte de sujet (bugContext)
    public function evaluateSubBreadContext($act) {
        if ($this->ei_subject != null):
            $this->breadcrumb[] = $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_subject',$this->ei_subject); 
            

        endif;
        $this->breadcrumb[] =$this->setBreadcrumbTabItem('fa-ellipsis-h','Intervention context' );  
            
        switch ($act):
            case "index":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_list','List',null,true,true);  
            
                break;
            case "newContext":
            case "createDefaultBugContext":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add','New',null,true,true);
            
                break;
                break;
            case "edit":
            case "update":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_edit','Edit',null,true,true);
            
                break;
                break;
            case "showForSubject":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_show','Show',null,true,true);
            
                break;
            default:
                break;
        endswitch;
    }

    //Détermination du prefixe de breadcrumb dans le cas des fonctions du sujet/bug (subjectfunction)
    public function evaluateSubBreadFunctions($act) {
        if ($this->ei_subject != null):
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_subject',$this->ei_subject);
            

        endif;
        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_function','Functions');
            
        switch ($act):
            case "index":
                $this->breadcrumb[] =  $this->setBreadcrumbTabItem('ei_list','List',null,true,true);
            
                break;
            case "new":
            case "create":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add','New',null,true,true); 
                break;
                break;
            case "edit":
            case "update":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_edit','Edit',null,true,true); 
                break;
                break;
            case "show":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_show','Show',null,true,true);  
                break;
            default:
                break;
        endswitch;
    }

}

?>
