<?php

/**
 * eiuserprofileparam actions.
 *
 * @package    kalifastRobot
 * @subpackage eiuserprofileparam
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiuserprofileparamActions extends sfActionsKalifast
{
  public function executeIndex(sfWebRequest $request)
  {
      $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();
      $this->checkProject($request);
      $this->ei_profiles=$this->ei_project->getProfils();//Profils du projet
      $this->project_params=$this->ei_project->getGlobalParams(); //Paramètres globaux du projet
       
      $this->ei_user_profile_params = Doctrine_Core::getTable('EiUserProfileParam')->createQuery('upp')
                ->where('EiProfileParam.id=upp.profile_param_id')
                ->andWhere("EiProfil.profile_id=EiProfileParam.profile_id And EiProfil.profile_ref=EiProfileParam.profile_ref")
                        ->andWhere('EiProfil.project_id=? And EiProfil.project_ref=? And upp.user_id=? And upp.user_ref=?',array(
                             $this->ei_project->getProjectId(),$this->ei_project->getRefId(),$this->ei_user->getUserId(),$this->ei_user->getRefId()))
                ->fetchArray();
      if(count($this->ei_user_profile_params)>0):
          foreach($this->ei_user_profile_params as $usProfPar):
          $usProfParAsArray[$usProfPar['profile_param_id']]=array('id'=>$usProfPar['id'],'value'=>$usProfPar['value']);         
          endforeach;
          $this->usProfParAsArray=$usProfParAsArray;
      endif;
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiUserProfileParamForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiUserProfileParamForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  //Reset d'un paramètre de profil niveau utilisateur. 
  //On remet la valeur du paramètre de profil définie par défaut au niveau du profil sur la plate forme centrale
  public function executeResetUserProfileParam(sfWebRequest $request){
      $this->forward404unless($request->isXmlHttpRequest()); 
      $this->checkProject($request); 
      $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();
      $this->user_profile_param_id=$request->getParameter('id'); //Id du paramètre surchargé
      $this->success=false;
      $this->html='Error in process ...';
      //On vérifie que le profil est surchargé pour l'utilisateur
        if($this->user_profile_param_id!=null && $this->ei_user !=null):
          $user_prof_param=Doctrine_Core::getTable('EiUserProfileParam')->findOneByUserIdAndUserRefAndId(
                 $this->ei_user->getUserId(),$this->ei_user->getRefId(),$this->user_profile_param_id );
        else:
            $user_prof_param=null;
        endif;
      if($user_prof_param!=null): //Le profil est définit pour l'utilisateur , on le supprime et on retourne la valeur d'origine
          //On réccupère le paramètre de profil original
          $this->ei_profile_param=Doctrine_Core::getTable('EiProfileParam')->findOneById($user_prof_param->getProfileParamId());
          $user_prof_param->delete();
          $this->html=$this->getPartial('eiuserprofileparam/profileParamCase',
                        array('ei_project' => $this->ei_project,
                              'param' =>$this->ei_profile_param->asArray() 
                    ));
          $this->success=true;
          else: //Le profil n'est pas définit : On retourne une alert à l'utilisateur
              $this->html="The environment param is not set for this user";
      endif;
      return $this->renderText(json_encode(array(
                        'html' =>$this->html,
                        'success' => $this->success)));
      
  }
  //On crée le paramètre utilisateur liée à un paramètre de profil avant de l'éditer.
  public function executeCreateAndEdit(sfWebRequest $request){
      $this->forward404unless($request->isXmlHttpRequest()); 
      $this->checkProject($request);
      $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();
      $this->checkProfile($request, $this->ei_project,false);
      //On recherche la paramètre de profil à crée pour l'utilisateur
      $this->profile_param_id=$request->getParameter('id');
      $this->ei_profile_param=Doctrine_Core::getTable('EiProfileParam')->findOneById($this->profile_param_id);
      $this->success=false;
      $this->html='Error in process ...';
      if($this->ei_profile_param !=null):
          //On vérifie que le profil n'est pas déjà surchargé pour l'utilisateur
          $user_prof_param=Doctrine_Core::getTable('EiUserProfileParam')->findOneByUserIdAndUserRefAndProfileParamId(
                 $this->ei_user->getUserId(),$this->ei_user->getRefId(),$this->profile_param_id );
          if($user_prof_param!=null):
              $ei_user_profile_param=$user_prof_param;
          else:
              $ei_user_profile_param=new EiUserProfileParam(null,false,$this->ei_profile_param,$this->ei_user);
              $ei_user_profile_param->setValue($this->ei_profile_param->getValue());
              $ei_user_profile_param->save();
          endif;       
          $this->form = new EiUserProfileParamForm($ei_user_profile_param);
          $this->success=true;
          $this->html= $this->getPartial('eiuserprofileparam/form',array(
                            'form' => $this->form,
                            'ei_project' => $this->ei_project,
                            'ei_user_profile_param'=> $ei_user_profile_param));
          else:
              $this->html='Environment param doesn\'t exist';
      endif;
      
      return $this->renderText(json_encode(array(
                        'html' =>$this->html,
                        'success' => $this->success)));
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404unless($request->isXmlHttpRequest()); 
    $this->checkProject($request);
    $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();
    $this->success=false;
    $this->html='Error in process ...';
    $ei_user_profile_param = Doctrine_Core::getTable('EiUserProfileParam')->findOneById($request->getParameter('id')); 
    $this->form = new EiUserProfileParamForm($ei_user_profile_param);
    $this->success=true;
          $this->html= $this->getPartial('eiuserprofileparam/form',array(
                            'form' => $this->form,
                            'ei_project' => $this->ei_project,
                            'ei_user_profile_param'=> $ei_user_profile_param));
    return $this->renderText(json_encode(array(
                        'html' =>$this->html,
                        'success' => $this->success)));
  }

  public function executeUpdate(sfWebRequest $request)
  { 
    $this->forward404unless($request->isXmlHttpRequest()); 
    $this->checkProject($request);
    $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();
    $this->success=false;
    $this->html='Error in process ...';
    $ei_user_profile_param = Doctrine_Core::getTable('EiUserProfileParam')->findOneById($request->getParameter('id')); 
    $this->form = new EiUserProfileParamForm($ei_user_profile_param);
    
    $this->processForm($request, $this->form);
    
    if($this->success){  
        $this->html= $this->getPartial('eiuserprofileparam/userProfileParamCase',array(
            'ei_project' => $this->ei_project, 
                            'ei_user_profile_param' =>array(
                                'id'=> $this->ei_user_profile_param->getId(),
                                'value' => $this->ei_user_profile_param->getValue())
            ));
    }
    return $this->renderText(json_encode(array(
                        'html' =>$this->html,
                        'success' => $this->success)));
    return sfView::NONE;
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_user_profile_param = Doctrine_Core::getTable('EiUserProfileParam')->find(array($request->getParameter('id'))), sprintf('Object ei_user_profile_param does not exist (%s).', $request->getParameter('id')));
    $ei_user_profile_param->delete();

    $this->redirect('eiuserprofileparam/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $this->ei_user_profile_param = $form->save();
      $this->success=true; 
      //$this->redirect('eiuserprofileparam/edit?id='.$ei_user_profile_param->getId());
    }
    else{
       $this->html="Error when trying to save Param"; 
    }
  }
}
