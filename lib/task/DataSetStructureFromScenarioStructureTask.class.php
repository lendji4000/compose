<?php

/**
 * Class DataSetStructureFromScenarioStructureTask
 */
class DataSetStructureFromScenarioStructureTask extends sfTask
{

    /**
     *
     */
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));
        $this->namespace = 'create';
        $this->name      = 'DataSetStructureFromScenarioStructure';
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
        $conn= Doctrine_Manager::connection();

        // Création des roots.
        $sqlDrop = "DROP PROCEDURE IF EXISTS creerRootStructureDataSet;";
        $sqlCreate = "
        CREATE PROCEDURE `creerRootStructureDataSet`()
        BEGIN
            DECLARE done INT DEFAULT 0;
            DECLARE idScenario VARCHAR(64);
            DECLARE idProject VARCHAR(64);
            DECLARE refProject VARCHAR(64);
            DECLARE curseurScenarios CURSOR FOR SELECT id, project_id, project_ref FROM ei_scenario WHERE id NOT IN (SELECT ei_scenario_id FROM ei_data_set_structure);
            DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

            OPEN curseurScenarios;

            REPEAT
                FETCH curseurScenarios INTO idScenario, idProject, refProject;

                IF done = 0 THEN
                    INSERT INTO ei_data_set_structure(ei_scenario_id, ei_dataset_structure_parent_id, root_id, project_id, project_ref,
                        type, name, description, created_at, updated_at, lft, rgt, level
                    ) VALUES (idScenario, null, LAST_INSERT_ID(), idProject, refProject, 'NodeDataSet', 'Root', '', now(), now(), 1, 2, 0);

                    UPDATE ei_data_set_structure SET root_id = LAST_INSERT_ID() WHERE id = LAST_INSERT_ID();
                END IF;

            UNTIL done
            END REPEAT;
        END;";

        $sqlCall = "CALL creerRootStructureDataSet();";

        // Copie de la structure du scénario dans la structure du jeu de données et mise à jour du data line.
        $sqlDropCopy = "DROP PROCEDURE IF EXISTS copyScenarioStructureToDataSetStructure;";
        $sqlCreateCopy = "
            # Création de la procédure permettant de copier la structure du scénario dans la structure du jeu de données.
            CREATE PROCEDURE `copyScenarioStructureToDataSetStructure`()
            BEGIN
                # Déclaration des variables utilisées.
                # Variable permettant de vérifier que la boucle est terminée.
                DECLARE done, nbElts, cpt, haveToDrop, cpt2, nbElts2 INT DEFAULT 0;
                # Variable permettant de contenir le scénario courant.
                DECLARE scenario_id BIGINT;

                # Variable permettant de contenir l'ID de la structure du scénario parente.
                DECLARE currentStrParentId BIGINT DEFAULT 0;
                # Variable permettant de contenir l'ID de la structure jdd parente.
                DECLARE currentDataSetStrParentId BIGINT DEFAULT 0;

                DECLARE strId, strParentId, rootI, dsRootI, projectI, projectR, lastDsID, dlI, scenarioStrId, dlDsI BIGINT;
                DECLARE strName, strType, dsStrType varchar(255);
                DECLARE strDesc longtext;
                DECLARE strUp, strCr datetime;
                DECLARE strLft, strRgt int(11);
                DECLARE strLvl smallint(6);

                DECLARE scenario BIGINT DEFAULT 1;
                DECLARE query longtext;

                DECLARE curseurScenarios CURSOR FOR SELECT id FROM ei_scenario sc WHERE (SELECT COUNT(*) FROM ei_data_set_structure WHERE ei_scenario_id = sc.id) = 1;

                DECLARE curseurStructure CURSOR FOR
                    SELECT scstr.id, ei_scenario_structure_parent_id, scstr.root_id, scstr.project_id, scstr.project_ref, scstr.type, scstr.name, scstr.description,
                                scstr.created_at, scstr.updated_at, scstr.lft, scstr.rgt, scstr.level, dsstr.root_id
                    FROM ei_scenario_structure scstr, ei_data_set_structure dsstr
                    WHERE scstr.ei_scenario_id = @scenario
                    AND scstr.ei_scenario_id = dsstr.ei_scenario_id
                    ORDER BY scstr.lft;

                DECLARE curseurDataLines CURSOR FOR SELECT id FROM ei_data_line WHERE ei_data_set_structure_id = @strId
                    AND id NOT IN (SELECT id FROM copy_scenario_structure_into_dataset_structure_data_line);

                DECLARE curseurNotMatchedDataLines CURSOR FOR SELECT id, ei_data_set_structure_id FROM prod_v2_kalifast.ei_data_line
                    WHERE ei_data_set_structure_id NOT IN (SELECT id FROM ei_data_set_structure);

                DECLARE curseurMatchedDataSetDataLine CURSOR FOR SELECT id FROM ei_data_set_structure  WHERE ei_dataset_structure_parent_id IS NULL
                    AND ei_scenario_id = (SELECT ei_scenario_id FROM ei_scenario_structure WHERE id = @scenarioStrId);

                DECLARE curseurStructureChanges CURSOR FOR SELECT  FROM copy_scenario_structure_into_dataset_structure;

                DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

                # Suppression des tables temporaires si elles existent déjà.
                DROP TABLE IF EXISTS copy_scenario_structure_into_dataset_structure;
                DROP TABLE IF EXISTS copy_scenario_structure_into_dataset_structure_data_line;

                # Création d'une table temporaire permettant de recenser toutes les correspondances entre les structures de scénario et jeu de données.
                CREATE TEMPORARY TABLE copy_scenario_structure_into_dataset_structure (
                    `id` bigint(20) NOT NULL,
                    `ei_scenario_id` bigint(20) NOT NULL,
                    `ei_scenario_structure_parent_id` bigint(20) DEFAULT NULL,
                    `root_id` bigint(20) DEFAULT NULL,
                    `project_id` bigint(20) NOT NULL,
                    `project_ref` bigint(20) NOT NULL,
                    `type` varchar(255) NOT NULL,
                    `name` varchar(255) NOT NULL,
                    `description` longtext,
                    `created_at` datetime NOT NULL,
                    `updated_at` datetime NOT NULL,
                    `lft` int(11) DEFAULT NULL,
                    `rgt` int(11) DEFAULT NULL,
                    `level` smallint(6) DEFAULT NULL,
                    `data_set_structure_id` bigint(20) NOT NULL,
                    `data_set_structure_parent_id` bigint(20),
                    `data_set_structure_root_id` bigint(20) NOT NULL
                ) DEFAULT CHARSET=utf8;

                # Création de la table contenant les jeux de données traités.
                CREATE TEMPORARY TABLE copy_scenario_structure_into_dataset_structure_data_line(
                    `id` bigint(20) NOT NULL,
                    `old`  bigint(20) NOT NULL,
                    `new`  bigint(20) NOT NULL
                ) DEFAULT CHARSET=utf8;

                # TRANSACTION.
                SET AUTOCOMMIT = 0;

                START TRANSACTION;

                # On regarde s'il y a besoin de créer la colonne dans ei_data_line.
                SELECT count(*) FROM information_schema.COLUMNS c WHERE c.TABLE_SCHEMA = database()
                    AND c.TABLE_NAME = 'ei_data_line' AND c.COLUMN_NAME = 'ei_data_set_structure_id' INTO haveToDrop;

                IF haveToDrop = 0 THEN
                    # Suppression de l'index et de la foreign key dans la table ei_data_line faisant référence à la structure du scénario.
                    ALTER TABLE ei_data_line DROP FOREIGN KEY ei_data_line_ei_scenario_structure_id_ei_scenario_structure_id;
                    ALTER TABLE ei_data_line DROP INDEX ei_scenario_structure_id_idx;

                    # On renomme.
                    ALTER TABLE ei_data_line CHANGE ei_scenario_structure_id ei_data_set_structure_id BIGINT;

                    # Création de l'index.
                    CREATE INDEX ei_data_set_structure_id_idx ON ei_data_line (ei_data_set_structure_id);
                END IF;

                # Parcours des scénarios.
                OPEN curseurScenarios;

                REPEAT
                    # On récupère l'ID du scénario.
                    FETCH curseurScenarios INTO scenario_id;

                    IF done = 0 Then
                        SET @scenario = scenario_id;

                        # On parcours tous les éléments de la structure du scénario.
                        OPEN curseurStructure;

                        # On stocke le nombre de lignes dans le compteur.
                        SELECT FOUND_ROWS() into nbElts;
                        SET cpt = 1;

                        # On récupère le premier élément qui doit être le root.
                        FETCH NEXT FROM curseurStructure INTO strId, strParentId, rootI, projectI, projectR, strType, strName, strDesc, strCr, strUp, strLft, strRgt,
                            strLvl, dsRootI;

                        # Si l'élément capturé est bien le root, on met à jour le root de la structure du jeu de données
                        # puis, on travaille à compléter la structure.
                        IF strParentId IS NULL THEN
                            SET @currentDataSetStrParentId = dsRootI;
                            SET @currentStrParentId = strParentId;
                            SET @dsStrType = 'NodeDataSet';
                            SET @strId = strId;

                            # On met à jour le champ right du noeud parent de la structure du jeu de données.
                            UPDATE ei_data_set_structure SET rgt = strRgt WHERE id = @currentDataSetStrParentId;

                            INSERT INTO copy_scenario_structure_into_dataset_structure VALUES (strId, @scenario, @currentStrParentId, rootI, projectI, projectR, @dsStrType,
                                strName, strDesc, strCr, strUp, strLft, strRgt, strLvl, dsRootI, NULL, dsRootI
                            );

                            OPEN curseurDataLines;
                                SELECT FOUND_ROWS() into nbElts2;

                                IF nbElts2 = 1 THEN
                                    FETCH NEXT FROM curseurDataLines INTO dlI;

                                    # Répercussion sur les lignes des jeux de données.
                                    UPDATE ei_data_line SET ei_data_set_structure_id = @lastDsID WHERE ei_data_set_structure_id = strId
                                        AND id NOT IN (SELECT id FROM copy_scenario_structure_into_dataset_structure_data_line);

                                    # Insertion dans la table temporaire.
                                    INSERT INTO copy_scenario_structure_into_dataset_structure_data_line VALUES (dlI, strId, @lastDsID);
                                END IF;
                            CLOSE curseurDataLines;

                            # Puis, on parcours les fils.
                            WHILE ( cpt < nbElts) DO
                                # On capture le nouvel élément.
                                FETCH NEXT FROM curseurStructure INTO strId, strParentId, rootI, projectI, projectR, strType, strName, strDesc, strCr, strUp, strLft,
                                    strRgt, strLvl, dsRootI;

                                # On détermine le type de noeud en fonction du type d'élément.
                                IF strType = 'BlockScenario' THEN
                                    SET @dsStrType = 'NodeDataSet';
                                ELSE
                                    SET @dsStrType = 'LeafDataSet';
                                END IF;

                                SET @strId = strId;

                                # On récupère l'ID de la structure parente.
                                SELECT data_set_structure_id FROM copy_scenario_structure_into_dataset_structure WHERE id = strParentId INTO @currentDataSetStrParentId;

                                # On insert l'élément dans la structure du jeu de données.
                                INSERT INTO ei_data_set_structure (`ei_scenario_id`, `ei_dataset_structure_parent_id`, `root_id`, `project_id`, `project_ref`, `type`,
                                    `name`, `description`, `created_at`, `updated_at`, `lft`, `rgt`, `level`) VALUES ( @scenario, @currentDataSetStrParentId, dsRootI,
                                    projectI, projectR, @dsStrType, strName, strDesc, strCr, strUp, strLft, strRgt, strLvl
                                );

                                SET @lastDsID = LAST_INSERT_ID();

                                # On insert l'élément de la structure du scénario avec les infos de l'élément de la structure du jeu de données lié.
                                INSERT INTO copy_scenario_structure_into_dataset_structure VALUES (strId, @scenario, strParentId, rootI, projectI, projectR, @dsStrType,
                                    strName, strDesc, strCr, strUp, strLft, strRgt, strLvl, @lastDsID, @currentDataSetStrParentId, dsRootI
                                );

                                # Parcours des lignes du jeu de données à convertir.
                                OPEN curseurDataLines;
                                    SET cpt2 = 1;
                                    SELECT FOUND_ROWS() into nbElts2;

                                    # Parcours des éléments.
                                    WHILE (cpt2 <= nbElts2) DO
                                        FETCH NEXT FROM curseurDataLines INTO dlI;

                                        # Répercussion sur les lignes des jeux de données.
                                        UPDATE ei_data_line SET ei_data_set_structure_id = @lastDsID WHERE ei_data_set_structure_id = strId
                                            AND id NOT IN (SELECT id FROM copy_scenario_structure_into_dataset_structure_data_line);

                                        # Insertion dans la table temporaire.
                                        INSERT INTO copy_scenario_structure_into_dataset_structure_data_line VALUES (dlI, strId, @lastDsID);

                                        # Incrémentation du compteur.
                                        SET cpt2 = cpt2 + 1;
                                    END WHILE;

                                CLOSE curseurDataLines;

                                # Incrémentation du compteur.
                                SET cpt = cpt + 1;

                            END WHILE;
                        END IF;

                        CLOSE curseurStructure;

                    END IF;

                UNTIL done
                END REPEAT;

                OPEN curseurNotMatchedDataLines;
                    SET cpt2 = 1;
                    SELECT FOUND_ROWS() into nbElts2;

                    # Parcours des éléments.
                    WHILE (cpt2 <= nbElts2) DO
                        FETCH NEXT FROM curseurNotMatchedDataLines INTO dlI, dlDsI;

                        SET @scenarioStrId = dlDsI;

                        OPEN curseurMatchedDataSetDataLine;
                            SELECT FOUND_ROWS() into nbElts;

                            IF nbElts = 1 THEN
                                FETCH NEXT FROM curseurMatchedDataSetDataLine INTO dlDsI;

                                SET @dlDsI = dlDsI;

                                # Répercussion sur les lignes des jeux de données.
                                UPDATE ei_data_line SET ei_data_set_structure_id = @dlDsI WHERE ei_data_set_structure_id = @scenarioStrId
                                    AND id NOT IN (SELECT id FROM copy_scenario_structure_into_dataset_structure_data_line);

                                # Insertion dans la table temporaire.
                                INSERT INTO copy_scenario_structure_into_dataset_structure_data_line VALUES (dlI, @scenarioStrId, @dlDsI);
                            END IF;
                        CLOSE curseurMatchedDataSetDataLine;

                        # Incrémentation du compteur.
                        SET cpt2 = cpt2 + 1;
                    END WHILE;
                CLOSE curseurNotMatchedDataLines;

                IF haveToDrop = 0 THEN
                    # Création du lien FK sur le champ ei_data_set_structure_id de la table ei_data_line.
                    ALTER TABLE ei_data_line ADD CONSTRAINT FOREIGN KEY ei_data_line_ei_data_set_structure_id_ei_data_set_structure_id (ei_data_set_structure_id)
                        REFERENCES ei_data_set_structure(id) ON UPDATE RESTRICT ON DELETE CASCADE;
                END IF;

                SELECT * FROM copy_scenario_structure_into_dataset_structure;
                SELECT * FROM copy_scenario_structure_into_dataset_structure_data_line;

                COMMIT;
            END;
        ";

        $sqlCallCopy = "CALL copyScenarioStructureToDataSetStructure();";

        try{
            $this->log("Create roots elements for data set structure.");

            $this->log("Drop old procedure if exists.");
            $conn->execute($sqlDrop);
            $this->log("Create procedure.");
            $conn->execute($sqlCreate);
            $this->log("Call procedure.");
            $conn->execute($sqlCall);
            $this->log("Drop procedure.");
            $conn->execute($sqlDrop);

            $this->log("done...");


            $this->log("Copy scenario structure into empties data set structure.");

            $this->log("Drop old procedure if exists.");
            $conn->execute($sqlDropCopy);
            $this->log("Create procedure.");
            $conn->execute($sqlCreateCopy);
            $this->log("Call procedure.");
            $conn->execute($sqlCallCopy);
            $this->log("Drop procedure.");
            $conn->execute($sqlDropCopy);
        }
        catch( Exception $exc ){
            $this->log($exc->getMessage());
        }

/*
        $this->log('Récupération des paramètres...OK');

        // Création de la requête permettant
        $this->log('Création de la requête d\'insertion...');

        $requeteToInsert = "INSERT INTO ei_param_block_function_mapping (ei_param_function_id, created_at, updated_at, ei_function_id) ";
        $requeteToInsert.= "VALUES(#{PARAM_ID}, now(), now(), #{FONCTION_ID});";
        $pile = array();

        foreach( $parametres->fetchAll() as $parametre ){
            // Remplacement PARAM ID.
            $tmpRequete = str_replace("#{PARAM_ID}", $parametre["param_id"], $requeteToInsert);
            // Remplacement FONCTION ID.
            $tmpRequete = str_replace("#{FONCTION_ID}", $parametre["id"], $tmpRequete);

            // Ajout dans la requête globale.
            $pile[] = $tmpRequete;
        }

        // Préparation de la requête.
        $this->log("Préparation de la requête...");

        $requete = implode(" ",$pile);

        try{
            // Exécution de la requête.
            $this->log("Exécution de la requête...");

            $conn->execute($requete);

            // Fin.
            $this->log("Processus terminé avec succès.");
        }
        catch( Exception $exc ){
            $this->log($exc->getMessage());
        }
*/
    }
}