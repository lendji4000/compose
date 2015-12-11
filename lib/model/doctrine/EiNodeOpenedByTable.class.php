<?php

/**
 * EiNodeOpenedByTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiNodeOpenedByTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiNodeOpenedByTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiNodeOpenedBy');
    }
    
    /**
     * Récupère la liste des noeuds ouverts de l'utilisateur.
     * @param type $user
     * @param type $project_id
     * @param type $project_ref
     * @return type
     */
    public function getOpenedNodes($user, $project_id, $project_ref){
        return self::getInstance()->createQuery('opened')
                ->leftJoin('opened.EiNode p')
                ->where('opened.user_id = ?', $user->getUserId())
                ->andWhere('opened.ref_id = ?', $user->getRefId())
                ->andWhere('p.project_id = ?', $project_id)
                ->andWhere('p.project_ref = ?', $project_ref)
                ->execute();
    }
    
    /**
     * Ferme le noeud $node_id de l'utilisateur $user. (suppression de la relation)
     * 
     * @param type $node_id
     * @param type $user
     */
    public function closeNode($node_id, $user){
         $opened = Doctrine_Core::getTable('EiNodeOpenedBy')
                ->findOneByUserIdAndRefIdAndEiNodeId($user->getUserId(), $user->getRefId(), $node_id);
         
         if($opened)
             $opened->delete();
    }
}