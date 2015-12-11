<?php

/**
 * eitaskauthorhistory actions.
 *
 * @package    kalifastRobot
 * @subpackage eitaskauthorhistory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eitaskauthorhistoryActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_task_author_historys = Doctrine_Core::getTable('EiTaskAuthorHistory')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiTaskAuthorHistoryForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiTaskAuthorHistoryForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_task_author_history = Doctrine_Core::getTable('EiTaskAuthorHistory')->find(array($request->getParameter('task_id'),
  $request->getParameter('new_author'),
  $request->getParameter('date'))), sprintf('Object ei_task_author_history does not exist (%s).', $request->getParameter('task_id'),
  $request->getParameter('new_author'),
  $request->getParameter('date')));
    $this->form = new EiTaskAuthorHistoryForm($ei_task_author_history);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_task_author_history = Doctrine_Core::getTable('EiTaskAuthorHistory')->find(array($request->getParameter('task_id'),
  $request->getParameter('new_author'),
  $request->getParameter('date'))), sprintf('Object ei_task_author_history does not exist (%s).', $request->getParameter('task_id'),
  $request->getParameter('new_author'),
  $request->getParameter('date')));
    $this->form = new EiTaskAuthorHistoryForm($ei_task_author_history);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_task_author_history = Doctrine_Core::getTable('EiTaskAuthorHistory')->find(array($request->getParameter('task_id'),
  $request->getParameter('new_author'),
  $request->getParameter('date'))), sprintf('Object ei_task_author_history does not exist (%s).', $request->getParameter('task_id'),
  $request->getParameter('new_author'),
  $request->getParameter('date')));
    $ei_task_author_history->delete();

    $this->redirect('eitaskauthorhistory/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_task_author_history = $form->save();

      $this->redirect('eitaskauthorhistory/edit?task_id='.$ei_task_author_history->getTaskId().'&new_author='.$ei_task_author_history->getNewAuthor().'&date='.$ei_task_author_history->getDate());
    }
  }
}
