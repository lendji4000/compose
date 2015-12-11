<?php

/**
 * eiparam actions.
 *
 * @package    kalifast
 * @subpackage eiparam
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiparamActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_params = Doctrine_Core::getTable('EiParam')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {

  }

  public function executeNew(sfWebRequest $request)
  {
          $id_fonction=$request->getParameter('id_fonction');
    $fonction=Doctrine_Core::getTable('EiFonction')->findOneBy('id', $id_fonction);
    if($fonction!=null){
      $eiparam=new EiParam(); $eiparam->EiFonction=$fonction;
      $eiparam->ei_scenario_id=$fonction->getEiVersion()->getEiScenario()->id;
      $eiparam->id_version=$fonction->getEiVersion()->id;
      $this->form = new EiParamForm($eiparam);
    }
    if($request->isXmlHttpRequest()){
        $this->module_depart=$request->getParameter('module_depart');
        echo $this->module_depart;
    }
    
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiParamForm();
    $url=$request->getParameter('module_depart');
    $this->processForm($request, $this->form , $url);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_param = Doctrine_Core::getTable('EiParam')->find(array($request->getParameter('id'))), sprintf('Object ei_param does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiParamForm($ei_param);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_param = Doctrine_Core::getTable('EiParam')->find(array($request->getParameter('id'))), sprintf('Object ei_param does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiParamForm($ei_param);
    
    $this->processForm($request, $this->form );

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    if($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT)){
            $request->checkCSRFProtection();
      }
      
    $this->forward404Unless($ei_param = Doctrine_Core::getTable('EiParam')->find(array($request->getParameter('id'))), sprintf('Object ei_param does not exist (%s).', $request->getParameter('id')));
    $ei_param->delete();

    $this->redirect('eiparam/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form , $url)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_param = $form->save();
           $this->redirect('eifonction/show?id='.$ei_param->getEiFonction()->id); 
    }
    else{
        $this->renderText('formulaire invalide !! vérifiez');
        }
  }
}
