<?php

/**
 * eiuser actions.
 *
 * @package    kalifastRobot
 * @subpackage eiuser
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiuserActions extends sfActionsKalifast
{ 
  
    /* Sauvegarde des données du profil dans la session utilisateur */
    public function setProfileSession($profile_name,$profile_id,$profile_ref){
        //Sauvegarde du profil en session utilisateur  
        if($profile_name!=null && $profile_id!=null && $profile_ref!=null):
        $this->getUser()->setAttribute("current_profile_name", $this->profile_name);
        $this->getUser()->setAttribute("current_profile_id", $this->profile_id);
        $this->getUser()->setAttribute("current_profile_ref", $this->profile_ref);
        endif;
    }
    
    public function executeIndex(sfWebRequest $request)
    {
        $user_settings = Doctrine_Core::getTable("EiUserSettings")->findOneByUserRefAndUserId($this->ei_user->getRefId(), $this->ei_user->getUserId());

        if( $user_settings != null ){
            $this->forward($this->getModuleName(), "edit");
        }
        else{
            $this->forward($this->getModuleName(), "new");
        }
    }
    
    /* Verifier par une requête ajax qu'un utilisateur a bien définit un package par défaut */
    public function executeIsUserGetDefaultPack(sfWebRequest $request){ 
        $this->forward404unless($request->isXmlHttpRequest());  
        $this->checkProject($request);
         $this->defPack=$this->ei_user->getDefaultIntervention($this->ei_project);
         //On vérifie que le package par défaut est bien lié à une intervention
        $this->result='User get default package'; $this->success=true;
         if($this->defPack==null):
             $this->success=false;
             $this->result="Please set a default package and re-try"; 
         endif;
         return $this->renderText(json_encode(array(
                        'html' =>$this->result,
                        'success' => $this->success)));
         return sfView::NONE; 
    }
    public function preSetDefaultPackage(){   
        //Recherche d'une ligne définissant pour l'utilisateur le package par défaut pour le projet
        $this->defPack=$this->ei_user->getDefaultIntervention($this->ei_project);
        
        if($this->defPack!=null):
            $this->defaultPackage = Doctrine_Core::getTable("EiTicket")->findOneByTicketRefAndTicketId(
                $this->defPack->getTicketRef(),$this->defPack->getTicketId());
        else:
            $this->defaultPackage=null;
        endif;
    }
    public function updateDefaultPackage(array $ticket,$is_new){ 
        $this->result=false;
        if($ticket[0]!=null && $ticket[1]!=null):
            $this->result =Doctrine_Core::getTable('EiUserDefaultPackage')
                    ->setDefaultPackage($ticket[0],$ticket[1],$this->ei_project,$this->ei_user,$is_new);
            if($this->result): 
                $this->form->setDefault('defaultPackage',$ticket[0].'_'.$ticket[1]); 
                $this->getUser()->setFlash ('defaultPackageDefineWell', 'Default package set successfully ...');
                else:
                    $this->getUser()->setFlash ('defaultPackageDefineError', 'Problem occur when trying to set default package ...');
            endif;
        endif;
    }
    
    public function executeSetDefaultPackage(sfWebRequest $request)
    {
        $this->checkProject($request);
        $this->preSetDefaultPackage(); 
            if($this->defaultPackage==null):   
                $this->executeNewDefaultPackage($request,true); 
            else :
                $this->executeEditDefaultPackage($request,true); 
            endif;  
    }
    
    public function executeNewDefaultPackage(sfWebRequest $request ,$isredirect=false){   
        $this->checkProject($request);
        if(!$isredirect) $this->preSetDefaultPackage(); 
        $this->form=new defaultPackageForm(array() ,
                array('projectPackages' => $this->ei_project->getTickets(),
                      'defPack' => $this->defPack)); 
        $this->setTemplate('setDefaultPackage');
    }
    
    
    public function executeCreateDefaultPackage(sfWebRequest $request ,$isredirect=false){
        $this->checkProject($request);
        if(!$isredirect) $this->preSetDefaultPackage();  
        $this->form=new defaultPackageForm(array(),
                array('projectPackages' => $this->ei_project->getTickets(),
                      'defPack' => $this->defPack));
        $default_package=$request->getParameter($this->form->getName());
        $this->updateDefaultPackage(explode('_', $default_package['defaultPackage']),0);
        if($this->result)
            $this->redirect('setDefaultPackage',array(
                    'action' =>'editDefaultPackage',
                    'project_id' => $this->ei_project->getProjectId(),
                    'project_ref' => $this->ei_project->getRefId())) ;
        
        $this->setTemplate('setDefaultPackage');
    }
    
    public function executeEditDefaultPackage(sfWebRequest $request,$isredirect=false){
        $this->checkProject($request);
        if(!$isredirect) $this->preSetDefaultPackage(); 
        $this->form=new defaultPackageForm(array(),
                array('projectPackages' => $this->ei_project->getTickets(),
                      'defPack' => $this->defPack));
        $this->setTemplate('setDefaultPackage');
    }
    
    public function executeUpdateDefaultPackage(sfWebRequest $request ,$isredirect=false){
        $this->checkProject($request);
        if(!$isredirect) $this->preSetDefaultPackage();  
        $this->form=new defaultPackageForm(array(),
                array('projectPackages' => $this->ei_project->getTickets(),
                      'defPack' => $this->defPack));
        $default_package=$request->getParameter($this->form->getName());
        $this->updateDefaultPackage(explode('_', $default_package['defaultPackage']),1); 
        if($this->result)
            $this->redirect('setDefaultPackage',array(
                    'action' =>'editDefaultPackage',
                    'project_id' => $this->ei_project->getProjectId(),
                    'project_ref' => $this->ei_project->getRefId())) ;
        
        $this->setTemplate('setDefaultPackage');
    }

    public function executeNew(sfWebRequest $request)
    {
        $this->checkProject($request);
        $userSettings = new EiUserSettings();
        $userSettings->setUserId($this->ei_user->getUserId());
        $userSettings->setUserRef($this->ei_user->getRefId());
        
        $this->form = new EiUserSettingsForm($userSettings);
    }

    public function executeCreate(sfWebRequest $request)
    {
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $this->checkProject($request);
        $userSettings = new EiUserSettings();
        $userSettings->setUserId($this->ei_user->getUserId());
        $userSettings->setUserRef($this->ei_user->getRefId());

        $this->form = new EiUserSettingsForm($userSettings);

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    /* Listing des profils d'un projet*/
    
    public function executeProjectProfiles(sfWebRequest $request){
        $this->checkProject($request);
        $this->ei_profiles=$this->ei_project->getProfils(); 
        //Récupération d'un eventuel profil par défaut définit pour l'utilisateur
        $this->ei_user_default_profile=Doctrine_Core::getTable("EiUserDefaultProfile")->findOneByUserIdAndUserRefAndProjectIdAndProjectRef(
                $this->ei_user->getUserId(),$this->ei_user->getRefId(),$this->ei_project->getProjectId(),$this->ei_project->getRefId());
    }
    /* Définition du profil par défaut d'un utilisateur */
    public function executeSetDefaultProfile(sfWebRequest $request){
        $this->checkProject($request);
        $this->forward404Unless($request->isXmlHttpRequest()); //On n'accepte que des requêtes ajax
        $jsonError1=json_encode(array(
                        'html' => "Error ! Environment doesn't exist ... " ,
                        'alertClass' => "alert alert-danger",
                        'success' => false));
        /*recherche du profil à définir comme profil par défaut */
        $this->profile_id=$request->getParameter("profile_id");
        $this->profile_ref=$request->getParameter("profile_ref");
        if($this->profile_id==null || $this->profile_ref==null):
            return $this->renderText($jsonError1);
        else:
            $profile=Doctrine_Core::getTable("EiProfil")->findOneByProfileIdAndProfileRefAndProjectIdAndProjectRef(
                    $this->profile_id,$this->profile_ref,$this->ei_project->getProjectId(),$this->ei_project->getRefId());
            if($profile==null):
                return $this->renderText($jsonError1);
            endif;
        endif;
        /* A cette étape on a tous les éléments permettant de définir ou modifier le profil utilisateur par défaut */
        $conn = Doctrine_Manager::connection();
        $stmt = $conn->prepare("INSERT INTO ei_user_default_profile (user_id, user_ref, project_ref,project_id,profile_id,profile_ref,created_at,updated_at) "
                            ."VALUES (:user_id, :user_ref, :project_ref,:project_id,:profile_id,:profile_ref,:created_at,:updated_at) "
                            ."ON DUPLICATE KEY UPDATE  profile_id=:profile_id,profile_ref=:profile_ref,updated_at=:updated_at"); 
             
                    $stmt->bindValue("user_id", $this->ei_user->getUserId());
                    $stmt->bindValue("user_ref", $this->ei_user->getRefId());
                    $stmt->bindValue("project_id", $this->ei_project->getProjectId());
                    $stmt->bindValue("project_ref", $this->ei_project->getRefId());
                    $stmt->bindValue("profile_id", $this->profile_id);
                    $stmt->bindValue("profile_ref", $this->profile_ref);
                    $stmt->bindValue("created_at", date("Y-m-d H-i-s"));
                    $stmt->bindValue("updated_at", date("Y-m-d H-i-s"));
                    $stmt->execute(array()); 
                return $this->renderText(json_encode(array(
                        'html' => "Well done !New default environment is  : ".$profile ,
                        'alertClass' => "alert alert-success ",
                        'resultId'=> "currentDefaultProfile",
                        'resultClass'=> "btn btn-success btn-sm" ,
                        'resultText'  => "Default environment",
                        'resultTitle' => "Default environment",
                        'oldDefaultId'=> "",
                        'oldDefaultClass'=> "btn btn-sm btn-default setDefaultUserProfile",
                        'oldDefaultText'  => "Set as default",
                        'oldDefaultTitle' => "Set profil as your default",
                        'success' => true)));           
    }
    public function executeEdit(sfWebRequest $request)
    {
        $this->checkProject($request);
        $this->forward404Unless($ei_user_settings = Doctrine_Core::getTable('EiUserSettings')->find(array($this->ei_user->getRefId(),
            $this->ei_user->getUserId())), sprintf('Object ei_user_settings does not exist (%s).', $this->ei_user->getRefId(),
            $this->ei_user->getUserId()));
        
        $this->form = new EiUserSettingsForm($ei_user_settings);
    }

    public function executeUpdate(sfWebRequest $request)
    {
        $this->checkProject($request);
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($ei_user_settings = Doctrine_Core::getTable('EiUserSettings')->find(array($this->ei_user->getRefId(),
            $this->ei_user->getUserId())), sprintf('Object ei_user_settings does not exist (%s).', $this->ei_user->getRefId(),
            $this->ei_user->getUserId()));
        $this->form = new EiUserSettingsForm($ei_user_settings);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        if ($form->isValid())
        {
            $form->save();

            $this->getUser()->setFlash('msg_success', 'Success...');

            $this->redirect('eiuser/index');
        }
    }
}
