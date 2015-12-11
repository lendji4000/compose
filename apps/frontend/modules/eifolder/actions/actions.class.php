<?php

/**
 * eifolder actions.
 *
 * @package    kalifastRobot
 * @subpackage eifolder
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eifolderActions extends sfActionsKalifast {

    public function executeIndex(sfWebRequest $request) {
        $this->ei_folders = Doctrine_Core::getTable('EiFolder')
                ->createQuery('a')
                ->execute();
    }

    public function executeNew(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->root_id = $request->getParameter('root_id');
        $this->ei_folder = new EiFolder();
        $this->ei_folder->setProject($this->ei_project);
        $this->form = new EiFolderForm($this->ei_folder, array('root_id' => $this->root_id));
        //Parent du noeud dans lequel on ajoute le dossier
        $this->node_parent = Doctrine_Core::getTable('EiNode')->findOneByIdAndProjectIdAndProjectRef(
                intval($this->root_id), $this->ei_project->getProjectId(), $this->ei_project->getRefId());
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT)|| $request->isXmlHttpRequest());
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant 
        $this->root_id = $request->getParameter('root_id');
        //        //Parent du noeud dans lequel on ajoute le dossier
        $this->node_parent = Doctrine_Core::getTable('EiNode')->findOneByIdAndProjectIdAndProjectRef(
                intval($this->root_id), $this->ei_project->getProjectId(), $this->ei_project->getRefId());

        $this->ei_folder = new EiFolder();
        $this->ei_folder->setProject($this->ei_project);
        $this->form = new EiFolderForm($this->ei_folder, array('root_id' => $this->root_id));
        return $this->processForm($request, $this->form);
        return sfView::NONE;
    }

    public function executeEdit(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant 
        $this->forward404Unless($ei_folder = Doctrine_Core::getTable('EiFolder')->find(array($request->getParameter('folder_id'))), sprintf('Object ei_folder does not exist (%s).', $request->getParameter('folder_id')));
        $this->ei_folder = $ei_folder;
        $this->ei_node = $this->ei_folder->getNode();
        $this->node_childs = Doctrine_Core::getTable('EiNode')->getNodesOrderByPosition($this->ei_node)->execute();
        $this->form = new EiFolderForm($this->ei_folder);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT)|| $request->isXmlHttpRequest());
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant 
        $this->forward404Unless($ei_folder = Doctrine_Core::getTable('EiFolder')->find(array($request->getParameter('folder_id'))), sprintf('Object ei_folder does not exist (%s).', $request->getParameter('folder_id')));
        $this->form = new EiFolderForm($ei_folder);
        //$this->getResponse()->setContentType('application/json');
        return $this->processForm($request, $this->form);
    }

    public function executeDelete(sfWebRequest $request) {
        //$this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant 
        $this->forward404Unless($ei_folder = Doctrine_Core::getTable('EiFolder')->find(array($request->getParameter('folder_id'))), sprintf('Object ei_folder does not exist (%s).', $request->getParameter('folder_id')));

        $redirect = $this->urlParameters;

        $folder_node = $ei_folder->getNode();
        if ($folder_node->getIsRoot()) {
            $this->getUser()->setFlash('msg_error', 'Youn can\'t delete the root folder !');
            $redirect['folder_id'] = $folder_node->getObjId();
            $redirect['node_id'] = $folder_node->getId();
            $redirect['action'] = 'edit';
            $this->redirect('path_folder', $redirect);
        }
        $ei_folder->getNode()->deleteNodeDiagram();
        $this->getUser()->setFlash('msg_success', 'Folder has been deleted successfully.');
        return $this->redirect('projet_eiscenario', $redirect);
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $update_mode=false;
            if(!$form->getObject()->isNew()) $update_mode=true;
            $this->ei_folder = $form->save();
            $this->getUser()->setFlash('alert_folder_form', array('title' => 'Success ',
                'class' => 'alert-success',
                'text' => 'Well done ...'));
            $nodeLine = $this->urlParameters;
            $nodeLine['ei_node'] = $this->ei_folder->getNode();
            $nodeLine['is_step_context'] = false;
            $JSONResponse['success'] = true;
            $JSONResponse['update_mode'] = $update_mode;
            $JSONResponse['nodeLine'] = $this->getPartial('einode/nodeLine', $nodeLine);
            $JSONResponse['flash_box']= $this->getPartial('global/alertBox',array(  'flash_string' => 'alert_folder_form' ));
        } else {
            $this->getUser()->setFlash('alert_folder_form', array('title' => 'Error ',
                'class' => 'alert-danger',
                'text' => 'Error occur on form , correct wrong fields ...'));
            $url_form = $this->urlParameters;
            $url_form['ei_folder'] = $this->ei_folder;
            $url_form['root_id'] = $this->root_id;
            $url_form['form'] = $this->form;
            $JSONResponse['success'] = false;
            $JSONResponse['html'] = $this->getPartial('form', $url_form);
        }
        return $this->renderText(json_encode($JSONResponse));
    }

}
