<?php

/**
 * EiVersionNotice
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiVersionNotice extends BaseEiVersionNotice
{
    public function __toString(){
        return sprintf('%s', $this->getName());
    }
    //Affichage de la langue en entier (exple : "english" au lieu de "en" )
    public function printLang() {
        return sprintf('%s', sfCultureInfo::getInstance()->getLanguage($this->getLang())); 
    } 
    public   function updateCentralNotice( array $data,Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        try {
            $conn->beginTransaction(); //Début de la transaction 
            $data['notice_id']=$this->getNoticeId();
            $data['notice_ref']=$this->getNoticeRef();
            $data['version_notice_id']=$this->getVersionNoticeId();
            $data['lang']=$this->getLang();
            $result_update = MyFunction::loadResultOfWebServiceByPostJson(
             MyFunction::getPrefixPath(null) . "/serviceweb/project/function/notice/updateNotice.json",$data); 
            $array_result= json_decode(html_entity_decode($result_update) ,true);       
            //Récupération du projet pour traitement
            if (count($array_result) == 0)  return false;
            
            if (array_key_exists("error", $array_result) ):
                return false;
            endif;
            if (!$array_result[0]):
                return false;
            endif; 
            
                //Rechargement d'une notice de fonction
                self::reload($array_result,$conn); 
                $conn->commit();
                return true; 
        } catch (Exception $e) {
            $conn->rollback();
            //return false;
            throw $e;
        }
    }
    //Rechargement d'une verion de notice d'une fonction 
    public static  function reload($array_result,$conn){
        if(count($array_result)>0): //var_dump($array_result);
            Doctrine_Core::getTable("KalFunction")->insertJsonFunction($array_result[0],$conn); //Chargement de la fonction
            Doctrine_Core::getTable("EiTree")->insertJsonFunction($array_result[0],"EiFunction",$conn); //Chargement du noeud associé  
            Doctrine_Core::getTable("EiVersionNotice")->insertJsonFunction($array_result[0],$conn); //Chargement de la version de notice de la fonction           
              
            /* Mise à jour du delta projet */ 
            $conn->execute("update ei_projet set version_courante=".$array_result[0]['p_vers']." , version_kalifast=".$array_result[0]['p_vers']." , updated_at='".$array_result[0]['p_updat']."'  
                    where project_id=".$array_result[0]['p_id']." and ref_id=".$array_result[0]['p_ref']); 
        endif;
    }
    
    /*Méthode permettant de créer une nouvelle langue pour une version de notice sur le système central */
    
    public static function createDistantNoticeLang ($version_notice_id,$notice_id,$notice_ref,$lang, Doctrine_Connection $conn = null){
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        try {
            $conn->beginTransaction(); //Début de la transaction 
            //Appel du webservice   
            $result_service = MyFunction::loadResultOfWebServiceByPostJson(MyFunction::getPrefixPath(null) . "serviceweb/createNoticeVersion.json", 
                    array(  'version_notice_id' => $version_notice_id,
                            'notice_id' =>$notice_id, 
                            'notice_ref' => $notice_ref,
                            'lang' => $lang)); 
            //Récupération de la nouvelle langue de notice pour traitement
            $array_result= json_decode(html_entity_decode($result_service) ,true);           
            if (count($array_result) == 0):
                return array("success" => false , "message" => "Error on transaction")  ;
            endif;
            
            if (array_key_exists("error", $array_result) ):
                return array("success" => false , "message" =>$array_result["error"])  ;
            endif;
            if (!$array_result[0]):
                return array("success" => false , "message" =>"Empty result content")  ;
            endif; 
            
            self::reload($array_result,$conn);  
                $conn->commit(); 
                return Doctrine_Core::getTable("EiVersionNotice")->findOneByVersionNoticeIdAndNoticeIdAndNoticeRefAndLang(
                       $version_notice_id,$notice_id,$notice_ref,$lang );
                
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
            return false;
            
        }
    } 

}