<?php

/**
 * eitaskstatehistory actions.
 *
 * @package    kalifastRobot
 * @subpackage eitaskstatehistory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eitaskstatehistoryActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_task_state_historys = Doctrine_Core::getTable('EiTaskStateHistory')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiTaskStateHistoryForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiTaskStateHistoryForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_task_state_history = Doctrine_Core::getTable('EiTaskStateHistory')->find(array($request->getParameter('task_id'),
    $request->getParameter('new_state'),
    $request->getParameter('date'))), sprintf('Object ei_task_state_history does not exist (%s).', $request->getParameter('task_id'),
    $request->getParameter('new_state'),
    $request->getParameter('date')));
    $this->form = new EiTaskStateHistoryForm($ei_task_state_history);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_task_state_history = Doctrine_Core::getTable('EiTaskStateHistory')->find(array($request->getParameter('task_id'),
    $request->getParameter('new_state'),
    $request->getParameter('date'))), sprintf('Object ei_task_state_history does not exist (%s).', $request->getParameter('task_id'),
    $request->getParameter('new_state'),
    $request->getParameter('date')));
    $this->form = new EiTaskStateHistoryForm($ei_task_state_history);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_task_state_history = Doctrine_Core::getTable('EiTaskStateHistory')->find(array($request->getParameter('task_id'),
    $request->getParameter('new_state'),
    $request->getParameter('date'))), sprintf('Object ei_task_state_history does not exist (%s).', $request->getParameter('task_id'),
    $request->getParameter('new_state'),
    $request->getParameter('date')));
    $ei_task_state_history->delete();

    $this->redirect('eitaskstatehistory/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_task_state_history = $form->save();

      $this->redirect('eitaskstatehistory/edit?task_id='.$ei_task_state_history->getTaskId().'&new_state='.$ei_task_state_history->getNewState().'&date='.$ei_task_state_history->getDate());
    }
  }
}
