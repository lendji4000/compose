<?php

/**
 * pack actions.
 *
 * @package    kalifastRobot
 * @subpackage pack
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class packActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ei_packs = Doctrine_Core::getTable('EiPack')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiPackForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiPackForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_pack = Doctrine_Core::getTable('EiPack')->find(array($request->getParameter('id_pack'),
                              $request->getParameter('id_ref'))), sprintf('Object ei_pack does not exist (%s).', $request->getParameter('id_pack'),
                              $request->getParameter('id_ref')));
    $this->form = new EiPackForm($ei_pack);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_pack = Doctrine_Core::getTable('EiPack')->find(array($request->getParameter('id_pack'),
                              $request->getParameter('id_ref'))), sprintf('Object ei_pack does not exist (%s).', $request->getParameter('id_pack'),
                              $request->getParameter('id_ref')));
    $this->form = new EiPackForm($ei_pack);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_pack = Doctrine_Core::getTable('EiPack')->find(array($request->getParameter('id_pack'),
                              $request->getParameter('id_ref'))), sprintf('Object ei_pack does not exist (%s).', $request->getParameter('id_pack'),
                              $request->getParameter('id_ref')));
    $ei_pack->delete();

    $this->redirect('pack/index');
  }
  public function executeSendArbo(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            if (!$request->getParameter('ref_pack') || !$request->getParameter('id_pack') ||
                    !$request->getParameter('ref_projet') || !$request->getParameter('id_projet') ||
                    !$request->getParameter('id_version') || !$request->getParameter('ei_scenario_id')) 
                    
                return $this->renderText('Erreur !! paramètre manquant');
             else {
                return $this->renderPartial('pack/arboPack', array('id_pack' => $request->getParameter('id_pack'), 'ref_pack' => $request->getParameter('ref_pack'),
                            'id_projet' => $request->getParameter('id_projet'), 'ref_projet' => $request->getParameter('ref_projet'),
                        'ei_scenario_id' => $request->getParameter('ei_scenario_id'), 'id_version' => $request->getParameter('id_version')));
            }
        }
        return sfView::NONE;
    }
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_pack = $form->save();

      $this->redirect('pack/edit?id_pack='.$ei_pack->getIdPack().'&id_ref='.$ei_pack->getIdRef());
    }
  }
}
