<?php
require_once(sfConfig::get('sf_plugins_dir').'/sfDoctrineGuardPlugin/modules/sfGuardRegister/lib/BasesfGuardRegisterActions.class.php');

/**
 * eiregister actions.
 *
 * @package    eisecure
 * @subpackage eiregister
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiregisterActions extends  BasesfGuardRegisterActions
{
    public function executeInscription(sfWebRequest $request)
  {
        $this->form = new sfGuardRegisterForm();
  }
}
