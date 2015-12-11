<?php

/**
 * EiParam form.
 *
 * @package    kalifast
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiParamForm extends BaseEiParamForm {

    public function configure() {
        $this->useFields(array('observation', 'valeur'));
//     $this->widgetSchema['valeur']->setAttribute("class", "input-xlarge");

//        $this->widgetSchema['valeur'] = new sfWidgetFormInputText();
        $this->widgetSchema['valeur']->setLabel($this->getOption('nomParam'));
        $this->widgetSchema['valeur']->setAttributes(Array(
            'placeholder' => 'Value for parameter ... ',
            'class' => 'field form-control',
            'rows' => 2));
    }

}
