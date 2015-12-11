<?php

class AdaptBlocksTypesTask extends sfBaseTask
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
        $this->namespace = 'adapt';
        $this->name      = 'BlocksTypes';
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

        $conn->execute("UPDATE ei_scenario_structure SET type = '".EiScenarioStructure::$TYPE_BLOCK."' WHERE type = 1");
        $this->log("Mise à jour des structures de scénarios de type " . EiScenarioStructure::$TYPE_BLOCK);

        $conn->execute("UPDATE ei_scenario_structure SET type = '".EiScenarioStructure::$TYPE_PARAM."' WHERE type = 2");
        $this->log("Mise à jour des structures de scénarios de type " . EiScenarioStructure::$TYPE_PARAM);
    }
}