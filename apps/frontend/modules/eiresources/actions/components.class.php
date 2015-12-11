<?php

/**
 * @author  Jean-Loup Combes
 */
class eiresourcesComponents extends sfComponentsKalifast { 
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
        if ($mod == 'eiresources' && $act == 'download')
        {
            $this->activeItem = 'download';
        }
        if ($mod == 'eiresources' && $act == 'devices')
        {
            $this->activeItem = 'devices';
        }
    }
}

?>
