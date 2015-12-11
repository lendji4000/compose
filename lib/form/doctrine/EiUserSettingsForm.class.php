<?php

/**
 * EiUserSettings form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiUserSettingsForm extends BaseEiUserSettingsForm
{
  public function configure()
  {
      // On retire de l'affichage les éléments updated_at & created_at.
      unset($this['created_at'], $this['updated_at']);

      // On transforme les éléments user_ref & user_id en
      $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['user_ref'] = new sfWidgetFormInputHidden();

      // Adaptation du champ "FirefoxPath".
      $this->widgetSchema['firefox_path']->setAttribute('class', 'col-lg-11 col-md-8 col-sm-5 form-control ' );
      $this->widgetSchema['firefox_path']->setAttribute('placeholder', 'Firefox Path ...');

      // Adaptation du champ "FirefoxPath".
      $this->widgetSchema['excel_mode']->setAttribute('class', 'col-lg-11 col-md-8 col-sm-5 form-control ' );
//      $this->widgetSchema['firefox_path']->setAttribute('placeholder', 'Firefox Path ...');
  }
}
