<?php

/**
 * EiDeviceTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiDeviceTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiResourcesDeviceParamsTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiDevice');
    }
    
    public static function getAvailablesDevices()
    {
        $conn = Doctrine_Manager::connection();
        $result = $conn->fetchAll("select d.id, d.device_identifier, dt.name, dt.logo_path "
                . "from ei_device d "
                . "inner join ei_device_type dt on d.device_type_id = dt.id "
                . "where d.id not in (select distinct device_id from ei_device_user);");
        return $result;
    }  
    
    //Récupération de la requête pour les  d'un projet
    public function getAvailablesIdsQuery(Doctrine_Connection $conn=null){
        if($conn==null){
            $conn = Doctrine_Manager::connection(); 
        }
        $unavailaibleDevicesTab= $conn->fetchAll("select distinct device_id as id from ei_device_user");
        if(count($unavailaibleDevicesTab)>0):
            foreach($unavailaibleDevicesTab as $dev):
                $unavailaibleDevices[]=$dev['id'];
            endforeach;
            return $conn->createQuery()->from('EiDevice')
                            ->whereNotIn('id',$unavailaibleDevices);
        else:
            return null;
        endif;
    }
    
    public static function getAvailablesIds()
    {
        $conn = Doctrine_Manager::connection();
        $result = $conn->fetchAll("select d.id "
                . "from ei_device d "
                . "inner join ei_device_type dt on d.device_type_id = dt.id "
                . "where d.id not in (select distinct device_id from ei_device_user);");
        return $result;
    }
}