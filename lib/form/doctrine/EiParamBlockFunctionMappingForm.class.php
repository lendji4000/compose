<?php

/**
 * EiParamBlockFunctionMapping form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiParamBlockFunctionMappingForm extends BaseEiParamBlockFunctionMappingForm
{
  public function configure()
  {
      $this->useFields(array("ei_param_block_id"));

      /** @var EiVersionStructure $structureParent */
      $structureParent = $this->getObject()->getEiFunction()->getEiVersionStructure()->getEiVersionStructureParent();

      $this->widgetSchema["ei_param_block_id"] = new sfWidgetFormDoctrineChoice(array(
          'model' => $this->getRelatedModelName('EiBlockParamMapping'),
          'add_empty' => true,
          'method' => 'getName',//getPathToString
          'query' => Doctrine_Core::getTable("EiBlockParam")->getQueryToFindBlockParams($structureParent->getId(), $structureParent->getLevel())
      ));

      $this->widgetSchema['ei_param_block_id']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
  }
}
