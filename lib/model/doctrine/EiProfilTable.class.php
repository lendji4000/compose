<?php

/**
 * EiProfilTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiProfilTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiProfilTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiProfil');
    }
    public function getProfils(){
        return $this->getInstance()->createQuery('p');
    }
    //Récupération du profil par défaut d'un projet
    public function getDefaultProjectProfile($project_ref,$project_id){
        if($project_ref==null || $project_id==null) return null;
        $q=Doctrine_Core::getTable('EiProfil')->createQuery('pfil')
                        ->where('pfil.project_id=? AND pfil.project_ref=?', array($project_id,$project_ref))
                        ->andWhere('pfil.is_default = ?', true)
                        ->execute();
        if(count($q)>0) return $q->getFirst();
        return null;
    }
    //Récupération des profils d'un projet sous forme de tableau associatif pour liste déroulante
    public function getProjectProfilesAsArray(EiProjet $ei_project , Doctrine_Connection $conn = null){
        $projectProfiles=$this->getProfils()->where('project_id=? And project_ref=?',array(
                    $ei_project->getProjectId(),$ei_project->getRefId() 
                    ))->execute();
        $arrayTab=array();
        if(count($projectProfiles)>0):
            foreach($projectProfiles as $profile): 
                $arrayTab[$profile->getProfileId().'_'.$profile->getProfileRef()]=$profile->getName();
            endforeach;
        endif;
        return $arrayTab;
    }
     
    /*Récupération des profils d'un projet sous forme de tableau associatif pour liste déroulante (option null) en plus */
    public function getProjectProfilesAsArrayWithNull(EiProjet $ei_project , Doctrine_Connection $conn = null){
        $tabProfiles =$this->getProjectProfilesAsArray($ei_project,$conn);
        return array_merge(array(0=>null),$tabProfiles); 
    }
    //Rechargement des éléments de type EiProfil pour un projet 
    public function reload($projets,$project_id, $project_ref, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();

        //Création de la collection d'objet EiProfil à ajouter
        $collection = new Doctrine_Collection("EiProfil");
        
        $items = $projets->getElementsByTagName("ei_profiles");
        if ($items->length > 0) {//ya t-il des éléments à traiter?
            $ei_profiles = $items->item(0)->getElementsByTagName("ei_profile");


            if ($ei_profiles != null) {
                foreach ($ei_profiles as $key => $ei_profile) {

                    $profile_id = $ei_profile->getAttribute("profile_id");
                    $profile_ref = $ei_profile->getAttribute("profile_ref"); 
                    //recherche du profil en base
                    if ($profile_id != null && $profile_ref) { 
                        //l'élément n'existe pas encore, et dans ce cas on le crée
                        $new_ei_profile = new EiProfil();
                        $new_ei_profile->setProfileId($profile_id);
                        $new_ei_profile->setProfileRef($profile_ref);
                        $new_ei_profile->setProjectId($project_id);
                        $new_ei_profile->setProjectRef($project_ref);
                        $new_ei_profile->setName($ei_profile->getElementsByTagName("name")->item(0)->nodeValue);
                        $new_ei_profile->setBaseUrl($ei_profile->getElementsByTagName("base_url")->item(0)->nodeValue);
                        $new_ei_profile->setDescription( $ei_profile->getElementsByTagName("description")->item(0)->nodeValue);
                        $new_ei_profile->setParentId( $ei_profile->getElementsByTagName("parent_id")->item(0)->nodeValue);
                        $new_ei_profile->setParentRef( $ei_profile->getElementsByTagName("parent_ref")->item(0)->nodeValue);
                        $new_ei_profile->setIsDefault($ei_profile->getElementsByTagName("is_default")->item(0)->nodeValue);
                        $new_ei_profile->setCreatedAt( $ei_profile->getElementsByTagName("created_at")->item(0)->nodeValue);
                        $new_ei_profile->setUpdatedAt($ei_profile->getElementsByTagName("updated_at")->item(0)->nodeValue);
                        $collection->add($new_ei_profile,$key);
                    }
                }
                
                /*Traitement de la collection de profil . 
                 * La gestion des rechargements de profil est spéciale car on doit prendre en compte
                 * les associations scenario-profil pour les profils dupliqués sur la plate forme 
                 * centrale.On éffectue pour célà un parcours afin d'analyser les profils récupérés 
                 */
                while ($collection->getFirst()) { //Tant qu'il existe des profiles dans la collection
                    foreach ($collection as $key => $profile):
                        if (!$this->findIfProfileExists($profile,$conn)) : //Si le profile n'existe pas en base
                            if ($profile->getParentId() != null && $profile->getParentRef() != null): //Si le profile possède un parent 
                                //On recherche le parent du profile
                                $profileParent=$conn->getTable('EiProfil')
                                    ->findOneByProfileIdAndProfileRef($profile->getParentId(), $profile->getParentRef());
                                
                                //Si le parent existe déjà côté compose (client) 
                                if($profileParent!=null):
                                    //On crée les relations scénario-profil pour le profile enfant
                                    $this->createRelationForProfileChild($profileParent, $profile,$conn); 
                                    $profile->save($conn);//On sauvegarde le profil
                                    $collection->remove($key); //On  supprime le profil de la collection 
                                endif;
                            else: // si le profil ne possède pas de parent (premier chargement du projet), on le crée
                                $profile->save($conn);
                                $collection->remove($key); //On  supprime le profil de la collection
                            endif;
                        else: //Si le profile existe , on le met à jour 
                            $this->updateExistProfile($profile ,$conn);
                            $collection->remove($key); //On  supprime le profil de la collection
                        endif;
                    endforeach;
                }
                
               // if($collection->getFirst()) $collection->save($conn); //Sauvegarde de la collection 
               return 1; 
            }
            return null;
        }
    } 

    public function getProfForProject($project_id,$project_ref,$ei_scenario_id,$ei_version_id){
        if($project_id!=null){
            $ei_project=Doctrine_Core::getTable('EiProjet')->findOneByProjectIdAndRefId($project_id,$project_ref);
            $q=Doctrine_Query::create()->from('EiProfil p')
                        ->Where('p.project_id='.$ei_project->project_id. ' And p.project_ref='.$ei_project->ref_id)
                        ->AndWhere('p.profile_id NOT IN (SELECT ps.profile_id FROM EiProfilScenario as ps
                        WHERE ps.ei_scenario_id= ? And ps.ei_version_id=? )',
                        array($ei_scenario_id,$ei_version_id))->execute();
            if($q->getFirst()) return $q;
            return null;
        }
        return null;
    } 
    /* Lorsqu'on recharge les profils d'un  projet, on doit tenir compte 
     * des relations scénario-profil existantes sur Compose.En effet si un profil a été dupliqué
     * sur la plate forme centrale (Script.kalifast) , on doit recrée les relations scénario-profil
     * pour le profil enfant
     */
    public function createRelationForProfileChild(EiProfil $profileParent, EiProfil $profile, Doctrine_Connection $conn = null){
        if ($conn == null)  $conn = Doctrine_Manager::connection();
        $collection1=$conn->getTable('EiProfilScenario')
                ->findByProfileIdAndProfileRef($profileParent->getProfileId(),$profileParent->getProfileRef());
        //Si des relations existent alors on les recrée pour le profil enfant
        if($collection1->getFirst()):
            $collection2=new Doctrine_Collection('EiProfilScenario');
            foreach ($collection1 as $profil_scenario):
                $new_obj=new EiProfilScenario();
                $new_obj->setProfileId($profile->getProfileId());
                $new_obj->setProfileRef($profile->getProfileRef());
                $new_obj->setEiScenarioId($profil_scenario->getEiScenarioId());
                $new_obj->setEiVersionId($profil_scenario->getEiVersionId());
                $collection2->add($new_obj);
            endforeach;
             if($collection2->getFirst()) $collection2->save($conn); //Sauvegarde des nouvelles relations 
        endif;
    }
    
    /* Vérifier si un profil existe */
    public function findIfProfileExists(EiProfil $profile , Doctrine_Connection $conn = null){
        if ($conn == null)  $conn = Doctrine_Manager::connection();
        $obj =$conn->getTable('EiProfil')
                ->findOneByProfileIdAndProfileRef($profile->getProfileId(),$profile->getProfileRef());
        if ($obj!=null) return true;
        return false;
    }
    /* Mise à jour d'un profil existant */
    public function updateExistProfile(EiProfil $profile , Doctrine_Connection $conn = null){
        if ($conn == null)  $conn = Doctrine_Manager::connection();
        $conn->createQuery()->update('EiProfil') 
                    ->set('name', '?', $profile->getName())
                    ->set('base_url', '?', $profile->getBaseUrl() )
                    ->set('description', '?', $profile->getDescription())
                    ->set('is_default', '?', $profile->getIsDefault())
                    ->set('parent_id', '?', $profile->getParentId())
                    ->set('parent_ref', '?', $profile->getParentRef())
                    ->where('profile_id=? And profile_ref=? ', 
                            array($profile->getProfileId(), $profile->getProfileRef()))
                    ->execute();
    }
    /* Suppression des profils d'un projet donné */
    public function deleteProjectProfiles($project_id, $project_ref , Doctrine_Connection $conn = null){ 
        if ($conn == null)  $conn = Doctrine_Manager::connection();
            $conn->getTable('EiProfil')->createQuery('p')
                ->delete()
                ->where('p.project_id=? And p.project_ref=?',
                        array($project_id,$project_ref)) 
                ->execute(); 
    }

    public function getProfilsScenarioForUser(EiUser $user, $scenarioId){
        // On définit la liste qui sera retournée à null.
        $liste = null;

        // On définit la requête SQL qui va interroger la base de données afin de récupérer les scénarios visibles par
        // l'utilisateur.
        $query = "
                SELECT *
                FROM ei_profil
                WHERE (profile_ref, profile_id) IN (
                    SELECT profile_ref, profile_id
                    FROM ei_profil_scenario
                    WHERE ei_scenario_id = ".$scenarioId."
                    AND ei_scenario_id IN (
                        SELECT id
                        FROM ei_scenario
                        WHERE (project_ref, project_id) IN (
                            SELECT project_ref, project_id
                            FROM ei_project_user
                            WHERE user_ref = ".$user->getRefId()."
                            AND user_id = ".$user->getUserId()."
                        )
                    )
                );
        ";

        // On récupère sous forme de tableau les scénarios.
        $profils = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAll($query);

        // Si le nombre de scénarios est supérieur à zéro, on crée les objets associés.
        if(count($profils) > 0){

            /** @var EiProfil $profilArray */
            foreach( $profils as $profilArray ){
                $temp = new EiProfil();
                $temp->setArray($profilArray);

                $liste[] = $temp;
            }
        }

        return $liste;
    }
}