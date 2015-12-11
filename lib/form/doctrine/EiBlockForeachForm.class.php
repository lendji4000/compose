<?php

/**
 * EiBlockForeach form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiBlockForeachForm extends BaseEiBlockForeachForm
{
    /**
     * @see EiScenarioStructureForm
     */
    public function configure()
    {
        unset(
            $this['ei_fonction_id'],
            $this['ei_version_id'],
            $this['ei_scenario_executable_id'],
            $this['ei_version_structure_parent_id'],
            $this['type'],
            $this['slug'],
            $this['created_at'],
            $this['updated_at'],
            $this['root_id'],
            $this['lft'],
            $this['rgt'],
            $this['level']
        );

        if( $this->isNew() ){
            $mapping = new EiMappingStructureSyncIn();
        }
        elseif( $this->getOption('mapping')!==null ){
            $mapping = $this->getOption("mapping");
        }
        else{
            $mapping = null;
        }

        $this->embedForm("Iterator", new EiMappingStructureSyncInForm($mapping));

        if($this->getOption('size')!==null):
            $form = new EiBlockParamCollectionForm(null, array(
                "size" => $this->getOption("size"),
                "block" => $this->getObject(),
                "elements" => $this->getOption("elements")
            ));
            $this->embedForm('EiBlockParams', $form);

        endif;

        $this->mergePostValidator(new XMLTagValidatorSchema());
        $this->mergePostValidator(new EiVersionStructureValidatorSchema(null, array('ei_version_structure' => $this->getObject(), 'is_new' => $this->getObject()->isNew())));

        $this->widgetSchema['name']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
        $this->widgetSchema['description']->setAttribute('class', 'subjectDescription form-control col-lg-8 col-md-8 col-sm-8');
        $this->widgetSchema['description']->setAttribute("rows", 5);

        parent::configure();
    }

    public function saveEmbeddedForms($con = null, $forms = null)
    {
        if (null === $forms)
        {
            $forms = $this->embeddedForms;
        }

        if(array_key_exists("EiBlockParams", $this->embeddedForms))
        {
            $subforms = $this->embeddedForms["EiBlockParams"]->embeddedForms;

            foreach ($subforms as $subform)
            {
                if($subform instanceof EiBlockParamForm ){
                    $subform->getObject()->setRootId($this->getObject()->getRootId());
                    $subform->getObject()->setEiVersionId($this->getObject()->getEiVersionId());
                    $subform->getObject()->setEiVersionStructureParentId($this->getObject()->getId());
                }
            }
        }

        foreach ($forms as $form)
        {
            if($form instanceof EiMappingStructureSyncInForm ){
                $form->getObject()->setEiVersionStructureMapping($this->getObject());
            }
        }

        parent::saveEmbeddedForms($con, $forms);
    }

    //Surcharge de la méthode bind pour faire correspondre les données des formulaires de paramètre imbriqués
    public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        $new_occurrences = new BaseForm();

        if( isset($taintedValues["EiBlockParams"]) ){
            try{
                $formParams = $this->getEmbeddedForm("EiBlockParams");
            }
            catch( InvalidArgumentException $exc ){
                $formParams = null;
            }
        }

        if(isset($taintedValues['EiBlockParams']))
        {
            foreach($taintedValues['EiBlockParams'] as $key => $new_occurrence)
            {
                try{
                    if( isset($new_occurrence["id"]) && $new_occurrence["id"] == "" ){
                        $blockParam = new EiBlockParam();
                        $blockParam->setEiVersionStructureParent($this->getObject());
                        $blockParam->setId($new_occurrence["id"]);
                        $blockParam->setEiVersionId($this->getObject()->getEiVersionId());

                        $occurrence_form = new EiBlockParamForm($blockParam);
                        $new_occurrences->embedForm($key,$occurrence_form);
                    }
                    elseif( $formParams != null && ($subForm = $formParams->getEmbeddedForm($key)) ){

                        $occurrence_form = new EiBlockParamForm($subForm->getObject());
                        $new_occurrences->embedForm($key,$occurrence_form);
                    }
                }
                catch( InvalidArgumentException $exc ){
                    // TODO: Traiter l'exception.
                }
            }

            $this->embedForm('EiBlockParams',$new_occurrences);
        }
        else{
            $this->embedForm("EiBlockParams", new BaseForm());
            $this->getObject()->setEiVersionStructures(new Doctrine_Collection("EiVersionStructure"));
        }

        parent::bind($taintedValues, $taintedFiles);
    }
}
