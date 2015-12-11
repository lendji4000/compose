<?php

/**
 * EiTree
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiTree extends BaseEiTree
{
    public function __toString() {
        return sprintf('%s', $this->getName());
    }
    //Récupération des éléments ordonnés d'un noeud (raccourcis , vues enfants et fonctions de la vue) 
    public function getNodes() {
        return Doctrine_Core::getTable('EiTree')->createQuery('t')
                ->where('t.root_id=?',$this->getId())
                ->orderBy('t.position ASC')
                ->execute();
    }
    /*Récupération des éléments ordonnés d'un noeud avec la vérification d'existence 
     * d'un ou plusieurs enfants par noeuds
    */
     
    public function getNodesWithChildsInf() {  
        return Doctrine_Core::getTable('EiTree')->getNodesWithChildsInf($this->getId());   
    }
    public function getNodeParent(){
        return Doctrine_Core::getTable('EiTree')->findOneById($this->getRootId());
    }
    
    public function getView() {
        return Doctrine_Core::getTable('EiView')->findOneByViewRefAndViewId($this->getRefObj(), $this->getObjId());
    }

    public function getFunction() {
        return Doctrine_Core::getTable('KalFunction')->findOneByFunctionRefAndFunctionId($this->getRefObj(), $this->getObjId());
    }

    public function getShortCut() {
        return Doctrine_Core::getTable('EiShortCut')->findOneByViewRefAndViewId($this->getRefObj(), $this->getObjId());
    }

    public function ReadyToChoice() {
        return sprintf('%s', $this->getIndentedName());
    }

    public function getParentId() {
        if (!$this->getNode()->isValidNode() || $this->getNode()->isRoot()) {
            return null;
        }

        $parent = $this->getNode()->getParent();

        return $parent['id'];
    }

    public function getIndentedName() {
        return $this['name'];
    }

    public function asArray()
    {

        return array(
        'id'                        => $this->getId(),
        'name'                      => $this->getName(),   
        'type'                      => $this->getType(),
        'obj_id'                    => $this->getObjId(),
        'ref_obj'                   => $this->getRefObj(),
        'is_root'                   => $this->getIsRoot(),
        'project_id'                => $this->getProjectId(),
        'project_ref'               => $this->getProjectRef(),
        'position'                  => $this->getPosition(),   
        'root_id'                   => $this->getParentId(),   
        'created_at'                => $this->getCreatedAt(),
        'updated_at'                => $this->getUpdatedAt(),    
        );
    }
}