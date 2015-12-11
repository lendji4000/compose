<?php

/**
 * EiDataSet form base class.
 *
 * @method EiDataSet getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiDataSetForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'ei_node_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiNode'), 'add_empty' => false)),
      'name'                    => new sfWidgetFormInputText(),
      'description'             => new sfWidgetFormTextarea(),
      'user_id'                 => new sfWidgetFormInputText(),
      'user_ref'                => new sfWidgetFormInputText(),
      'ei_data_set_template_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetTemplate'), 'add_empty' => true)),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_node_id'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiNode'))),
      'name'                    => new sfValidatorString(array('max_length' => 255)),
      'description'             => new sfValidatorString(array('required' => false)),
      'user_id'                 => new sfValidatorInteger(array('required' => false)),
      'user_ref'                => new sfValidatorInteger(array('required' => false)),
      'ei_data_set_template_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetTemplate'), 'required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_data_set[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiDataSet';
  }

}
