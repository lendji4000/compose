<?php

/**
 * EiNodeDataSet form base class.
 *
 * @method EiNodeDataSet getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiNodeDataSetForm extends EiDataSetStructureForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('ei_node_data_set[%s]');
  }

  public function getModelName()
  {
    return 'EiNodeDataSet';
  }

}
