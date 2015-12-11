<?php

class UpdateOraclesTask extends sfBaseTask
{

    public function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));
        $this->namespace = 'update';
        $this->name      = 'oracles';
    }

    public function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager(sfProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true));
        $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true);
        sfContext::createInstance($configuration);
        $conn= Doctrine_Manager::connection();

        // Création de la requête permettant de mettre à jour les auteurs du test set.
        $titre = "Mise à jour des auteurs dans Test Set...";
        $this->log($titre.'[EN COURS]');

        try{
            $sql = "UPDATE ei_test_set ts SET author_id = COALESCE(
(SELECT guard_id FROM ei_user, ei_log WHERE ei_log.ei_test_set_id = ts.id AND ref_id = ei_log.user_ref AND ei_user.user_id = ei_log.user_id GROUP BY ei_log.ei_test_set_id), NULL);";

            $conn->execute($sql);

            $this->log($titre."[OK]");
        }
        catch( Exception $exc ){
            $this->log($titre."[ECHEC]");
            $this->log($exc->getMessage());
        }

        // Création de la requête permettant de mettre à jour les modes d'exécution du test set.
        $titre = "Mise à jour des modes...";
        $this->log($titre.'[EN COURS]');

        try{
            $sql = "UPDATE ei_test_set SET mode = 'Campaign' WHERE id IN (SELECT ei_test_set_id FROM ei_campaign_execution_graph);";

            $conn->execute($sql);

            $this->log($titre."[OK]");
        }
        catch( Exception $exc ){
            $this->log($titre."[ECHEC]");
            $this->log($exc->getMessage());
        }

        // Mise à jour
        $titre = "Création des statuts...";
        $this->log($titre."[EN COURS]");

        try{
            $sql = "SELECT ref_id, project_id FROM ei_projet WHERE (ref_id, project_id) NOT IN (SELECT project_ref, project_id FROM ei_test_set_state);";
            $sqlAdd = "INSERT INTO ei_test_set_state (name, color_code, state_code, project_id, project_ref, created_at, updated_at) VALUES ";
            $executes = array();
            $projets = $conn->execute($sql)->fetchAll();

            // On vérifie si des projets n'ont pas encore leurs statuts.
            if(count($projets) > 0){
                /** @var Doctrine_Connection_Statement $statement Création du statement */
                $statement = $conn->prepare($sqlAdd);

                foreach( $projets as $projet ){
                    $executes[] = "('Success', '#58A155', 'OK', ".$projet["project_id"].", ".$projet["ref_id"].", now(), now())";
                    $executes[] = "('Failed', '#D8473D', 'KO', ".$projet["project_id"].", ".$projet["ref_id"].", now(), now())";
                    $executes[] = "('Aborted', '#B9B5AF', 'NA', ".$projet["project_id"].", ".$projet["ref_id"].", now(), now())";
                }

                $conn->execute($sqlAdd . implode(",", $executes) . ";");
            }
            $this->log($titre."[OK]");
        }
        catch( Exception $exc ){
            $this->log($titre."[ECHEC]");
            $this->log($exc->getMessage());
        }
    }
} 