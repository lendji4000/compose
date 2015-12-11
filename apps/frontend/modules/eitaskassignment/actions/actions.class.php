<?php

/**
 * eitaskassignment actions.
 *
 * @package    kalifastRobot
 * @subpackage eitaskassignment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eitaskassignmentActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_task_assignments = Doctrine_Core::getTable('EiTaskAssignment')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiTaskAssignmentForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiTaskAssignmentForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_task_assignment = Doctrine_Core::getTable('EiTaskAssignment')->find(array($request->getParameter('task_id'),
         $request->getParameter('author_id'))), sprintf('Object ei_task_assignment does not exist (%s).', $request->getParameter('task_id'),
         $request->getParameter('author_id')));
    $this->form = new EiTaskAssignmentForm($ei_task_assignment);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_task_assignment = Doctrine_Core::getTable('EiTaskAssignment')->find(array($request->getParameter('task_id'),
         $request->getParameter('author_id'))), sprintf('Object ei_task_assignment does not exist (%s).', $request->getParameter('task_id'),
         $request->getParameter('author_id')));
    $this->form = new EiTaskAssignmentForm($ei_task_assignment);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_task_assignment = Doctrine_Core::getTable('EiTaskAssignment')->find(array($request->getParameter('task_id'),
         $request->getParameter('author_id'))), sprintf('Object ei_task_assignment does not exist (%s).', $request->getParameter('task_id'),
         $request->getParameter('author_id')));
    $ei_task_assignment->delete();

    $this->redirect('eitaskassignment/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_task_assignment = $form->save();

      $this->redirect('eitaskassignment/edit?task_id='.$ei_task_assignment->getTaskId().'&author_id='.$ei_task_assignment->getAuthorId());
    }
  }
}
