<?php

/**
 * EiLeafDataSet filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedInheritanceTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiLeafDataSetFormFilter extends EiDataSetStructureFormFilter
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('ei_leaf_data_set_filters[%s]');
  }

  public function getModelName()
  {
    return 'EiLeafDataSet';
  }
}
