<?php

class NoticeConvertHtmlToUnicodeTask extends sfBaseTask
{
    public function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));
        $this->namespace = 'notice';
        $this->name      = 'convertHtmlToUnicode';
    }

    public function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager(sfProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true));
        $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true);
        sfContext::createInstance($configuration);
        $conn= Doctrine_Manager::connection();

        // Récupération de toutes les notices.
        $notices = $conn->execute("SELECT notice_ref, notice_id, version_notice_id, lang, description, expected, result FROM ei_version_notice;");
        $this->log('Récupération des notices...OK');

        // Création de la requête permettant
        $this->log('Création de la requête de mise à jour...');

        $requeteToUpdate = "UPDATE ei_version_notice SET description = DESC, expected = EXP, result = RES WHERE notice_ref = NOT_REF AND notice_id = NOT_ID AND version_notice_id = NOT_VE AND lang = 'NOT_LANG';";
        $requeteGlobale = array();

        foreach( $notices->fetchAll() as $notice ){
            // Remplacement description.
            $tmpRequete = str_replace("DESC", $conn->quote(html_entity_decode($notice["description"])), $requeteToUpdate);
            // Remplacement expected.
            $tmpRequete = str_replace("EXP", $conn->quote(html_entity_decode($notice["expected"])), $tmpRequete);
            // Remplacement result.
            $tmpRequete = str_replace("RES", $conn->quote(html_entity_decode($notice["result"])), $tmpRequete);

            // Remplacement des identifiants de la notice.
            $tmpRequete = str_replace("NOT_REF", $notice["notice_ref"], $tmpRequete);
            $tmpRequete = str_replace("NOT_ID", $notice["notice_id"], $tmpRequete);
            $tmpRequete = str_replace("NOT_VE", $notice["version_notice_id"], $tmpRequete);
            $tmpRequete = str_replace("NOT_LANG", $notice["lang"], $tmpRequete);

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
