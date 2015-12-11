<?php

/**
 * kalparam actions.
 *
 * @package    kalifast
 * @subpackage kalparam
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class kalparamActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->kal_params = Doctrine_Core::getTable('KalParam')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->kal_param = Doctrine_Core::getTable('KalParam')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->kal_param);
  }

  public function executeAddParamField(sfWebRequest $request){
      $this->forward404unless($request->isXmlHttpRequest());
      $number = intval($request->getParameter("num")); 
      $param_type = $request->getParameter("paramType"); 
      $this->form = new KalFunctionForm(null,array('size' => $number,'param_type' => $param_type)); 
 
      return $this->renderText(json_encode(array(
                    'html' =>  $this->getPartial('kalparam/newParamField',array(
                        'form' => $this->form, 'number' => $number)),
                    'success' => true )));
      
      return sfView::NONE;
    }
    
  public function executeNew(sfWebRequest $request)
  { 
    $this->form = new KalParamForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new KalParamForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($kal_param = Doctrine_Core::getTable('KalParam')->find(array($request->getParameter('id'))), sprintf('Object kal_param does not exist (%s).', $request->getParameter('id')));
    $this->form = new KalParamForm($kal_param);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($kal_param = Doctrine_Core::getTable('KalParam')->find(array($request->getParameter('id'))), sprintf('Object kal_param does not exist (%s).', $request->getParameter('id')));
    $this->form = new KalParamForm($kal_param);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($kal_param = Doctrine_Core::getTable('KalParam')->find(array($request->getParameter('id'))), sprintf('Object kal_param does not exist (%s).', $request->getParameter('id')));
    $kal_param->delete();

    $this->redirect('kalparam/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $kal_param = $form->save();

      $this->redirect('kalparam/edit?id='.$kal_param->getId());
    }
  }
}
