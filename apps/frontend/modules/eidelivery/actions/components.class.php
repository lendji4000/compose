<?php

/**
 *
 * @author Lenine DJOUATSA
 */
class eideliveryComponents extends sfComponentsKalifast {

    //Recherche d'une livraison avec les paramètres de requête
    public function checkEiDelivery(sfWebRequest $request, EiProjet $ei_project) {
        $this->delivery_id = $request->getParameter('delivery_id');
        if ($this->delivery_id == null)
            $this->delivery_id = $request->getParameter('id');
        if (($this->delivery_id) != null) {
            //Recherche de la livraison en base
            $this->ei_delivery = Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
                    $this->delivery_id, $ei_project->getProjectId(), $ei_project->getRefId());
        } else
            $this->ei_delivery = null;
    }
    /* Recherche d'une itération de livraison */
    public function checkIteration(sfWebRequest $request){
        $this->iteration_id= $request->getParameter('iteration_id');
        if ($this->iteration_id == null)
            $this->forward404('Missing iteration  parameters'); 
         //Recherche de l'itération
        $this->ei_iteration = Doctrine_Core::getTable('EiIteration')->findOneById($this->iteration_id);
        if ($this->ei_iteration == null)
            $this->forward404('Iteration not found ...');
    }

    public function executeSideBarDelivery(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiDelivery($request, $this->ei_project);
        /* Liste des livraisons ouvertes dans la limite de 10 livraisons ordonnées pad date */
        $this->openDeliveries = $this->checkOpenDeliveries($this->ei_project);
    }

    /* Composant permettant de retourner le chemin vers l'objet */

    public function executeBreadcrumb(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->breadcrumb = array(); //Tableau destiné à contenir le breadcrumb
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');
        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_delivery', 'Deliveries', $this->generateUrl('delivery_list', $this->urlParameters), null, null, "AccessProjectDeliveriesOnBreadCrumb", null);
        $this->checkEiDelivery($request, $this->ei_project);
        if ($this->ei_delivery != null):
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_delivery', $this->ei_delivery);
        endif;
        switch ($mod):
            case "eidelivery":

                switch ($act):
                    case "index":
                    case "searchDeliveries":
                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_list', 'List', null, true, true);

                        break;
                        break;
                    case "new":
                    case "create" :

                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add', 'New', null, true, true);
                        break;
                        break;
                    case "edit":
                    case "update":
                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_edit', 'Edit', null, true, true);

                        break;
                        break;
                    case "searchDeliverySubjects":
                    case "getDeliverySubjects":
                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_subject', 'Delivery interventions', null, true, true);
                        break;
                        break;
                    case "show":
                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_edit', 'View', null, true, true);

                        break;
                    default:
                        break;
                endswitch;
                break;
            case "eideliveryCampaign":
                $this->evaluateSubBreadCamp($request);
                break;
            case "eiiteration": $this->evaluateSubBreadIteration($request,$act);
                break;
            default:
                break;
        endswitch;
    }

    public function executeSideBarHeaderObject(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiDelivery($request, $this->ei_project);
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');
        if ($mod == 'eidelivery' && $act == 'show')
            $this->activeItem = 'Show';
        if ($mod == 'eidelivery' && ($act == 'searchDeliveries' || $act == 'index'))
            $this->activeItem = 'deliveriesList';
        if ($mod == 'eidelivery' && ($act == 'statistics'))
            $this->activeItem = 'statistics';
        if ($mod == 'eidelivery' && ($act == 'impacts'))
            $this->activeItem = 'impacts';
        if ($mod == 'eidelivery' && ($act == 'create' || $act == 'new'))
            $this->activeItem = 'New';
        if ($mod == 'eideliverystate' && ($act == 'index'))
            $this->activeItem = 'stateList';
        if ($mod == 'eidelivery' && $act == 'adminMigration')
            $this->activeItem = 'adminMigration';
        if ($mod == 'eidelivery' && $act == 'deliveryProcess')
            $this->activeItem = 'deliveryProcess';
        if ($mod == 'eidelivery' && $act == 'edit')
            $this->activeItem = 'Edit';
        if ($mod == 'eidelivery' && ($act == 'getDeliverySubjects' || $act == 'searchDeliverySubjects'))
            $this->activeItem = 'Bugs';
        if ($mod == 'eideliveryCampaign')
            $this->activeItem = 'Campaigns';
        if ($mod == 'eiiteration')
            $this->activeItem = 'Iterations';
    }

    //Détermination du chemin jusqu'à la campagne
    public function evaluateSubBreadCamp(sfWebRequest $request) {
        $this->checkEiDelivery($request, $this->ei_project);
        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_delivery', $this->ei_delivery);

        switch ($act):
            case "index":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_list', 'List', null, true, true);
                break;
            case "new":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add', 'New', null, true, true);
                break;
            case "create":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add', 'Create', null, true, true);
                break;
            case "edit":
            case "update":
            case "show":
                $this->checkEiDelivery($request, $this->ei_project);
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_edit', $this->ei_delivery, null, true, true);
                break;
                break;
                break;
            default:
                break;
        endswitch;
    }
    //Détermination du chemin jusqu'à l'itération
    public function evaluateSubBreadIteration(sfWebRequest $request,$act) { 
        $uri_list=$this->urlParameters; $uri_list['delivery_id']=$this->ei_delivery->getId();
        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_iteration', 'Iterations', $this->generateUrl('ei_iteration_global', $uri_list), null, null, "AccessDeliveryIterationsOnBreadCrumb", null);
        switch ($act):
            case "index":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_list', 'List', null, true, true);
                break;
            case "new":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add', 'New', null, true, true);
                break;
            case "create":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add', 'Create', null, true, true);
                break;
            case "edit":
            case "update":
            case "show":
                $this->checkIteration($request);
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_iteration', $this->ei_iteration->getId(), null, true, true);
                break;
                break;
                break;
            default:
                break;
        endswitch;
    }

}

?>
