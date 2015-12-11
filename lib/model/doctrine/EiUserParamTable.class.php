<?php

/**
 * EiUserParamTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiUserParamTable extends Doctrine_Table {

    /**
     * Returns an instance of this class.
     *
     * @return object EiUserParamTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('EiUserParam');
    }

    public function getUserParams(EiUser $ei_user = null, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        $q = $conn->createQuery()->from('EiUserParam up');
        if ($ei_user != null):
            $q = $q->where('up.user_id=? And up.user_ref=?', array($ei_user->getUserId(), $ei_user->getRefId()));
        endif;
        $q = $q->orderBy('up.name');
        return $q->execute();
    }

}