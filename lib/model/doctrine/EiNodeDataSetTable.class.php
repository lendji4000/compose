<?php

/**
 * EiNodeDataSetTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiNodeDataSetTable extends EiDataSetStructureTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiNodeDataSetTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiNodeDataSet');
    }

    //********************************************************************//
    //**********          REQUETES INTERROGATION ARBRE          **********//
    //********************************************************************//

    /**
     * Méthode qui retourne la liste des noeuds fils d'un noeud parent.
     *
     * @param $eiNodeDataSetId
     * @return Doctrine_Query
     */
    public function getChildrenQuery($eiNodeDataSetId) {
        return Doctrine_Query::create()
            ->select('node.*')
            ->from('EiNodeDataSet node')
            ->where('node.ei_dataset_structure_parent_id = ?', $eiNodeDataSetId)
            ->orderBy('node.lft');
    }

    public function getNodeChildren($eiNodeDataSetId){
        return $this->getChildrenQuery($eiNodeDataSetId)->execute();
    }

    /**
     * Retourne la liste des feuilles d'un noeud.
     *
     * @param $eiNodeDataSetId
     * @return mixed
     */
    public function getLeaves($eiNodeDataSetId){
        return Doctrine_Core::getTable("EiLeafDataSet")->getLeaves($eiNodeDataSetId);
    }
}