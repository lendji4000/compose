<?php

/**
 * EiMappingStructureSyncOut form base class.
 *
 * @method EiMappingStructureSyncOut getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiMappingStructureSyncOutForm extends EiBlockDataSetMappingForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('ei_mapping_structure_sync_out[%s]');
  }

  public function getModelName()
  {
    return 'EiMappingStructureSyncOut';
  }

}
