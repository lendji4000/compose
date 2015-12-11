<?php

/**
 * @author  Lenine DJOUATSA
 */
class statisticsComponents extends sfComponentsKalifast { 
 
    /* Composant permettant de retourner le chemin vers l'objet */

    public function executeBreadcrumb(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
            
    }

    public function executeSideBarHeaderObject(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); 
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');
        if ($mod == 'statistics' && $act == 'stats')
            $this->activeItem = 'statistics';
        if ($mod == 'statistics' && $act == 'functionsStats')
            $this->activeItem = 'functionsStats';  
    } 
    public function executeSideBarStats(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); 
        /* Liste des livraisons ouvertes dans la limite de 10 livraisons ordonnées pad date */
        $this->openDeliveries=$this->checkOpenDeliveries($this->ei_project);
    }
}

?>
