<?php

/**
 * EiBlockForeach filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedInheritanceTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiBlockForeachFormFilter extends EiVersionStructureFormFilter
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('ei_block_foreach_filters[%s]');
  }

  public function getModelName()
  {
    return 'EiBlockForeach';
  }
}
