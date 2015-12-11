<?php

/**
 * EiSubjectDetails form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiSubjectDetailsForm extends BaseEiSubjectDetailsForm
{
  public function configure()
  {
      unset($this['created_at'],$this['updated_at'],  $this["subject_id"]); 
      $this->widgetSchema['details'] = new sfWidgetFormTextarea(array(),
              array('placeholder' => 'Enter details for intervention', 
                      'class' => 'span12 tinyMceSubject',
                  ));
  }
}
