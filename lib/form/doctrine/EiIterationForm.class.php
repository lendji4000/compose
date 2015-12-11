<?php

/**
 * EiIteration form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiIterationForm extends BaseEiIterationForm
{
  public function configure()
  {
      unset( $this['delivery_id'], $this['profile_id'], $this['profile_ref'],$this['project_id'],$this['project_ref'],$this['author_id'],
           $this['created_at'],$this['updated_at']);
      $this->widgetSchema['description']->setAttribute('class', ' form-control col-lg-12 col-md-12 col-sm-12 col-xs-12' );
  }
}
