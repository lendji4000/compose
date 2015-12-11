<?php

/**
 * EiBlock form base class.
 *
 * @method EiBlock getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiBlockForm extends EiVersionStructureForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('ei_block[%s]');
  }

  public function getModelName()
  {
    return 'EiBlock';
  }

}
