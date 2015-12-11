<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EiDataSetFolder
 *
 * @author gregoriz
 */
class EiDataSetFolderForm extends BaseEiNodeForm {

    public function configure() {
        //vide tous les champs et créer un input d'upload de fichier.
        $this->setWidgets(array(
            'name' => new sfWidgetFormInput(
                    array(), array('class' => 'form-control'))
        ));
        $this->setValidators(array('name' => new sfValidatorString()));
        $this->widgetSchema->setNameFormat('ei_data_set[%s]');

        $this->parent_node = $this->getOption('ei_node_parent');

        if ($this->parent_node == null && $this->getObject()->isNew())
            throw new Exception('Le noeud parent doit être passé en paramètre au formulaire EiDataSetForm.');
    }

    public function save($conn = null) {
        if ($this->getObject()->isNew()) {
            $node = $this->getObject();
            $node->setRootId($this->parent_node->getId());
            $node->setProjectId($this->parent_node->getProjectId());
            $node->setProjectRef($this->parent_node->getProjectRef());
            $node->setName($this->getValue('name'));
            $node->setType('EiDataSetFolder');
        }
         return parent::save($conn);
         return  $this->getObject();
    }

}

?>
