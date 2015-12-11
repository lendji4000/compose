<?php

/**
 * EiSubjectMessage form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiSubjectMessageForm extends BaseEiSubjectMessageForm {

    public function configure() {
        unset($this['created_at'], $this['updated_at']);
        $this->widgetSchema['guard_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['subject_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['message_type_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['type'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['position'] = new sfWidgetFormInputHidden();

        $this->widgetSchema['parent_id'] = new sfWidgetFormInputHidden();
        $this->validatorSchema['parent_id'] = new sfValidatorDoctrineChoice(array(
          'required' => false,
          'model' => 'EiSubjectMessage'
          ));
        $this->setDefault('parent_id', $this->object->getParentId());
        $this->widgetSchema->setLabel('parent_id', 'Child of');
 
      }

    public function updateParentIdColumn($parentId) {
        $this->parentId = $parentId;
        // further action is handled in the save() method
    }

    protected function doSave($con = null) {
        parent::doSave($con);
 
    $node = $this->object->getNode();
 
    if ($this->parentId != $this->object->getParentId() || !$node->isValidNode())
    {
      if (empty($this->parentId))
      {
        //save as a root
        if ($node->isValidNode())
        {
          $node->makeRoot($this->object['id']);
          $this->object->save($con);
        }
        else
        {
          $this->object->getTable()->getTree()->createRoot($this->object); //calls $this->object->save internally
        }
      }
      else
      {
        //form validation ensures an existing ID for $this->parentId
        $parent = $this->object->getTable()->find($this->parentId);
        $method = ($node->isValidNode() ? 'move' : 'insert') . 'AsFirstChildOf';
        $node->$method($parent); //calls $this->object->save internally
      }
    } 
    }

}
