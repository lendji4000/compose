<?php

/**
 *
 * @author Lenine DJOUATSA
 */
class eideliveryCampaignComponents extends sfComponentsKalifast {

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

    public function executeSideBarDelivery(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiDelivery($request, $this->ei_project);
    }

    /* Composant permettant de retourner le chemin vers l'objet */

    public function executeBreadcrumb(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->breadcrumb = array(); //Tableau destiné à contenir le breadcrumb
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');
        $this->breadcrumb[] = array(
            'logo' =>  ei_icon('ei_delivery'),
            'title' => 'Deliveries',
            'uri' => $this->generateUrl('delivery_list', $this->urlParameters),
            'active' => false,
            'is_last_bread' => false,
            'id' => "AccessProjectDeliveriesOnBreadCrumb",
            'class' => "");
        $this->checkEiDelivery($request, $this->ei_project);
        $delivery_show = $this->urlParameters;
        $delivery_show['delivery_id'] = $this->ei_delivery->getId();
        $delivery_show['action'] = 'show';
        $this->breadcrumb[] = array(
            'logo' =>   ei_icon('ei_delivery'),
            'title' => MyFunction::troncatedText($this->ei_delivery, 20),
            'uri' => $this->generateUrl('delivery_edit', $delivery_show),
            'active' => false,
            'is_last_bread' => false,
            'id' => "",
            'class' => "");
        switch ($mod):
            case "eideliveryCampaign":
                $this->evaluateSubBreadCamp($request, $act);
                break;
            default:
                break;
        endswitch;
    }

    public function executeSideBarHeaderObject(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiDelivery($request, $this->ei_project);
    }

    //Détermination du chemin jusqu'à une campagne
    public function evaluatePathToObject(EiDelivery $ei_delivery) {
        $this->chemin = "";
    }

    //Détermination du chemin jusqu'à une campagne
    public function evaluateSubBreadCamp(sfWebRequest $request, $act) {
        $getDeliveryCampaigns = $this->urlParameters;
        $getDeliveryCampaigns['delivery_id'] = $this->ei_delivery->getId();
        $this->breadcrumb[] = array(
            'logo' => ei_icon('ei_campaign')  ,
            'title' => 'Delivery Campaigns',
            'uri' => $this->generateUrl('getDeliveryCampaigns', $getDeliveryCampaigns),
            'active' => false,
            'is_last_bread' => false,
            'id' => "AccessDeliveryCampaignsOnBreadCrumb",
            'class' => "");
        switch ($act):

            case "getDeliveryCampaigns":
                $this->breadcrumb[] = array(
                    'logo' => '',
                    'title' => 'List',
                    'uri' => '#',
                    'active' => true,
                    'is_last_bread' => true,
                    'id' => "",
                    'class' => "");
                break;

            case "index":
                $this->breadcrumb[] = array(
                    'logo' => '',
                    'title' => 'List',
                    'uri' => '#',
                    'active' => true,
                    'is_last_bread' => true,
                    'id' => "",
                    'class' => "");
                break;
            case "new":
                $this->breadcrumb[] = array(
                    'logo' => '',
                    'title' => 'New ',
                    'uri' => '#',
                    'active' => true,
                    'is_last_bread' => true,
                    'id' => "",
                    'class' => "");
                break;
            case "create":
                $this->breadcrumb[] = array(
                    'logo' => '',
                    'title' => 'Create',
                    'uri' => '#',
                    'active' => true,
                    'is_last_bread' => true,
                    'id' => "",
                    'class' => "");
                break;
            case "edit":
                $this->checkEiDelivery($request, $this->ei_project);
                $this->breadcrumb[] = array(
                    'logo' => '',
                    'title' => MyFunction::troncatedText($this->ei_delivery, 20),
                    'uri' => '#',
                    'active' => true,
                    'is_last_bread' => true,
                    'id' => "",
                    'class' => "");
                break;
            case "update":
                $this->checkEiDelivery($request, $this->ei_project);
                $this->breadcrumb[] = array(
                    'logo' => '',
                    'title' => MyFunction::troncatedText($this->ei_delivery, 20),
                    'uri' => '#',
                    'active' => true,
                    'is_last_bread' => true,
                    'id' => "",
                    'class' => "");
                break;
            case "show":
                $this->checkEiDelivery($request, $this->ei_project);
                $this->breadcrumb[] = array(
                    'logo' => '',
                    'title' => MyFunction::troncatedText($this->ei_delivery, 20),
                    'uri' => '#',
                    'active' => true,
                    'is_last_bread' => true,
                    'id' => "",
                    'class' => "");
                break;
            default:
                break;
        endswitch;
    }

}

?>
