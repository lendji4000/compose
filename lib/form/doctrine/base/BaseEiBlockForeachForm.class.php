<?php

/**
 * EiBlockForeach form base class.
 *
 * @method EiBlockForeach getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiBlockForeachForm extends EiVersionStructureForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('ei_block_foreach[%s]');
  }

  public function getModelName()
  {
    return 'EiBlockForeach';
  }

}
