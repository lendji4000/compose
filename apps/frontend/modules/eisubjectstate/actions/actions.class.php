<?php

/**
 * eisubjectstate actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubjectstate
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjectstateActions extends sfActionsKalifast
{
    //Recherche d'un statut de bug
  public function findState(EiProjet $ei_project,sfWebRequest $request){
      $this->ei_subject_state=doctrine_core::getTable('EiSubjectState')->findOneByIdAndProjectIdAndProjectRef
              ($request->getParameter('state_id'),$ei_project->getProjectId(),$ei_project->getRefId());
  }  
  public function executeIndex(sfWebRequest $request)
  {
    $this->checkProject($request);
    $this->checkProfile($request, $this->ei_project);  
    $this->ei_subject_states = Doctrine_Core::getTable('EiSubjectState')->getSubjectStateForProjectQuery(
            $this->ei_project->getProjectId(),$this->ei_project->getRefId())
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiSubjectStateForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiSubjectStateForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }
 
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404unless($request->isXmlHttpRequest());
      $this->checkProject($request);
      $this->checkProfile($request, $this->ei_project);
      $this->findState($this->ei_project, $request);
      $this->form = new EiSubjectStateForm($this->ei_subject_state);
      $formParam=$this->urlParameters;
      $formParam['form']=$this->form;
      return
                $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eisubjectstate/form', array('formParam' =>$formParam)),
                    'success' => true)));

        return sfView::NONE;   
  }

  public function executeUpdate(sfWebRequest $request)
  {
   $this->forward404unless($request->isXmlHttpRequest());
      $this->checkProject($request);
      $this->checkProfile($request, $this->ei_project);
      $this->findState($this->ei_project, $request);
     $this->form = new EiSubjectStateForm($this->ei_subject_state);
     $this->success=false;
    $this->processForm($request, $this->form);

    if($this->success):
        $stateLineParams=$this->urlParameters;; $stateLineParams['state']=$this->ei_subject_state;
        return
                $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eisubjectstate/stateLine', array('stateLineParams' =>$stateLineParams)),
                    'success' => true))); 
        else:
            $formParam=$this->urlParameters;
            $formParam['form']=$this->form;
            return
                $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eisubjectstate/form', array('formParam' =>$formParam)),
                    'success' => false)));
    endif;
    
    return sfView::NONE;
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_subject_state = Doctrine_Core::getTable('EiSubjectState')->find(array($request->getParameter('id'))), sprintf('Object ei_subject_state does not exist (%s).', $request->getParameter('id')));
    $ei_subject_state->delete();

    $this->redirect('eisubjectstate/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $this->ei_subject_state = $form->save();
      $this->success=true; 
    }
  }
}
