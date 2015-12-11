<?php

/**
 * Class MigrateScenarioStructureIntoVersionStructureTask
 */
class MigrateScenarioStructureIntoVersionStructureTask extends sfTask
{
    /** @var array  */
    private static $STEPS = array(
        1 => array(
            "title" => "Vérification de l'existence des champs nom, slug et description dans ei_version_structure",
            1 => array(
                "title" => "Recherche des champs dans la table"
            ),
            2 => array(
                "title" => "Création des champs nom, slug et description dans la table ei_version_structure"
            )
        ),
        2 => array(
            "title" => "Fusion de la structure du scénario vers la structure de la version",
            1 => array(
                "title" => "Récupération des paramètres de la structure du scénario"
            ),
            2 => array(
                "title" => "Copie des paramètres de la structure du scénario vers la structure de la version"
            ),
            3 => array(
                "title" => "Récupération de tous les blocs de la structure du scénario"
            ),
            4 => array(
                "title" => "Copie du nom et de la description de chaque bloc dans la structure de la version"
            ),
            5 => array(
                "title" => "Création des SLUG"
            )
        ),
        3 => array(
            "title" => "Remplacement dans la table EiBlockDataSetMapping de la référence du scénario structure par la version structure",
            1 => array(
                "title" => "Recherche du champ ei_version_structure_id dans la table EiBlockDataSetMapping"
            ),
            2 => array(
                "title" => "Ajout du champ ei_version_structure_id dans la table EiBlockDataSetMapping"
            ),
            3 => array(
                "title" => "Vérification de l'existence de la colonne ei_scenario_structure dans la table EiBlockDataSetMapping"
            ),
            4 => array(
                "title" => "Mise à jour du champ ei_version_structure_id de la table EiBlockDataSetMapping"
            )
        ),
        4 => array(
            "title" => "Remplacement dans la table EiParamBlockFunctionMapping de la référence du scénario structure par la version structure",
            1 => array(
                "title" => "Recherche si une contrainte FK existe sur le champ ei_param_block_id de la table EiParamBlockFunctionMapping"
            ),
            2 => array(
                "title" => "Suppression de la contrainte FK sur le champ ei_param_block_id de la table EiParamBlockFunctionMapping"
            ),
            3 => array(
                "title" => "Mise à jour du champ ei_param_block_id en fonction de la correspondance avec la table ei_version_structure"
            ),
            4 => array(
                "title" => "Création de la nouvelle contrainte FK en direction de la table ei_version_structure"
            )
        ),
        5 => array(
            "title" => "Suppression des données obsolètes",
            1 => array(
                "title" => "Vérification de l'existence de la colonne ei_scenario_structure dans la table EiBlockDataSetMapping"
            ),
            2 => array(
                "title" => "Suppression de la colonne ei_scenario_structure dans la table EiBlockDataSetMapping"
            ),
            3 => array(
                "title" => "Vérification de l'existence de la colonne ei_scenario_structure dans la table EiVersionStructure"
            ),
            4 => array(
                "title" => "Suppression de la colonne ei_scenario_structure dans la table EiVersionStructure"
            ),
            5 => array(
                "title" => "Vérification de l'existence de la table EiScenarioStructure"
            ),
            6 => array(
                "title" => "Suppression du contenu de la table EiScenarioStructure"
            )
        ),
        6 => array(
            "title" => "Ajout des contraintes not null"
        )
    );

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
        $this->name      = 'ScenarioStructureIntoVersionStructure';
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

        $this->proceedStep(1, 2, array(
            "exists" => $exists
        ));
    }

    private function proceedStep1_1()
    {
        // Requête SQL permettant de vérifier la présence des champs dans la table ei_version_structure.
        $reqChampsSQL = "SELECT count(*) as count FROM information_schema.COLUMNS c WHERE c.TABLE_SCHEMA = database() " .
            "AND c.TABLE_NAME = 'ei_version_structure' AND c.COLUMN_NAME IN ('name', 'description', 'slug')";

        // Récupération des résultats.
        $resultats = $this->connexion->execute($reqChampsSQL)->fetch();

        $nbColonnes = $resultats["count"];

        if( $nbColonnes == 3 ){
            $this->displayResultStep("Les colonnes existent déjà.");

            return true;
        }
        else{
            $this->displayResultStep("Les colonnes doivent être créées.");

            return false;
        }
    }

    /**
     * @param bool $exists
     */
    private function proceedStep1_2($exists = true)
    {
        if( $exists == false )
        {
            $this->displayResultStep("Création des colonnes name, description et slug pour la table ei_version_structure");

            $reqSQLCreate = "ALTER TABLE ei_version_structure ADD COLUMN name varchar(255) AFTER type;";
            $reqSQLCreate.= "ALTER TABLE ei_version_structure ADD COLUMN description longtext AFTER name;";
            $reqSQLCreate.= "ALTER TABLE ei_version_structure ADD COLUMN slug varchar(255) AFTER description;";

            $this->connexion->execute($reqSQLCreate);

            $this->displayResultStep("Les colonnes ont été créées avec succès");
        }
        else
        {
            $this->displayResultStep("Création des champs omise");
        }
    }

    /**********     FIN STEP 1     **********/


    /**********     DEBUT STEP 2     **********/

    private function proceedStep2()
    {
        // Récupération des paramètres de la structure du scénario.
        $parametres = $this->proceedStep(2, 1);

        // Copie des paramètres.
        $this->proceedStep(2, 2, array(
            "parametres" => $parametres
        ));

        // Récupération des éléments de la structure du scénario pour mise à jour des noms et descriptions.
        $elements = $this->proceedStep(2, 3);

        $this->proceedStep(2, 4, array(
            "blocs" => $elements
        ));

        $this->proceedStep(2, 5);
    }

    private function proceedStep2_1()
    {
        // Requête SQL permettant de récupérer les paramètres de blocs ordonnés par le scénario, le père et par ordre dans l'arbre.
        $reqSQL = "SELECT scstr.id, scstr.ei_scenario_id, scstr.ei_scenario_structure_parent_id, scstr.name, scstr.description, ver.id as vs_id, ver.ei_version_id
        FROM ei_scenario_structure scstr, ei_version_structure as ver
        WHERE scstr.type = 'ParamBlock'
        AND ver.type IN ('EiBlockForeach', 'EiBlock')
        AND ei_scenario_structure_parent_id = ei_scenario_structure_id
        AND scstr.name NOT IN (SELECT name FROM ei_version_structure WHERE ei_version_structure.type = 'EiBlockParam' AND ei_version_structure_parent_id = ver.id)
        ORDER BY scstr.ei_scenario_id, ei_scenario_structure_parent_id, scstr.lft;";

        $results = $this->connexion->execute($reqSQL);
        $results = $results->fetchAll();

        $this->displayResultStep("Récupération de " . count($results) . " résultats.");

        return $results;
    }

    /**
     * @param EiBlockParam[] $parametres
     */
    private function proceedStep2_2(array $parametres)
    {
        // Déclaration des variables contenant la liste des scénario structure parents manipulés et versions.
        /** @var EiVersionStructure[] $parentsVersion */
        $parentsVersion = array();
        $last = array();

        foreach( $parametres as $param ){

            // On récupère la version parente.
            /** @var EiVersionStructure $versionStrParente */
            $versionStrParente = Doctrine_Core::getTable("EiVersionStructure")->find($param["vs_id"]);

            $parentsVersion[$versionStrParente->getId()][] = $param["id"];

            // Création du paramètre.
            $paramVersion = new EiVersionStructure();
            $paramVersion->setType(EiVersionStructure::$TYPE_BLOCK_PARAM);
            $paramVersion->setEiVersionId($versionStrParente->getEiVersionId());
            $paramVersion->setEiVersionStructureParentId($versionStrParente->getId());
            $paramVersion->setName($param["name"]);
            $paramVersion->setDescription($param["description"]);

            $paramVersion->save();

            $req = "UPDATE ei_version_structure SET ei_scenario_structure_id = ".$param["id"]." WHERE id = ".$paramVersion->getId().";";
            $this->connexion->execute($req);

            $this->displayResultStep("Création du paramètre " . $param["name"] . " pour la version " . $versionStrParente->getEiVersionId());

            if( count($parentsVersion[$versionStrParente->getId()]) == 1 ){
                $paramVersion->getNode()->insertAsFirstChildOf($versionStrParente);
            }
            else{
                $paramVersion->getNode()->insertAsNextSiblingOf($last[$versionStrParente->getId()]);
            }

            $last[$versionStrParente->getId()] = $paramVersion;
        }
    }

    /**
     *
     */
    private function proceedStep2_3()
    {
        $reqSQL = "SELECT scstr.id, scstr.ei_scenario_id, scstr.ei_scenario_structure_parent_id, scstr.name, scstr.description, ver.id as ver_id
          FROM ei_scenario_structure scstr, ei_version_structure as ver
          WHERE scstr.type != 'ParamBlock'
          AND ver.type IN ('EiBlockForeach', 'EiBlock')
          AND scstr.id = ei_scenario_structure_id
          ORDER BY scstr.ei_scenario_id, ei_scenario_structure_parent_id, scstr.lft;";

        $results = $this->connexion->execute($reqSQL);
        $results = $results->fetchAll();

        $this->displayResultStep("Récupération de " . count($results) . " résultats.");

        return $results;
    }

    /**
     * @param EiScenarioStructure[] $blocs
     */
    private function proceedStep2_4(array $blocs)
    {
        foreach( $blocs as $bloc )
        {
            // On récupère la version de structure.
            /** @var EiVersionStructure $versionStr */
            $versionStr = Doctrine_Core::getTable("EiVersionStructure")->find($bloc["ver_id"]);

            $versionStr->setName($bloc["name"]);
            $versionStr->setDescription($bloc["description"]);

            $versionStr->save();
        }
    }

    /**
     * Création des SLUGs.
     */
    private function proceedStep2_5()
    {
        // Récupération de toutes les notices.
        $noeuds = $this->connexion->execute("SELECT id, name FROM ei_version_structure WHERE type != '".EiVersionStructure::$TYPE_FONCTION."';")->fetchAll();

        $this->displayResultStep('Récupération des '.count($noeuds).' éléments de versions de structures.');

        // Création de la requête permettant
        $this->displayResultStep('Création de la requête de mise à jour...');

        $requeteToUpdate = "UPDATE ei_version_structure SET slug = #{NEW_SLUG} WHERE id = #{NODE_ID};";
        $requeteGlobale = array();

        foreach( $noeuds as $noeud ){
            // Remplacement SLUG.
            $tmpRequete = str_replace("#{NEW_SLUG}", $this->connexion->quote(MyFunction::sluggifyForXML($noeud["name"])), $requeteToUpdate);
            // Remplacement ID.
            $tmpRequete = str_replace("#{NODE_ID}", $noeud["id"], $tmpRequete);

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

    /**********     FIN STEP 2     **********/



    /**********     DEBUT STEP 3     **********/

    /**
     *
     */
    private function proceedStep3()
    {
        $champExiste = $this->proceedStep(3, 1);

        $this->proceedStep(3, 2, array(
            "exists" => $champExiste
        ));

        $exists = $this->proceedStep(3, 3);

        $this->proceedStep(3, 4, array(
            "exists" => $exists
        ));
    }

    private function proceedStep3_1()
    {
        // Requête SQL permettant de vérifier la présence des champs dans la table ei_version_structure.
        $reqChampsSQL = "SELECT count(*) as count FROM information_schema.COLUMNS c WHERE c.TABLE_SCHEMA = database() " .
            "AND c.TABLE_NAME = 'ei_block_data_set_mapping' AND c.COLUMN_NAME = 'ei_version_structure_id'";

        // Récupération des résultats.
        $resultats = $this->connexion->execute($reqChampsSQL)->fetch();

        $nbColonnes = $resultats["count"];

        if( $nbColonnes == 1 ){
            $this->displayResultStep("La colonne ei_version_structure_id existe déjà.");

            return true;
        }
        else{
            $this->displayResultStep("La colonne ei_version_structure_id n'existe pas.");

            return false;
        }
    }

    /**
     * @param bool $exists
     */
    private function proceedStep3_2($exists = false)
    {
        if( !$exists ){
            $this->displayResultStep("Création de la colonne ei_version_structure_id pour la table ei_block_data_set_mapping");

            $reqSQLCreate = "ALTER TABLE ei_block_data_set_mapping ADD COLUMN ei_version_structure_id bigint AFTER id;";
            $reqSQLCreate.= "ALTER TABLE ei_block_data_set_mapping ADD CONSTRAINT FOREIGN KEY ei_block_data_set_mapping_ei_version_structure_id_ei_vs_id (ei_version_structure_id)
                        REFERENCES ei_version_structure(id) ON UPDATE RESTRICT ON DELETE CASCADE;";

            $this->connexion->execute($reqSQLCreate);

            $this->displayResultStep("La colonne a été créée avec succès");
        }
        else{
            $this->displayResultStep("Création de la colonne omise");
        }
    }

    private function proceedStep3_3()
    {
        // Requête SQL permettant de vérifier la présence des champs dans la table ei_version_structure.
        $reqChampsSQL = "SELECT count(*) as count FROM information_schema.COLUMNS c WHERE c.TABLE_SCHEMA = database() " .
            "AND c.TABLE_NAME = 'ei_block_data_set_mapping' AND c.COLUMN_NAME = 'ei_scenario_structure_id'";

        // Récupération des résultats.
        $resultats = $this->connexion->execute($reqChampsSQL)->fetch();

        $nbColonnes = $resultats["count"];

        if( $nbColonnes == 1 ){
            $this->displayResultStep("La colonne ei_scenario_structure_id existe déjà.");

            return true;
        }
        else{
            $this->displayResultStep("La colonne ei_scenario_structure_id n'existe pas.");

            return false;
        }
    }

    private function proceedStep3_4($exists = true)
    {
        if( $exists )
        {
            $reqSQL = "SELECT bdsm.ei_scenario_structure_id, evs.id
        FROM ei_block_data_set_mapping bdsm, ei_version_structure evs
        WHERE bdsm.ei_scenario_structure_id IS NOT NULL
        AND evs.type IN ('EiBlockParam', 'EiBlockForeach')
        AND evs.ei_scenario_structure_id = bdsm.ei_scenario_structure_id
        GROUP BY bdsm.ei_scenario_structure_id, evs.id;";

            // Récupération des résultats.
            $noeuds = $this->connexion->execute($reqSQL)->fetchAll();

            // Affichage des informations relatives à l'exécution.
            $this->displayResultStep('Récupération des '.count($noeuds).' éléments de mapping.');

            // Création de la requête permettant
            $this->displayResultStep('Création de la requête de mise à jour...');

            $requeteToUpdate = "UPDATE ei_block_data_set_mapping SET ei_version_structure_id = #{NEW_DEP} WHERE ei_scenario_structure_id = #{NODE_ID};";
            $requeteGlobale = array();

            foreach( $noeuds as $noeud ){
                // Remplacement Version structure.
                $tmpRequete = str_replace("#{NEW_DEP}", $noeud["id"], $requeteToUpdate);
                // Remplacement ID.
                $tmpRequete = str_replace("#{NODE_ID}", $noeud["ei_scenario_structure_id"], $tmpRequete);

                $this->displayResultStep("Mise à jour du noeud " . $noeud["ei_scenario_structure_id"]. " par la version " . $noeud["id"]);

                // Ajout dans la requête globale.
                $requeteGlobale[] = $tmpRequete;
            }

            // Préparation de la requête.
            $this->displayResultStep("Préparation de la requête...");

            $requete = implode(" ",$requeteGlobale);

            // Exécution de la requête.
            $this->displayResultStep("Exécution de la requête...");

            $this->connexion->execute($requete);

            // Fin.
            $this->displayResultStep("Processus terminé avec succès.");
        }
        else{
            $this->displayResultStep("Mise à jour omise car le champ ei_scenario_structure_id n'existe pas.");
        }
    }

    /**********     FIN STEP 3     **********/



    /**********     DEBUT STEP 4     **********/

    private function proceedStep4()
    {
        $exists = $this->proceedStep(4, 1);

        $this->proceedStep(4, 2, array(
            "exists" => $exists
        ));

        $this->proceedStep(4, 3);

        $this->proceedStep(4, 4);
    }

    private function proceedStep4_1()
    {
        $this->displayResultStep("Recherche du nom de la clé étrangère sur ei_param_block_id.");

        $resultat = false;

        $reqForeign = "SELECT CONSTRAINT_NAME as name
            FROM information_schema.REFERENTIAL_CONSTRAINTS
            WHERE TABLE_NAME = 'ei_param_block_function_mapping'
            AND REFERENCED_TABLE_NAME = 'ei_scenario_structure'
            AND CONSTRAINT_SCHEMA = database();";

        $constraint = $this->connexion->execute($reqForeign)->fetch();

        if( isset($constraint["name"]) ){
            $resultat = $constraint["name"];
            $this->displayResultStep("La clé étrangère est " . $constraint["name"]. ".");
        }

        return $resultat;
    }

    private function proceedStep4_2($exists = false)
    {
        if( $exists != false )
        {
            $reqSQLDrop = "ALTER TABLE ei_param_block_function_mapping DROP FOREIGN KEY ".$exists.";";

            $this->connexion->execute($reqSQLDrop);

            $this->displayResultStep("Suppression de la clé étrangère réalisée avec succès.");
        }
        else{
            $this->displayResultStep("Suppression de la clé étrangère omise.");
        }
    }

    private function proceedStep4_3()
    {
        $this->displayResultStep("Recherche du nom de la clé étrangère sur ei_param_block_id.");

        $reqForeign = "SELECT COUNT(*) as count FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE TABLE_NAME = 'ei_param_block_function_mapping'
            AND REFERENCED_TABLE_NAME = 'ei_version_structure' AND CONSTRAINT_SCHEMA = database();";

        $constraint = $this->connexion->execute($reqForeign)->fetch();

        $this->displayResultStep("Il y a ".count($constraint)." résultat(s).");

        if( $constraint["count"] == 0 )
        {
            $reqSQL = "SELECT pbfm.id, pbfm.ei_param_block_id, evs.id as evs_id
            FROM ei_param_block_function_mapping pbfm, ei_version_structure evs
            WHERE pbfm.ei_param_block_id IS NOT NULL
            AND evs.type IN ('EiBlockParam')
            AND evs.ei_scenario_structure_id = pbfm.ei_param_block_id;";

            // Récupération des résultats.
            $noeuds = $this->connexion->execute($reqSQL)->fetchAll();

            // Affichage des informations relatives à l'exécution.
            $this->displayResultStep('Récupération des '.count($noeuds).' éléments de mapping.');

            // Création de la requête permettant
            $this->displayResultStep('Création de la requête de mise à jour...');

            $requeteToUpdate = "UPDATE ei_param_block_function_mapping SET ei_param_block_id = #{NEW_DEP} WHERE id = #{NODE_ID};";
            $requeteGlobale = array();

            foreach( $noeuds as $noeud ){
                // Remplacement Version structure.
                $tmpRequete = str_replace("#{NEW_DEP}", $noeud["evs_id"], $requeteToUpdate);
                // Remplacement ID.
                $tmpRequete = str_replace("#{NODE_ID}", $noeud["id"], $tmpRequete);

                // Ajout dans la requête globale.
                $requeteGlobale[] = $tmpRequete;
            }

            // Préparation de la requête.
            $this->displayResultStep("Préparation de la requête...");

            $requete = implode(" ",$requeteGlobale);

            if( strlen($requete) > 5 ){

                // Exécution de la requête.
                $this->displayResultStep("Exécution de la requête...");

                $this->connexion->execute($requete);
            }
            else{
                $this->displayResultStep("La requête est vide.");
            }

            // Fin.
            $this->displayResultStep("Processus terminé avec succès.");
        }
        else{
            $this->displayResultStep("Mise à jour omise.");
        }

    }

    private function proceedStep4_4()
    {
        $this->displayResultStep("Recherche du nom de la clé étrangère sur ei_param_block_id.");

        $reqForeign = "SELECT COUNT(*) as count FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE TABLE_NAME = 'ei_param_block_function_mapping'
            AND REFERENCED_TABLE_NAME = 'ei_version_structure' AND CONSTRAINT_SCHEMA = database();";

        $constraint = $this->connexion->execute($reqForeign)->fetchAll();

        $this->displayResultStep("Il y a ".count($constraint)." résultat(s).");

        if( count($constraint) == 0 ){
            $reqUpdate = "ALTER TABLE ei_param_block_function_mapping ADD CONSTRAINT FOREIGN KEY ei_param_block_function_mapping_param_block_id_version (ei_param_block_id)
                        REFERENCES ei_version_structure(id) ON UPDATE RESTRICT ON DELETE CASCADE";

            $this->connexion->execute($reqUpdate);
        }
    }

    /**********     FIN STEP 4     **********/



    /**********     DEBUT STEP 5     **********/

    private function proceedStep5()
    {
        $exists = $this->proceedStep(5, 1);

        $this->proceedStep(5, 2, array(
            "exists" => $exists
        ));

        $exists = $this->proceedStep(5, 3);

        $this->proceedStep(5, 4, array(
            "exists" => $exists
        ));

        $exists = $this->proceedStep(5, 5);

        $this->proceedStep(5, 6, array(
            "exists" => $exists
        ));
    }

    private function proceedStep5_1()
    {
        // Requête SQL permettant de vérifier la présence des champs dans la table ei_version_structure.
        $reqChampsSQL = "SELECT count(*) as count FROM information_schema.COLUMNS c WHERE c.TABLE_SCHEMA = database() " .
            "AND c.TABLE_NAME = 'ei_block_data_set_mapping' AND c.COLUMN_NAME = 'ei_scenario_structure_id'";

        // Récupération des résultats.
        $resultats = $this->connexion->execute($reqChampsSQL)->fetch();

        $nbColonnes = $resultats["count"];

        if( $nbColonnes == 1 ){
            $this->displayResultStep("La colonne ei_scenario_structure_id existe déjà.");

            return true;
        }
        else{
            $this->displayResultStep("La colonne ei_scenario_structure_id n'existe pas.");

            return false;
        }
    }

    private function proceedStep5_2($exists = false)
    {
        if( $exists )
        {
            $this->displayResultStep("Recherche du nom de la clé étrangère.");

            $reqForeign = "SELECT CONSTRAINT_NAME as name
            FROM information_schema.REFERENTIAL_CONSTRAINTS
            WHERE TABLE_NAME = 'ei_block_data_set_mapping'
            AND REFERENCED_TABLE_NAME = 'ei_scenario_structure'
            AND CONSTRAINT_SCHEMA = database();";

            $constraint = $this->connexion->execute($reqForeign)->fetch();

            $this->displayResultStep("La clé étrangère est " . $constraint["name"]. ".");

            $this->displayResultStep("Recherche du nom de l'index.");

            $reqIndex = "SELECT INDEX_NAME as name
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = database()
            AND TABLE_NAME = 'ei_block_data_set_mapping'
            AND COLUMN_NAME = 'ei_scenario_structure_id';";

            $index = $this->connexion->execute($reqIndex)->fetch();

            $this->displayResultStep("Le nom de l'index est " . $index["name"]. ".");

            $reqSQLDrop = "ALTER TABLE ei_block_data_set_mapping DROP FOREIGN KEY ".$constraint["name"].";";
            $reqSQLDrop.= "ALTER TABLE ei_block_data_set_mapping DROP INDEX ".$index["name"].";";
            $reqSQLDrop.= "ALTER TABLE ei_block_data_set_mapping DROP COLUMN ei_scenario_structure_id;";

            $this->connexion->execute($reqSQLDrop);

            $this->displayResultStep("La colonne a été supprimée avec succès");
        }
        else
        {
            $this->displayResultStep("Suppression de la colonne omise");
        }
    }

    private function proceedStep5_3()
    {
        // Requête SQL permettant de vérifier la présence des champs dans la table ei_version_structure.
        $reqChampsSQL = "SELECT count(*) as count FROM information_schema.COLUMNS c WHERE c.TABLE_SCHEMA = database() " .
            "AND c.TABLE_NAME = 'ei_version_structure' AND c.COLUMN_NAME = 'ei_scenario_structure_id'";

        // Récupération des résultats.
        $resultats = $this->connexion->execute($reqChampsSQL)->fetch();

        $nbColonnes = $resultats["count"];

        if( $nbColonnes == 1 ){
            $this->displayResultStep("La colonne ei_scenario_structure_id existe déjà.");

            return true;
        }
        else{
            $this->displayResultStep("La colonne ei_scenario_structure_id n'existe pas.");

            return false;
        }
    }

    private function proceedStep5_4($exists = false)
    {
        if( $exists )
        {
            $this->displayResultStep("Recherche du nom de la clé étrangère.");

            $reqForeign = "SELECT CONSTRAINT_NAME as name
            FROM information_schema.REFERENTIAL_CONSTRAINTS
            WHERE TABLE_NAME = 'ei_version_structure'
            AND REFERENCED_TABLE_NAME = 'ei_scenario_structure'
            AND CONSTRAINT_SCHEMA = database();";

            $constraint = $this->connexion->execute($reqForeign)->fetch();

            $this->displayResultStep("La clé étrangère est " . $constraint["name"]. ".");

            $this->displayResultStep("Recherche du nom de l'index.");

            $reqIndex = "SELECT INDEX_NAME as name
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = database()
            AND TABLE_NAME = 'ei_version_structure'
            AND COLUMN_NAME = 'ei_scenario_structure_id';";

            $index = $this->connexion->execute($reqIndex)->fetch();

            $this->displayResultStep("Le nom de l'index est " . $index["name"]. ".");

            $reqSQLDrop = "ALTER TABLE ei_version_structure DROP FOREIGN KEY ".$constraint["name"].";";
            $reqSQLDrop.= "ALTER TABLE ei_version_structure DROP INDEX ".$index["name"].";";
            $reqSQLDrop.= "ALTER TABLE ei_version_structure DROP COLUMN ei_scenario_structure_id;";

            $this->connexion->execute($reqSQLDrop);

            $this->displayResultStep("La colonne a été supprimée avec succès");
        }
        else
        {
            $this->displayResultStep("Suppression de la colonne omise");
        }
    }

    private function proceedStep5_5()
    {
        // Requête SQL permettant de vérifier la présence des champs dans la table ei_version_structure.
        $reqChampsSQL = "SELECT count(*) as count FROM information_schema.TABLES WHERE TABLE_SCHEMA = database() AND table_name = 'ei_scenario_structure';";

        // Récupération des résultats.
        $resultats = $this->connexion->execute($reqChampsSQL)->fetch();

        $nbColonnes = $resultats["count"];

        if( $nbColonnes > 0 ){
            $this->displayResultStep("La table ei_scenario_structure existe.");

            return true;
        }
        else{
            $this->displayResultStep("La table ei_scenario_structure n'existe plus.");

            return false;
        }
    }

    private function proceedStep5_6($exists = false)
    {
        if( $exists == true )
        {
            $reqSQLDrop = "DROP TABLE ei_scenario_structure";

            $this->connexion->execute($reqSQLDrop);

            $this->displayResultStep("La table a été supprimée avec succès");
        }
        else
        {
            $this->displayResultStep("Suppression de la table omise");
        }
    }

    /**********     FIN STEP 5     **********/


    /**********     DEBUT STEP 6     **********/

    private function proceedStep6()
    {
        $sql = "ALTER TABLE ei_version_structure MODIFY name varchar(255) NOT NULL";

        $this->connexion->execute($sql);

        $this->displayResultStep("La colonne name de la table ei_version_structure a été fixée à not null.");
    }

    /**********     FIN STEP 6     **********/
}