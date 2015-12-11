<?php

/**
 * EiProjectUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiProjectUser extends BaseEiProjectUser
{
    public function __toString()
    {
        return 'Projet  :'.$this->getEiProjet().'    Utilisateur    :'.$this->getEiUser();
    }

    public function getEiProjet(){
       return Doctrine_Core::getTable('EiProjet')->findOneByProjectIdAndRefId($this->getProjectId(),$this->getProjectRef());
    }
    public function getEiUser(){
        return Doctrine_Core::getTable('sfGuardUser')->findOneById($this->getGuardId());
    }
}
