<?php

/**
 * EiLeafDataSet form base class.
 *
 * @method EiLeafDataSet getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiLeafDataSetForm extends EiDataSetStructureForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('ei_leaf_data_set[%s]');
  }

  public function getModelName()
  {
    return 'EiLeafDataSet';
  }

}
