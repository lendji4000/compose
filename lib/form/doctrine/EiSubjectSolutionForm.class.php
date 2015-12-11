<?php

/**
 * EiSubjectSolution form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiSubjectSolutionForm extends BaseEiSubjectSolutionForm
{
  public function configure()
  {
      unset($this['created_at'],$this['updated_at'],  $this["subject_id"]); 
      $this->widgetSchema['solution'] = new sfWidgetFormTextarea(array(),
              array('placeholder' => 'Enter solution for intervention', 
                      'class' => 'span12 tinyMceSubject',
                  ));
  }
}
