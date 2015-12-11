<?php

/**
 * Class MigrateDataSetsTemplatesTask
 */
class MigrateDataSetsTemplatesTask extends sfBaseTask
{
    /** @var array  */
    private static $STEPS = array(
        1 => array(
            "title" => "Création des enregistrements de la table ei_data_set dans ei_data_set_template",
            1 => array(
                "title" => "Vérification de l'existence de la table ei_data_set_template."
            ),
            2 => array(
                "title" => "Récupération de tous les jeux de données."
            ),
            3 => array(
                "title" => "Création des templates à partir des jeux de données."
            ),
            4 => array(
                "title" => "Modification de la table ei_node."
            ),
            5 => array(
                "title" => "Création des noeuds fils."
            ),
            6 => array(
                "title" => "Mise à jour du node_id du jeu de données."
            )
        ),
    );

    private static $TABLE_TEMPLATES = "ei_data_set_template";

    /** @var string  */
    private static $STATUT_START = "[IN PROCESSING]";

    /** @var string  */
    private static $STATUT_END = "[DONE]";

    /** @var string  */
    private static $STATUT_FAILED = "[FAILED]";

    /** @var Doctrine_Connection $connexion  */
    private $connexion = null;

    private $statutExec = true;

    private $currentStep = 1;

    private $currentSubStep = null;

    /**
     *
     */
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));
        $this->namespace = 'migrate';
        $this->name      = 'DataSetsTemplate';
    }

    /**
     * Executes the current task.
     *
     * @param array $arguments An array of arguments
     * @param array $options An array of options
     *
     * @return integer 0 if everything went fine, or an error code
     */
    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager(sfProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true));
        $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true);
        sfContext::createInstance($configuration);
        $this->connexion = Doctrine_Manager::connection();

        $initialStep = 1;

        do{
            $this->proceedStep($initialStep++);
        }
        while( $this->statutExec === true );
    }

    /**
     * @param int $number
     * @param null $substep
     * @param null $statut
     * @param string $extra
     */
    private function displayStep($number = 1, $substep = null, $statut = null, $extra = "")
    {
        if( isset(self::$STEPS[$number]) && $substep == null )
        {
            $this->log("---------->          " . $this->displaying(self::$STEPS[$number]["title"], $statut, $extra));

            if( in_array($statut, array(self::$STATUT_END, self::$STATUT_FAILED)) ){
                $this->log("");
            }
        }
        elseif( isset(self::$STEPS[$number]) && $substep != null && isset(self::$STEPS[$number][$substep]) )
        {
            $this->log("----->     " . $this->displaying(self::$STEPS[$number][$substep]["title"], $statut, $extra));
        }
    }

    private function displayResultStep($message = "")
    {
        if( isset(self::$STEPS[$this->currentStep]) && $this->currentSubStep == null )
        {
            $this->log("------->       " . $message);
        }
        elseif( isset(self::$STEPS[$this->currentStep]) && $this->currentSubStep != null && isset(self::$STEPS[$this->currentStep][$this->currentSubStep]) )
        {
            $this->log("-->  " . $message);
        }
    }

    /**
     * @param $title
     * @param $statut
     * @param $extra
     * @return string
     */
    private function displaying($title, $statut, $extra){
        return $title . "..." . ($statut != null ? $statut:self::$STATUT_START) . (strlen($extra) > 0 ? " : ".$extra:"");
    }

    /**
     * Méthode permettant de réaliser les étapes.
     *
     * @param int $number
     * @param null $substep
     */
    private function proceedStep($number = 1, $substep = null, array $args = array())
    {
        try
        {
            $resultat = true;

            if( !(!isset(self::$STEPS[$number]) && !isset(self::$STEPS[$number][$substep])) && $this->statutExec == true )
            {
                $this->displayStep($number, $substep, self::$STATUT_START);

                $step = $substep == null ? $number:$number . "_" . $substep;
                $fonction = "proceedStep".$step;

                if( method_exists($this, $fonction) ){

                    $this->currentStep = $number;
                    $this->currentSubStep = $substep;

                    $resultat = call_user_func_array(array($this, $fonction), $args);

                    if( $this->statutExec == false ){
                        return false;
                    }
                }
                else{
                    throw new Exception("L'étape " . str_replace("_", ".", $step) . " n'existe pas.");
                }

                $this->displayStep($number, $substep, self::$STATUT_END);
            }
            else{
                $resultat = false;
                $this->statutExec = false;
            }

            return $resultat;
        }
        catch(Exception $exc){
            $this->displayStep($number, $substep, self::$STATUT_FAILED, $exc->getMessage());

            $this->statutExec = false;

            return false;
        }
    }

    /**********     DEBUT STEP 1     **********/

    /**
     * Exécution de l'Etape 1 relative à la vérification des champs obligatoires.
     *
     * STEP 1
     */
    private function proceedStep1()
    {
        $exists = $this->proceedStep(1, 1);

        $dataSets = $this->proceedStep(1, 2, array(
            "exists" => $exists
        ));

        $this->proceedStep(1, 3, array(
            "exists" => $exists,
            "dataSets" => $dataSets
        ));

        $this->proceedStep(1, 4, array(
            "exists" => $exists
        ));

        $this->proceedStep(1, 5, array(
            "exists" => $exists
        ));

        $this->proceedStep(1, 6, array(
            "exists" => $exists
        ));
    }

    private function proceedStep1_1()
    {
        // Requête SQL permettant de vérifier la présence de la table ei_data_set_template.
        $reqChampsSQL = "SELECT count(*) as count FROM information_schema.TABLES c WHERE c.TABLE_SCHEMA = database() " .
            "AND c.TABLE_NAME = '".self::$TABLE_TEMPLATES."'";

        // Récupération des résultats.
        $resultats = $this->connexion->execute($reqChampsSQL)->fetch();

        $nbTable = $resultats["count"];

        if( $nbTable == 1 ){
            $this->displayResultStep("La table existe déjà.");

            return true;
        }
        else{
            $this->displayResultStep("La table ".self::$TABLE_TEMPLATES." doit être créée.");

            return false;
        }
    }

    /**
     * @param bool $exists
     */
    private function proceedStep1_2($exists = false)
    {
        if( $exists == true )
        {
            $this->displayResultStep("Récupération des jeux de données.");

            // Requête SQL permettant de récupérer les jeux de données.
            $reqSQL = "SELECT * FROM ei_data_set WHERE id NOT IN (SELECT ei_data_set_ref_id FROM ".self::$TABLE_TEMPLATES.") ORDER BY id ASC";

            $results = $this->connexion->execute($reqSQL);
            $results = $results->fetchAll();

            $this->displayResultStep("Les jeux de données ont été récupérés avec succès : ".count($results)." éléments.");

            return $results;
        }
        else
        {
            $this->displayResultStep("Récupération des jeux de données omis.");
        }

        return false;
    }

    /**
     * @param bool $exists
     */
    private function proceedStep1_3($exists = false, $dataSets = array())
    {
        if( $exists == true )
        {
            $this->displayResultStep("Création des templates à partir des jeux de données.");
            $count = 0;

            $requeteToUpdate = "INSERT INTO ".self::$TABLE_TEMPLATES." (ei_node_id, name, description, user_id, user_ref, ei_data_set_ref_id, created_at, updated_at) " .
                "VALUES (#{EI_NODE_ID},#{NAME},#{DESC},#{USER_ID},#{USER_REF},#{EI_DATA_SET_ID},NOW(), NOW());";
            $requeteGlobale = array();

            foreach( $dataSets as $dataSet ){
                $count++;
                // Remplacement EI_NODE_ID.
                $tmpRequete = str_replace("#{EI_NODE_ID}", $dataSet["ei_node_id"], $requeteToUpdate);
                // Remplacement NAME.
                $tmpRequete = str_replace("#{NAME}", $this->connexion->quote($dataSet["name"]), $tmpRequete);
                // Remplacement DESC.
                $tmpRequete = str_replace("#{DESC}", $this->connexion->quote(strlen($dataSet["description"]) > 0 ? $dataSet["description"] :""), $tmpRequete);
                // Remplacement DESC.
                $tmpRequete = str_replace("#{USER_ID}", strlen($dataSet["user_id"]) > 0 ? $dataSet["user_id"]:"NULL", $tmpRequete);
                // Remplacement USER_REF.
                $tmpRequete = str_replace("#{USER_REF}", strlen($dataSet["user_ref"]) > 0 ? $dataSet["user_ref"]:"NULL", $tmpRequete);
                // Remplacement EI_DATA_SET_ID.
                $tmpRequete = str_replace("#{EI_DATA_SET_ID}", $dataSet["id"], $tmpRequete);

                // Ajout dans la requête globale.
                $requeteGlobale[] = $tmpRequete;
            }

            // Préparation de la requête.
            $this->displayResultStep("Préparation de la requête...");

            $requete = implode(" ",$requeteGlobale);
            // Exécution de la requête.
            $this->displayResultStep("Exécution de la requête...");

            if( strlen($requete) > 5 ){
                $this->connexion->beginTransaction();

                try{
                    while( count($requeteGlobale) > 0 ){

                        $sousRequeteGlobale = array_slice($requeteGlobale, 0,10);
                        $sousRequete = implode(" ",$sousRequeteGlobale);

                        $this->connexion->execute($sousRequete);

                        array_splice($requeteGlobale, 0, 10);
                    }

                    $this->connexion->commit();
                }
                catch( Exception $exc ){
                    $this->connexion->rollback();
                    throw $exc;
                }
            }

            // Fin.
            $this->displayResultStep("Processus terminé avec succès : ".$count." éléments mis à jour.");
        }
        else
        {
            $this->displayResultStep("Création des templates à partir des jeux de données omise.");
        }
    }

    /**
     * @param bool $exists
     */
    private function proceedStep1_4($exists = false)
    {
        if( $exists == true )
        {
            $this->displayResultStep("Mise à jour des noeuds.");

            // Requête SQL permettant de récupérer les jeux de données.
            $reqSQL = "UPDATE ei_node SET type = 'EiDataSetTemplate' WHERE type = 'EiDataSet' AND id IN (SELECT ei_node_id FROM ".self::$TABLE_TEMPLATES.")";

            // Préparation de la requête.
            $this->displayResultStep("Préparation de la requête...");

            // Exécution de la requête.
            $this->displayResultStep("Exécution de la requête...");

            $this->connexion->execute($reqSQL);

            $this->displayResultStep("Mise à jour des noeuds effectuée avec succès");

            return true;
        }
        else
        {
            $this->displayResultStep("Mise à jour des noeuds omis.");
        }

        return false;
    }

    private function proceedStep1_5($exists = false)
    {
        if( $exists == true )
        {
            $this->displayResultStep("Récupération des noeuds fils restant à créer.");

            // Requête SQL permettant de récupérer les noeuds fils à créer.
            $reqSQL = "SELECT en.* FROM ei_node en WHERE type = 'EiDataSetTemplate' AND (SELECT COUNT(*) FROM ei_node WHERE root_id = en.id) = 0;";
            $results = $this->connexion->execute($reqSQL);
            $noeudsFils = $results->fetchAll();

            $this->displayResultStep("Il y a " . count($noeudsFils) . " noeuds fils à créer.");

            // Requête SQL permettant de récupérer les noeuds fils à créer.
            $reqSQL = "SELECT * FROM ".self::$TABLE_TEMPLATES;
            $results = $this->connexion->execute($reqSQL);
            $templatesResults = $results->fetchAll();

            $templates = array();

            foreach( $templatesResults as $templateResult ){
                $templates[$templateResult["ei_data_set_ref_id"]] = $templateResult["id"];
            }

            $this->displayResultStep("Création des noeuds fils.");

            $requeteToInsert = "INSERT INTO ei_node (name, type, obj_id, project_id, project_ref, position, root_id, created_at, updated_at) " .
                "VALUES (#{NAME}, 'EiDataSet', #{OBJ_ID}, #{PROJECT_ID}, #{PROJECT_REF}, 1, #{ROOT_ID}, NOW(), NOW());";
            $requeteToUpdate = "UPDATE ei_node SET obj_id = #{OBJ_ID} WHERE id = #{EI_NODE_ID};";
            $requeteToUpdate.= "UPDATE ei_data_set SET ei_data_set_template_id = #{TEMPLATE_ID} WHERE id = #{DATA_SET_ID};";
            $requeteGlobale = array();

            foreach( $noeudsFils as $template ){
                if( isset($templates[$template["obj_id"]]) ){
                    // Remplacement NODE ID.
                    $tmpRequete = str_replace("#{EI_NODE_ID}", $template["id"], $requeteToUpdate);
                    // Remplacement OBJ ID.
                    $tmpRequete = str_replace("#{OBJ_ID}", $templates[$template["obj_id"]], $tmpRequete);
                    // Remplacement PROJECT ID.
                    $tmpRequete = str_replace("#{PROJECT_ID}", $template["project_id"], $tmpRequete);
                    // Remplacement PROJECT REF.
                    $tmpRequete = str_replace("#{PROJECT_REF}", $template["project_ref"], $tmpRequete);
                    // Remplacement TEMPLATE ID.
                    $tmpRequete = str_replace("#{TEMPLATE_ID}", $templates[$template["obj_id"]], $tmpRequete);
                    // Remplacement DATA SET ID.
                    $tmpRequete = str_replace("#{DATA_SET_ID}", $template["obj_id"], $tmpRequete);

                    // Ajout dans la requête globale.
                    $requeteGlobale[] = $tmpRequete;

                    // Remplacement NODE ID.
                    $tmpRequete = str_replace("#{NAME}", $this->connexion->quote($template["name"]), $requeteToInsert);
                    // Remplacement OBJ ID.
                    $tmpRequete = str_replace("#{OBJ_ID}", $template["obj_id"], $tmpRequete);
                    // Remplacement ROOT ID.
                    $tmpRequete = str_replace("#{ROOT_ID}", $template["id"], $tmpRequete);
                    // Remplacement PROJECT ID.
                    $tmpRequete = str_replace("#{PROJECT_ID}", $template["project_id"], $tmpRequete);
                    // Remplacement PROJECT REF.
                    $tmpRequete = str_replace("#{PROJECT_REF}", $template["project_ref"], $tmpRequete);

                    // Ajout dans la requête globale.
                    $requeteGlobale[] = $tmpRequete;
                }
                else{
                    $this->displayResultStep("*** Anomalie : Le template n'existe pas pour le jeu de données N°".$template["obj_id"]." ***");
                }
            }

            // Préparation de la requête.
            $this->displayResultStep("Préparation de la requête...");

            $requete = implode(" ",$requeteGlobale);

            // Exécution de la requête.
            $this->displayResultStep("Exécution de la requête...");

            if( strlen($requete) > 5 ){
                $this->connexion->beginTransaction();

                try{
                    while( count($requeteGlobale) > 0 ){

                        $sousRequeteGlobale = array_slice($requeteGlobale, 0,5);
                        $sousRequete = implode(" ",$sousRequeteGlobale);

                        $this->connexion->execute($sousRequete);

                        array_splice($requeteGlobale, 0, 5);
                    }

                    $this->connexion->commit();
                }
                catch( Exception $exc ){
                    $this->connexion->rollback();
                    throw $exc;
                }
            }

            // Fin.
            $this->displayResultStep("Processus terminé avec succès.");
        }
        else
        {
            $this->displayResultStep("Création des noeuds fils omise.");
        }

        return false;
    }

    private function proceedStep1_6($exists = false)
    {
        if( $exists == true )
        {
            $reqSQL = "SELECT * FROM ei_node WHERE type='EiDataSet';";
            $results = $this->connexion->execute($reqSQL);
            $noeudsFils = $results->fetchAll();

            $requeteToUpdate = "UPDATE ei_data_set SET ei_node_id = #{EI_NODE_ID} WHERE id = #{ID};";
            $requeteGlobale = array();

            foreach( $noeudsFils as $noeud ){
                // Remplacement NODE ID.
                $tmpRequete = str_replace("#{EI_NODE_ID}", $noeud["id"], $requeteToUpdate);
                // Remplacement OBJ ID.
                $tmpRequete = str_replace("#{ID}", $noeud["obj_id"], $tmpRequete);

                // Ajout dans la requête globale.
                $requeteGlobale[] = $tmpRequete;
            }

            // Préparation de la requête.
            $this->displayResultStep("Préparation de la requête...");

            $requete = implode(" ",$requeteGlobale);

            // Exécution de la requête.
            $this->displayResultStep("Exécution de la requête...");

            if( strlen($requete) > 5 ){
                $this->connexion->execute($requete);
            }

            // Fin.
            $this->displayResultStep("Processus terminé avec succès.");
        }
        else
        {
            $this->displayResultStep("Mise à jour du node_id du jeu de données omise.");
        }

        return false;
    }
} 