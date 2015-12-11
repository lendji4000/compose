<?php

/**
 * Class postMigrationTask
 */
class preMigrationTask extends sfBaseTask
{
    const INDEX_KEY_COLUMN = "Key_name";
    const INDEX_COLNAME_COLUMN = "Column_name";

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'apply';
        $this->name = 'preMigration';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [postMigration|INFO] task apply pre-migration batchs.
Call it with:

  [php symfony apply:preMigration|INFO]
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
    protected function execute($arguments = array(), $options = array())
    {
        // Initialisation de la base de données.
        $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true);
        sfContext::createInstance($configuration);
        $conn = Doctrine_Manager::connection();

        try{
            $this->log("[INFO] Nettoyage des index superflus.");
            $this->cleanPointlessIndexes($conn);
        }
        catch(Exception $e){
            $this->log("[ERROR] " . $e->getMessage());
        }
    }

    /**
     * Méthode regardant pour toutes les tables les colonnes qui ont plusieurs index. On supprime alors la/les superflus.
     * La priorité est donnée aux index définis par l'utilisateur.
     *
     * @param Doctrine_Connection $conn
     */
    private function cleanPointlessIndexes(Doctrine_Connection $conn)
    {
        ini_set("memory_limit", "-1");

        $sqlDeleteIndex = "ALTER TABLE :table_name DROP INDEX :index_name;";
        $sqlRequests = array();

        $conn->beginTransaction();

        try{
            $table = Doctrine_Core::getTable("EiCampaignExecutionGraph");

            $indexesGraphId = $conn->execute("SHOW INDEX FROM " . $table->getTableName()." WHERE Non_unique = 1 AND Column_name = 'graph_id';")->fetchAll();
            $indexesVersionId = $conn->execute("SHOW INDEX FROM " . $table->getTableName()." WHERE Non_unique = 1 AND Column_name = 'version_id';")->fetchAll();

            if( count($indexesGraphId) == 1 && $indexesGraphId[0]["Key_name"] == "ei_campaign_execution_graph_graph_id_idx" ){
                $sqlRequests[] = "ALTER TABLE ei_campaign_execution_graph ADD INDEX graph_id_index_idx (graph_id);";
                $sqlRequests[] = "ALTER TABLE ei_campaign_execution_graph DROP INDEX ei_campaign_execution_graph_graph_id_idx;";
            }

            if( count($indexesVersionId) == 1 && $indexesVersionId[0]["Key_name"] == "ei_campaign_execution_graph_version_id_idx" ){
                $sqlRequests[] = "ALTER TABLE ei_campaign_execution_graph ADD INDEX version_id_index_idx (version_id);";
                $sqlRequests[] = "ALTER TABLE ei_campaign_execution_graph DROP INDEX ei_campaign_execution_graph_version_id_idx;";
            }

            // On récupère tous les index de la table.
            $tableIndexes = $conn->execute("SHOW INDEX FROM " . $table->getTableName()." WHERE Non_unique = 1;")->fetchAll();

            $tableIndexes = $this->groupIndexes($tableIndexes);
            $relations = $table->getRelations();
            $tableOptions = $table->getOptions();
            $userIndexes = $tableOptions["indexes"];
            $userRelationsIndexes = array();

            // Parcours la liste des relations et vérifie si ce dernier est indexé par un index utilisateur.
            /** @var Doctrine_Relation_LocalKey $relation */
            foreach( $relations as $relation ){
                $indexed = false;

                foreach( $userIndexes as $index ){
                    if( count($index["fields"]) == 1 && $index["fields"][0] == $relation->getLocalColumnName() ){
                        $indexed = true;
                        break;
                    }
                }

                $userRelationsIndexes[$relation->getLocalColumnName()] = $indexed;
            }

            /**
             * Pour chaque index en base de données, je vérifie s'il est superflu ou non.
             */
            foreach( $tableIndexes as $indexName => $index ){
                $realIndex = substr($indexName, 0, -4);
                $columnIndex = $index[0][self::INDEX_COLNAME_COLUMN];

                // Si index utilisateur...
                if( array_key_exists($realIndex, $userIndexes) ){
                    // Nothing to do.
                }
                // Sinon...
                else{
                    // On vérifie si la colonne fait référence à une clé étrangère.
                    $related = false;

                    /** @var Doctrine_Relation_LocalKey $relation */
                    foreach( $relations as $relation ){
                        if( $relation->getLocalColumnName() == $columnIndex ){
                            $related = true;
                            break;
                        }
                    }

                    // Si lié à un FK et sans index utilisateur...on garde.
                    if( $related && !$userRelationsIndexes[$columnIndex] ){
                        $userRelationsIndexes[$columnIndex] = true;
                    }
                    else{
                        $sqlRequests[] = str_replace(":table_name", $table->getTableName(), str_replace(":index_name", $indexName, $sqlDeleteIndex));

                        $this->log("[INFO] Suppression de l'index ".$indexName." de la table ".$table->getTableName().".");
                    }
                }
            }

            if( count($sqlRequests) > 0 ){
                $conn->execute(implode(" ", $sqlRequests));
            }

            $conn->commit();
            $this->log("[INFO] Nettoyage des index superflus terminé.");
        }
        catch(Exception $exc){
            $conn->rollback();
            $this->log("[ERROR] " . $exc->getMessage());
        }
    }

    /**
     * @param $indexes
     * @return array
     */
    private function groupIndexes($indexes){
        $newIndexes = array();

        foreach( $indexes as $index ){
            $newIndexes[$index[self::INDEX_KEY_COLUMN]][] = $index;
        }

        return $newIndexes;
    }
}
?>