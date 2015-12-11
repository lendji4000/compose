<?php

/**
 * subjectassignmenthistory actions.
 *
 * @package    kalifastRobot
 * @subpackage subjectassignmenthistory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class subjectassignmenthistoryActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_subject_assignment_historys = Doctrine_Core::getTable('EiSubjectAssignmentHistory')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiSubjectAssignmentHistoryForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiSubjectAssignmentHistoryForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_subject_assignment_history = Doctrine_Core::getTable('EiSubjectAssignmentHistory')->find(array($request->getParameter('subject_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date'))), sprintf('Object ei_subject_assignment_history does not exist (%s).', $request->getParameter('subject_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date')));
    $this->form = new EiSubjectAssignmentHistoryForm($ei_subject_assignment_history);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_subject_assignment_history = Doctrine_Core::getTable('EiSubjectAssignmentHistory')->find(array($request->getParameter('subject_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date'))), sprintf('Object ei_subject_assignment_history does not exist (%s).', $request->getParameter('subject_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date')));
    $this->form = new EiSubjectAssignmentHistoryForm($ei_subject_assignment_history);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_subject_assignment_history = Doctrine_Core::getTable('EiSubjectAssignmentHistory')->find(array($request->getParameter('subject_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date'))), sprintf('Object ei_subject_assignment_history does not exist (%s).', $request->getParameter('subject_id'),
$request->getParameter('author_of_assignment'),
$request->getParameter('assign_to'),
$request->getParameter('date')));
    $ei_subject_assignment_history->delete();

    $this->redirect('subjectassignmenthistory/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_subject_assignment_history = $form->save();

      $this->redirect('subjectassignmenthistory/edit?subject_id='.$ei_subject_assignment_history->getSubjectId().'&author_of_assignment='.$ei_subject_assignment_history->getAuthorOfAssignment().'&assign_to='.$ei_subject_assignment_history->getAssignTo().'&date='.$ei_subject_assignment_history->getDate());
    }
  }
}
