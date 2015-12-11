<?php

/**
 * eisubjectcategory actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubjectcategory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjectcategoryActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_subject_categorys = Doctrine_Core::getTable('EiSubjectCategory')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiSubjectCategoryForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiSubjectCategoryForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_subject_category = Doctrine_Core::getTable('EiSubjectCategory')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_category does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiSubjectCategoryForm($ei_subject_category);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_subject_category = Doctrine_Core::getTable('EiSubjectCategory')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_category does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiSubjectCategoryForm($ei_subject_category);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_subject_category = Doctrine_Core::getTable('EiSubjectCategory')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_category does not exist (%s).', $request->getParameter('id')));
    $ei_subject_category->delete();

    $this->redirect('eisubjectcategory/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_subject_category = $form->save();

      $this->redirect('eisubjectcategory/edit?id='.$ei_subject_category->getId());
    }
  }
}
