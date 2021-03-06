<?php

/**
 * EiUserProfileParam form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiUserProfileParamForm extends BaseEiUserProfileParamForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);
      $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['user_ref'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['profile_param_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['value']->setAttribute('class', ' form-control');
  }
}
