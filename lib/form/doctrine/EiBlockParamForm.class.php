<?php

/**
 * EiBlockParam form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiBlockParamForm extends BaseEiBlockParamForm
{
  /**
   * @see EiScenarioStructureForm
   */
  public function configure()
  {
    unset(
        $this['ei_fonction_id'],
        $this['ei_version_id'],
        $this['ei_scenario_executable_id'],
        $this['ei_version_structure_parent_id'],
        $this['type'],
        $this['slug'],
        $this['created_at'],
        $this['updated_at'],
        $this['root_id'],
        $this['lft'],
        $this['rgt'],
        $this['level']
    );
    
    $this->mergePostValidator(new XMLTagValidatorSchema());
    $this->mergePostValidator(new EiVersionStructureValidatorSchema(null, array('ei_version_structure' => $this->getObject(),'is_new' => $this->getObject()->isNew())));
    $this->widgetSchema['description']->setAttribute("class", "form-control");
    $this->widgetSchema['description']->setAttribute("rows", 1);
    $this->widgetSchema['name']->setAttribute("class", "form-control");
    parent::configure();
  }
}

?>