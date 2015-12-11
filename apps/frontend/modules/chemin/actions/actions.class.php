<?php

/**
 * chemin actions.
 *
 * @package    kalifastRobot
 * @subpackage chemin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cheminActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }

  public function executeScreen1(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }
  public function executeScreen2(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }
  public function executeScreen3(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }
  public function executeScreen4(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }
  public function executeScreen5(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }
  public function executeScreen6(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }
  public function executeScreen7(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }
  public function executeScreen8(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }
  public function executeScreen9(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }
  public function executeScreen10(sfWebRequest $request)
  {  
    $this->setLayout('templating');
  }
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new CheminForm();
    
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new CheminForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($chemin = Doctrine_Core::getTable('Chemin')->find(array($request->getParameter('id'))), sprintf('Object chemin does not exist (%s).', $request->getParameter('id')));
    $this->form = new CheminForm($chemin);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($chemin = Doctrine_Core::getTable('Chemin')->find(array($request->getParameter('id'))), sprintf('Object chemin does not exist (%s).', $request->getParameter('id')));
    $this->form = new CheminForm($chemin);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    if ($this->getRoute()->getObject()->getNode()->delete())
    {
      $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
    }

    $this->redirect('@chemin');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $chemin = $form->save();

      $this->redirect('chemin/edit?id='.$chemin->getId());
    }
  }
}
