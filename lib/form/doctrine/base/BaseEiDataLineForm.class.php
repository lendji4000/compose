<?php

/**
 * EiDataLine form base class.
 *
 * @method EiDataLine getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiDataLineForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'ei_data_set_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => false)),
      'ei_data_line_parent_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataLineParent'), 'add_empty' => true)),
      'ei_data_set_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructure'), 'add_empty' => true)),
      'valeur'                   => new sfWidgetFormInputText(),
      'created_at'               => new sfWidgetFormDateTime(),
      'updated_at'               => new sfWidgetFormDateTime(),
      'root_id'                  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataLineRoot'), 'add_empty' => true)),
      'lft'                      => new sfWidgetFormInputText(),
      'rgt'                      => new sfWidgetFormInputText(),
      'level'                    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_data_set_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'))),
      'ei_data_line_parent_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataLineParent'), 'required' => false)),
      'ei_data_set_structure_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructure'), 'required' => false)),
      'valeur'                   => new sfValidatorPass(array('required' => false)),
      'created_at'               => new sfValidatorDateTime(),
      'updated_at'               => new sfValidatorDateTime(),
      'root_id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataLineRoot'), 'required' => false)),
      'lft'                      => new sfValidatorInteger(array('required' => false)),
      'rgt'                      => new sfValidatorInteger(array('required' => false)),
      'level'                    => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ei_data_line[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiDataLine';
  }

}
