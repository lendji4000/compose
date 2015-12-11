<?php

/**
 * EiDeviceUser form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiDeviceUserForm extends BaseEiDeviceUserForm
{
    public function configure()
    {
        unset($this['created_at'], $this['updated_at']);

        $this->widgetSchema['name'] = new sfWidgetFormInput();
        
        $this->widgetSchema['owner'] = new sfWidgetFormInputHidden();
        
        $this->widgetSchema['device_user_visibility_id'] = new sfWidgetFormDoctrineChoice(array(
            'model' => $this->getRelatedModelName('EiDeviceUserVisibility'),
            'multiple' => false,
            'add_empty' => false
        ));
        $this->widgetSchema['device_id'] = new sfWidgetFormDoctrineChoice(
                  array('model' => 'EiDevice',
                        'multiple' => false,
                        'expanded' => true,
                        'query' => Doctrine_Core::getTable('EiDevice')->getAvailablesIdsQuery(),
                        'add_empty' => false));
        
        $this->widgetSchema['name']->setAttribute('class', ' form-control col-lg-4 col-md-4 col-sm-5' );
        $this->widgetSchema['device_user_visibility_id']->setAttribute('class', ' form-control col-lg-4 col-md-4 col-sm-5' );
    }
}
