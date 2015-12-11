<?php

/**
 * EiLeafDataSet form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiLeafDataSetForm extends BaseEiLeafDataSetForm
{
  /**
   * @see EiDataSetStructureForm
   */
  public function configure()
  {
      unset($this['project_id'],
      $this['project_ref'],
      $this['created_at'],
      $this['updated_at'],
      $this['root_id'],
      $this['ei_scenario_id'],
      $this['ei_dataset_structure_parent_id'],
      $this['type'],
      $this['lft'],
      $this['rgt'],
      $this['level']);

      $this->mergePostValidator(new XMLTagAltValidatorSchema());
      $this->mergePostValidator(new EiDataSetStructureValidatorSchema(null, array('ei_dataset_structure' => $this->getObject(), 'is_new' => $this->getObject()->isNew())));
      $this->widgetSchema['description']->setAttribute("rows", "1");

      parent::configure();
  }
}
