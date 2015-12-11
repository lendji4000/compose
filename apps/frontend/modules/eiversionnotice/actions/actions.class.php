<?php

/**
 * eiversionnotice actions.
 *
 * @package    kalifastRobot
 * @subpackage eiversionnotice
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiversionnoticeActions extends sfActionsKalifast
{
  public function executeIndex(sfWebRequest $request)
  {
      $this->checkProject($request);
      $this->checkProfile($request, $this->ei_project);
      $this->checkFunction($request, $this->ei_project);
      /* Récupération de la version de notice du profil courant et de la langue par défaut */
      $this->current_notice_version=Doctrine_Core::getTable('EiVersionNotice')->getCurrentNoticeVersion($this->ei_project,$this->ei_profile,$this->kal_function);
      /* Récupération de toutes les langues du projet */
      $this->project_langs=$this->ei_project->getProjectLangs();
      /* Récupération des versions de notices de la fonction */
      $this->noticeVersionsForDropdownList=Doctrine_Core::getTable('EiVersionNotice')->getNoticeVersionsForDropdownList($this->kal_function);
      /*Récupération des profils actifs sur une notice */
      if(count($this->current_notice_version)>0):
      $this->activesProfilesForNoticeVersion=Doctrine_Core::getTable('EiVersionNotice')->getActiveProfilesForVersion(
              $this->ei_project,$this->kal_function,$this->current_notice_version['version_notice_id'],$this->current_notice_version['notice_id'], $this->current_notice_version['notice_ref']);
      endif;
      /* Récupération de la version de notice par défaut pour renvoyer le formulaire d'édition */
      $this->ei_version_notice=Doctrine_Core::getTable('EiVersionNotice')->findOneByVersionNoticeIdAndNoticeIdAndNoticeRefAndLang(
              $this->current_notice_version['version_notice_id'],$this->current_notice_version['notice_id'], $this->current_notice_version['notice_ref'],$this->current_notice_version['lang']);
      $this->form = new EiVersionNoticeForm($this->ei_version_notice);
      /*Récupération des paramètres d'entrées et de sortie dela fonction */
      $this->getAndParseFunctionAndProjectParametersToArray(); //Récupération des paramètres de fonction et de projet sous forme de tableau 
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ei_version_notice = Doctrine_Core::getTable('EiVersionNotice')->find(array($request->getParameter('version_notice_id'),
                                 $request->getParameter('notice_id'),
                                 $request->getParameter('notice_ref'),
                                 $request->getParameter('lang')));
    $this->forward404Unless($this->ei_version_notice);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiVersionNoticeForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiVersionNoticeForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($request->isXmlHttpRequest()); 
    $this->checkProject($request);
    $this->checkProfile($request, $this->ei_project);
    $this->checkFunction($request, $this->ei_project);
    $version_notice_id=$request->getParameter('version_notice_id');$notice_id=$request->getParameter('notice_id');
    $notice_ref=$request->getParameter('notice_ref'); $lang=$request->getParameter('lang');
    
    $this->ei_version_notice=  Doctrine_Core::getTable('EiVersionNotice')->findOneByVersionNoticeIdAndNoticeIdAndNoticeRefAndLang(
              $version_notice_id,$notice_id,$notice_ref,$lang); 
    /* Si la version de notice n'existe pas pour la langue , on la crée sur l'application distante */
    if($this->ei_version_notice==null):
        $this->ei_version_notice =EiVersionNotice::createDistantNoticeLang($version_notice_id,$notice_id,$notice_ref,$lang);
    if($this->ei_version_notice==null || is_array($this->ei_version_notice)): // La version de notice n'a pas pu être créée sur le site distant : on retourne une erreur
        return $this->renderText(json_encode(array(
                        'header' => $this->ei_version_notice['message'] ,
                        'formContent' =>  "Version notice can't be created on central system ..." ,
                        'success' => false)));  
    endif;
    
    endif;
    
      /* Récupération de toutes les langues du projet */
      $this->project_langs=$this->ei_project->getProjectLangs();
      /* Récupération des versions de notices de la fonction */
      $this->noticeVersionsForDropdownList=Doctrine_Core::getTable('EiVersionNotice')->getNoticeVersionsForDropdownList($this->kal_function);
      /*Récupération des profils actifs sur une notice */ 
      $this->activesProfilesForNoticeVersion=Doctrine_Core::getTable('EiVersionNotice')->getActiveProfilesForVersion(
              $this->ei_project,$this->kal_function,$this->ei_version_notice->getVersionNoticeId(),$this->ei_version_notice->getNoticeId(), $this->ei_version_notice->getNoticeRef());
        
      $this->form = new EiVersionNoticeForm($this->ei_version_notice);
      /*Récupération des paramètres d'entrées et de sortie dela fonction */
      $this->getAndParseFunctionAndProjectParametersToArray(); //Récupération des paramètres de fonction et de projet sous forme de tableau 
      /* Gestion des retours json */
      /* En tête de la notice */
      $headerParams=$this->urlParameters;
      $headerParams['function_id']=$this->kal_function->getFunctionId(); $headerParams['function_ref']=$this->kal_function->getFunctionRef();
      $headerParams['ei_version_notice']=$this->ei_version_notice;$headerParams['noticeVersions']=$this->noticeVersionsForDropdownList;
      $headerParams['project_langs']=$this->project_langs;$headerParams['activesProfilesForNoticeVersion']=$this->activesProfilesForNoticeVersion;
      $headerParams['default_notice_lang']=$this->ei_project->getDefaultNoticeLang();
      /* Formulaire de la notice */
      $formContentParams=$this->urlParameters;
      $formContentParams['function_id']=$this->kal_function->getFunctionId(); $formContentParams['function_ref']=$this->kal_function->getFunctionRef();
       $formContentParams['version_notice_id']=$this->ei_version_notice->getVersionNoticeId(); $formContentParams['notice_id']=$this->ei_version_notice->getNoticeId();
        $formContentParams['notice_ref']=$this->ei_version_notice->getNoticeRef(); $formContentParams['lang']=$this->ei_version_notice->getLang();
       $formContentParams['action']="update";  
       /* Retour de la réponse json*/
      return $this->renderText(json_encode(array(
                        'header' => $this->getPartial("versionNoticeHeader",$headerParams) ,
                        'formContent' =>    $this->getPartial("blockForm",array(
                            "form" => $this->form,
                            "url_form" => $formContentParams,
                            'inParameters' => $this->inTabParameters,
                            'outParameters' => $this->outTabParameters)) ,
                        'success' => true)));  
    return sfView::NONE;
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isXmlHttpRequest()); 
    $this->checkProject($request);
    $this->checkProfile($request, $this->ei_project);
    $this->checkFunction($request, $this->ei_project);
    $this->ei_version_notice=  Doctrine_Core::getTable('EiVersionNotice')->findOneByVersionNoticeIdAndNoticeIdAndNoticeRefAndLang(
              $request->getParameter('version_notice_id'),$request->getParameter('notice_id'),$request->getParameter('notice_ref'),$request->getParameter('lang')); 
      $this->form = new EiVersionNoticeForm($this->ei_version_notice); 
    $this->processForm($request, $this->form);
    if($this->success):
        return $this->renderText(json_encode(array(
                        'html' => "Datas saved successfuly ..." ,
                        'success' => true))); 
     else:
         /*Récupération des paramètres d'entrées et de sortie dela fonction */
        $this->getAndParseFunctionAndProjectParametersToArray(); //Récupération des paramètres de fonction et de projet sous forme de tableau 
        $url_form=$this->urlParameters;
        $url_form['version_notice_id']=$this->ei_version_notice->getVersionNoticeId();
                        $url_form['notice_id']=$this->ei_version_notice->getNoticeId();
                        $url_form['notice_ref']=$this->ei_version_notice->getNoticeRef();
                        $url_form['function_id']=$this->kal_function->getFunctionId();
                        $url_form['function_ref']=$this->kal_function->getFunctionRef();
                        $url_form['lang']=$this->ei_version_notice->getLang();
                        $url_form['action']="update"; 
        return $this->renderText(json_encode(array(
                        'html' => $this->getPartial("form",array(
                            "form" => $this->form,
                            "url_form" => $url_form,
                            'inParameters' => $this->inTabParameters,
                            'outParameters' => $this->outTabParameters)) ,
                        'success' => false)));
    endif;
    
    return sfView::NONE;
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_version_notice = Doctrine_Core::getTable('EiVersionNotice')->find(array($request->getParameter('version_notice_id'),
           $request->getParameter('notice_id'),
           $request->getParameter('notice_ref'),
           $request->getParameter('lang'))), sprintf('Object ei_version_notice does not exist (%s).', $request->getParameter('version_notice_id'),
           $request->getParameter('notice_id'),
           $request->getParameter('notice_ref'),
           $request->getParameter('lang')));
    $ei_version_notice->delete();

    $this->redirect('eiversionnotice/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      //$this->ei_version_notice = $form->save(); 
      $this->success=$this->ei_version_notice->updateCentralNotice(array(
                "description" => $form->getValue("description"),
                "expected" => $form->getValue("expected"),
                "result" => $form->getValue("result"),
            ))  ; 
     }
    else{ 
        $this->success=false;
    }
  }
  
  /* Récupération des paramètres de fonction et de projet pour le formulaire d'oracle (notice)
     * d'une fonction chez le client (compose)
     */

    public function getAndParseFunctionAndProjectParametersToArray() { 
        $this->InFunctionsParameters = $this->kal_function->getInKalParams(); //Récupération  des paramètres d'entrée de la fonction
        $this->OutFunctionsParameters = $this->kal_function->getOutKalParams(); //Récupération  des paramètres de sortie de la fonction 
        $this->projectParams = $this->ei_project->getGlobalParams(); //Paramètres du projet
        /* Transformation des resultats en tableaux pour le json */
        $this->parseFunctionAndProjectParamsToArray($this->InFunctionsParameters, $this->OutFunctionsParameters, $this->projectParams);
    }
  //Parsing des paramètres de fonction et de projet sous forme de tableau
    public function parseFunctionAndProjectParamsToArray($InFunctionsParameters, $OutFunctionsParameters, $projectParams) {
        $inTabParameters = array();
        $outTabParameters = array();
        //Parse inParameters to array 
        if (count($InFunctionsParameters)):
            foreach ($InFunctionsParameters as $i => $inParameter):
                $inTabParameters[$i] = $inParameter->getName();
            endforeach;
        endif;
        //Parse out parameters to array 
        if (count($OutFunctionsParameters)):
            foreach ($OutFunctionsParameters as $i => $outParameter):
                $outTabParameters[$i] = $outParameter->getName();
            endforeach;
        endif;
        //Parse global parameters ( project parameters) to array
        //Parse global parameters ( project parameters) to array
        if (count($projectParams)):
            foreach ($projectParams as $i => $inProjectParameter): 
                if($inProjectParameter->getParamType()== "IN"): //Si param de type "IN"
                    $inTabParameters[] = $inProjectParameter->getName();
                endif;
                if($inProjectParameter->getParamType()== "OUT"): //Si param de type "OUT"
                    $outTabParameters[] = $inProjectParameter->getName();
                endif; 
            endforeach;
        endif;

        $this->inTabParameters = $inTabParameters;
        $this->outTabParameters = $outTabParameters;
    }
}
