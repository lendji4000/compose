<?php

/**
 * EiUserDefaultPackageTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiUserDefaultPackageTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiUserDefaultPackageTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiUserDefaultPackage');
    }
    public function setDefaultPackage($ticket_id,$ticket_ref,EiProjet $ei_project,EiUser $ei_user,$is_new,Doctrine_Connection $conn = null){
        if ($conn == null) $conn = Doctrine_Manager::connection();
        if($ticket_id!=null && $ticket_ref!=null):  
            if($is_new==0):   
                $conn->insert($this->getInstance(),array(
                        'user_ref' => $ei_user->getRefId(),
                        'user_id' =>$ei_user->getUserId(),
                        'project_id' => $ei_project->getProjectId(),
                        'project_ref' => $ei_project->getRefId(),
                        'ticket_id' => $ticket_id,
                        'ticket_ref' => $ticket_ref,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s")) );
                else:   
                $conn->update($this->getInstance(), 
                    array( 
                        'ticket_id' => $ticket_id, 
                        'ticket_ref' => $ticket_ref,
                        'updated_at' => date("Y-m-d H:i:s")
                    ), array(
                        'user_ref' =>   $ei_user->getRefId(),
                        'user_id' =>    $ei_user->getUserId(),
                        'project_ref' => $ei_project->getRefId(),
                        'project_id' => $ei_project->getProjectId()));
            endif;
            
        return true;
        endif;
        return false;
    }
}