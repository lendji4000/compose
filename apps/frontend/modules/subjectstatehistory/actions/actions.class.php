<?php

/**
 * subjectstatehistory actions.
 *
 * @package    kalifastRobot
 * @subpackage subjectstatehistory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class subjectstatehistoryActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_subject_state_historys = Doctrine_Core::getTable('EiSubjectStateHistory')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiSubjectStateHistoryForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiSubjectStateHistoryForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_subject_state_history = Doctrine_Core::getTable('EiSubjectStateHistory')->find(array($request->getParameter('subject_id'),
$request->getParameter('new_state'),
$request->getParameter('date'))), sprintf('Object ei_subject_state_history does not exist (%s).', $request->getParameter('subject_id'),
$request->getParameter('new_state'),
$request->getParameter('date')));
    $this->form = new EiSubjectStateHistoryForm($ei_subject_state_history);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_subject_state_history = Doctrine_Core::getTable('EiSubjectStateHistory')->find(array($request->getParameter('subject_id'),
$request->getParameter('new_state'),
$request->getParameter('date'))), sprintf('Object ei_subject_state_history does not exist (%s).', $request->getParameter('subject_id'),
$request->getParameter('new_state'),
$request->getParameter('date')));
    $this->form = new EiSubjectStateHistoryForm($ei_subject_state_history);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_subject_state_history = Doctrine_Core::getTable('EiSubjectStateHistory')->find(array($request->getParameter('subject_id'),
$request->getParameter('new_state'),
$request->getParameter('date'))), sprintf('Object ei_subject_state_history does not exist (%s).', $request->getParameter('subject_id'),
$request->getParameter('new_state'),
$request->getParameter('date')));
    $ei_subject_state_history->delete();

    $this->redirect('subjectstatehistory/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_subject_state_history = $form->save();

      $this->redirect('subjectstatehistory/edit?subject_id='.$ei_subject_state_history->getSubjectId().'&new_state='.$ei_subject_state_history->getNewState().'&date='.$ei_subject_state_history->getDate());
    }
  }
}
