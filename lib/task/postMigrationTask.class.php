<?php

/**
 * Class postMigrationTask
 */
class postMigrationTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'apply';
        $this->name = 'postMigration';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [postMigration|INFO] task apply post-migration batchs.
Call it with:

  [php symfony apply:postMigration|INFO]
EOF;
    }

    /**
     * Executes the current task.
     *
     * @param array $arguments An array of arguments
     * @param array $options An array of options
     *
     * @return integer 0 if everything went fine, or an error code
     */
    protected function execute($arguments = array(), $options = array()) {
        // Initialisation de la base de données.
        $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true);
        sfContext::createInstance($configuration);
        $conn = Doctrine_Manager::connection();

        $sql1 = "UPDATE ei_test_set_state SET state_code = 'ABORTED' WHERE name = 'Aborted';";
        $sql2 = "UPDATE ei_campaign_execution SET termine = 1;";
        $sql3 = "UPDATE ei_test_set SET termine = 1;";
        $sql4 = "
        INSERT INTO ei_test_set_state (name, color_code, state_code, project_id, project_ref, created_at, updated_at)

SELECT 'Processing', '#FFD300', 'NA', project_id, project_ref, NOW(), NOW()

FROM ei_test_set_state

WHERE (project_id, project_ref) NOT IN
(
SELECT project_id, project_ref
FROM ei_test_set_state
WHERE name = 'Processing'
GROUP BY project_id, project_ref
)


GROUP BY project_id, project_ref
        ";
        $sql5 = "UPDATE ei_campaign_execution_graph exg, ei_test_set_status_vw ts
SET state = (CASE WHEN ts.status_nom = 'Success' THEN 'Ok' WHEN ts.status_nom = 'Failed' THEN 'Ko' WHEN 'Aborted' THEN 'Aborted' ELSE 'Blank' END)
WHERE exg.ei_test_set_id = ts.id
;";

        $sql6 = "UPDATE ei_test_set ts LEFT JOIN (
SELECT
	CASE
		WHEN SUM(CASE WHEN func.status ='ko' THEN 1 else 0 end ) > 0 THEN 'KO'
		WHEN SUM(CASE WHEN func.status ='NA' THEN 1 else 0 end ) > 0 THEN 'AB'
		WHEN SUM(CASE WHEN (func.status ='processing' OR func.status = 'blank') THEN 1 else 0 end ) > 0 THEN 'AB'
		ELSE 'OK'
	END as statut, ts.id
FROM ei_test_set_function func, ei_test_set ts
WHERE ts.id = ei_test_set_id
GROUP BY ei_test_set_id
) tsf ON tsf.id = ts.id
SET ts.status = (CASE WHEN tsf.statut IS NULL THEN 'AB' ELSE tsf.statut END)
;
        ";

        $sql7 = "update kal_function set criticity='Blank' where criticity is NULL";

        try {
            $this->log("[INFO] Mise à jour du statut ABORTED.");
            $conn->execute($sql1);
            $this->log("[INFO] Passage de toutes les exécutions de campagne à l'état <terminé>.");
            $conn->execute($sql2);
            $this->log("[INFO] Passage de tous les jeux de test à l'état <terminé>.");
            $conn->execute($sql3);
            $this->log("[INFO] Ajout du statut <Processing> dans tous les projets ne le comportant pas.");
            $conn->execute($sql4);
            $this->log("[INFO] Mise à jour des statuts de campagne par rapport aux statuts des jeux de test.");
            $conn->execute($sql5);
            $this->log("[INFO] Mise à jour des statuts du jeu de tests.");
            $conn->execute($sql6);
            $this->log("Mise à jour des criticité de fonction à null. Remplacement par des criticités 'Blank'.");
            $conn->execute($sql7);

            /* Mise à jour des positions des éléments de l'arbre des scénarios */
            $this->log("Mise à jour des positions des éléments de l'arbre des scénarios.");
            /* trouver tous les root_id c'est-à-dire la liste des noeuds parents */
            $stmt = $conn->prepare("SELECT DISTINCT root_id FROM ei_node WHERE (type LIKE 'EiScenario' OR type LIKE 'EiFolder') AND root_id IS NOT NULL");
            $stmt->execute(array());
            $result = $stmt->fetchAll();
            foreach ($result as $root_id) {
                /* Pour chaque noeud parent, on trouve les id des fils */
                $parent_id = $root_id['root_id'];
                $stmt2 = $conn->prepare("SELECT id FROM ei_node WHERE root_id = :root_id ORDER BY position");
                $stmt2->bindValue("root_id", $parent_id);
                $stmt2->execute(array());
                $result2 = $stmt2->fetchAll();

                $position = 0;
                foreach ($result2 as $child_id) {
                    /* update de la position du fils */
                    $id = $child_id['id'];
                    $position++;
                    $stmt3 = $conn->prepare('UPDATE ei_node SET position = :position where id = :id');
                    $stmt3->bindValue("position", $position);
                    $stmt3->bindValue("id", $id);
                    $stmt3->execute(array());
                }
            }

            $this->transformDataSetTreeFromRecursiveToNested($conn);
        } catch (Exception $e) {
            $this->log("[ERROR] " . $e->getMessage());
        }
        /* Changement des statuts de livraison de "Close" à "Closed" */
        $conn->execute("update ei_delivery_state set name ='Closed' where name like '%Close%' ");
    }

    /**
     * @param Doctrine_Connection $conn
     */
    private function transformDataSetTreeFromRecursiveToNested(Doctrine_Connection $conn) {
        ini_set("memory_limit", "-1");

        /** @var EiDataSetTable $tableDs */
        $tableDs = Doctrine_Core::getTable("EiDataSet");
        // Requête permettant de supprimer les lignes de JDD orphelines.
        $sqlRemoveOrphans = "DELETE FROM ei_data_line WHERE ei_data_line_parent_id IS NULL AND ei_data_set_structure_id IS NULL;";
        // Requêtes permettant de récupérer les arbres des JDD à traiter.
        $sqlGetDS = "SELECT id FROM ei_data_set WHERE id IN (SELECT ei_data_set_id FROM ei_data_line WHERE lft IS NULL OR rgt IS NULL GROUP BY ei_data_set_id HAVING COUNT(ei_data_set_id) > 0);";
        // Update level from structure.
        $sqlUpdateLevel = "UPDATE ei_data_line dl, ei_data_set_structure dss SET dl.level = dss.level WHERE dl.ei_data_set_structure_id = dss.id";
        // Update root_id from root elements.
        $updateRootId = "UPDATE ei_data_line SET root_id = id WHERE root_id IS NULL AND level = 0;";

        try {
            $this->log("[INFO] ---   Début MAJ des arbres de JDD   ---");

            $this->log("[INFO] Suppression des orphelins.");
            $conn->execute($sqlRemoveOrphans);

            $this->log("[INFO] Récupération de la liste des jeux de données à mettre à jour.");
            $dataSets = $conn->execute($sqlGetDS)->fetchAll();
            $this->log("[INFO] " . count($dataSets) . " jeu(x) de données à mettre à jour.");

            if (count($dataSets) > 0) {
                foreach ($dataSets as $dataSet) {
                    /** @var EiDataSet $ds */
                    $ds = $tableDs->find($dataSet["id"]);

                    $ds->updateDataLines($ds->generateOldXML());
                }
            }
            $this->log("[INFO] Jeux de données mis à jour.");

            $conn->execute($sqlUpdateLevel);
            $this->log("[INFO] Mise à jour des niveaux de chaque ligne relativement à la structure.");

            $conn->execute($updateRootId);
            $this->log("[INFO] Mise à jour du root_id pour chaque élément root.");

            $this->log("[INFO] ---   Fin MAJ des arbres de JDD   ---");
        } catch (Exception $e) {
            $this->log("[ERROR] " . $e->getMessage());
        }
    }

}

?>