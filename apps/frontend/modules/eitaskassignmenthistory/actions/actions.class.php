<?php

/**
 * eitaskassignmenthistory actions.
 *
 * @package    kalifastRobot
 * @subpackage eitaskassignmenthistory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eitaskassignmenthistoryActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_task_assignment_historys = Doctrine_Core::getTable('EiTaskAssignmentHistory')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiTaskAssignmentHistoryForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiTaskAssignmentHistoryForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_task_assignment_history = Doctrine_Core::getTable('EiTaskAssignmentHistory')->find(array($request->getParameter('task_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date'))), sprintf('Object ei_task_assignment_history does not exist (%s).', $request->getParameter('task_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date')));
    $this->form = new EiTaskAssignmentHistoryForm($ei_task_assignment_history);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_task_assignment_history = Doctrine_Core::getTable('EiTaskAssignmentHistory')->find(array($request->getParameter('task_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date'))), sprintf('Object ei_task_assignment_history does not exist (%s).', $request->getParameter('task_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date')));
    $this->form = new EiTaskAssignmentHistoryForm($ei_task_assignment_history);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_task_assignment_history = Doctrine_Core::getTable('EiTaskAssignmentHistory')->find(array($request->getParameter('task_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date'))), sprintf('Object ei_task_assignment_history does not exist (%s).', $request->getParameter('task_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date')));
    $ei_task_assignment_history->delete();

    $this->redirect('eitaskassignmenthistory/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_task_assignment_history = $form->save();

      $this->redirect('eitaskassignmenthistory/edit?task_id='.$ei_task_assignment_history->getTaskId().'&author_of_assignment='.$ei_task_assignment_history->getAuthorOfAssignment().'&assign_to='.$ei_task_assignment_history->getAssignTo().'&date='.$ei_task_assignment_history->getDate());
    }
  }
}
