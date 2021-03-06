<?php

/**
 * EiFolder
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiFolder extends BaseEiFolder {

    /* Setter projet pour le dossier */
    public function setProject(EiProjet $ei_project){
        $this->setProjectId($ei_project->getProjectId());
        $this->setProjectRef($ei_project->getRefId());
    }
    public static function createFolder(EiNode $root_node, $folder_name) {
        if ($root_node == null)
            return null;
        $conn = Doctrine_Manager::connection();

        try {
            $conn->beginTransaction();
            
            $ei_folder=new EiFolder();
            $ei_folder->setProjectId($root_node->getProjectId());
            $ei_folder->setProjectRef($root_node->getProjectRef());
            $ei_folder->setName($folder_name);
            $ei_folder->save($conn);
            
            $ei_node = new EiNode();
            $ei_node->setProjectId($root_node->getProjectId());
            $ei_node->setProjectRef($root_node->getProjectRef()); 
            $ei_node->setObjId($ei_folder->getId());
            $ei_node->setName($folder_name);
            $ei_node->setPosition(Doctrine_Core::getTable('EiNode')->getLastPositionInNode(
                            $root_node->getProjectId(), $root_node->getProjectRef(), $root_node->getId()));
            $ei_node->setIsRoot(false);
            $ei_node->setIsShortcut(false);
            $ei_node->setRootId($root_node->getId());
            $ei_node->setType("EiFolder");
            $ei_node->save($conn);

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }
    
    public function save(\Doctrine_Connection $conn = null) {
        if(!$this->isNew()){
            $node=$this->getNode();
            $node->setName($this->getName());
            $node->save($conn);
        }
        parent::save($conn);
    }

    //Récupération du noeud parent du scénario
    public function getNode(){
        return Doctrine_Core::getTable('EiNode')->findOneByObjIdAndType($this->getId(),'EiFolder');
    }
}
