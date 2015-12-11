<?php

/**
 * EiFunctionHasParam
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiFunctionHasParam extends BaseEiFunctionHasParam
{
    public function setKalFunction(KalFunction $kalfunction){
        $this->setFunctionId($kalfunction->getFunctionId());
        $this->setFunctionRef($kalfunction->getFunctionRef());
    }
    public function getKalFunction(){
        return Doctrine_Core::getTable('KalFunction')->findOneByFunctionIdAndFunctionRef(
               $this->getFunctionId(),$this->getFunctionRef());
    }

    /**
     * @param Doctrine_Connection $conn
     */
    public function save(Doctrine_Connection $conn = null)
    {
        $isNew = $this->isNew();

        parent::save($conn);

        if( $isNew && $this->getParamType() == 'OUT' ){

            /** @var EiFonction[] $fonctions */
            $fonctions = Doctrine_Core::getTable("EiFonction")->findByFunctionIdAndFunctionRef($this->getFunctionId(), $this->getFunctionRef());

            foreach( $fonctions as $fonction ){
                $mapping = new EiParamBlockFunctionMapping();
                $mapping->setEiFunction($fonction);
                $mapping->setEiFunctionParamMapping($this);

                $mapping->save();
            }
        }
    }
    
    public function asArray()
  { //rendu d'un paramètre sous forme de tableau
    return array(
      'param_id'            => $this->getParamId(),
      'function_ref'         => $this->getFunctionRef() ,
      'function_id'         => $this->getFunctionId() ,
      'param_type'          => $this->getParamType(),
      'name'                => $this->getName(),
      'description'          => $this->getDescription(),
      'default_value'       => $this->getDefaultValue(),
      'created_at'       => $this->getCreatedAt(),
      'updated_at'       => $this->getUpdatedAt()
    );
  }
    
    /* Suppression d'un paramètre sur  la plate forme centrale */

    public  function deleteParam(EiProjet $ei_project, EiProfil $ei_profile, KalFunction $kal_function, $param_id, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        try {
                        
            $conn->beginTransaction(); //Début de la transaction 
            //Appel du webservice  
            $result_update = MyFunction::loadResultOfWebServiceByPostJson(
             MyFunction::getPrefixPath(null) . "/serviceweb/project/parameter/delete.json", array(
                        'project_id' => $ei_project->getProjectId(),
                        'project_ref' => $ei_project->getRefId(),
                        'profile_id' => $ei_profile->getProfileId(),
                        'profile_ref' => $ei_profile->getProfileRef(),
                        'function_id' => $kal_function->getFunctionId(),
                        'function_ref' => $kal_function->getFunctionRef(), 
                        'param_id' => $param_id)); 
            $array_result= json_decode(html_entity_decode($result_update) ,true);            
            //Récupération du paramètre pour traitement
            if (count($array_result) == 0)  return false;
            
            if (array_key_exists("error", $array_result) ):
                return false;
            endif;  
                //Rechargement d'un paramètre
                $this->delete($conn);
                $conn->commit();
                return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
            throw $e;
        }
    }
    
    /* Création ou mise à jour d'un paramètre  sur la plate forme centrale */

    public static function createOrUpdateDistantParams(EiProjet $ei_project, EiProfil $ei_profile, KalFunction $kal_function, $data, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        try {
                        
            $conn->beginTransaction(); //Début de la transaction 
            //Appel du webservice  
            $result_update = MyFunction::loadResultOfWebServiceByPostJson(
             MyFunction::getPrefixPath(null) . "/serviceweb/project/parameter/createOrUpdate.json", array(
                        'project_id' => $ei_project->getProjectId(),
                        'project_ref' => $ei_project->getRefId(),
                        'profile_id' => $ei_profile->getProfileId(),
                        'profile_ref' => $ei_profile->getProfileRef(),
                        'function_id' => $kal_function->getFunctionId(),
                        'function_ref' => $kal_function->getFunctionRef(), 
                        'data' => $data)); 
            $array_result= json_decode(html_entity_decode($result_update) ,true);            
            //Récupération du paramètre pour traitement
            if (count($array_result) == 0)  return false;
            
            if (array_key_exists("error", $array_result) ):
                return false;
            endif;
            if (!$array_result[0]):
                return false;
            endif; 
            
                //Rechargement d'un paramètre
                self::reload($array_result,$conn); 
                $conn->commit();
                return $array_result[0]['fp_id']; 
        } catch (Exception $e) {
            $conn->rollback();
            return false;
            throw $e;
        }
    }
    
    
    //Rechargement d'un paramètre
    public static function reload($array_result,$conn){
        if (count($array_result) > 0):
            Doctrine_Core::getTable("KalFunction")->insertJsonFunction($array_result[0], $conn); //Chargement de la fonction 
            /* On vérifie que le paramètre est nouveau. */
            $exist_param = $conn->getTable("EiFunctionHasParam")->findOneByParamId($array_result[0]['fp_id']);
            Doctrine_Core::getTable("EiFunctionHasParam")->insertJsonFunction($array_result[0], $conn); //Chargement des paramètres   
            /* On crée les ei_params pour le nouveau paramètre */
            if ($exist_param == null): //S'il s'agit d'un nouveau paramètre
                $ei_function_has_param = $conn->getTable("EiFunctionHasParam")->findOneByParamId($array_result[0]['fp_id']);
                if ($ei_function_has_param != null):
                    $conn->getTable("EiFunctionHasParam")->createNewEiParams($ei_function_has_param,$conn);
                endif;

            endif;
            /* Mise à jour du delta projet */
            $conn->execute("update ei_projet set version_courante=" . $array_result[0]['p_vers'] . " , version_kalifast=" . $array_result[0]['p_vers'] . " , updated_at='" . $array_result[0]['p_updat'] . "'  
                    where project_id=" . $array_result[0]['p_id'] . " and ref_id=" . $array_result[0]['p_ref']);
        endif;
    }

}