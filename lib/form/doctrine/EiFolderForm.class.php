<?php

/**
 * EiFolder form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiFolderForm extends BaseEiFolderForm
{
  public function configure()
  {
      unset(  $this['created_at'],$this['updated_at']);
        $this->widgetSchema["project_ref"] = new sfWidgetFormInputHidden();
        $this->widgetSchema["project_id"] = new sfWidgetFormInputHidden();
        $this->widgetSchema['name']->setAttributes(Array( 
            'class' => '  form-control' ));
        
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
            $ei_node->setType('EiFolder');
            $ei_node->setRootId($root_id); 
            //on imbrique le sous formulaire au formulaire de creation d'un dossier
            $this->embedForm('ei_node', new EiNodeForm($ei_node)); 
        }
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
                $form->getObject()->setName($this->getObject()->getName()); 
            }

            $form->getObject()->setCreatedAt($this->getObject()->getCreatedAt());
            $form->getObject()->setUpdatedAt($this->getObject()->getUpdatedAt());
        }
      
      
      parent::saveEmbeddedForms($con, $forms);
  }
 
}
