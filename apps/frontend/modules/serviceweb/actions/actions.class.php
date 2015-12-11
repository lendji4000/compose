<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of actionsclass
 *
 * @author eisge
 */
class servicewebActions extends sfActions
{
    /** @var EiUser */
    private $user;

    /** @var string  */
//    private $login = "";

    /**
     * Méthode appelée avant chaque appel d'action.
     */
    public function preExecute()
    {
        /** @var EiUserTable $table */
        $table = Doctrine_Core::getTable('EiUser');
        $this->token = $this->getRequest()->getParameter("token");
        $this->user = $table::getInstance()->getUserByTokenApi($this->token);

        $this->forward404If(is_bool($this->user) && $this->user === false, "You are not allowed to access this page." );

        // On récupère le nom d'utilisateur ainsi que le mot de passe pour les intéractions avec script.
        $this->login = $this->user->getGuardUser()->getUsername();

        // On authentifie l'utilisateur s'il ne l'est pas.
        if( MyFunction::getGuard() == null )
            $this->getUser()->signIn($this->user->getGuardUser(), true);
    }

    /* Cette fonction permet de rechercher la fonction (EiFonction) avec les paramètres renseignés.  */

    public function checkFunction(sfWebRequest $request) {
        $this->function_id = $request->getParameter('function_id');

        if ($this->function_id != null) {
            //Recherche de la fonction en base
            $this->ei_fonction = Doctrine_Core::getTable('EiFonction')
                    ->findOneById($this->function_id)
            ;
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->ei_fonction == null)
                $this->ei_fonction = null;
        }
        else {
            $this->function_id = null;
        }
    }

    /* Cette fonction permet de rechercher la fonction (KalFunction) avec les paramètres renseignés.  */

    public function checkKalFunction(sfWebRequest $request) {
        $this->function_id = $request->getParameter('function_id');
        $this->function_ref = $request->getParameter('function_ref');

        if ($this->function_id != null && $this->function_ref != null) {
            //Recherche de la fonction en base
            $this->kal_function = KalFunctionTable::getInstance()
                    ->findOneByFunctionRefAndFunctionId($this->function_ref, $this->function_id);
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->kal_function == null)
                $this->kal_function = null;
        }
        else {
            $this->function_id = null;
            $this->function_ref = null;
        }
    }

    /* Cette fonction permet de rechercher le profil (EiProfil)
     * avec les paramètres renseignés en s'assurant que le profil est un objet du projet.
     */

    public function checkProfileOfProject(sfWebRequest $request, EiProjet $ei_project) {
        $this->profile_id = $request->getParameter('profile_id');
        $this->profile_ref = $request->getParameter('profile_ref');
        if ($ei_project == null || $this->profile_id == null || $this->profile_ref == null):
            $this->ei_profile = null;
            $this->profile_id = null;
            $this->profile_ref = null;
            return 0;
        endif;
        //Recherche du profil en base
        $this->ei_profile = Doctrine_Core::getTable('EiProfil')
                ->findOneByProfileIdAndProfileRefAndProjectIdAndProjectRef(
                $this->profile_id, $this->profile_ref, $ei_project->getProjectId(), $ei_project->getRefId());
    }

    /* Cette fonction permet de rechercher le package(ticket) (EiProfil) 
     * avec les paramètres renseignés en s'assurant que le ticket est un objet du projet.
     */

    public function checkTicketOfProject(sfWebRequest $request, EiProjet $ei_project) {
        $this->ticket_id = $request->getParameter('ticket_id');
        $this->ticket_ref = $request->getParameter('ticket_ref');
        if ($ei_project == null || $this->ticket_id == null || $this->ticket_ref == null):
            $this->ei_ticket = null;
            $this->ticket_id = null;
            $this->ticket_ref = null;
            return 0;
        endif;
        //Recherche du ticket en base
        $this->ei_ticket = Doctrine_Core::getTable('EiTicket')
                ->findOneByTicketIdAndTicketRefAndProjectIdAndProjectRef(
                $this->ticket_id, $this->ticket_ref, $ei_project->getProjectId(), $ei_project->getRefId());
    }

    /* Cette fonction permet de rechercher le profil (EiProfil) avec les paramètres renseignés.  */

    public function checkProfile(sfWebRequest $request) {
        $this->profile_id = $request->getParameter('profile_id');
        $this->profile_ref = $request->getParameter('profile_ref');

        if ($this->profile_id != null && $this->profile_ref != null) {
            //Recherche du profil en base
            $this->ei_profile = Doctrine_Core::getTable('EiProfil')
                    ->findOneByProfileIdAndProfileRef($this->profile_id, $this->profile_ref);
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->ei_profile == null)
                $this->ei_profile = null;
        }
        else {
            $this->profile_id = null;
            $this->profile_ref = null;
        }
    }

    /* Cette fonction permet de rechercher le projet (EiProjet) avec les paramètres renseignés.  */

    public function checkProject(sfWebRequest $request) {
        $this->project_id = $request->getParameter('project_id');
        $this->project_ref = $request->getParameter('project_ref');

        if ($this->project_id != null && $this->project_ref != null) {
            //Recherche du profil en base
            $this->ei_project = Doctrine_Core::getTable('EiProjet')
                    ->findOneByProjectIdAndRefId($this->project_id, $this->project_ref);
        } else {
            $this->project_id = null;
            $this->project_ref = null;
        }
    }

    /* Recherche d'un scénario avec les paramètres d'url */

    public function checkScenario($scenario_id, EiProjet $ei_project) {
        $this->scenario_id = $scenario_id;
        if ($this->scenario_id == null || $ei_project == null) :
            $this->ei_scenario = null;
            return 0;
        endif;
        //Recherche du profil en base

        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->findOneByIdAndProjectIdAndProjectRef(
                $this->scenario_id, $ei_project->getProjectId(), $ei_project->getRefId());
        return 1;
    }

    /* Recherche d'un jeu de test  avec le sceénario et l'id du jeu de test en question */

    public function checkJdtWithScenario($ei_test_set_id, EiScenario $ei_scenario = null) {
        $this->ei_test_set_id = $ei_test_set_id;
        if ($ei_scenario == null || $this->ei_test_set_id == null):
            $this->ei_test_set_id = null;
            $this->ei_test_set = null;
            return 0;
        endif;
        //Recherche du profil en base
        $this->ei_test_set = Doctrine_Core::getTable('EiTestSet')->findOneByIdAndEiScenarioId(
                $this->ei_test_set_id, $ei_scenario->getId());
        return 1;
    }

    /*
     * Recherche d'une fonction d'un jeu de test par sa position , le scénario et le jeu de test 
     */

    public function checkFunctionWithScenarioAndJdt($position, EiScenario $ei_scenario = null, EiTestSet $ei_test_set = null) {
        $this->position = $position;
        if ($ei_scenario == null || $ei_test_set == null || $this->position == null):
            $this->ei_fonction_id = null;
            $this->ei_fonction = null;
            return 0;
        endif;
        //Recherche du profil en base
        $this->ei_fonction = Doctrine_Core::getTable('EiFonction')->getFunctionByScenarioTestSetAndPosition(
                $position, $ei_scenario, $ei_test_set);
        return 1;
    }

    /* Récupération des commandes sélénium d'un projet */

    public function executeSeleneseProjectCmds(sfWebRequest $request) {
        $this->checkProject($request);
        $this->seleneseCmds = $this->ei_project->getSeleneseCmds();
    }

    /*
     * Rechargement d'un projet lors d'une transaction par webservice si necessaire
     */

    public function reloadProjectIfNecessery(sfWebRequest $request, EiProjet $ei_project = null) {
        if ($ei_project == null)
            return null;

        if ($ei_project->needsReload($this->login)) {
            $xml = $ei_project->downloadKalFonctions($request);
            if ($xml != null)
                $ei_project->transactionToLoadObjectsOfProject($xml);
        }
    }

    /* Recherche d'un noeud de l'arbre de scénarios */

    public function checkNode(sfWebRequest $request) {
        $this->node_id = $request->getParameter('node_id');

        if ($this->node_id) {
            //Recherche du noeud en base
            $this->ei_node = Doctrine_Core::getTable('EiNode')->findOneById($this->node_id);
            //Si le noeud n'existe pas
            if ($this->ei_node == null)
                $this->ei_node = null;
        }
        else {
            $this->node_id = null;
        }
    }

    //Web service de retour des profils d'un projet

    public function executeListProfil(sfWebRequest $request) {
        $project_id = $request->getParameter('project_id');
        $project_ref = $request->getParameter('project_ref');

        $this->profils = Doctrine_Core::getTable('EiProfil')->findByProjectIdAndProjectRef($project_id, $project_ref);
    }

//Récupération des scripts d'une fonction pour l'ide sélénium afin de modifier ces dernières
    public function executeListScripts(sfWebRequest $request) {
        //Tout s'est bien passé . du coup on recupère les paramètres du projet
        $this->checkProject($request);
        if (!$this->ei_project || $this->ei_project == null):
            $this->error = "Project not found with theses parameters ... ";
        else:
            //Rechargement du projet si nécessaire
            $this->reloadProjectIfNecessery($request, $this->ei_project);
            $this->checkProfileOfProject($request, $this->ei_project); //Récupération du profil
            //On Récupère le scenario , le jeu de test et la fonction
            $this->checkScenario($request->getParameter('scenario_id'), $this->ei_project);
            $this->checkJdtWithScenario($request->getParameter('jdt_id'), $this->ei_scenario);
            $this->checkFunctionWithScenarioAndJdt($request->getParameter('position'), $this->ei_scenario, $this->ei_test_set);
            if ($this->ei_scenario != null && $this->ei_test_set != null && $this->position != null && $this->ei_fonction != null && $this->ei_profile != null):
                //Récupération des commandes de la fonction concernée
                $this->cmds = $this->ei_fonction->getCmdsForProfile($this->ei_profile);
            else:
                $this->error = "Scenario or Test set or function or environment  not found ...";
            endif;
        endif;
    }

    //Vérification des paramètres de traitement d'un script à partir de données recu de l'IDE.
    public function checkParameterForScriptProcess(sfWebRequest $request) {
        //Tout s'est bien passé . du coup on recupère les paramètres du projet
        $this->checkProject($request);
        if (!$this->ei_project || $this->ei_project == null):
            $this->error = "Project not found with theses parameters ... ";
        else:
            //Rechargement du projet si nécessaire
            $this->reloadProjectIfNecessery($request, $this->ei_project);
            $this->checkProfileOfProject($request, $this->ei_project); //Récupération du profil
            $this->checkTicketOfProject($request, $this->ei_project); //Récupération du ticket(package) 
            if ($this->ei_ticket == null):
                $this->error = "Ticket not found with theses parameters...";
            endif;
        endif;
    }

    //Récupération du fichier json contenant les lignes de commande modifiées d'un script afin de les mettre à jour 
    public function executeUpdateScript(sfWebRequest $request) {
                
        $this->checkParameterForScriptProcess($request);
        if (!$this->error):
            //On Récupère le scenario , le jeu de test et la fonction  
            $this->checkScenario($request->getParameter('scenario_id'), $this->ei_project);
            $this->checkJdtWithScenario($request->getParameter('jdt_id'), $this->ei_scenario);
            $this->checkFunctionWithScenarioAndJdt($request->getParameter('position'), $this->ei_scenario, $this->ei_test_set);
            if ($this->ei_scenario != null && $this->ei_test_set != null && $this->position != null && $this->ei_fonction != null && $this->ei_profile != null):
                $this->kal_fonction = $this->ei_fonction->getKalFonction();
                //Récupération du script de la fonction concernée en utilisant la package et les ids de la fonction 
                $this->ei_script = Doctrine_Core::getTable('EiScript')->findOneByFunctionIdAndFunctionRefAndTicketIdAndTicketRef(
                        $this->kal_fonction->getFunctionId(), $this->kal_fonction->getFunctionRef(), $this->ei_ticket->getTicketId(), $this->ei_ticket->getTicketRef());
                $this->json_string = $request->getParameter('json_string');
                if ($this->ei_script != null):
                    $this->result = EiScript::getScriptUpdateResult($this->ei_project, $this->ei_profile, $this->ei_ticket, $this->json_string, $this->kal_fonction,$this->user, $this->ei_script->getScriptId());
                     
                else :   //Le script n'existe pas pour le ticket (package) et la fonction, on le crée  
                    $this->result = EiScript::getScriptUpdateResult($this->ei_project, $this->ei_profile, $this->ei_ticket, $this->json_string, $this->kal_fonction,$this->user,0);
                endif; 
                
                if ($this->result["success"]):  
                    //Association de la fonction modifiées au bugs du package
                    $packageBugs = $this->ei_ticket->getEiSubjects();
                    if (count($packageBugs) > 0):
                        foreach ($packageBugs as $bug):
                            EiSubjectFunctionsTable::createNewLink($this->kal_fonction, $bug, true);
                        endforeach;
                    endif;
                   // Si le process réussit , on recharge le projet
//                    $xml = $this->ei_project->downloadKalFonctions($request);
//                    if ($xml != null): $this->ei_project->transactionToLoadObjectsOfProject($xml);
//                    endif;
                endif;

            else:
                $this->error = "Scenario or Test set or function or environment  not found ...";
            endif;
        endif;
    }

//Renvoi des tickets par webservice à l'ide
    public function executeListTickets(sfWebRequest $request)
    {
        //si tous les paramètres sont bien renseignés
        $this->guard_user = $this->user->getGuardUser();

        //Tout s'est bien passé . On recupère les paramètres du projet
        $this->checkProject($request);
        if (!$this->ei_project || $this->ei_project == null):
            $this->error = "Project not found with theses parameters ... ";
        else:
            $this->ei_tickets = $this->ei_project->getTickets();

        /* On récupère également le package par défaut */
       $this->defPack=Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByProjectIdAndProjectRefAndUserIdAndUserRef(
       $this->ei_project->getProjectId(),$this->ei_project->getRefId(),$this->user->getUserId(),$this->user->getRefId() );

        endif;
    }

    //Retour du dossier root et de ses fils de premier niveau à l'ide kalifast
    public function executeSendRootFolderToIde(sfWebRequest $request) {
        $this->checkProfile($request);
        $this->checkProject($request);

        if ($this->ei_profile != null && $this->ei_project != null) {

            if ($this->ei_project != null && $this->ei_project->needsReload($this->login)) {
                $xml = $this->ei_project->downloadKalFonctions($request);
                if ($xml != null)
                    $this->ei_project->transactionToLoadObjectsOfProject($xml);
            }

            $this->root_folder = $this->ei_project->getRootFolder(); //Récupération du dossier root
            $this->childs = $this->root_folder->getNodes();
        }
    }

//Retour du dossier  et de ses fils de premier niveau à l'ide kalifast
    public function executeSendFolderToIde(sfWebRequest $request) {
        $this->checkProfile($request);
        $this->checkProject($request);

        $this->node_id = $request->getParameter('node_id');
        $this->ei_node = Doctrine_Core::getTable('EiNode')->findOneByIdAndProjectIdAndProjectRef(
                $this->node_id, $this->project_id, $this->project_ref);

        if ($this->ei_profile != null && $this->ei_project != null && $this->ei_node){
            //Récupération des fils du noeud
            $this->childs = $this->ei_node->getNodes(true, true);
        }
        else {
            $this->error = "Connection problem or  Missing parameters";
        }
    }

    public function executeListScenario(sfWebRequest $request) {
        $project_id = $request->getParameter('project_id');
        $project_ref = $request->getParameter('project_ref');
        $this->checkProfile($request);

        if ($this->ei_profile != null && $project_id != null && $project_ref != null) {
            $this->scenarios = Doctrine_Core::getTable('EiScenario')->createQuery('s')
                    ->where(' EiProjet.project_id=s.project_id And EiProjet.ref_id=s.project_ref And s.project_id=? And s.project_ref=?', Array($project_id, $project_ref))
                    ->andWhere("EiProfilScenario.ei_scenario_id=s.id And EiProfilScenario.profile_id=? And EiProfilScenario.profile_ref=? ", array($this->profile_id, $this->profile_ref))
                    ->execute();
        }
    }

    /**
     * @param sfWebRequest $request
     */
    public function executeCreateEmptyDataSet(sfWebRequest $request) {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $reponse = "";

        //recupération du login
        $project_id = $request->getParameter('project_id');
        $project_ref = $request->getParameter('project_ref');
        $this->checkProfile($request);

        if ($this->ei_profile != null && $project_id != null && $project_ref != null) {
            $reponse = $this->getController()->getPresentationFor("eidataset", "createEmpty");
        }

        return $this->renderText($reponse);
    }

    /**
     * @deprecated Deprecated since version 3.8.6
     *
     * @param sfWebRequest $request
     */
    public function executeListProjet(sfWebRequest $request) {
        $this->projets = Doctrine_Core::getTable('EiProjet')->createQuery('p')
                ->where(' EiProjectUser.project_id=p.project_id And EiProjectUser.project_ref=p.ref_id')
                ->andWhere("EiProjectUser.user_id=? And EiProjectUser.user_ref=? ", array($this->user->getUserId(), $this->user->getRefId()))
                ->andWhere("p.obsolete = ?", false)
                ->execute();
    }

    /**
     * Evolution du web service retournant la liste des projets.
     *
     * @param sfWebRequest $request
     */
    public function executeListProject(sfWebRequest $request) {
        $this->projets = Doctrine_Core::getTable('EiProjet')->createQuery('p')
            ->where(' EiProjectUser.project_id=p.project_id And EiProjectUser.project_ref=p.ref_id')
            ->andWhere("EiProjectUser.user_id=? And EiProjectUser.user_ref=? ", array($this->user->getUserId(), $this->user->getRefId()))
            ->andWhere("p.obsolete = ?", false)
            ->execute();
    }

    public function executeVersionProjet(sfWebRequest $request) {
        $project_id = $request->getParameter('project_id');
        $project_ref = $request->getParameter('project_ref');
        $this->ei_project = null;

        if ($project_id != null && $project_ref != null) {
            //On verifie que l'utilisateur appartient au dit projet
            $userprojet = Doctrine_Core::getTable('EiProjectUser')
                ->findOneByProjectIdAndProjectRefAndUserIdAndUserRef($project_id, $project_ref, $this->user->getUserId(), $this->user->getRefId());

            if ($userprojet != null) {
                $this->ei_project = Doctrine_Core::getTable('EiProjet')->findOneByProjectIdAndRefId($project_id, $project_ref);
            }
        }
    }

    public function executeListFonctions(sfWebRequest $request) {
        $this->checkProfile($request);
        $this->ei_scenario_id = $request->getParameter('ei_scenario_id');

        //si tous les paramètres sont bien renseignés
        if ($this->ei_profile != null && $this->ei_scenario_id != null) {
            //Recherche du scénario
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->findOneById($this->ei_scenario_id);
            if ($this->ei_scenario != null) {
                //Récupération de la version racine du scénario dont le profil est affecté
                $this->ei_root_version = $this->ei_scenario->getVersionForProfil($this->ei_profile);

                if ($this->ei_root_version != null) {
                    //Récupération du contenu ordonné de la version
                    $this->objects = $this->ei_root_version->getOrderedContent();
                }
            }
        }
    }

    public function executeGenerateXMLLink(sfWebRequest $request) {
        $this->checkFunction($request);
        $this->checkProfile($request);

        if ($this->ei_fonction != null && $this->ei_profile != null) // si le scenario ou la version n'est pas récupérée 
            $this->kal_fonction = $this->ei_fonction->getKalFonction();
    }

    //Génération du xsl d'une fonction

    public function executeGenererXSL(sfWebRequest $request) {
        $this->checkProfile($request);
        $this->checkKalFunction($request);

        $this->guardUser = $this->user->getGuardUser();

        //Création du web service
        if ($this->ei_profile != null && $this->kal_function != null) : //Si les paramètres attendus sont bien renseignés
            $this->ei_project = $project = $this->ei_profile->getProject();

            //Rechargement de la fonction si Mise à jour rencontrée
            /* Si les login et mot de passe sont renseignés dans le
              fichier de config , alors on vérifie l'état des fonctions */
            if (sfConfig::get('project_login_systeme') && sfConfig::get('project_pwd_systeme')) :

                if ($this->ei_project != null) :
                    //Rechargement du projet si nécessaire
                    if ($this->ei_project->needsReload($this->login)):
                        $this->ei_project->transactionToLoadObjectsOfProject($this->ei_project->downloadKalFonctions($request));
                    endif;
                endif;
            endif;

            //Génération du XSL
            $this->result = $this->kal_function->getXmlOrXslFunction($request, $this->ei_profile, $this->ei_project, $this->user);
        endif;


        $this->setTemplate("download");
    }

    public function executeGetLogFile(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request);
        $this->checkNode($request);
        $this->file = $request->getParameter('file');
        if ($this->file != null) {
            $doc = new DOMDocument();
            // chargement du fichier de logs
            $doc->loadXML($this->file);
            $doc->saveXML();
            $this->textLog = $doc->documentElement->getElementsByTagName("texte")->item(0)->nodeValue;
        }
    }

    /**
     * Méthode permettant de retourner la liste des scénarios à exécuter dans la campagne
     * à partir de l'intervalle.
     *
     * @param sfWebRequest $request
     */
    public function executeListScenariosCampaign(sfWebRequest $request) {
        // On désactive le rendu d'un template et on indique le format des données retournées.
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        // Déclaration de la variable contenant la/les erreur(s).
        $erreur = false;

        // Récupération des paramètres.
        $projetNom = $request->getParameter("projet_nom");
        $profilNom = $request->getParameter("profil_nom");
        $campagneId = $request->getParameter("campagne_id");
        $positionDebut = $request->getParameter("pos_dep");
        $positionFin = $request->getParameter("pos_fin");
        $execution_id = $request->getParameter("execution_id");

        // On récupère le projet, le profil et la campagne.
        /** @var EiProjet $projet */
        $projet = Doctrine_Core::getTable("EiProjet")->findOneByName($projetNom);
        /** @var EiProfil $profil */
        $profil = ( $projet != null ) ? Doctrine_Core::getTable("EiProfil")->findOneByNameAndProjectIdAndProjectRef($profilNom, $projet->getProjectId(), $projet->getRefId()) : null;
        /** @var EiCampaign $campagne */
        $campagne = Doctrine_Core::getTable("EiCampaign")->find($campagneId);
        /** @var EiCampaignExecution $execution */
        $execution = Doctrine_Core::getTable("EiCampaignExecution")->find($execution_id);

        // On vérifie la concordance entre le projet et le profil puis le projet et la campagne.
        if (!($profil != null && $projet != null && $campagne != null)) {
            $erreur = "Impossible to retrieve environment, project or campaign.";
        }
        // Concordance Projet/Profil
        elseif (!($projet->getProjectId() == $profil->getProjectId() && $projet->getRefId() == $profil->getProjectRef())) {
            $erreur = "Impossible to retrieve project's environment.";
        }
        // Concordance Projet/Campagne.
        elseif (!($projet->getProjectId() == $campagne->getProjectId() && $projet->getRefId() == $campagne->getProjectRef())) {
            $erreur = "Impossible to retrieve project's campaign.";
        }
        // Recherche des scénarios à récupérer.
        else {
            $graphs = Doctrine_Core::getTable("EiCampaign")->getCampaignScenarios($campagne, $positionDebut, $positionFin);
        }


        // Façonnage du résultat.
        if (is_bool($erreur)) {

            $childs = array();

            /** @var EiCampaignGraph $graph */
            foreach ($graphs as $ind => $graph) {
                $childs[$ind] = array(
                    "ID" => $graph->getId(),
                    "SCENARIO" => array(
                        "ID" => $graph->getEiScenario()->getId(),
                        "NOM" => $graph->getEiScenario()->getNomScenario(),
                        "JDD" => $graph->getEiDataSet() != null && $graph->getEiDataSet()->getId() != null ? $graph->getEiDataSet()->getId() : 0,
                        "OnError" => $campagne->getEiBlockType()->getName()
                    )
                );
            }

            $resultat = $this->renderText(json_encode($childs));
        } else {
            $resultat = $this->renderText(json_encode(array("error" => $erreur)));
        }

        return $resultat;
    }

}

?>