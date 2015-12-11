<?php

/**
 * EiSubjectMessageTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiSubjectMessageTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiSubjectMessageTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiSubjectMessage');
    }
    
    
    //Récupération des méssages sur un sujet relativement au type 
    public function getMessages($subject_id,$type){ 
        $q = Doctrine_Query::create()
            ->select('sm.* , author.*')
            ->from('EiSubjectMessage sm')  
            ->leftJoin('sm.sfGuardUser author')
            ->where('sm.subject_id = ? And sm.type= ?',array($subject_id,$type))
                ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
                //->orderBy('sm.created_at DESC')
                ;
        
        $treeObject =self::getInstance()->getTree();
        $treeObject->setBaseQuery($q); 
        $tree= $treeObject->fetchRoots();  
        $treeObject->resetBaseQuery();
        return $tree;
    }
    
    public function fetchBranchMsg ($ei_message_id,$type){
        $q = Doctrine_Query::create()
            ->select('sm.* , author.*')
            ->from('EiSubjectMessage sm')  
            ->leftJoin('sm.sfGuardUser author')
            ->where('sm.id=? And  sm.type= ?',array($ei_message_id,$type))
            ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
                ; 
        $treeObject =self::getInstance()->getTree();
        $treeObject->setBaseQuery($q); 
        $tree= $treeObject->fetchBranch($ei_message_id);
        $treeObject->resetBaseQuery();
        return $tree;
    }
    
}