<?php

/**
 * eifuncttionparams actions.
 *
 * @package    kalifastRobot
 * @subpackage eifuncttionparams
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eifunctionparamsActions extends sfActionsKalifast {

    public function executeIndex(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $this->functionsParameters = $this->kal_function->getKalParams();
        /* Génération des formulaires correspondant à chaque paramètres */
        $this->functionParams = array(
            'IN' => array(),
            'OUT' => array());
        if (count($this->functionsParameters) > 0):
            foreach ($this->functionsParameters as $param):
                if ($param->getParamType() == "IN"):
                    $this->functionParams['IN'][$param->getParamId()] = $param;
                else:
                    $this->functionParams['OUT'][$param->getParamId()] = $param;
                endif;
            endforeach;
        endif;
    }

    public function executeShow(sfWebRequest $request) {
        $this->ei_function_has_param = Doctrine_Core::getTable('EiFunctionHasParam')->find(array($request->getParameter('param_id')));
        $this->forward404Unless($this->ei_function_has_param);
    }

    public function executeNew(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $this->ei_function_has_param = new EiFunctionHasParam();
        $this->ei_function_has_param->setFunctionId($this->kal_function->getFunctionId());
        $this->ei_function_has_param->setFunctionRef($this->kal_function->getFunctionRef());
        $this->ei_function_has_param->setParamType($request->getParameter("param_type"));
        $this->form = new EiFunctionHasParamForm($this->ei_function_has_param);
        $url_form = $this->urlParameters;
        $url_form['function_id'] = $this->kal_function->getFunctionId();
        $url_form['function_ref'] = $this->kal_function->getFunctionRef();
        $url_form['action'] = "create";
        /* Retour de la réponse json */
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial("form", array(
                        "form" => $this->form,
                        "url_form" => $url_form)),
                    'success' => true)));
        return sfView::NONE;
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $this->ei_function_has_param = new EiFunctionHasParam();
        $this->ei_function_has_param->setFunctionId($this->kal_function->getFunctionId());
        $this->ei_function_has_param->setFunctionRef($this->kal_function->getFunctionRef());
        $this->form = new EiFunctionHasParamForm($this->ei_function_has_param);

        $this->processForm($request, $this->form);

        $url_form = $this->urlParameters;
        $url_form['function_id'] = $this->kal_function->getFunctionId();
        $url_form['function_ref'] = $this->kal_function->getFunctionRef();
        if ($this->success):
            $url_form['param_id'] = $this->param_id;
            $url_form['action'] = "update";
            $updateParam = Doctrine_Core::getTable('EiFunctionHasParam')->findOneByParamId($this->param_id);

            $paramDatas = $this->urlParameters;
            $paramDatas['function_id'] = $this->kal_function->getFunctionId();
            $paramDatas['function_ref'] = $this->kal_function->getFunctionRef();
            $paramDatas['param_id'] = $this->param_id;
            $paramDatas['ei_function_has_param'] = $updateParam->asArray();
            /* Retour de la réponse json */
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('paramLine', $paramDatas),
                        'param_id' => $this->param_id,
                        'param_type' => $updateParam['param_type'],
                        'success' => true)));
        endif;
        /* Retour de la réponse json */
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial("form", array(
                        "form" => $this->form,
                        "url_form" => $url_form)),
                    'createMode' => true,
                    'success' => $this->success)));
        return sfView::NONE;
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $this->ei_function_has_param = Doctrine_Core::getTable('EiFunctionHasParam')->find(array($request->getParameter('param_id')));
        $this->forward404Unless($this->ei_function_has_param);
        $this->form = new EiFunctionHasParamForm($this->ei_function_has_param);
        $url_form = $this->urlParameters;
        $url_form['function_id'] = $this->ei_function_has_param->getFunctionId();
        $url_form['function_ref'] = $this->ei_function_has_param->getFunctionRef();
        $url_form['param_id'] = $this->ei_function_has_param->getParamId();
        $url_form['action'] = "update";
        /* Retour de la réponse json */
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial("form", array(
                        "form" => $this->form,
                        "url_form" => $url_form)),
                    'success' => true)));
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $this->ei_function_has_param = Doctrine_Core::getTable('EiFunctionHasParam')->find(array($request->getParameter('param_id')));
        $this->form = new EiFunctionHasParamForm($this->ei_function_has_param);

        $this->processForm($request, $this->form);

        if ($this->success):

            $paramDatas = $this->urlParameters;
            $paramDatas['function_id'] = $this->kal_function->getFunctionId();
            $paramDatas['function_ref'] = $this->kal_function->getFunctionRef();
            $paramDatas['param_id'] = $this->ei_function_has_param->getParamId();

            $updateParam = Doctrine_Core::getTable('EiFunctionHasParam')->findOneByParamId($this->param_id);
            $paramDatas['ei_function_has_param'] = $updateParam->asArray();
            /* Retour de la réponse json */
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('paramLine', $paramDatas),
                        'param_id' => $this->ei_function_has_param->getParamId(),
                        'param_type' => $this->ei_function_has_param->getParamType(),
                        'success' => true)));
        else:
            $url_form = $this->urlParameters;
            $url_form['function_id'] = $this->kal_function->getFunctionId();
            $url_form['function_ref'] = $this->kal_function->getFunctionRef();
            $url_form['param_id'] = $this->ei_function_has_param->getParamId();
            $url_form['action'] = "update";
            /* Retour de la réponse json */
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial("form", array(
                            "form" => $this->form,
                            "url_form" => $url_form)),
                        'success' => false)));
        endif;
        return sfView::NONE;
    }

    public function executeDelete(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $this->ei_function_has_param = Doctrine_Core::getTable('EiFunctionHasParam')->find(array($request->getParameter('param_id')));
        $this->success = false;
        $this->html = "Error on process. Can't delete function parameter ...";
        $deleteRes = $this->ei_function_has_param->deleteParam($this->ei_project, $this->ei_profile, $this->kal_function, $this->ei_function_has_param->getParamId());
        if ($deleteRes):
            $this->success = true;
            $this->html = "Well done.Parameter has been delete successfully ...";
        endif;
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'success' => $this->success)));
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $this->html = $request->getParameter($form->getName());
            //Si les données sont valides, on envoi en format json la fonction et ses éventuels paramètres pour insertion. 
            $this->param_id = EiFunctionHasParam::createOrUpdateDistantParams($this->ei_project, $this->ei_profile, $this->kal_function, json_encode($this->html));
            $this->success = true;
        } else {
            $this->success = false;
        }
    }

}
