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
class playOnRobotForm extends sfForm{
    
    protected static $navigateurs = array('*firefox', '*chrome', '*iexplorer');
    protected static $versions = array('n/a', 'n/a', 'n/a');
    protected static $robot = array('10.1.10.145', '10.1.10.146', '10.1.10.147');
    protected static $vitesse_jeu = array('5', '10', '20');
    
    public function __clone() {
        parent::__clone();
    }
    public function  __construct($params) {
        parent::__construct(null,$params); //passage de l'id_ref au formulaire pour reccuperer la liste des groupe du referentiel en question
    }
    public function setUp()
    {

                

                $this->setWidgets(array(
                  'url_depart'    => new sfWidgetFormInput(),
                  'navigateur'   => new sfWidgetFormSelect(array('choices' => self::$navigateurs)),
                  'version' => new sfWidgetFormSelect(array('choices' => self::$versions)),
                  'robot' => new sfWidgetFormSelect(array('choices' => self::$robot)),
                  'vitesse_jeu' => new sfWidgetFormSelect(array('choices' => self::$vitesse_jeu)),
                  'environnement' => new sfWidgetFormInput(),
                )); 

                $this->setValidators(array(
                  'url_depart'    => new sfValidatorUrl(),
                  'navigateur'   => new sfValidatorChoice(array('choices' => array_keys(self::$navigateurs))),
                  'version' => new sfValidatorChoice(array('choices' => array_keys(self::$versions))),
                  'robot' => new sfValidatorChoice(array('choices' => array_keys(self::$robot))),
                  'vitess_jeu' => new sfValidatorString(array('min_length' => 4), array('required' => 'Le champ message est obligatoire.')),
                ));

              $this->widgetSchema->setNameFormat('playOnRobot[%s]');

              parent::setup();
        }

}
?>