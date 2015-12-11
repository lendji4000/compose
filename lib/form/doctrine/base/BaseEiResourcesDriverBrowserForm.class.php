<?php

/**
 * EiResourcesDriverBrowser form base class.
 *
 * @method EiResourcesDriverBrowser getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiResourcesDriverBrowserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'device_params_driver_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDeviceParamsDriver'), 'add_empty' => false)),
      'browser_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesBrowser'), 'add_empty' => false)),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'device_params_driver_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDeviceParamsDriver'))),
      'browser_id'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesBrowser'))),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_resources_driver_browser[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiResourcesDriverBrowser';
  }

}
