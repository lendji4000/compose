<?php

/**
 * sfGuardRegisterForm for registering new users
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: BasesfGuardChangeUserPasswordForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardRegisterForm extends BasesfGuardRegisterForm
{
  /**
   * @see sfForm
   */
  public function configure()
  {
      $this->widgetSchema['first_name']->setLabel('Prenom');
      $this->widgetSchema['last_name']->setLabel('Nom');
      $this->widgetSchema['email_address']->setLabel('Email');
      $this->widgetSchema['username']->setLabel('Login');
      $this->widgetSchema['password']->setLabel('Mot de passe');
      $this->widgetSchema['password_again']->setLabel('Mot de passe à nouveau');
  }
}