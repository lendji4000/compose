<?php

/**
 * EiFunctionNotice form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiFunctionNoticeForm extends BaseEiFunctionNoticeForm {

    public function configure() {
        unset($this['created_at'], $this['updated_at']);
        $this->widgetSchema["ei_version_id"] = new sfWidgetFormInputHidden();
        $this->widgetSchema["ei_fonction_id"] = new sfWidgetFormInputHidden();
        $this->widgetSchema["lang"] = new sfWidgetFormInputHidden();

        //$this->widgetSchema['description'] = new sfWidgetFormTextareaTinyMCECustom(array());
        $this->widgetSchema['description']->setAttribute('class', 'tinyMceContent');
        $this->widgetSchema['expected']->setAttribute('class', 'tinyMceNoticeExpect');
        $this->widgetSchema['result']->setAttribute('class', 'tinyMceNoticeResult');
    }

}
