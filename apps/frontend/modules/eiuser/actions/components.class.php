<?php

/**
 *
 * @author   Lenine DJOUATSA
 */
class eiuserComponents extends sfComponentsKalifast {
    /* Composant permettant de retourner le chemin vers l'objet */

    public function executeBreadcrumb(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);

        $this->breadcrumb = array(); //Tableau destiné à contenir le breadcrumb
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');
        $this->breadcrumb[] = array(
            'logo' => '<i class="fa fa-gears"></i>',
            'title' => 'User settings',
            'uri' => '#',
            'active' => false,
            'is_last_bread' => false,
            'id' => "AccessUsersSettingsOnBreadCrumb",
            'class' => "");
    }

    public function executeSideBarHeaderObject(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        //$this->checkProfile($request, $this->ei_project);
    }

    public function executeSideBarUser(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        /* Liste des livraisons ouvertes dans la limite de 10 livraisons ordonnées pad date */
        $this->openDeliveries=$this->checkOpenDeliveries($this->ei_project);
    }
 

}

?>
