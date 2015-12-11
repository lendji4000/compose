<?php

/**
 * EiMappingStructureSyncOut filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedInheritanceTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiMappingStructureSyncOutFormFilter extends EiBlockDataSetMappingFormFilter
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('ei_mapping_structure_sync_out_filters[%s]');
  }

  public function getModelName()
  {
    return 'EiMappingStructureSyncOut';
  }
}
