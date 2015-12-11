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
class choiceVersionForm extends sfForm{

    public function __clone() {
        parent::__clone();
    }
    public function  __construct($params) {
        parent::__construct(null,$params); //passage de l'id_ref au formulaire pour reccuperer la liste des groupe du referentiel en question
    }
    public function setUp()
    {
//        $this->getEmbeddedForm()->
        //Récupération des paramètres passés au formulaire
        $params=$this->options;
         $versions['0']=null;
         foreach ($params['versions'] as $i => $q1) {
            $versions[$q1->id] = $q1->libelle .'    '.$q1->getEiScenario()->nom_scenario;
        }

                $this->widgetSchema['version'] = new sfWidgetFormChoice(array(
                            'choices' => $versions,
                        ));

                $this->validatorSchema['version'] = new sfValidatorChoice(array(
                            'required' => true,
                            'choices' => array_keys($versions)
                        ));


              $this->widgetSchema->setNameFormat('choiceVersion[%s]');

              parent::setup();
        }

}
?>