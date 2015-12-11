<?php
class sfActionsKalifast extends sfActions
{
    public function preExecute() {
        parent::preExecute();

        // Récupération de l'utilisateur.
        $this->guard_user = $this->getUser()->getGuardUser();

        if( $this->guard_user != null ){
            $this->ei_user = $this->guard_user->getEiUser();
        }
        else{
            $this->ei_user = null;
        }

        $requiredModules = array(
            "eicampaignexecution",
            "eitestset",
            "eilog"
        );

        // Mise à jour des statuts des campagnes/JDT.
        if( in_array($this->getModuleName(), $requiredModules) ){
            Doctrine_Core::getTable("EiTestSet")->closeUnterminatedTestSet();
        }

        if( $this->getModuleName() == "eicampaignexecution" ){
            Doctrine_Core::getTable("EiCampaignExecution")->closeUnterminatedTestSet();
        }
    }
    /*
     * On nettoie les variables de session du projet pour qu'elle ne nuisent pas
     *  Ã  une nouvelle tentative de connexion
     */

    public function cleanSessionVar(){
        //Variables de session projet
         $this->getUser()->setAttribute("current_project_ref", null);
         $this->getUser()->setAttribute("current_project_id",null); 
         //Variable de session profil
         $this->getUser()->setAttribute("current_profile_name",null);
         $this->getUser()->setAttribute("current_profile_ref",null);
         $this->getUser()->setAttribute("current_profile_id",null); 
    }
    /* Nettoyage des données de session d'une livraison */
    public function cleanDeliverySessionVar(){
        $this->getUser()->setAttribute("current_delivery_name", null);
        $this->getUser()->setAttribute("current_delivery_id",null);
        $this->getUser()->setAttribute("current_delivery_project_ref", null);
        $this->getUser()->setAttribute("current_delivery_project_id", null);
        $this->getUser()->setAttribute("current_delivery_profile_name", null);
        $this->getUser()->setAttribute("current_delivery_profile_id", null);
        $this->getUser()->setAttribute("current_delivery_profile_ref", null);
    }
    /* Nettoyage des données de session d'un subject */
    public function cleanSubjectSessionVar(){
        $this->getUser()->setAttribute("current_subject_name", null);
        $this->getUser()->setAttribute("current_subject_id",null);
        $this->getUser()->setAttribute("current_subject_project_ref", null);
        $this->getUser()->setAttribute("current_subject_project_id", null);
        $this->getUser()->setAttribute("current_subject_profile_name", null);
        $this->getUser()->setAttribute("current_subject_profile_id", null);
        $this->getUser()->setAttribute("current_subject_profile_ref", null);
    }
    /* Sauvegarde des données du profil dans la session utilisateur */
    public function setProfileSession($profile_name,$profile_id,$profile_ref){
        //Sauvegarde du profil en session utilisateur  
        if($profile_name!=null && $profile_id!=null && $profile_ref!=null):
        $this->getUser()->setAttribute("current_profile_name", $profile_name);
        $this->getUser()->setAttribute("current_profile_id", $profile_id);
        $this->getUser()->setAttribute("current_profile_ref", $profile_ref);
        endif;
    } 
    /* Ajout d'un livraison dans la session utilisateur */
    public function addDeliveryInUserSession(EiDelivery $ei_delivery){
        //On enregistre la livraison et le contexte d'enregistrement en session (profil) pour pouvoir retrouver l'objet dans les mêmes circonstances
        $this->getUser()->setAttribute("current_delivery_name", $ei_delivery->getName());
        $this->getUser()->setAttribute("current_delivery_id", $ei_delivery->getId());
        $this->getUser()->setAttribute("current_delivery_project_ref", $ei_delivery->getProjectRef());
        $this->getUser()->setAttribute("current_delivery_project_id", $ei_delivery->getProjectId());
        $this->getUser()->setAttribute("current_delivery_profile_name", $this->profile_name);
        $this->getUser()->setAttribute("current_delivery_profile_id", $this->profile_id);
        $this->getUser()->setAttribute("current_delivery_profile_ref", $this->profile_ref);
        
    }
    /* Ajout d'un sujet dans la session utilisateur */

    public function setInterventionAsDefault(EiSubject $ei_subject) { 
        $ei_subject->save();
        //$tabPackage=  $ei_subject->getPackage();
        
        $this->ei_subject=Doctrine_Core::getTable("EiSubject")->findOneById($ei_subject->getId());
        $this->ei_ticket =Doctrine_Core::getTable('EiTicket')->findOneByTicketIdAndTicketRef($this->ei_subject->getPackageId(),$this->ei_subject->getPackageRef());
        //On définit le package du sujet comme package courant 
             
            //On vérifie que le package par défaut du sujet est définit pour le user et le projet
            $defPack = Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByUserRefAndUserIdAndProjectRefAndProjectId(
                    $this->ei_user->getRefId(), $this->ei_user->getUserId(), $ei_subject->getProjectRef(), $ei_subject->getProjectId()); 
            
            Doctrine_Core::getTable('EiUserDefaultPackage')->setDefaultPackage(
                    $this->ei_subject->getPackageId(), $this->ei_subject->getPackageRef(), $this->ei_project, $this->ei_user, ($defPack == null ? 0 : 1)); 
    }
    /* Récupération du package par défaut d'un utilisateur */
    public function getDefaultPackage(EiUser $ei_user , EiProjet $ei_project){
        return Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByUserRefAndUserIdAndProjectRefAndProjectId(
                    $ei_user->getRefId(), $ei_user->getUserId(), $ei_project->getRefId(), $ei_project->getProjectId()); 
    }
    /* Récupération de l'intervention par défaut d'un utilisateur */
    public function getDefaultIntervention(EiUser $ei_user , EiProjet $ei_project, Doctrine_Connection $conn = null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        return $ei_user->getDefaultIntervention($ei_project); 
    }
    /* Récupération de l'intervention par défaut d'un utilisateur avec la liaison sur la version d'un scénario */
    public function getDefaultInterventionWithScenarioVersion(EiUser $ei_user , EiProjet $ei_project,EiVersion $ei_version ,Doctrine_Connection $conn = null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        return $ei_user->getDefaultInterventionWithScenarioVersion($ei_project,$ei_version); 
    }
    /* Cette fonction permet de rechercher les paramètres du projet(project_id et project_ref ).  */
    public function checkProjectParameters(sfWebRequest $request) {
        $this->project_id = $request->getParameter('project_id');
        $this->project_ref = $request->getParameter('project_ref');
      
        if ($this->project_id == null || $this->project_ref==null)  
        {
            //On vérifie les paramètres de projet 
            $this->project_ref=$this->getUser()->getAttribute("current_project_ref");
            $this->project_id=$this->getUser()->getAttribute("current_project_id");   
        }
    }
     
    /* Cette fonction permet de rechercher le projet (EiProjet) avec les paramètres renseignés.  */
    public function checkProject(sfWebRequest $request) { 
        $this->checkProjectParameters($request);
        if($this->project_id == null || $this->project_ref==null)
            $this->forward404 ('Project not found with theses parameters');
            $this->ei_project=Doctrine_Core::getTable('EiProjet')
                    ->findOneByProjectIdAndRefId($this->project_id,$this->project_ref);
            if($this->ei_project!=null && !is_bool($this->ei_project)){
                $this->project_name=$this->ei_project->getName();

                //On défine les variables de sessions permettant de naviguer au sein d'un projet
                $this->getUser()->setAttribute("current_project_ref", $this->ei_project->getRefId());
                $this->getUser()->setAttribute("current_project_id", $this->ei_project->getProjectId());

                //On vérifie que l'utilisateur a les droits nécessaires pour l'utilisation du projet

                //on vérifie que l'utilisateur est éligible à l'exploitation du projet identifié par project_ref et project_id
                $this->forward404Unless(Doctrine_Core::getTable('EiProjectUser')->getEiProjet(
                    $this->ei_project->getProjectId(), $this->ei_project->getRefId(), $this->getUser()->getGuardUser()->getEiUser()), "Acces denied.");
            }
            else{
                $this->forward404("Access denied");
            }
    }
    /* Cette fonction permet de rechercher le profil (EiProfil) avec les paramètres renseignés.  */

    public function checkProfile(sfWebRequest $request,  EiProjet $ei_project,$setProfileSession=true) {
        $this->profile_id = $request->getParameter('profile_id');
        $this->profile_ref = $request->getParameter('profile_ref');

        if ($this->profile_id != null && $this->profile_ref != null) {
            //Recherche du profil en base
            $this->ei_profile = Doctrine_Core::getTable('EiProfil')
                    ->findOneByProfileIdAndProfileRefAndProjectIdAndProjectRef(
                            $this->profile_id, $this->profile_ref,$ei_project->getProjectId(),$ei_project->getRefId());
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->ei_profile == null)
                $this->ei_profile = null;
            else {
                if($setProfileSession) //Si l'utilisateur décide de mettre le profil recherché en session
                    $this->setProfileSession ($this->ei_profile->getName(), $this->profile_id, $this->profile_ref);
                $this->profile_name=  MyFunction::sluggifyStr($this->ei_profile->getName());  
                //Définition du tableau de paramètres basique pour les différents objets
                $this->urlParameters = array(
                    'project_id' => $ei_project->getProjectId(),
                    'project_ref' => $ei_project->getRefId(),
                    'profile_id' => $this->profile_id,
                    'profile_ref' => $this->profile_ref,
                    'profile_name' => $this->profile_name);
            }
        }
        else {
            $this->profile_id = null;
            $this->profile_ref = null;
        }
    }
    //Recherche de la livraison
    public function checkDelivery(sfWebRequest $request, EiProjet $ei_project) {
        $this->delivery_id = $request->getParameter('delivery_id');
        if ($this->delivery_id == null)
            $this->forward404('Missing delivery parameters'); 
        //Recherche de la livraison tout en s'assurant qu'elle corresponde au projet courant 
        $this->ei_delivery = Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
                $this->delivery_id, $ei_project->getProjectId(), $ei_project->getRefId());
        if ($this->ei_delivery == null)
            $this->forward404('Delivery not found');
    }
    /* Recherche d'une itération de livraison */
    public function checkIteration(sfWebRequest $request){
        $this->iteration_id= $request->getParameter('iteration_id');
        if ($this->iteration_id == null)
            $this->forward404('Missing iteration  parameters'); 
         //Recherche de l'itération   
        $this->ei_iteration = Doctrine_Core::getTable('EiIteration')->findOneById($this->iteration_id);
        if ($this->ei_iteration == null)
            $this->forward404('Iteration not found ...');
    }
    //Recherche d'un scénario avec les paramètres de requête
    public function checkEiScenario(sfWebRequest $request, EiProjet $ei_project) {
        if (($this->ei_scenario_id = $request->getParameter('ei_scenario_id')) != null) {
            //Recherche du scénario en base
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')
                    ->findOneByIdAndProjectIdAndProjectRef(
                    $this->ei_scenario_id, $ei_project->getProjectId(), $ei_project->getRefId()); 
            //Si le scénario n'existe pas , alors on retourne un erreur 404
            if ($this->ei_scenario == null) {
                $message = 'Missing scenario ...';
                $request->setParameter('msg', $message);
                $request->setParameter('back_link', $request->getReferer());
                $this->forward('erreur', 'error404');
            }
        } else {
            $this->forward404('Missing scenario parameters  ...');
        }
    }
    /* Recherche d'une version courante de scénario */

    public function checkEiVersion(sfWebRequest $request, EiScenario $ei_scenario) {
        $this->ei_version_id = $request->getParameter('ei_version_id');
        if ($this->ei_version_id == null) :
            $this->ei_version = null;
        else:
            $this->ei_version = Doctrine_Core::getTable("EiVersion")->findOneByIdAndEiScenarioId($this->ei_version_id, $ei_scenario->getId());
        endif;
    }
    //Récupération des livraisons ouvertes d'un projet
    public function checkOpenDeliveries(EiProjet $ei_project){
        return Doctrine_Core::getTable('EiDelivery')->getOpenDeliveries($ei_project,10);
    }
    public function checkSubjectByCriteria(EiProjet $ei_project,Array $searchSubjectCriteria,$limit =null){
        $q=  Doctrine_Core::getTable('EiSubject')
                        ->sortSubjectByCriterias(Doctrine_Core::getTable('EiSubject')
                                ->getProjectSubjects($ei_project->getProjectId(), $ei_project->getRefId()), $searchSubjectCriteria);
        if($limit!=null) $q=$q->limit($limit);
         return $q->fetchArray();
    }
    /* Récupération des bugs à traiter de l'utilisateur courant par type "kalifast" dans la limite de 10 chacun */
    public function checkKalifastUserBugs(EiProjet $ei_project,Array $searchSubjectCriteria,$limit =null){  
        $searchSubjectCriteria['type']=array('0'=>Doctrine_Core::getTable('EiSubjectType')
                ->getSubjectTypeId($ei_project, 'Kalifast' )); 
        return $this->checkSubjectByCriteria($ei_project, $searchSubjectCriteria,$limit);  
    }
    /* Récupération des bugs à traiter de l'utilisateur courant par type "Defect" dans la limite de 10 chacun */
    public function checkDefectsUserBugs(EiProjet $ei_project,Array $searchSubjectCriteria,$limit =null){  
        $searchSubjectCriteria['type']=array('0'=>Doctrine_Core::getTable('EiSubjectType')
                ->getSubjectTypeId($ei_project, 'Defect' ));
        return $this->checkSubjectByCriteria($ei_project, $searchSubjectCriteria,$limit);  
    }
    /* Récupération des bugs à traiter de l'utilisateur courant par type "Service request" dans la limite de 10 chacun */
    public function checkServiceRequestUserBugs(EiProjet $ei_project,Array $searchSubjectCriteria,$limit =null){  
        $searchSubjectCriteria['type']=array('0'=>Doctrine_Core::getTable('EiSubjectType')
                ->getSubjectTypeId($ei_project, 'Service Request' ));
        return $this->checkSubjectByCriteria($ei_project, $searchSubjectCriteria,$limit);  
    } 
    /* Récupération des bugs à traiter de l'utilisateur courant par type "Enhancement" dans la limite de 10 chacun */
    public function checkEnhancementUserBugs(EiProjet $ei_project,Array $searchSubjectCriteria,$limit =null){ 
        $searchSubjectCriteria['type']=array('0'=>Doctrine_Core::getTable('EiSubjectType')
                ->getSubjectTypeId($ei_project, 'Enhancement' ));
        return $this->checkSubjectByCriteria($ei_project, $searchSubjectCriteria,$limit);  
     }  
    
    
    /* Cette fonction permet de rechercher la fonction (KalFunction) avec les paramètres renseignés.  */

    public function checkFunction(sfWebRequest $request,  EiProjet $ei_project) {
        $this->function_id = $request->getParameter('function_id');
        $this->function_ref = $request->getParameter('function_ref');

        if ($this->function_id != null || $this->function_ref != null) {
            //Recherche de la fonction en base
            $this->kal_function = Doctrine_Core::getTable('KalFunction')
                    ->findOneByFunctionIdAndFunctionRefAndProjectIdAndProjectRef(
                            $this->function_id,$this->function_ref,$ei_project->getProjectId(),$ei_project->getRefId());
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->kal_function == null)
                $this->kal_function = null;
        }
        else {
            $this->function_id = null;
            $this->function_ref = null;
        }
    }
    /* Cette fonction permet de rechercher le package (EiTicket) avec les paramètres renseignés.  */

    public function checkTicket(sfWebRequest $request,  EiProjet $ei_project) {
        $this->ticket_id = $request->getParameter('ticket_id');
        $this->ticket_ref = $request->getParameter('ticket_ref');

        if ($this->ticket_id != null || $this->ticket_ref != null) {
            //Recherche de la fonction en base
            $this->ei_ticket = Doctrine_Core::getTable('EiTicket')
                    ->findOneByTicketIdAndTicketRefAndProjectIdAndProjectRef(
                            $this->ticket_id,$this->ticket_ref,$ei_project->getProjectId(),$ei_project->getRefId());
            //Si le package n'existe pas , alors on retourne null
            if ($this->ei_ticket == null)
                $this->ei_ticket = null;
        }
        else {
            $this->ticket_id = null;
            $this->ticket_ref = null;
        }
    }
    /* Cette fonction permet de rechercher le package avec les paramètres renseignés.  */

    public function checkPackage(sfWebRequest $request,  EiProjet $ei_project) {
        $this->package_id = $request->getParameter('package_id');
        $this->package_ref = $request->getParameter('package_ref');

        if ($this->package_id != null || $this->package_ref != null) {
            //Recherche de la fonction en base
            $this->ei_package = Doctrine_Core::getTable('EiTicket')
                    ->findOneByTicketIdAndTicketRefAndProjectIdAndProjectRef(
                            $this->package_id,$this->package_ref,$ei_project->getProjectId(),$ei_project->getRefId());
            //Si le package n'existe pas , alors on retourne null
            if ($this->ei_package == null)
                $this->ei_package = null;
        }
        else {
            $this->package_id = null;
            $this->package_ref = null;
        }
    }

     

     //Recherche d'un sujet
    public function checkSubject(sfWebRequest $request, EiProjet $ei_project) {
        $this->subject_id = $request->getParameter('subject_id');         
        if ($this->subject_id == null)
            $this->forward404('Missing subject parameters');
        //Recherche du sujet tout en s'assurant qu'elle corresponde au projet courant 
        $this->ei_subject_with_relation = Doctrine_Core::getTable('EiSubject')
                ->getSubject($ei_project->getProjectId(), $ei_project->getRefId(), $this->subject_id);
        $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneById($this->subject_id);
        if ($this->ei_subject == null)
            $this->forward404('Subject not found');
    }
    
    //On recherche la  campagne
    public function checkCampaign(sfWebRequest $request, EiProjet $ei_project) {
        $this->campaign_id = $request->getParameter('campaign_id');

        if ($this->campaign_id == null):
            $this->forward404('Campaign parameter not found');
        else:
            $this->ei_campaign = Doctrine_Core::getTable('EiCampaign')
                ->findOneByIdAndProjectIdAndProjectRef(
                    $this->campaign_id, $ei_project->getProjectId(), $ei_project->getRefId());
            if ($this->ei_campaign == null):
                $this->forward404('Campaign not found');
            endif;
        endif;
    }

    /**
     * Méthode permettant de vérifier & recherche une exécution d'une campagne.
     *
     * @param sfWebRequest $request
     * @param EiProjet $ei_project
     * @param EiCampaign $ei_campaign
     */
    public function checkCampaignExecution(sfWebRequest $request, EiProjet $ei_project, EiCampaign $ei_campaign)
    {
        $this->campaign_execution_id = $request->getParameter('campaign_execution_id');

        if ($this->campaign_execution_id == null):
            $this->forward404('Campaign execution parameter not found');
        else:
            $this->campaign_execution = Doctrine_Core::getTable('EiCampaignExecution')->findExecution($this->campaign_execution_id);

            if ($this->campaign_execution == null):
                $this->forward404('Campaign execution not found');
            endif;
        endif;
    }

    /**
     * Méthode permettant de vérifier que le jeu de données est valide et concorde avec le scénario.
     *
     * A savoir :
     *    - scénario/jeu de données -> le second doit être contenu dans le premier
     *
     * @param sfWebRequest $request
     * @param EiScenario $scenario
     */
    public function checkEiDataSet(sfWebRequest $request, EiScenario $scenario){
        $this->ei_data_set_id = $request->getParameter("ei_data_set_id");

        // On vérifie que l'identifiant du JDD est bien renseigné.
        if( $this->ei_data_set_id != null ){
            // Puis, on récupère le jeu de données.
            $this->ei_data_set = Doctrine_Core::getTable("EiDataSet")->find($this->ei_data_set_id);

            // Et on vérifie que le jeu de données existe.
            if( $this->ei_data_set != null ){
                // Enfin, on vérifie que ces deux derniers sont cohérents
                if( !($this->ei_data_set->getEiNode()->getEiScenarioNode()->getObjId() == $scenario->getId()) ){
                    $this->forward404("Bad data set : It's not related to the input scenario");
                }
            }
            else{
                $this->forward404("Data set not found");
            }

        }
        else{
            $this->forward404('Missing data line parameters');
        }
    }

    /**
     * Méthode permettant de vérifier que tous les paramètres relatifs à une ligne d'un jeu de données concordent.
     *
     * A savoir :
     *    - jeu de données/ligne -> la ligne doit être un élément du jeu de données.
     *
     * @param sfWebRequest $request
     * @param EiDataSet $dataset
     */
    public function checkEiDataLine(sfWebRequest $request, EiDataSet $dataset){
        $this->ei_data_line_id = $request->getParameter("ei_data_line_id");

        // On vérifie que la ligne a bien été renseignée dans l'URL.
        if( $this->ei_data_line_id != null ){
            // Puis, on récupère la ligne dans la base.
            $this->ei_data_line = Doctrine_Core::getTable("EiDataLine")
                ->findOneByIdAndEiDataSetId($this->ei_data_line_id, $dataset->getId())
            ;

            // Si l'on a rien récupéré, c'est qu'il y a une incohérence entre le JDD & la ligne.
            if( $this->ei_data_line == null ){
                $this->forward404("Data line not found");
            }
        }
        else{
            $this->forward404('Missing data line parameters');
        }
    }

    /**
     * @return EiActiveIteration
     */
    public function getRequestActiveIteration(){
        // Récupération table des itérations actives.
        /** @var EiActiveIterationTable $tableActItr */
        $tableActItr = Doctrine_Core::getTable("EiActiveIteration");

        // Récupération de l'itération active selon le projet & profil.
        $iterationActive =  $tableActItr->getActiveIteration($this->ei_project, $this->ei_profile);

        return $iterationActive == null ? null:$iterationActive->getEiIteration();
    }
    
}
?>