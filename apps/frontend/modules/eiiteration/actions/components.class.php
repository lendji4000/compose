<?php

/**
 *
 * @author Lenine DJOUATSA
 */
class eiiterationComponents extends sfComponentsKalifast {

    //Recherche d'une itération avec les paramètres de requête
    public function checkEiIteration(sfWebRequest $request, EiProjet $ei_project) {
        $this->iteration_id = $request->getParameter('iteration_id'); 
        if (($this->iteration_id) != null) {
            //Recherche de l'iteration en base
            $this->ei_iteration = Doctrine_Core::getTable('EiIteration')->findOneByIdAndProjectIdAndProjectRef(
                    $this->iteration_id, $ei_project->getProjectId(), $ei_project->getRefId());
        } else
            $this->iteration_id = null;
    } 
    //Recherche d'une livraison avec les paramètres de requête
    public function checkEiDelivery(sfWebRequest $request, EiProjet $ei_project) {
        $this->delivery_id = $request->getParameter('delivery_id'); 
        if (($this->delivery_id) != null) {
            //Recherche de la livraison en base
            $this->ei_delivery = Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
                    $this->delivery_id, $ei_project->getProjectId(), $ei_project->getRefId());
        } else
            $this->ei_delivery = null;
    }
    public function executeSideBarIteration(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiIteration($request, $this->ei_project);
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
        /* Recherche de la livraison si cette dernière est spécifiée */
        
        $getDeliverySubjectsUri=$this->urlParameters;
        $this->checkEiIteration($request, $this->ei_project);
        if ($this->ei_iteration != null):
            $this->ei_delivery=$this->ei_iteration->getEiDelivery();  
            $getDeliverySubjectsUri['delivery_id']=$this->ei_delivery->getId();
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_delivery', $this->ei_delivery, $this->generateUrl('getDeliverySubjects', $getDeliverySubjectsUri), null, null, "accessDeliveryBugs", null);
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_iteration', 'Iterations');
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_iteration', $this->ei_iteration);
        else:
            $this->checkEiDelivery($request, $this->ei_project); 
            $getDeliverySubjectsUri['delivery_id']=$this->ei_delivery->getId();
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_delivery', $this->ei_delivery, $this->generateUrl('getDeliverySubjects', $getDeliverySubjectsUri), null, null, "accessDeliveryBugs", null);
            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_iteration', 'Iterations');
        endif;
        
        switch ($mod):
            case "eiiteration":

                switch ($act):
                    case "index": 
                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_list', 'List', null, true, true);

                        break;   
                    case "statistics": 
                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_stats', 'Statistics', null, true, true);
                        break; 
                    case "show":
                        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_show', 'View', null, true, true);

                        break;
                    default:
                        break;
                endswitch;
                break; 
            default:
                break;
        endswitch;
    }

    public function executeSideBarHeaderObject(sfWebRequest $request) {      
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiIteration($request, $this->ei_project);
        if($this->ei_iteration==null):
            $this->checkEiDelivery($request, $this->ei_project);
        else:
            $this->ei_delivery=$this->ei_iteration->getEiDelivery();
        endif;
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');
        if ($mod == 'eiiteration' && $act == 'show')
            $this->activeItem = 'Show';
        if ($mod == 'eiiteration' &&   $act == 'index')
            $this->activeItem = 'List';
        if ($mod == 'eiiteration' && ($act == 'statistics'))
            $this->activeItem = 'statistics'; 
        if ($mod == 'eiiteration' && ($act == 'create' || $act == 'new'))
            $this->activeItem = 'New';   
        if ($mod == 'eiiteration' && $act == 'edit')
            $this->activeItem = 'Edit';  
    }
                    

}

?>
