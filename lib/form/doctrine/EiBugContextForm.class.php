<?php

/**
 * EiBugContext form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiBugContextForm extends BaseEiBugContextForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at'], $this['subject_id']);  
      $this->widgetSchema['profile_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['profile_ref'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['ei_data_set_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['ei_test_set_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['ei_fonction_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['scenario_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['campaign_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['campaign_graph_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['delivery_id'] = new sfWidgetFormInputHidden(); 
      $this->widgetSchema['author_id'] = new sfWidgetFormInputHidden(); 
      
      if(!$this->getObject()->isNew()):
          
          $ei_project=$this->getOption('ei_project'); 
          $this->widgetSchema['author'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_subject_by_author',
                    'class' => 'form-control',
                    'data-provide'=> "typeahead" ,
                    'data-items' =>'4', 
                    ));
          $this->validatorSchema['author']=new sfValidatorString(array('required' => true));
          //Choix du profil dans le contexte d'un intervention
          $projectProfiles=Doctrine_Core::getTable('EiProfil')->getProjectProfilesAsArray($ei_project);
          $this->widgetSchema['profile'] = new sfWidgetFormChoice(array('choices' => $projectProfiles),
             array('class' => 'form-control'));
          $this->validatorSchema['profile'] = new sfValidatorChoice(array('choices' => array_keys($projectProfiles), 'required' => true));
          //Récupération des livraisons d'un projet 
        $this->widgetSchema['delivery_id'] = new sfWidgetFormDoctrineChoice(
                array('model' => 'EiDelivery', 'multiple' => false,
                    'query' => Doctrine_Core::getTable('EiDelivery')
                    ->getOpenDeliveriesQuery($ei_project),
                'add_empty' => true, 'order_by' => array('name', 'asc')),
             array('class' => 'form-control'));
        
        $this->widgetSchema['campaign_id'] = new sfWidgetFormDoctrineChoice(
                array('model' => 'EiCampaign', 'multiple' => false,
                    'query' => Doctrine_Core::getTable('EiCampaign')
                    ->getProjectCampaignsQuery($ei_project->getProjectId(), $ei_project->getRefId()),
                'add_empty' => true, 'order_by' => array('name', 'asc')),
             array('class' => 'form-control'));
        
        $this->widgetSchema['campaign_graph_id'] = new sfWidgetFormDoctrineChoice(
                array('model' => 'EiCampaignGraph', 'multiple' => false,
                    'query' => Doctrine_Core::getTable('EiCampaignGraph')
                        ->getProjectCampaignsGraphQuery($ei_project,$this->getObject()->getBugContextCampaign()),
                'add_empty' => true, 'order_by' => array('id', 'asc')),
             array('class' => 'form-control'));
             
        //Post validation pour s'assurer de la cohérence de certaines données saisies
            $this->validatorSchema->setPostValidator(
                    new sfValidatorCallback(array('callback' => array($this, 'checkAuthorAndProfile'),
                        'arguments' => array('ei_project' => $ei_project)
                        )));
        
      endif;
  }
  //Création d'un widget de step de campagne pour les mises à jour de liste déroulante de steps
  public function createWidgetForCampaignStep(EiProjet $ei_project , EiCampaign $ei_campaign =null){
     return  $this->widgetSchema['campaign_graph_id'] = new sfWidgetFormDoctrineChoice(
                array('model' => 'EiCampaignGraph', 'multiple' => false,
                    'query' => Doctrine_Core::getTable('EiCampaignGraph')
                        ->getProjectCampaignsGraphQuery($ei_project, $ei_campaign),
                'add_empty' => true, 'order_by' => array('id', 'asc')),
             array('class' => 'form-control'));
  } 
  //Surcharge du bind en cas de mise à jour 
  public function bind(array $taintedValues = null, array $taintedFiles = null) {
      if (!$this->getObject()->isNew()){
          $guard=Doctrine_Core::getTable('sfGuardUser')->findOneByEmailAddress($taintedValues['author']);
          if($guard!=null)//On met à jour l'url saisie par l'utilisateur
          $taintedValues['author_id']=$guard->getId(); 
          //Mise à jour du nouveau profil avant le bidding
          $profileVal= explode('_', $taintedValues['profile']);
                if(isset($profileVal[0]) && isset($profileVal[1])):
                    $taintedValues['profile_id']=$profileVal[0];
                    $taintedValues['profile_ref']=$profileVal[1];
                endif;
      } 
      parent::bind($taintedValues, $taintedFiles);
  }
  function checkAuthorAndProfile($validator, $values, $arguments) {
      //On veut savoir si l'email saisie est une email d'utilisateur ayant accès au projet
        $ei_project=$arguments['ei_project'];
        if (!$ei_project ) throw new sfValidatorError($validator, 'invalid');
        
        if (!$values['author'])
            throw new sfValidatorError($validator, 'invalid');
        $projectUser=Doctrine_Core::getTable('sfGuardUser')
                ->createQuery('u')
                ->where('EiUser.guard_id=u.id')
                ->AndWhere('EiProjectUser.user_id=EiUser.user_id And EiProjectUser.user_ref=EiUser.ref_id ')
                ->AndWhere('EiProjectUser.project_id=? And EiProjectUser.project_ref =? And u.email_address= ?',
                        array($ei_project->getProjectId(),$ei_project->getRefId(),$values['author']))
                ->execute(); 
        
        //Si l'utilisateur n'est pas retrouvé , alors soit il n'existe pas , soit il n'a pas l'accès au projet
        if(!count($projectUser) >0  )
            throw new sfValidatorError($validator, 'May be User doesn\'t exist or doesn\'t have access to project');
       
        //On vérifie ensuite si le profil sélectionné est un profil du projet
        $profileVal= explode('_', $values['profile']);
        if(!isset($profileVal[0]) || !isset($profileVal[1]))
            throw new sfValidatorError($validator, 'This profile doesn\'t exist...');
        //throw new Exception($profileVal[0].'/'.$profileVal[1]);
        //Le profile_id se trouve dans $profileVal[0] et le profile_ref dans $profileVal[1]
         $profileProject=Doctrine_Core::getTable('EiProfil')->findOneByProjectIdAndProjectRefAndProfileIdAndProfileRef(
                 $ei_project->getProjectId(),$ei_project->getRefId(),$profileVal[0] ,$profileVal[1]);
         if($profileProject==null)
             throw new sfValidatorError($validator, 'This profile doesn\'t belong to project...');
        return $values;
    }
  
}
