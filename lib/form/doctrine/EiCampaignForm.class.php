<?php

/**
 * EiCampaign form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiCampaignForm extends BaseEiCampaignForm
{
  public function configure()
  {
      unset($this['created_at'],$this['updated_at'],$this['author_id']);
      $this->widgetSchema['project_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['project_ref'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['description']->setAttribute('class', 'campaignDescription form-control' );
      $this->widgetSchema['description']->setAttribute('row', 10);
      $this->widgetSchema['description']->setAttribute('placeholder', 'Description ...'); 
      $this->widgetSchema['coverage']->setAttribute('id', 'ei_campaign_coverage');
      $this->widgetSchema['coverage']->setAttribute('onkeyup','progress("ei_campaign_coverage","ei_campaign_coverage_indicator")' );
      $this->widgetSchema['coverage']->setAttribute('class','form-control' ); 
        $this->widgetSchema['name']->setAttribute("class", "form-control");  
      $this->widgetSchema['on_error']=new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiBlockType'), 'add_empty' => false));
      $this->validatorSchema['on_error']=new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiBlockType'), 'required' => true));
      $this->widgetSchema['on_error']->setAttribute("class", "form-control"); 
  }
}
