<?php

/**
 * EiDeliveryHasCampaign form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiDeliveryHasCampaignForm extends BaseEiDeliveryHasCampaignForm
{
  public function configure()
  {
      unset($this['created_at'],$this['updated_at']);
  }
}
