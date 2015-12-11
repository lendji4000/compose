<?php

/**
 * Chemin filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCheminFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_obj'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ref_obj'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type_objet' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'root_id'    => new sfWidgetFormFilterInput(),
      'lft'        => new sfWidgetFormFilterInput(),
      'rgt'        => new sfWidgetFormFilterInput(),
      'level'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'id_obj'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ref_obj'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type_objet' => new sfValidatorPass(array('required' => false)),
      'root_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('chemin_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Chemin';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'id_obj'     => 'Number',
      'ref_obj'    => 'Number',
      'type_objet' => 'Text',
      'root_id'    => 'Number',
      'lft'        => 'Number',
      'rgt'        => 'Number',
      'level'      => 'Number',
    );
  }
}
