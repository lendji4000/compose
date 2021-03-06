<?php

/**
 * EiLeafDataSetTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiLeafDataSetTable extends EiDataSetStructureTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiLeafDataSetTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiLeafDataSet');
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
            ->select('leaf.*')
            ->from('EiLeafDataSet leaf')
            ->where('leaf.ei_dataset_structure_parent_id = ?', $eiNodeDataSetId)
            ->orderBy('leaf.lft');
    }

    /**
     * @param $eiNodeDataSetId
     * @return Doctrine_Collection
     */
    public function getLeaves($eiNodeDataSetId){
        return $this->getChildrenQuery($eiNodeDataSetId)->execute();
    }
}