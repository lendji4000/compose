<?php

/**
 * EiScenario form.
 *
 * @package    kalifast
 * @subpackage form
 * @author     Grégory Elhaimer
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiScenarioForm extends BaseEiScenarioForm {

    public function configure() {
        unset($this['created_at'], $this['updated_at'], $this['nb_joue'], $this['ei_node_id'], $this['ei_version_structure_id']);
        $this->widgetSchema['project_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['project_ref'] = new sfWidgetFormInputHidden();
        
        //Modification des labels
        $this->widgetSchema->setLabels(array(
            'nom_scenario' => 'Libelle'
        ));
        if (!$this->isNew()) $this->widgetSchema['nom_scenario']->setAttribute('class', 'nom_scenario form-control');
        else $this->widgetSchema['nom_scenario']->setAttribute('class', 'libelle form-control');
        $this->widgetSchema['nom_scenario']->setAttribute('placeholder','Enter test suite name ...');
        $this->widgetSchema['description']->setAttribute('placeholder','Enter a description for test suite ...');
        $this->widgetSchema['description']->setAttribute('class', 'description_scenario form-control');
        
        
        //Imbrication du formulaire pour le noeud parent
        $root_id=$this->getOption('root_id');
        if (isset($root_id) && $root_id != null) {
            $ei_node = new EiNode(); 
            $ei_node->setIsRoot(false);
            $ei_node->setProjectId($this->getObject()->getProjectId());
            $ei_node->setProjectRef($this->getObject()->getProjectRef());
            $ei_node->setObjId($this->getObject()->getId()); 
            $ei_node->setPosition(Doctrine_Core::getTable('EiNode')->getLastPositionInNode(
                    $this->getObject()->getProjectId(),$this->getObject()->getProjectRef(),$root_id));
            $ei_node->setType('EiScenario');
            $ei_node->setRootId($root_id);
            $this->getObject()->setEiNode($ei_node);
            //on imbrique le sous formulaire au formulaire de creation d'un scénario
            $this->embedForm('ei_node', new EiNodeForm($ei_node)); 
        }
        /* On vérifie à la validation que l'utilisateur possède un package par défaut */
        
        $this->validatorSchema->setPostValidator(
                    new sfValidatorCallback(array('callback' => array($this, 'checkIfDefaultPackageExist')))
            );
    }

    //On vérifie à la validation que l'utilisateur possède un package par défaut
  function checkIfDefaultPackageExist($validator, $values, $arguments) { 
      if(!$this->getObject()->isNew()) return $values; // C'est n 'est pas un nouveau scénario , on retourne les valeurs du formulaire
      //On récupère l'utilisateur courant 
        $ei_user=MyFunction::getGuard()->getEiUser(); 
        $defPack=Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByProjectIdAndProjectRefAndUserIdAndUserRef(
               $values['project_id'],$values['project_ref'],$ei_user->getUserId(),$ei_user->getRefId() ); 
        
        if($defPack !=null): 
            //On et la propriété défaultPackage du scénario pour la sauvegarde de sa version par défaut
            $this->getObject()->setDefaultPackage($defPack);
            return $values; //Le package par défaut existe 
        endif;
            throw new sfValidatorError($validator, 
                         'You have to select package before create à test suite ...')  ;  
            
        return $values;
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
            if($form instanceof EiNodeForm ){ 
                $form->getObject()->setObjId($this->getObject()->getId()); 
                $form->getObject()->setName($this->getObject()->getNomScenario()); 
            }

            $form->getObject()->setCreatedAt($this->getObject()->getCreatedAt());
            $form->getObject()->setUpdatedAt($this->getObject()->getUpdatedAt());
        }
      
      
      parent::saveEmbeddedForms($con, $forms);
  }
  
  
  
}
