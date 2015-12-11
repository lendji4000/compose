<?php

/**
 * EiMappingStructureSyncIn form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiMappingStructureSyncInForm extends BaseEiMappingStructureSyncInForm
{
  /**
   * @see EiBlockDataSetMappingForm
   */
  public function configure()
  {
      unset($this['created_at'], $this['updated_at'], $this['type'], $this["ei_version_structure_id"]);

      $this->widgetSchema['ei_dataset_structure_id'] = new sfWidgetFormInputHidden();

      parent::configure();
  }
}
