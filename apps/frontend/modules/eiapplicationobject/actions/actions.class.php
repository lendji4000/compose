<?php

/**
 * eiapplicationobject actions.
 *
 * @package    kalifastRobot
 * @subpackage eiapplicationobject
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiapplicationobjectActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_application_objects = Doctrine_Core::getTable('EiApplicationObject')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiApplicationObjectForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiApplicationObjectForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_application_object = Doctrine_Core::getTable('EiApplicationObject')->find(array($request->getParameter('id'))), sprintf('Object ei_application_object does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiApplicationObjectForm($ei_application_object);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_application_object = Doctrine_Core::getTable('EiApplicationObject')->find(array($request->getParameter('id'))), sprintf('Object ei_application_object does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiApplicationObjectForm($ei_application_object);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_application_object = Doctrine_Core::getTable('EiApplicationObject')->find(array($request->getParameter('id'))), sprintf('Object ei_application_object does not exist (%s).', $request->getParameter('id')));
    $ei_application_object->delete();

    $this->redirect('eiapplicationobject/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_application_object = $form->save();

      $this->redirect('eiapplicationobject/edit?id='.$ei_application_object->getId());
    }
  }
}
