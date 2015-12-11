<?php

/**
 * eisubjectpriority actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubjectpriority
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjectpriorityActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_subject_prioritys = Doctrine_Core::getTable('EiSubjectPriority')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiSubjectPriorityForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiSubjectPriorityForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_subject_priority = Doctrine_Core::getTable('EiSubjectPriority')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_priority does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiSubjectPriorityForm($ei_subject_priority);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_subject_priority = Doctrine_Core::getTable('EiSubjectPriority')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_priority does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiSubjectPriorityForm($ei_subject_priority);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_subject_priority = Doctrine_Core::getTable('EiSubjectPriority')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_priority does not exist (%s).', $request->getParameter('id')));
    $ei_subject_priority->delete();

    $this->redirect('eisubjectpriority/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_subject_priority = $form->save();

      $this->redirect('eisubjectpriority/edit?id='.$ei_subject_priority->getId());
    }
  }
}
