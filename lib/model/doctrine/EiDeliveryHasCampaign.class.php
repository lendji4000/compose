<?php

/**
 * EiDeliveryHasCampaign
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiDeliveryHasCampaign extends BaseEiDeliveryHasCampaign
{
    public function save(\Doctrine_Connection $conn = null) {
        //On vérifie si la relation n'hexiste pas déjà
        $ei_delivery_has_campaign=Doctrine_Core::getTable('EiDeliveryHasCampaign')
              ->findOneByDeliveryIdAndCampaignId($this->getDeliveryId(),$this->getCampaignId());
        if($ei_delivery_has_campaign!=null) return -1;
        parent::save($conn);
        return 1;
    }
}
