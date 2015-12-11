 
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of finalForm
 *
 * @author lenine
 */
class defaultPackageForm extends sfForm {

    public function configure() {
        unset($this['created_at'], $this['updated_at']);
        $projectPackages = $this->getOption('projectPackages');
        $defPack = $this->getOption('defPack');
        $packageTab = array();
        if (count($projectPackages) > 0):
            foreach ($projectPackages as $package):
                $packageTab[$package->getTicketId() . '_' . $package->getTicketRef()] = $package->getName();
            endforeach;
        endif;
        $this->setWidgets(array(
            'defaultPackage' => new sfWidgetFormSelect(array('choices' => $packageTab))
        ));

        $this->setValidators(array(
            'defaultPackage' => new sfValidatorChoice(array('choices' => array_keys($packageTab))),
        ));
        if ($defPack != null):
            $this->getWidget('defaultPackage')->setDefault(array(
                'choices' => $defPack->getTicketId() . '_' . $defPack->getTicketRef()));
        endif;
        $this->widgetSchema['defaultPackage']->setAttribute('class', ' form-control col-lg-10 col-md-8 col-sm-5');
        $this->widgetSchema->setNameFormat('defaultPackage[%s]');

        parent::setup();
    }

}

?>