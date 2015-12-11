<?php

/**
 * eiuserparam actions.
 *
 * @package    kalifastRobot
 * @subpackage eiuserparam
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiuserparamActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  { 
    $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();  
    $this->ei_user_params = Doctrine_Core::getTable('EiUserParam')->getUserParams($this->ei_user); 
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();
    $ei_user_param=new EiUserParam();
    $ei_user_param->setEiUser($this->ei_user);
    $this->form = new EiUserParamForm($ei_user_param);
  }

  public function executeCreate(sfWebRequest $request)
  {
      $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();
    $ei_user_param=new EiUserParam();
    $ei_user_param->setEiUser($this->ei_user); 

    $this->form = new EiUserParamForm($ei_user_param);

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_user_param = Doctrine_Core::getTable('EiUserParam')->find(array($request->getParameter('id'))), sprintf('Object ei_user_param does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiUserParamForm($ei_user_param);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_user_param = Doctrine_Core::getTable('EiUserParam')->find(array($request->getParameter('id'))), sprintf('Object ei_user_param does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiUserParamForm($ei_user_param);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_user_param = Doctrine_Core::getTable('EiUserParam')->find(array($request->getParameter('id'))), sprintf('Object ei_user_param does not exist (%s).', $request->getParameter('id')));
    $ei_user_param->delete();

    $this->redirect('eiuserparam/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_user_param = $form->save();

      $this->redirect('eiuserparam/edit?id='.$ei_user_param->getId());
    }
  }
}
