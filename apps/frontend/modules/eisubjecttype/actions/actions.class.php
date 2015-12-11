<?php

/**
 * eisubjecttype actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubjecttype
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjecttypeActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_subject_types = Doctrine_Core::getTable('EiSubjectType')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiSubjectTypeForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiSubjectTypeForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_subject_type = Doctrine_Core::getTable('EiSubjectType')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_type does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiSubjectTypeForm($ei_subject_type);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_subject_type = Doctrine_Core::getTable('EiSubjectType')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_type does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiSubjectTypeForm($ei_subject_type);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_subject_type = Doctrine_Core::getTable('EiSubjectType')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_type does not exist (%s).', $request->getParameter('id')));
    $ei_subject_type->delete();

    $this->redirect('eisubjecttype/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_subject_type = $form->save();

      $this->redirect('eisubjecttype/edit?id='.$ei_subject_type->getId());
    }
  }
}
