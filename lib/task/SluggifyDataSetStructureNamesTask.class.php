<?php

class SluggifyDataSetStructureNamesTask extends sfBaseTask
{
    public function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));
        $this->namespace = 'sluggify';
        $this->name      = 'DataSetStructureNames';
    }

    public function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager(sfProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true));
        $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true);
        sfContext::createInstance($configuration);
        $conn= Doctrine_Manager::connection();

        // Récupération de toutes les notices.
        $noeuds = $conn->execute("SELECT id, name FROM ei_data_set_structure;");
        $this->log('Récupération des noeuds...OK');

        // Création de la requête permettant
        $this->log('Création de la requête de mise à jour...');

        $requeteToUpdate = "UPDATE ei_data_set_structure SET slug = #{NEW_SLUG} WHERE id = #{NODE_ID};";
        $requeteGlobale = array();

        foreach( $noeuds->fetchAll() as $noeud ){
            // Remplacement SLUG.
            $tmpRequete = str_replace("#{NEW_SLUG}", $conn->quote(MyFunction::sluggifyForXML($noeud["name"])), $requeteToUpdate);
            // Remplacement ID.
            $tmpRequete = str_replace("#{NODE_ID}", $noeud["id"], $tmpRequete);

            // Ajout dans la requête globale.
            $requeteGlobale[] = $tmpRequete;
        }

        // Préparation de la requête.
        $this->log("Préparation de la requête...");

        $requete = implode(" ",$requeteGlobale);

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
