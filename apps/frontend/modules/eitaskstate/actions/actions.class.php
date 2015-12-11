<?php

/**
 * eitaskstate actions.
 *
 * @package    kalifastRobot
 * @subpackage eitaskstate
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eitaskstateActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_task_states = Doctrine_Core::getTable('EiTaskState')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiTaskStateForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiTaskStateForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_task_state = Doctrine_Core::getTable('EiTaskState')->find(array($request->getParameter('id'))), sprintf('Object ei_task_state does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiTaskStateForm($ei_task_state);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_task_state = Doctrine_Core::getTable('EiTaskState')->find(array($request->getParameter('id'))), sprintf('Object ei_task_state does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiTaskStateForm($ei_task_state);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_task_state = Doctrine_Core::getTable('EiTaskState')->find(array($request->getParameter('id'))), sprintf('Object ei_task_state does not exist (%s).', $request->getParameter('id')));
    $ei_task_state->delete();

    $this->redirect('eitaskstate/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_task_state = $form->save();

      $this->redirect('eitaskstate/edit?id='.$ei_task_state->getId());
    }
  }
}
