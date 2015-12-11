<?php

/**
 * Class CreateFunctionsOutParamsForMappingTask
 */
class CreateFunctionsOutParamsForMappingTask extends sfBaseTask
{
    /**
     *
     */
    public function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));
        $this->namespace = 'create';
        $this->name      = 'FunctionsOutParamsForMapping';
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

        /** @var $parametres Récupération des paramètres à charger */
        $parametres = $conn->execute("
            SELECT *
            FROM ei_function_has_param, ei_fonction
            WHERE param_type = 'OUT'
            AND (param_id, ei_fonction.id) NOT IN (SELECT ei_param_function_id, ei_function_id FROM ei_param_block_function_mapping)
            AND ei_function_has_param.function_ref = ei_fonction.function_ref
            AND ei_function_has_param.function_id = ei_fonction.function_id
        ");

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
    }
}