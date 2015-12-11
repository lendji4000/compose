<?php

/**
 * subjectpriorityhistory actions.
 *
 * @package    kalifastRobot
 * @subpackage subjectpriorityhistory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class subjectpriorityhistoryActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_subject_priority_historys = Doctrine_Core::getTable('EiSubjectPriorityHistory')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiSubjectPriorityHistoryForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiSubjectPriorityHistoryForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_subject_priority_history = Doctrine_Core::getTable('EiSubjectPriorityHistory')->find(array($request->getParameter('subject_id'),
$request->getParameter('new_priority'),
$request->getParameter('date'))), sprintf('Object ei_subject_priority_history does not exist (%s).', $request->getParameter('subject_id'),
$request->getParameter('new_priority'),
$request->getParameter('date')));
    $this->form = new EiSubjectPriorityHistoryForm($ei_subject_priority_history);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_subject_priority_history = Doctrine_Core::getTable('EiSubjectPriorityHistory')->find(array($request->getParameter('subject_id'),
$request->getParameter('new_priority'),
$request->getParameter('date'))), sprintf('Object ei_subject_priority_history does not exist (%s).', $request->getParameter('subject_id'),
$request->getParameter('new_priority'),
$request->getParameter('date')));
    $this->form = new EiSubjectPriorityHistoryForm($ei_subject_priority_history);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_subject_priority_history = Doctrine_Core::getTable('EiSubjectPriorityHistory')->find(array($request->getParameter('subject_id'),
$request->getParameter('new_priority'),
$request->getParameter('date'))), sprintf('Object ei_subject_priority_history does not exist (%s).', $request->getParameter('subject_id'),
$request->getParameter('new_priority'),
$request->getParameter('date')));
    $ei_subject_priority_history->delete();

    $this->redirect('subjectpriorityhistory/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_subject_priority_history = $form->save();

      $this->redirect('subjectpriorityhistory/edit?subject_id='.$ei_subject_priority_history->getSubjectId().'&new_priority='.$ei_subject_priority_history->getNewPriority().'&date='.$ei_subject_priority_history->getDate());
    }
  }
}
