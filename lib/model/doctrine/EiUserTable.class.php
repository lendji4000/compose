<?php

/**
 * EiUserTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiUserTable extends Doctrine_Table {

    /**
     * Returns an instance of this class.
     *
     * @return object EiUserTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('EiUser');
    }

    public static function rechargerUser($users, Doctrine_Connection $conn = null) {
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        $ei_user = $users->getElementsByTagName("ei_user");
        if ($ei_user->length != null) { //s'il ya au moins une balise trouvé
            foreach ($ei_user as $u) {
 
                $ref_id = $u->getAttribute("ref_id"); 
                $user_id = $u->getAttribute("user_id");  
                $guard_id = $u->getElementsByTagName("guard_id")->item(0)->nodeValue;
                $matricule = $u->getElementsByTagName("matricule")->item(0)->nodeValue;
                
                //recherche de l'utilisateur en base
                /** @var EiUser $q */
                $q = Doctrine_Core::getTable('EiUser')->findOneByRefIdAndUserId($ref_id, $user_id);

                if ($q && $q != null) {//si l'utilisateur existe , on fait une mise à jour
                    $q->matricule = $matricule;

                    if( $q->token_api === "" || $q->token_api === null ){
                        $q->token_api = sha1(rand(11111, 99999999999).$q->getGuardUser()->getEmailAddress().rand(11111, 99999999999));
                    }

                    $q->save($conn);
                } else {//l utilisateur n'existe pas encore , alors on le  crée
                    self::createUser(array(
                        'ref_id' =>$ref_id,
                        'user_id' =>$user_id,
                        'matricule' =>$matricule,
                    ), $guard_id,$conn);   
                }
            }
        }
    }

    public function getUserByTokenApi($token){
        return $this->findOneBy("token_api", $token);
    }

    /**
     * Méthode permettant de générer un nouveau jeton pour l'utilisateur à destinations de l'api.
     *
     * @param EiUser $user
     * @return string
     */
    public function generateToken(EiUser $user){
        $token = sha1(rand(11111, 99999999999).$this->calculateToken($user->getGuardUser()->getEmailAddress()).rand(11111, 99999999999));

        $user->setTokenApi($token);
        $user->save($this->getConnection());

        return $token;
    }

    /**
     * Retourne un nouveau token à partir de l'adresse email.
     *
     * @param $email
     * @return string
     */
    private function calculateToken($email){
        return sha1(rand(11111, 99999999999).$email.rand(11111, 99999999999));
    }
    /* Création d'un EiUser  avec un tableau passé en paramètre */
    public static function createUser(Array $ei_user_tab, $guard_id, Doctrine_Connection $conn = null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        $new_ei_user = new EiUser();
        $new_ei_user->setGuardId($guard_id);
        $new_ei_user->setRefId($ei_user_tab['ref_id']);
        $new_ei_user->setUserId($ei_user_tab['user_id']);
        $new_ei_user->setMatricule($ei_user_tab['matricule']);
        $new_ei_user->save();
        return $new_ei_user;
    }
    
    /* Recupération du profil par défaut d'un  utilisateur */
    public function getDefaultProfile($user_id ,$user_ref,$project_id,$project_ref , Doctrine_Connection $conn = null){
         if($conn==null) $conn = Doctrine_Manager::connection();
         if($user_id==null || $user_ref==null || $project_id==null || $project_ref ==null ) return null; //Doctrine_Query::create()->
         
         $res= Doctrine_Query::create()->from("EiProfil p")
                ->where('EiUserDefaultProfile.profile_id=p.profile_id And EiUserDefaultProfile.profile_ref=p.profile_ref')
                ->andWhere('EiUserDefaultProfile.user_id=? And  EiUserDefaultProfile.user_ref=? And EiUserDefaultProfile.project_id=? And  EiUserDefaultProfile.project_ref=? ',array(
                    $user_id,$user_ref,$project_id,$project_ref
                )) 
                ->execute();
        if (count($res)>0):
            return $res->getFirst(); 
        endif;
    }
}