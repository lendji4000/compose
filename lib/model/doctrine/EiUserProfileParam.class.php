<?php

/**
 * EiUserProfileParam
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiUserProfileParam extends BaseEiUserProfileParam
{
    private $ei_user;
    private $ei_profile_param;
    
    public function __construct($table = null, $isNewEntry = false, EiProfileParam $ei_profile_param=null,EiUser $ei_user=null) {
         
        parent::__construct($table, $isNewEntry);
        if($ei_profile_param!=null) $this->setEiProfileParam($ei_profile_param); 
        if($ei_user!=null) $this->setEiUser($ei_user); 
       
    }
    
    public function save(\Doctrine_Connection $conn = null) {
        parent::save($conn);
    }
    
    protected function getEiProfileParam(){
        return $this->ei_profile_param;
    } 
    
    protected function setEiProfileParam(EiProfileParam $ei_profile_param){
        $this->setProfileParamId($ei_profile_param->getId());
         $this->ei_profile_param=$ei_profile_param;
    } 
    
    protected function getEiUser(){
        return $this->ei_user;
    }
    
    protected function setEiUser(EiUser $ei_user){
        $this->ei_user=$ei_user;
        $this->setUserId($ei_user->getUserId());
        $this->setUserRef($ei_user->getRefId());
    }
}