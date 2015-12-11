<?php

/**
 * eitask actions.
 *
 * @package    kalifastRobot
 * @subpackage eitask
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eitaskActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_tasks = Doctrine_Core::getTable('EiTask')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiTaskForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiTaskForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_task = Doctrine_Core::getTable('EiTask')->find(array($request->getParameter('id'))), sprintf('Object ei_task does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiTaskForm($ei_task);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_task = Doctrine_Core::getTable('EiTask')->find(array($request->getParameter('id'))), sprintf('Object ei_task does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiTaskForm($ei_task);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_task = Doctrine_Core::getTable('EiTask')->find(array($request->getParameter('id'))), sprintf('Object ei_task does not exist (%s).', $request->getParameter('id')));
    $ei_task->delete();

    $this->redirect('eitask/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_task = $form->save();

      $this->redirect('eitask/edit?id='.$ei_task->getId());
    }
  }
}
