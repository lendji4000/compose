<?php

/**
 * EiProjet
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifast
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiProjet extends BaseEiProjet {

    public function __toString() {
        return sprintf('%s', $this->getName());
    }
    //Fonction d'affichage d'un projet
    //Fonction temporaire permettant de recreer le dossier root du projet 
    public function createRootFolderIfNew(Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();

        try {
            $conn->beginTransaction();
            $root_folder = new EiFolder();
            $root_folder->setProjectId($this->getProjectId());
            $root_folder->setProjectRef($this->getRefId());
            $root_folder->setName('Root');
            $root_folder->save($conn);

            $root_node = new EiNode();
            $root_node->setProjectId($this->getProjectId());
            $root_node->setProjectRef($this->getRefId());
            $root_node->setObjId($root_folder->getId());
            $root_node->setName('Root');
            $root_node->setType('EiFolder');
            $root_node->setPosition(1);
            $root_node->setIsRoot(true);
            $root_node->setIsShortcut(false);
            $root_node->setRootId(Null);
            $root_node->save($conn);

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }

    /**
     * Récupère le nom tronqué du projet.
     * 
     */
    public function getTroncatedName($size = 10) {
        if ($size <= 0)
            throw new InvalidArgumentException('Invalid size value to troncate project name. ' . $size . ' is not a valid value.');

        if (strlen($this->getName()) > $size):
            return substr($this->getName(), 0, $size - 3) . '...';
        else:
            return $this->getName();
        endif;
    }

    //Récupération du dossier root du projet
    public function getRootFolder() {
        return Doctrine_Core::getTable('EiNode')->findOneByIsRootAndProjectIdAndProjectRefAndType(
                        true, $this->getProjectId(), $this->getRefId(), 'EiFolder');
    }
 

    public function save(Doctrine_Connection $conn = null) {

        $date = new DateTime();
        $date = $date->format('Y-m-d H:i:s');

        $this->setCheckedAt($date);
        $this->setUpdatedAt($date);

        if ($this->isNew()) {


            $conn = Doctrine_Manager::connection();
            try {
                $conn->beginTransaction();
                $ret = parent::save($conn);


                $conn->commit();

                return $ret;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
        }

        return parent::save($conn);
    }

    //Récupération du package root d'un projet

    public function getRootView() {
        return Doctrine_Core::getTable('EiView')->getRootView($this->getRefId(), $this->getProjectId());
    }

    //Transaction permettant de recharger tous les objets liés à un projet

    public function transactionToLoadObjectsOfProject($xmlfile,Doctrine_Connection $conn = null) {
        /* L'idée est de récupérer le contenu d'un projet morceaux par morceaux.
         Pour ce faire, on récupère les fonctions comprises dans un intervalle de delta du projet.
         Le delta du projet récupéré su compose n'est donc pas forcement le delta de la plate forme centrale mais 
         le delta de la dernière fonction récupérée.
        */  
            if ($conn == null) $conn = Doctrine_Manager::connection();
            try {

                $conn->beginTransaction();
                $ret = $this->ChargerFonctions($xmlfile, $conn);
                $this->save($conn);
                $conn->commit();

                return $ret;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } 
        
    }
    
    /* Récupération des commandes sélénium d'un projet par webservice
     * On intérroge le système script et on renvoie les données à l'ide 
     */
    public function getSeleneseCmds (){
        $url=MyFunction::getPrefixPath(null) . 
                "serviceweb/". $this->getProjectId() . 
                "/" . $this->getRefId() . 
                "/listSeleneseCmds.json";
        //Appel du webservice
            $cobj = curl_init($url); // créer une nouvelle session cURL
        if ($cobj) {
            curl_setopt($cobj,CURLOPT_URL,$url);
            curl_setopt($cobj, CURLOPT_RETURNTRANSFER, 1); //définition des options
            $json = curl_exec($cobj); //execution de la requete curl
            curl_close($cobj); //liberation des ressources
            return  json_decode($json, true);
        }
  
    }

    public function getProfils() {
       return Doctrine_Core::getTable('EiProfil')
                ->findByProjectIdAndProjectRef($this->getProjectId(), $this->getRefId()); 
    }
    
    /* Récupération de toutes les campagnes du projet */
    public function getAllProjectCampaigns(Doctrine_Connection $conn = null){
        if ($conn == null) $conn = Doctrine_Manager::connection();
        return Doctrine_Core::getTable('EiCampaign')->getAllProjectCampaigns($this->getProjectId(),$this->getRefId());
    }
    
    public function getScriptVersion($username) {
        $script = new DOMDocument();

        //Appel du webservice
        $scriptVersion = ServicesWeb::loadResultOfWebService(
                        MyFunction::getPrefixPath(null) .
                        "serviceweb/" . $username .
                        "/" . $this->getProjectId() .
                        "/" . $this->getRefId() .
                        "/versionProjet.xml");
        //Récupération du projet pour traitement
        if ($scriptVersion == null)
            return null;
        $script->loadXML($scriptVersion);
        $script->save('projectVersion.xml'); /* sauvegarde du fichier pour vérifier le bon fonctionnement du web service */
        return $script->documentElement;
    }

    /**
     * Appel le webservice ScriptVersion et parse le résultat afin d'obtenir le numéro
     * de version du côté de script.kalifast.
     * 
     * Si le fichier XML est mauvais, on retourne null.
     * 
     * @return null ou numéro de version côté script.kalifast
     */
    public function getScriptNumVersion($username = null) {
        if ($username == null)
            $username = MyFunction::getGuard()->getUsername();
        if ($username == null)
            return null;
        $scriptVersion = $this->getScriptVersion($username);
        if ($scriptVersion == null)
            return null;

        else
        if ($scriptVersion->getElementsByTagName("project_ref")->item(0) != null && $scriptVersion->getElementsByTagName("project_id")->item(0) != null &&
                $scriptVersion->getElementsByTagName("version")->item(0) != null) {

            if ($this->getProjectId() == $scriptVersion->getElementsByTagName("project_id")->item(0)->nodeValue && $this->getRefId() == $scriptVersion->getElementsByTagName("project_ref")->item(0)->nodeValue)
                return $scriptVersion->getElementsByTagName("version")->item(0)->nodeValue;
            else
                throw new Exception('Mauvais projet récupéré');
        }
        elseif (!$scriptVersion->getElementsByTagName("error"))
            throw new Exception('Fichier XML erroné');

        return null;
    }

    public function downloadKalFonctions($request=null) { 
        $syst_domain = str_replace('.', '___', sfConfig::get('project_system_uri'));
        return ServicesWeb::loadResultOfWebService(MyFunction::getPrefixPath() . "serviceweb/" . $this->getVersionCourante() .
                        '/' . $this->getProjectId() . "/" . $this->getRefId() . "/" . $syst_domain . "/listFonctions.xml");
        
    }

    public function ChargerFonctions($xmlfile, Doctrine_Connection $conn = null) { 
        /* Tous les objets vont etre recharger sur le projet courant. 
         * Du coup on récupère l'id et le ref du projet
         */
        $project_id=$this->getProjectId(); 
        $project_ref=$this->getRefId();
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        $dom = new DomDocument();
        if ($xmlfile != null) {
            $dom->loadXML($xmlfile);
            $dom->save('projet.xml'); /* A utiliser en cas de deboguage pour visualiser le contenu du fichier */
            //recherche de l'element racine <projets>
            if ($dom->documentElement) {
                $projets = $dom->documentElement;
                //rechargement des données du projet
                //si le projet a été trouvé
                if ($projets->getElementsByTagName("info")->item(0)) {
                    MyFunction::rechargerProjet($projets->getElementsByTagName("info")->item(0), true, $conn);

                    //Création des tables temporaires pour opérations d'insert - delete
                    //Table temporaire des fonctions
                    Doctrine_Core::getTable('ScriptEiFunction')->insertTmpData($projets,$project_id, $project_ref, $conn);
                    //Table temporaire des dossiers de fonctions (EiView)
                    Doctrine_Core::getTable('ScriptEiView')->insertTmpData($projets,$project_id, $project_ref, $conn);
                    //Table temporaire des scripts 
                    Doctrine_Core::getTable('ScriptEiScript')->insertTmpData($projets,$project_id, $project_ref, $conn);

                    //Table temporaire des relations script-profil
                    Doctrine_Core::getTable('ScriptEiScriptVersion')->insertTmpData($projets,$project_id, $project_ref, $conn);

                    //Table temporaire des notices
                    Doctrine_Core::getTable('ScriptEiNotice')->insertTmpData($projets,$project_id, $project_ref, $conn);

                    //Table temporaire des profils de notice
                    Doctrine_Core::getTable('ScriptEiNoticeProfil')->insertTmpData($projets,$project_id, $project_ref, $conn);

                    //Table temporaire des versions de notice
                    Doctrine_Core::getTable('ScriptEiVersionNotice')->insertTmpData($projets,$project_id, $project_ref, $conn);

                    //Rechargement des relations version_notice-profil du projet 
                    Doctrine_Core::getTable('EiNoticeProfil')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des  versions de notice d'un projet 
                    Doctrine_Core::getTable('EiVersionNotice')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des  notice d'un projet
                    //$this->getNotices()->delete($conn);
                    Doctrine_Core::getTable('EiNotice')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des liaisons utilisateurs-tickets
                    $this->getUserTickets()->delete($conn);
                    //Doctrine_Core::getTable('EiUserTicket')->deleteUserTicketForProject($project_id, $project_ref , $conn);
                    Doctrine_Core::getTable('EiUserTicket')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des paramètres de profil
                    //$this->getProjectProfileParams()->delete($conn);
                    Doctrine_Core::getTable('EiProfileParam')->deleteProjectProfileParams($project_id, $project_ref, $conn);
                    Doctrine_Core::getTable('EiProfileParam')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des paramètres d'une fonction 
                    Doctrine_Core::getTable('EiFunctionHasParam')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des liaisons script-profil des fonctions d'un projet 
                    Doctrine_Core::getTable('EiScriptVersion')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des scripts de fonction  
                    Doctrine_Core::getTable('EiScript')->reload($projets,$project_id, $project_ref, $conn);


                    //Rechargement des fonctions (KalFunction pour ne pas créer d'ambiguité avec EiFonction)
                    Doctrine_Core::getTable('KalFunction')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des commandes de fonction d'un projet 
                    Doctrine_Core::getTable('EiFunctionHasCommande')->reload($projets,$project_id, $project_ref, $conn);
                    //Rechargement des vues du projet 
                    //Doctrine_Core::getTable('EiView')->deleteProjectViews($project_id, $project_ref , $conn);
                    Doctrine_Core::getTable('EiView')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des profils 
                    //Doctrine_Core::getTable('EiProfil')->deleteProjectProfiles($project_id, $project_ref , $conn );
                    Doctrine_Core::getTable('EiProfil')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des tickets 
                    Doctrine_Core::getTable('EiTicket')->deleteProjectTickets($project_id, $project_ref , $conn );
                    Doctrine_Core::getTable('EiTicket')->reload($projets,$project_id, $project_ref, $conn);

                     
                    //récupération des noeuds ouverts pour les garder ouverts.
                    $nodes = $this->getAllProjectNodes();
                    $openNodes = array();
                    foreach($nodes as $i => $n){
                        $opened = $n->getEiTreeOpenedByUsers();
                        
                        if($opened->count() > 0){
                            $openNodes[$n->getId()] = $opened;
                        }
                    }
                     
                    //Doctrine_Core::getTable('EiTree')->deleteProjectNodes($project_id, $project_ref ,$conn);
                    //Rechargement de l'arbre 
                    Doctrine_Core::getTable('EiTree')->reload($projets,$project_id, $project_ref, $conn, $openNodes);

                    //Rechargement des langues du projet 
                    Doctrine_Core::getTable('EiProjectLang')->deleteProjectLangs($project_id, $project_ref , $conn );
                    Doctrine_Core::getTable('EiProjectLang')->reload($projets,$project_id, $project_ref, $conn);

                    //Rechargement des paramètres globaux du projet 
                    Doctrine_Core::getTable('EiProjectParam')->deleteProjectParams($project_id, $project_ref ,$conn);
                    Doctrine_Core::getTable('EiProjectParam')->reload($projets,$project_id, $project_ref, $conn);

                    //Suppression des fonctions inactives
                    Doctrine_Core::getTable('KalFunction')->deleteInactiveFunctions($this, $conn);
                    //Création des status par défaut du projet
                    $this->createDefaultStates($conn);
                    
                }
            }
            //sinon on quite la fonction
            else {
                $d = date('Y-m-d H:i:s');
                $this->setObsolete(true);
                $this->setCheckedAt($d);
                $this->save();
            }
        }
        return null;
    }

    /*
     * Retourne vrai si le projet a besoin d'être recharghé
     * Faux sinon.
     */

    public function needsReload($username = null) {
        /* Le username est passé en paramètre à la fonction dans le cas ou la requête provient de l'IDE.
          A ce moment , l'utilisateur n'est pas forcement connecté */
        try {
            $numVersion = $this->getScriptNumVersion($username);
            if ($numVersion != null && $numVersion != $this->getVersionCourante()) 
                return true;
            else
                return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getEiUsers() {
        Doctrine_Core::getTable('EiProjectUser')->findByProjectId($this->id);
    }
    /* Bugs par utilisateurs d'un projet */
    public function getUserBugs(Doctrine_Connection $conn = null){
        if ($conn == null) $conn = Doctrine_Manager::connection(); 
        return $conn->fetchAll("select concat(vw.st_id,vw.g_id) as user_bug_id, vw.*
                  from ei_user_bugs_vw vw where st_project_id=".$this->getProjectId()." and st_project_ref=".$this->getRefId());
    }
    /* Utilisateurs du projet */
    public function getProjectUsers(Doctrine_Connection $conn = null){
        if ($conn == null) $conn = Doctrine_Manager::connection(); 
        return $conn->createQuery()->from('sfGuardUser g ')
                        ->where('EiUser.guard_id=g.id')
                        ->AndWhere('EiProjectUser.user_id=EiUser.user_id And EiProjectUser.user_ref=EiUser.ref_id')
                        ->AndWhere('EiProjectUser.project_ref=? AND EiProjectUser.project_id=?',
                                array($this->getRefId(), $this->getProjectId()))
                        ->execute();
    }

    /**
     * Retourne la requête récupérant les profils d'un projet.
     * @return Doctrine_Query 
     */
    public function getProfilsQuery() {
        return Doctrine_Core::getTable('EiProfil')->createQuery('pfil')
                        ->where('pfil.project_id=? AND pfil.project_ref=?', array($this->getProjectId(), $this->getRefId()));
    }

    public function getScenariosQuery() {
        return Doctrine_Core::getTable('EiScenario')->createQuery('sc')
                        ->where('sc.project_id=? And sc.project_ref=?', array($this->getProjectId(), $this->getRefId()));
    }

    public function getScenarios() {
        return Doctrine_Core::getTable('EiScenario')->findByProjectIdAndProjectRef($this->getProjectId(), $this->getRefId());
    }

    /**
     * Récupère le profil par défaut du projet
     * 
     * @return EiProfil 
     */
    public function getDefaultProfil() {
        return $this->getProfilsQuery()->andWhere('pfil.is_default = ?', true)->execute()->getFirst();
    }

    //Récupération des langues du projet
    public function getProjectLangs() {
        return Doctrine_Core::getTable('EiProjectLang')->findByProjectIdAndProjectRef(
                        $this->getProjectId(), $this->getRefId());
    }

    //Récupération des paramètres globaux définies sur le projet

    public function getGlobalParams() {
        return Doctrine_Core::getTable('EiProjectParam')->findByProjectIdAndProjectRef(
                        $this->getProjectId(), $this->getRefId());
    }

    //Récupération de tous les éléments d'arbre du projet (vues, raccourci et fonctions)
    public function getAllProjectNodes() {
        return Doctrine_Core::getTable('EiTree')->findByProjectIdAndProjectRef(
                        $this->getProjectId(), $this->getRefId());
    }

    //Récupération des vues d'un projet
    public function getViews() {
        return Doctrine_Core::getTable('EiView')->findByProjectIdAndProjectRef(
                        $this->getProjectId(), $this->getRefId());
    }

    //Récupération des fonctions d'un projet
    public function getFunctions() {
        return Doctrine_Core::getTable('KalFunction')->findByProjectIdAndProjectRef(
                        $this->getProjectId(), $this->getRefId());
    }

    public function getTickets() {
        //Récuppération des tickets d'un projet
        return Doctrine_Core::getTable('EiTicket')->findByProjectIdAndProjectRef($this->getProjectId(), $this->getRefId());
    }

    public function getProjectParams() {
        return Doctrine_Query::create()->from('EiFunctionHasParam p ')
                        ->where('KalFunction.function_ref=p.function_ref And KalFunction.function_id=p.function_id')
                        ->AndWhere('EiProjet.ref_id=KalFunction.project_ref And EiProjet.project_id=KalFunction.project_id')
                        ->AndWhere('EiProjet.ref_id=? AND EiProjet.project_id=?', array($this->getRefId(), $this->getProjectId()))
                        ->execute();
    }

    public function getProjectProfileParams() {
        return Doctrine_Query::create()->from('EiProfileParam p ')
                        ->where('EiProfil.profile_ref=p.profile_ref And EiProfil.profile_id=p.profile_id')
                        ->AndWhere('EiProjet.ref_id=EiProfil.project_ref And EiProjet.project_id=EiProfil.project_id')
                        ->AndWhere('EiProjet.ref_id=? AND EiProjet.project_id=?', array($this->getRefId(), $this->getProjectId()))
                        ->execute();
    }

    public function getUserTickets() {
        return Doctrine_Query::create()->from('EiUserTicket t ')
                        ->where('EiTicket.ticket_ref=t.ticket_ref And EiTicket.ticket_id=t.ticket_id')
                        ->AndWhere('EiProjet.ref_id=EiTicket.project_ref And EiProjet.project_id=EiTicket.project_id')
                        ->AndWhere('EiProjet.ref_id=? AND EiProjet.project_id=?', array($this->getRefId(), $this->getProjectId()))
                        ->execute();
    }

    public function getScripts() {
        return Doctrine_Query::create()->from('EiScript s ')
                        ->where('KalFunction.function_ref=s.function_ref And KalFunction.function_id=s.function_id')
                        ->AndWhere('EiProjet.ref_id=KalFunction.project_ref And EiProjet.project_id=KalFunction.project_id')
                        ->AndWhere('EiProjet.ref_id=? AND EiProjet.project_id=?', array($this->getRefId(), $this->getProjectId()))
                        ->execute();
    }

    public function getCommands() {
        return Doctrine_Query::create()->from('EiFunctionHasCommande c ')
                        ->where('KalFunction.function_ref=c.function_ref And KalFunction.function_id=c.function_id')
                        ->AndWhere('EiProjet.ref_id=KalFunction.project_ref And EiProjet.project_id=KalFunction.project_id')
                        ->AndWhere('EiProjet.ref_id=? AND EiProjet.project_id=?', array($this->getRefId(), $this->getProjectId()))
                        ->execute();
    }

    public function getScriptVersions() {
        //Récuppération des relations script-profil sur des fonctions d'un projet
        return Doctrine_Core::getTable('EiScriptVersion')->findByProjectIdAndProjectRef($this->getProjectId(), $this->getRefId());
    }

    public function getNotices() {
        //Récuppération des notices d'un projet
        return Doctrine_Query::create()->from('EiNotice n')
                        ->where('KalFunction.function_ref=n.function_ref And KalFunction.function_id=n.function_id')
                        ->AndWhere('EiProjet.ref_id=KalFunction.project_ref And EiProjet.project_id=KalFunction.project_id')
                        ->AndWhere('EiProjet.ref_id=? AND EiProjet.project_id=?', array($this->getRefId(), $this->getProjectId()))
                        ->execute();
    }

    public function getNoticeVersions() {
        //Récuppération des versions de notice  d'un projet
        return Doctrine_Query::create()->from('EiVersionNotice vn')
                        ->where('EiNotice.notice_ref=vn.notice_ref And EiNotice.notice_id=vn.notice_id')
                        ->AndWhere('KalFunction.function_id=EiNotice.function_id And KalFunction.function_ref=EiNotice.function_ref')
                        ->AndWhere('EiProjet.ref_id=KalFunction.project_ref And EiProjet.project_id=KalFunction.project_id')
                        ->AndWhere('EiProjet.ref_id=? AND EiProjet.project_id=?', array($this->getRefId(), $this->getProjectId()))
                        ->execute();
    }

    public function getNoticeProfiles() {
        //Récuppération des relations version_notice-profil  des notice d'un projet
        return Doctrine_Query::create()->from('EiNoticeProfil np')
                        ->where('EiProfil.profile_ref=np.profile_ref And EiProfil.profile_id=np.profile_id')
                        ->AndWhere('EiProjet.ref_id=EiProfil.project_ref And EiProjet.project_id=EiProfil.project_id')
                        ->AndWhere('EiProjet.ref_id=? AND EiProjet.project_id=?', array($this->getRefId(), $this->getProjectId()))
                        ->execute();
    }

/*
 *    Gestion des livraisons 
 */ 
    //Création des statuts  (de livraison, de taches, de sujet  ) s'ils n'existent pas encore
    public function createDefaultStates(Doctrine_Connection $conn = null){
        if ($conn == null) $conn = Doctrine_Manager::connection();
        
        try {

            $conn->beginTransaction();  
            //Création des statuts par défaut du projet
             $this->getTable()->createDefaultStates($this->getProjectId(),$this->getRefId(),$conn); 
            $conn->commit();
 
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
         
    }
    
    //Création des step type par défaut pour une campagne de test
    public function createDefaultStepTypeCampaign(){
    $this->getTable()->createDefaultStepTypeCampaign($this->getProjectId(),$this->getRefId());   
    }
    
    //Récupération des livraisons paginées
    public function getPaginateDelivery($first_entry,$max_delivery_per_page,$searchDeliveryCriteria) {
        $conn = Doctrine_Manager::connection();
       return  $this->getTable()
                ->getPaginateDelivery($this->getProjectId(),$this->getRefId(),$first_entry,$max_delivery_per_page,$searchDeliveryCriteria,$conn);
        
    }
    //Récupération des sujets paginées
    public function paginateSubjects($first_entry,$max_subject_per_page,$searchSubjectCriteria) {
        $conn = Doctrine_Manager::connection();
       return  $this->getTable()->paginateSubjects(
               $this->getProjectId(),$this->getRefId(),$first_entry,$max_subject_per_page,$searchSubjectCriteria,$conn);
        
    }
    //Récupération des campagnes de tests paginées
    public function getPaginateCampaigns($first_entry,$max_campaign_per_page,$searchCampaignCriteria) {
        $conn = Doctrine_Manager::connection();
       return  $this->getTable()->paginateCampaigns(
               $this->getProjectId(),$this->getRefId(),$first_entry,$max_campaign_per_page,$searchCampaignCriteria,$conn);
        
    }
    //Récupération des campagnes de tests paginées (liste des campagnes n'apparaissant dans aucune livraison et aucun bug(sujet))
    public function getPaginateCampaignsList($first_entry,$max_campaign_per_page,$searchCampaignCriteria) {
        $conn = Doctrine_Manager::connection();
       return  $this->getTable()->paginateCampaignsList(
               $this->getProjectId(),$this->getRefId(),$first_entry,$max_campaign_per_page,$searchCampaignCriteria,$conn);
        
    }
    
}