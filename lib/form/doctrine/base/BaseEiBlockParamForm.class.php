<?php

/**
 * EiBlockParam form base class.
 *
 * @method EiBlockParam getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiBlockParamForm extends EiVersionStructureForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('ei_block_param[%s]');
  }

  public function getModelName()
  {
    return 'EiBlockParam';
  }

}
