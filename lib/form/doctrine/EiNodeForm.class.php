<?php

/**
 * EiNode form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiNodeForm extends BaseEiNodeForm
{
  public function configure()
  {
      unset( $this['lft'], $this['rgt'], $this['level'],
           $this['created_at'],$this['updated_at'],$this['is_shortcut']);
   
   $this->widgetSchema["project_ref"] = new sfWidgetFormInputHidden();
   $this->widgetSchema["project_id"] = new sfWidgetFormInputHidden(); 
   $this->widgetSchema["obj_id"] = new sfWidgetFormInputHidden();
   $this->widgetSchema["root_id"] = new sfWidgetFormInputHidden();
   $this->widgetSchema["position"] = new sfWidgetFormInputHidden();
   $this->widgetSchema["is_root"] = new sfWidgetFormInputHidden();
   $this->widgetSchema["type"] = new sfWidgetFormInputHidden();
   
   
   $this->validatorSchema['type']=  new sfValidatorString(array('max_length' => 45,'required' => false));
   $this->validatorSchema['obj_id'] = new sfValidatorInteger(array('required' => false)); 
   $this->validatorSchema['name'] = new sfValidatorString(array('required' => false)); 
   
   //Si la vue racine  alors on rend ineditable le nom
                if (!$this->isNew && $this->getObject()->root_id == null)
                    $this->widgetSchema['name']->setAttribute('readonly', 'readonly');
                
    $this->widgetSchema['parent_id'] = new sfWidgetFormDoctrineChoice(array(
      'model' => 'EiNode',
      'add_empty' => 'Object is at root level',
      'order_by' => array('root_id, lft',''),
      'method' => 'getIndentedName'
      ));
    $this->validatorSchema['parent_id'] = new sfValidatorDoctrineChoice(array(
      'required' => false,
      'model' => 'EiNode'
      ));
    
    if($this->getObject()->getRootId()!=null) {
        $this->setDefault('parent_id', $this->object->getRootId());
        $this->widgetSchema['parent_id']->setAttribute('class', 'node_parent' );
         $this->widgetSchema['parent_id']->setAttribute('disabled', 'disabled');
        }
    else $this->setDefault('parent_id', $this->object->getParentId());
    
    $this->widgetSchema->setLabel('parent_id', 'Node Parent');
     
  }
   
}
