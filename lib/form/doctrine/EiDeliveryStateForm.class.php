<?php

/**
 * EiDeliveryState form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiDeliveryStateForm extends BaseEiDeliveryStateForm
{
  public function configure()
  {
      unset($this['created_at'],$this['updated_at']);
      $this->widgetSchema['project_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['project_ref'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['id']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
  }
}
