<?php

/**
 * EiBugContext
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiBugContext extends BaseEiBugContext
{
    public function getProfile(){
        if($this->getProfileId()==null ||  $this->getProfileRef()==null) return null;
        return Doctrine_Core::getTable('EiProfil')->findOneByProfileIdAndProfileRef(
                $this->getProfileId(), $this->getProfileRef());
    }
}
