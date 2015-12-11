<?php

class reorderProjectNodesTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'reoder';
    $this->name             = 'projectNodes';
    $this->briefDescription = 'réordonner les noeuds d\'un projet si ces derniers sont mals ordonnés ';
    
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
//    $databaseManager = new sfDatabaseManager($this->configuration);
//    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', $options['env'], true);
    sfContext::createInstance($configuration);
    $conn= Doctrine_Manager::connection();
    $this->log('Récupération des noeuds de dossier et scénarios'); 
    //Création de la vue sql 
    $ei_nodes=$conn->execute("SELECT id ,type FROM ei_node where type='EiFolder' or type='EiScenario'" )  ;
    
    if(count($ei_nodes) > 0):
        foreach ($ei_nodes  as $ei_node ):
            //throw new Exception($ei_node['id']);
            $conn->execute("
                SET @rank=0;
                update 
                ei_node n 
                ,(
                SELECT   id,@rank := @rank+1 AS rownum 
                FROM     ei_node  
                where  (root_id=".$ei_node['id']." and (type='EiScenario' or type='EiFolder')) 
                ORDER BY  position asc) src
                set n.position=src.rownum
                where  (n.root_id=".$ei_node['id']." and n.id=src.id and (n.type='EiScenario' or n.type='EiFolder'))
                    " )  ;
        endforeach; 
    endif;
     
  $this->log('Noeud réordonnés'); 
  }
}
