<?php

/**
 * view actions.
 *
 * @package    kalifastRobot
 * @subpackage view
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class viewActions extends sfActionsKalifast {
    /* Sauvegarde des données du profil dans la session utilisateur */

    public function setProfileSession($profile_name, $profile_id, $profile_ref) {
        //Sauvegarde du profil en session utilisateur  
        if ($profile_name != null && $profile_id != null && $profile_ref != null):
            $this->getUser()->setAttribute("current_profile_name", $this->profile_name);
            $this->getUser()->setAttribute("current_profile_id", $this->profile_id);
            $this->getUser()->setAttribute("current_profile_ref", $this->profile_ref);
        endif;
    }
    /* Cette fonction permet de rechercher la vue avec les paramètres renseignés.  */
    public function checkParentTree(sfWebRequest $request,EiProjet $ei_project) {
        $this->parent_id=$request->getParameter('parent_id');
        if ($this->parent_id == null) $this->forward404('Parent node parameter not found ...');
        $this->ei_parent_tree=Doctrine_Core::getTable('EiTree')->findOneByIdAndProjectIdAndProjectRef(
                $this->parent_id,$ei_project->getProjectId(), $ei_project->getRefId());
        if ($this->ei_parent_tree == null) $this->forward404('Parent node not found');
        
    }
    public function executeIndex(sfWebRequest $request) {
        $this->ei_views = Doctrine_Core::getTable('EiView')
                ->createQuery('a')
                ->execute();
    }

    public function executeNew(sfWebRequest $request) { 
        $this->forward404unless($request->isXmlHttpRequest());
         $this->html='Error ...'; $this->success=false;
         $this->checkProject($request);
         $this->checkParentTree($request, $this->ei_project);
         $view=new EiView();
         $view->setProjectId($this->ei_project->getProjectId());
         $view->setProjectRef($this->ei_project->getRefId()); 
         $this->form = new EiViewForm($view);
         $this->success=true;
         $this->html=$this->getPartial('view/form',array(
                'form' => $this->form,
                'ei_parent_tree' => $this->ei_parent_tree, 
                'ei_project'=>$this->ei_project));
         return $this->renderText(json_encode(array(
                    'html' =>  $this->html,
                    'success' => $this->success )));
         
         return sfView::NONE;
    }

    public function executeCreate(sfWebRequest $request) {  
        $this->forward404unless($request->isXmlHttpRequest());
        $this->html='Error ...'; $this->success=false;
        $this->checkProject($request); 
        $this->checkParentTree($request, $this->ei_project);
        $view=new EiView();
        $view->setProjectId($this->ei_project->getProjectId());
        $view->setProjectRef($this->ei_project->getRefId()); 
        $this->form = new EiViewForm($view);
        
        $this->processForm($request, $this->form);

        return $this->renderText(json_encode(array(
                    'html' =>  $this->html,
                    'success' => $this->success )));
      
        return sfView::NONE;
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($ei_view = Doctrine_Core::getTable('EiView')->find(array($request->getParameter('view_id'),
            $request->getParameter('view_ref'))), sprintf('Object ei_view does not exist (%s).', $request->getParameter('view_id'), $request->getParameter('view_ref')));
        $this->form = new EiViewForm($ei_view);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($ei_view = Doctrine_Core::getTable('EiView')->find(array($request->getParameter('view_id'),
            $request->getParameter('view_ref'))), sprintf('Object ei_view does not exist (%s).', $request->getParameter('view_id'), $request->getParameter('view_ref')));
        $this->form = new EiViewForm($ei_view);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($ei_view = Doctrine_Core::getTable('EiView')->find(array($request->getParameter('view_id'),
            $request->getParameter('view_ref'))), sprintf('Object ei_view does not exist (%s).', $request->getParameter('view_id'), $request->getParameter('view_ref')));
        $ei_view->delete();

        $this->redirect('view/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $this->html=$request->getParameter($form->getName());
            //Si les données sont valides, on envoi en format json la fonction et ses éventuels paramètres pour insertion.
             $this->result=EiView::createDistantView($this->ei_project,$this->ei_parent_tree,json_encode($this->html)); 
            $this->success=true;
        } 
        else{ 
              $this->html=$this->getPartial('view/form',array(
                'form' => $form,
                'ei_parent_tree' => $this->ei_parent_tree, 
                'ei_project'=>$this->ei_project));  
        }
    }

}
