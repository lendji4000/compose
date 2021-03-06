<?php

/**
 * EiCampaignGraphHasGraph
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiCampaignGraphHasGraph extends BaseEiCampaignGraphHasGraph
{

    public function save(Doctrine_Connection $conn = null) {
        if($this->getParentId()!=null && $this->getParentId()!=0 ):
            parent::save($conn); 
        endif;
        
    }
    
    /* Création d'une ligne campagne mère - campagne fille */
    public static function createItem(array $item_fields ,Doctrine_Connection $conn = null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        if(isset($item_fields['parent_id']) && isset($item_fields['child_id']) && isset($item_fields['campaign_id'])):
                $new_relation=new EiCampaignGraphHasGraph();
                $new_relation->setParentId($item_fields['parent_id']);
                $new_relation->setChildId($item_fields['child_id']);
                $new_relation->setCampaignId($item_fields['campaign_id']); 
                $new_relation->save($conn);
                return $new_relation;
            else:
                return null;
        endif; 
    }
}
