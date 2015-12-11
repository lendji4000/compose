<?php

/**
 * EiCampaignGraph form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiCampaignGraphForm extends BaseEiCampaignGraphForm
{
  public function configure()
  {
      unset($this['campaign_id'],$this['mime_type'],$this['created_at'],$this['updated_at']); 
      $ei_project=$this->getOption('ei_project');
      $this->widgetSchema['scenario_id']=new sfWidgetFormInputHidden();
      $this->widgetSchema['data_set_id']=new sfWidgetFormInputHidden();
      $this->widgetSchema['description']->setAttribute('class', 'campaignGraphDescription span12' );
      $this->widgetSchema['description']->setAttribute('row', 10);
      $this->widgetSchema['description']->setAttribute('placeholder', 'Description ...');
      //Widget du fichier attaché   
      $this->widgetSchema['tmpPath'] = new sfWidgetFormInputHidden(array(
          'label' => 'file'),
              array('id' => 'filePath' ));
      $this->widgetSchema['filename']=new sfWidgetFormInputHidden(array(),array('id' => 'fileName'));
      $this->validatorSchema['tmpPath'] = new sfValidatorString(array('required' => false));
      $this->widgetSchema['state']->setAttribute('class', 'span12' );
        //Récupération des types de step d'une campagne ("Test Suite , "Manual action" )  
        $this->widgetSchema['step_type_id'] = new sfWidgetFormDoctrineChoice(
                array('model' => 'EiCampaignGraphType', 'multiple' => false,
                    'query' => Doctrine_Core::getTable('EiCampaignGraphType')
                    ->getProjectCampaignGraphType($ei_project->getProjectId(), $ei_project->getRefId()),
                'add_empty' => false, 'order_by' => array('name', 'asc')));
      
  } 
  
  public function bind(array $taintedValues = null, array $taintedFiles = null) {
      $taintedValues['path']='/campaignGraphAttachments/'.time().session_id().$taintedValues['filename'];
      parent::bind($taintedValues, $taintedFiles);
  }
}
