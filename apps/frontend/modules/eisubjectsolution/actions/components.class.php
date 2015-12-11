<?php

/**
 *
 * @author Lenine DJOUATSA
 */
class eisubjectsolutionComponents extends sfComponentsKalifast {

    //Recherche d'un sujet avec les paramètres de requête
    public function checkEiSubject(sfWebRequest $request, EiProjet $ei_project) {
        $this->subject_id = $request->getParameter('subject_id');
        if ($this->subject_id == null)
            $this->subject_id = $request->getParameter('id');
        if (($this->subject_id) != null) {
            //Recherche du sujet en base
            $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneByIdAndProjectIdAndProjectRef(
                    $this->subject_id, $ei_project->getProjectId(), $ei_project->getRefId());
        }
        else
            $this->ei_subject = null;
    }

    public function executeSideBarSubject(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiSubject($request, $this->ei_project);
    }

    /* Composant permettant de retourner le chemin vers l'objet */

    public function executeBreadcrumb(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiSubject($request, $this->ei_project);
        if ($this->ei_subject != null)//On n'oubli pas de renvoyer le chemin vers une campagne 
            $this->evaluatePathToObject($this->ei_subject); 
    }

    public function executeSideBarHeaderObject(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiSubject($request, $this->ei_project); 
    }
 

}

?>
