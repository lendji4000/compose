<?php

/**
 * sfGuardUserTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class sfGuardUserTable extends PluginsfGuardUserTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object sfGuardUserTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('sfGuardUser');
    }
    //Récupération de la liste des utilisateurs d'un projet
    
    public function getProjectUsers(EiProjet $ei_project){
         return Doctrine_Core::getTable('sfGuardUser')
                        ->createQuery('g')
                        ->where('EiUser.guard_id =g.id ')
                        ->AndWhere('EiProjectUser.user_id=EiUser.user_id And EiProjectUser.user_ref=EiUser.ref_id ')
                        ->AndWhere('EiProjet.project_id=EiProjectUser.project_id And EiProjet.ref_id=EiProjectUser.project_ref')
                        ->AndWhere('EiProjet.project_id=? And EiProjet.ref_id= ?', array(
                            $ei_project->getProjectId(), $ei_project->getRefId()))
                        ->execute();
    }

    public static function reloadGuardUsers($guard_users, Doctrine_Connection $conn = null){
        $guard_user = $guard_users->getElementsByTagName("guard_user");
        if ($guard_user->length >0) { //s'il ya au moins une balise trouvé
            foreach ($guard_user as $u) { 
                    $id = $u->getAttribute("id"); 
                    $first_name = $u->getElementsByTagName("first_name")->item(0)->nodeValue;  
                    $last_name = $u->getElementsByTagName("last_name")->item(0)->nodeValue;  
                    $email_address = $u->getElementsByTagName("email_address")->item(0)->nodeValue; 
                    $company = $u->getElementsByTagName("company")->item(0)->nodeValue; 
                    $username = $u->getElementsByTagName("username")->item(0)->nodeValue; 
                    $password = $u->getElementsByTagName("password")->item(0)->nodeValue;
                    
                //recherche de l'utilisateur en base
                if ($email_address != null && $username != null) {
                    $q = Doctrine_Core::getTable('sfGuardUser')->findOneByUsername($username);

                    if ($q && $q != null) {//si l'utilisateur existe , on fait une mise à jour
                        $q->first_name = $first_name;
                        $q->last_name = $last_name;
                        $q->email_address = $email_address;
                        $q->company = $company;
                        $q->username = $username;
                        $q->setPassword($password);
                        $q->save($conn);
                    } else {//l utilisateur n'existe pas encore , alors on le  crée
                        $guard_user = new sfGuardUser();
                        $guard_user->setId($id);
                        $guard_user->first_name = $first_name;
                        $guard_user->last_name = $last_name;
                        $guard_user->email_address = $email_address;
                        $guard_user->company = $company;
                        $guard_user->username = $username;
                        $guard_user->setPassword($password);
                        $guard_user->save($conn);
                    }
                }
            }
        }
    }
    /* Création d'un user à partir d'un tableau de paramètres */
    public function createUser(Array $guard_tab,$ei_user_tab) {
        $new_guard = new sfGuardUser();
        $new_guard->setId($guard_tab['id']);
        $new_guard->setUsername($guard_tab['username']);
        $new_guard->setFirstName($guard_tab['first_name']);
        $new_guard->setLastName($guard_tab['last_name']);
        $new_guard->setEmailAddress($guard_tab['email_address']);
        $new_guard->setPassword($guard_tab['password']);
        $new_guard->save();
        
        /* Création du EiUser */ 
        EiUserTable::createUser($ei_user_tab,$new_guard->getId()); 
        
        return $new_guard;
    }
}