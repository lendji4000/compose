<?php

/**
 * EiEnvironnementProjet form base class.
 *
 * @method EiEnvironnementProjet getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiEnvironnementProjetForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ref_environnement'  => new sfWidgetFormInputHidden(),
      'id_environnement'   => new sfWidgetFormInputHidden(),
      'id_projet'          => new sfWidgetFormInputText(),
      'ref_projet'         => new sfWidgetFormInputText(),
      'url_base'           => new sfWidgetFormInputText(),
      'nom_environnement'  => new sfWidgetFormInputText(),
      'desc_environnement' => new sfWidgetFormTextarea(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'ref_environnement'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ref_environnement')), 'empty_value' => $this->getObject()->get('ref_environnement'), 'required' => false)),
      'id_environnement'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_environnement')), 'empty_value' => $this->getObject()->get('id_environnement'), 'required' => false)),
      'id_projet'          => new sfValidatorInteger(),
      'ref_projet'         => new sfValidatorInteger(),
      'url_base'           => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'nom_environnement'  => new sfValidatorString(array('max_length' => 45)),
      'desc_environnement' => new sfValidatorString(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_environnement_projet[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiEnvironnementProjet';
  }

}
