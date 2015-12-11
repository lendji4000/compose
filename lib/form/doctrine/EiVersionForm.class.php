<?php

/**
 * EiVersion form.
 *
 * @package    kalifast
 * @subpackage form
 * @author     Grégory Elhaimer
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiVersionForm extends BaseEiVersionForm {
    //variable qui sera initialisée à l'appelle de la méthode
    //récursiveSetValue() elle même appelée par EiScenarioForm::saveEmbeddedForms.
    //elle est indispensable pour la post validation.
    protected $value = null;

    public function configure() {
        //la méthode useFields doit être privilégiée à unset.
        //unset détruit complètement le champs
        //tandis que usefields stipule les champs que la vue pourra exploiter.
        $this->useFields(array('libelle', 'description'));
        unset($this['id']);

        $this->widgetSchema['libelle']->setAttribute('placeholder', 'Enter name for test suite version');
        $this->widgetSchema['libelle']->setLabel('Name');
        $this->widgetSchema['description']->setAttribute('placeholder', 'Description for test suite version ... '); 
        $this->widgetSchema['description']->setAttribute("class", "description_version form-control"); 
        $this->widgetSchema['libelle']->setAttribute("class", "form-control");
         
        if ($this->getObject()->isNew()):
        //Imbrication du formulaire de liaison entre le package et le scenario
        $ei_scenario_package=$this->getOption('ei_scenario_package');   
            //on imbrique le sous formulaire au formulaire de creation d'une version
            $this->embedForm('ei_scenario_package', new EiScenarioPackageForm($ei_scenario_package)); 
        endif;
        parent::configure();
    }
    public function saveEmbeddedForms($con = null, $forms = null){

      if (null === $forms)
        {
          $forms = $this->embeddedForms;
        }
        //Traitement du noeud parent  
        foreach ($forms as $form)
        {
            //On complète les champs manquants au noeud de l'arbre associé à l'objet
            if($form instanceof EiScenarioPackageForm ){ 
                $form->getObject()->setEiVersionId($this->getObject()->getId()); 
            }

            $form->getObject()->setCreatedAt($this->getObject()->getCreatedAt());
            $form->getObject()->setUpdatedAt($this->getObject()->getUpdatedAt());
        }
      
      
      parent::saveEmbeddedForms($con, $forms);
  }
}
