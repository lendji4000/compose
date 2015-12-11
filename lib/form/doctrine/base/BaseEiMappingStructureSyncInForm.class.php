<?php

/**
 * EiMappingStructureSyncIn form base class.
 *
 * @method EiMappingStructureSyncIn getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiMappingStructureSyncInForm extends EiBlockDataSetMappingForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('ei_mapping_structure_sync_in[%s]');
  }

  public function getModelName()
  {
    return 'EiMappingStructureSyncIn';
  }

}
