<?php

/**
 * EiVersionNotice form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiVersionNoticeForm extends BaseEiVersionNoticeForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at'], $this['is_active'],$this['name']);
        $this->widgetSchema["version_notice_id"] = new sfWidgetFormInputHidden();
        $this->widgetSchema["notice_id"] = new sfWidgetFormInputHidden();
        $this->widgetSchema["notice_ref"] = new sfWidgetFormInputHidden();
        $this->widgetSchema["lang"] = new sfWidgetFormInputHidden();

        //$this->widgetSchema['description'] = new sfWidgetFormTextareaTinyMCECustom(array());
        $this->widgetSchema['description']->setAttribute('class', 'tinyMceContent');
        $this->widgetSchema['expected']->setAttribute('class', 'tinyMceNoticeExpect');
        $this->widgetSchema['result']->setAttribute('class', 'tinyMceNoticeResult');
  }
}
