<?php

/**
 * EiScenarioPackage form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiScenarioPackageForm extends BaseEiScenarioPackageForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at'],$this['ei_version_id']);
        $this->widgetSchema['ei_scenario_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['package_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['package_ref'] = new sfWidgetFormInputHidden();
        
        $this->validatorSchema['ei_scenario_id'] = new sfValidatorInteger();
        $this->validatorSchema['package_id'] = new sfValidatorInteger();
        $this->validatorSchema['package_ref'] = new sfValidatorInteger();
        //$this->widgetSchema['ei_version_id'] = new sfWidgetFormInputHidden();
  }
}
