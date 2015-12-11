<?php

/**
 * EiView form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiViewForm extends BaseEiViewForm
{
  public function configure()
  {
      unset(  $this['created_at'],$this['updated_at'],$this['is_active']);
      
      $this->widgetSchema['view_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['view_ref'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['project_ref'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['project_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['name']=new sfWidgetFormInputText();
      $this->validatorSchema['name']=new sfValidatorString(); 
      $this->setValidator("name", new sfValidatorAnd(
                array(
            new sfValidatorString(array('required' => true, 'trim' => true), array('required' => 'Empty Name'), array('min_length' => 1)),
            new sfValidatorRegex(
                    array('pattern' => '/^[\w]+[\w\s]*[\w]$/i'), array('invalid' => 'Node name mustn\'t contain special chars.')
            )
                )
        ));
  }
}
