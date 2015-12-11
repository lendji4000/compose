<?php

/**
 * blockparam actions.
 *
 * @package    kalifastRobot
 * @subpackage blockparam
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class blockparamActions extends sfActions
{
    /**
     * Action permettant d'ajouter un paramètre à un bloc (formulaire).
     *
     * Retourne le formulaire au format HTML.
     *
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeAddParamBlock(sfWebRequest $request)
    {
        // Appel AJAX requis.
        $this->forward404unless($request->isXmlHttpRequest());

        // On récupère le nombre de paramètres existants que l'on incrémente.
        $sizeOfParams = intval($request->getParameter("size"));

        $formClass = "EiBlockForm";
        $typeBlock = $request->getParameter("type");

        if( $typeBlock && $typeBlock == EiVersionStructure::$TYPE_FOREACH ){
            $formClass = "EiBlockForeachForm";
        }

        // Création du formulaire.
        $this->form = new $formClass(null,array('size' => $sizeOfParams + 1));

        return $this->renderText(json_encode(array(
            'html' =>  $this->getPartial('blockparam/newBlockParam', array(
                    'form' => $this->form,
                    'size' => $sizeOfParams
                )),
            'success' => true
        )));
    }

    public function executeNew(sfWebRequest $request) {

        $this->form = new EiBlockParamForm();

        $this->forward404Unless($ei_block_id = $request->getParameter('ei_block_id'), "ei_block_id missing");

        $ei_block_parent = Doctrine_Core::getTable('EiBlock')->find($ei_block_id);

        $this->forward404Unless($ei_block_parent, "Block not found.");

        $this->ei_block_parent_id = $ei_block_parent->getId();

        return $this->renderPartial('form');
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->ei_block_parent_id = $request->getParameter('ei_block_parent_id');

        $this->ei_block_parent = Doctrine_Core::getTable('EiBlock')->find(
                $this->ei_block_parent_id);

        $this->forward404Unless($this->ei_block_parent);

        $this->forward404Unless($this->ei_scenario = Doctrine_Core::getTable('EiScenario')
                ->find($this->ei_block_parent->getEiScenario()->getId()));

        $this->forward404Unless(Doctrine_Core::getTable('EiProjectUser')
                        ->getEiProjet($this->ei_scenario->getProjectId(), $this->ei_scenario->getProjectRef(), $this->getUser()->getGuardUser()->getEiUser()));

        $this->ei_block_param = new EiBlockParam();

        $this->ei_block_param->setEiVersionStructureParentId($this->ei_block_parent->getId());

        $this->ei_block_param->setEiScenario($this->ei_scenario);

        $this->form = new EiBlockParamForm($this->ei_block_param);

        if (!($JSONResponse = $this->processForm($request, $this->form))) {
            $JSONResponse = $this->createJSONResponse('created', 'ok');
            $JSONResponse['link'] = $this->generateUrl('eiblockparam_delete', array('ei_block_param_id' => $this->ei_block_param->getId()));
            $JSONResponse['action'] = $this->generateUrl('eiblockparam_update', array('ei_block_param_id' => $this->ei_block_param->getId()));
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($ei_block_param = Doctrine_Core::getTable('EiBlockParam')->find(array($request->getParameter('id'))), sprintf('Object ei_block_param does not exist (%s).', $request->getParameter('id')));
        $this->form = new EiBlockParamForm($ei_block_param);
    }

    public function executeUpdate(sfWebRequest $request) {
        
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        
        $this->forward404Unless($this->ei_block_param = Doctrine_Core::getTable('EiBlockParam')
                ->find($request->getParameter('ei_block_param_id')), sprintf('Object ei_block_param does not exist (%s).',
                        $request->getParameter('id')));
        
        $this->form = new EiBlockParamForm($this->ei_block_param);

       
        if (!($JSONResponse = $this->processForm($request, $this->form))) {
            $JSONResponse = $this->createJSONResponse('updated', 'ok');
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    public function executeDelete(sfWebRequest $request) {

        $this->ei_block_param = Doctrine_Core::getTable('EiBlockParam')
                ->find($request->getParameter('ei_block_param_id'));

        if ($this->ei_block_param == null) {

            $JSONResponse['status'] = "error";

            $JSONResponse['message'] = "Parameter named ".$this->ei_block_param->getName()." not found.";
            
        } else {

            $this->ei_block_param->getNode()->delete();

            $this->getResponse()->setContentType('application/json');

            $JSONResponse = $this->createJSONResponse('deleted', 'ok');
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * Format les réponses JSON de succès.
     * 
     * @param type $action
     * @param type $status
     * @return string
     */
    private function createJSONResponse($action, $status) {
        $JSONResponse['status'] = $status;
        $JSONResponse['message'] = "Parameter " . $this->ei_block_param->getName() . " has been $action successfully.";

        return $JSONResponse;
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        if ($form->isValid()) {
            $isNew = $this->ei_block_param->isNew();

            $this->ei_block_param = $form->save();
            if ($isNew)
                $this->ei_block_param->getNode()->insertAsLastChildOf($this->ei_block_parent);

            return false;
        }
        else {
            $JSONResponse['status'] = "error";
            $errors  = $form->getErrorSchema()->getErrors();
            
            $JSONResponse['message'] = "Unable to save the parameter. " . $errors['name'];
        }

        return $JSONResponse;
    }

}
