<!-- http://trac.symfony-project.org/browser/branches/1.4/test/unit/form/sfFormTest.php?rev=33598 -->

<!--Tests sur les méthodes de la classe EiScenario -->
<?php
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t=new lime_test(6,new lime_output_color());
//Instanciation du contexte  
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);
$context=sfContext::createInstance($configuration); 

/* Création d'une instance request et d'une connexion à la base de test */
$dispatcher=new sfEventDispatcher();
$request=  new sfWebRequest($dispatcher);
$conn= Doctrine_Manager::connection();

$request->setParameter('login', 'admin'); 
$request->setParameter('pwd', 'eisgeeisge2010');   

/* Connexion distante */
$t->comment('Connexion distante en tant que super utilisateur ...');
$login='admin';$pwd='eisgeeisge2010';
$project_id=51;$project_ref=2;
$result_connect =  distantConnect($login, $pwd,$context);

$t->is($result_connect, true, 'Connexion distante effectuée avec succes ...');

$t->comment('Récupération du super utilisateur et connexion à la plate forme compose ...');
/* Récupération du super user et connexion  */
$superAdmin =Doctrine_Core::getTable('sfGuardUser')->findOneByUsername($login); //Connexion en tant que super admin
$context->getUser()->signIn($superAdmin);

$t->is($superAdmin->getUsername(), 'admin', 'Super utilisateur bien récupéré');



$t->comment('Rechargement des projets sur compose ...');
//Recupération des projets sur la plate forme centrale 
$xmlfile = Doctrine_Core::getTable('EiProjet')->downloadKalProjet($superAdmin->getUserName());

if ($xmlfile != null) {//si le fichier xml obtenu n'est pas vide
    try {
        //Transaction de rechargement d'un projet
        $t->comment('Rechargement des principaux objets du projet ...');
        $r = Doctrine_Core::getTable('EiProjet')->transactionToLoadProject($xmlfile,$superAdmin->getId());
    } catch (Exception $e) {
        $t->comment('Echec du rechargement des principaux objets du projet...');
    }
     
}
else
    $t->comment('Echec du rechargement des projets ...'); 

/*Récupération du projet kalifast*/
$kalifast_project=new EiProjet();
$kalifast_project=Doctrine_Core::getTable('EiProjet')->findOneByProjectIdAndRefId(
        $project_id,$project_ref);

$t->isnt($kalifast_project, null, 'Project kalifast récupéré');

/* Chargement du projet kalifast */
$project_xmlfile = $kalifast_project->downloadKalFonctions();
        if ($project_xmlfile != null) {
            //si le fichier xml obtenu n'est pas vide
            $r = $kalifast_project->transactionToLoadObjectsOfProject($project_xmlfile);
            $t->comment('Projet kalifast rechargé avec succès ...'); 
        }
        else $t->comment('Echec du rechargement du projet kalifast ...'); 
/* Création d'un scénario au projet kalifast */

$default_profile=$kalifast_project->getDefaultProfil();

$t->isnt($default_profile, null, 'Profil par défaut du projet kalifast récupéré');

/* Récupération du noeud racine du projet dans lequel on va mettre le scénario */
$root_node=$kalifast_project->getRootFolder();

$t->isnt($root_node, null, 'Noeud racine récupéré avec succes...');

$ei_scenario=createAndSaveScenario($kalifast_project,$root_node);

$t->isnt($ei_scenario, null, 'Scénario crée avec succès ...');

/* Création d'un scénario dans un dossier de projet */
function createAndSaveScenario(EiProjet $ei_project, EiNode $root_node) {
    //On crée le noeud du scénario avant de créer le scénario en soit
    $ei_node = new EiNode(); 
    $ei_scenario = new EiScenario();
            $ei_node->setIsRoot(false);
            $ei_node->setProjectId($ei_project->getProjectId());
            $ei_node->setProjectRef($ei_project->getRefId());
            $ei_node->setName('testNomScenario');
            $ei_node->setPosition(Doctrine_Core::getTable('EiNode')->getLastPositionInNode(
                    $ei_project->getProjectId(),$ei_project->getRefId(),$root_node->getId()));
            $ei_node->setType('EiScenario');
            $ei_node->setRootId($root_node->getId());  
    $ei_scenario->project_id = $ei_project->getProjectId();
    $ei_scenario->project_ref = $ei_project->getRefId();
    $ei_scenario->setNomScenario('testNomScenario');
    $ei_scenario->setEiNode($ei_node);
    $ei_scenario->save();
    $ei_node->setObjId($ei_scenario->getId()); 
    $ei_node->save();
    return $ei_scenario;
}

/* Connexion distante */
function distantConnect($login,$pwd,$context){
     $result_connect = MyFunction::connexionDistante($login, $pwd);
     if ($result_connect == null) throw new Exception('Empty File . Contact administrator');
      if (is_array($result_connect)) {
                $guard_tab = $result_connect['guard_tab'];

                //On vérifie que l'utilisateur n'est pas désactivé coté script
                if ($guard_tab['is_active'] != 1)  
                    throw new Exception('Inactive user . Contact administrator to no more about'); 
                
                $ei_user_tab = $result_connect['ei_user_tab'];
                $guard_user = Doctrine_Core::getTable("sfGuardUser")->findOneBy('username', $guard_tab['username']);

                //Si l'utilisateur se connecte pour la premiere fois ou s'il n'est pas enregistré , on le crée
                if (!$guard_user || $guard_user == null)  
                    $guard_user = Doctrine_Core::getTable('sfGuardUser')->createUser($guard_tab, $ei_user_tab);
                
                $context->getUser()->setAttribute('user_id', $guard_user->getId(), 'sfGuardSecurityUser');
                $context->getUser()->setAuthenticated(true);
                
                return true;
 
            } else {
                if ($result_connect == null)
                    throw new Exception('Connexion error , unexpected raison ... Contact administrator'); 
                else
                    throw new Exception($result_connect); 
            } 
}
?>