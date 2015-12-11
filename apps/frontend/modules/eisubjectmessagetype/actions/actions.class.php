<?php

/**
 * eisubjectmessagetype actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubjectmessagetype
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjectmessagetypeActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_subject_message_types = Doctrine_Core::getTable('EiSubjectMessageType')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiSubjectMessageTypeForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiSubjectMessageTypeForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_subject_message_type = Doctrine_Core::getTable('EiSubjectMessageType')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_message_type does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiSubjectMessageTypeForm($ei_subject_message_type);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_subject_message_type = Doctrine_Core::getTable('EiSubjectMessageType')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_message_type does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiSubjectMessageTypeForm($ei_subject_message_type);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_subject_message_type = Doctrine_Core::getTable('EiSubjectMessageType')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_message_type does not exist (%s).', $request->getParameter('id')));
    $ei_subject_message_type->delete();

    $this->redirect('eisubjectmessagetype/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_subject_message_type = $form->save();

      $this->redirect('eisubjectmessagetype/edit?id='.$ei_subject_message_type->getId());
    }
  }
}
