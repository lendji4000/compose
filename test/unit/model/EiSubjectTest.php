<!-- http://trac.symfony-project.org/browser/branches/1.4/test/unit/form/sfFormTest.php?rev=33598 -->

<!--Tests sur les méthodes de la classe EiScenario -->
<?php
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t=new lime_test(12,new lime_output_color());
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
reloadProjectAndPrintOutPut( $kalifast_project, $t);

/* récupération du profil par défaut du projet kalifast */

$default_profile=$kalifast_project->getDefaultProfil();

$t->isnt($default_profile, null, 'Profil par défaut du projet kalifast récupéré');

/* Vérification du nombre de package dans le système avant la création du bug */
$nbPacksBefore=count(Doctrine_Core::getTable('EiTicket')->findAll());

/* Création d'un bug */
 
$t->comment(MyFunction::getPrefixPath()); 
$ei_subject=createSubject($kalifast_project,$superAdmin);

$t->isnt($ei_subject, null, 'Bug crée avec succès ...');
/* Vérification des paramètres package_id et package_ref du bug */
$t->isnt($ei_subject->getPackageId(), null, 'Id du package non null ...');
$t->isnt($ei_subject->getPackageId(), 0, 'Id du package différent de 0 ...');
$t->isnt($ei_subject->getPackageRef(), 0, 'Ref du package différent de null ...');
$t->isnt($ei_subject->getPackageRef(), 0, 'Ref du package différent de 0 ...');

/* Rechargement du projet */
reloadProjectAndPrintOutPut( $kalifast_project, $t);
/* Vérification du nombre de package dans le système */
$nbPacksAfter=count(Doctrine_Core::getTable('EiTicket')->findAll());

/* vérification de la cohérence */
$t->is($nbPacksBefore+1, $nbPacksAfter, 'Equilibre des packages : cas de la création d un bug ...');

/* On tente ensuite de faire appel à la fonction getPackage() du bug et teste les retours pour s'assurer qu'il n'ya pas eu une anomalie */

$t->comment('Edition d un bug et verification de la variation des tickets ...');
$ei_subject->getPackage();
/* Rechargement du projet */
reloadProjectAndPrintOutPut( $kalifast_project, $t);
/* Vérification du nombre de package dans le système avant la création du bug */
$nbPacksBefore2=count(Doctrine_Core::getTable('EiTicket')->findAll());
/* Vérification du nombre de package dans le système */
$nbPacksAfter2=count(Doctrine_Core::getTable('EiTicket')->findAll());

/* vérification de la cohérence */
$t->is($nbPacksBefore2, $nbPacksAfter2, 'Equilibre des packages : cas d edition d un bug...');

function rechargerProjet(EiProjet $ei_project){
    $project_xmlfile = $ei_project->downloadKalFonctions();
        if ($project_xmlfile != null) {
            //si le fichier xml obtenu n'est pas vide
            $r = $ei_project->transactionToLoadObjectsOfProject($project_xmlfile); 
            return true;
        }
        return false;
}
/* Création d'un bug */
function createSubject(EiProjet $ei_project, $superAdmin) { 
    $ei_subject = new EiSubject(); 
    $ei_subject->setName("Bug".time());
    $ei_subject->setAuthorId($superAdmin->getId());
    $ei_subject->setProjectId($ei_project->getProjectId());
    $ei_subject->setProjectRef($ei_project->getRefId());  
    $ei_subject->save();
    return $ei_subject;
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

function reloadProjectAndPrintOutPut(EiProjet $ei_project,lime_test $t){
    $res2=rechargerProjet($ei_project);
    if($res2):
        $t->comment('Projet kalifast rechargé avec succès ...'); 
        else:
        $t->comment('Echec du rechargement du projet kalifast ...'); 
    endif;
}
?>