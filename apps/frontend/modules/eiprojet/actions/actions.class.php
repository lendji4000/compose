<?php

/**
 * eiprojet actions.
 *
 * @package    kalifast
 * @subpackage eiprojet
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiprojetActions extends sfActionsKalifast {

    public function executeIndex(sfWebRequest $request) {

        //Néttoyage des variables de session lors de l'accès à la page d'accueil
        $this->cleanSessionVar();
        //$this->ei_projets = Doctrine_Core::getTable('EiProjet')->findUserProjet($this->getUser()->getGuardUser()->id);
        
        $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();
        $this->ei_projets = Doctrine_Core::getTable('EiProjet')->findUserProjects($this->ei_user);
        //$this->defaultUserProfil = $this->ei_user->getDefaultProfile($this->ei_project); //recherche d'un profil par défaut pour l'utilisateur
        $this->profile_name = $request->getParameter('profile_name');
        if (!isset($this->ei_projets)) {
            $this->getUser()->setFlash('undefine_project', sprintf('No project found for listing.contact administrator system'));
        } else {
            if ($this->ei_projets == null) {
                $this->getUser()->setFlash('no_project', sprintf('No project belonging to you was found on the central system.'));
            }
        }
    }

    public function cleanDeliveryAndSubjectSessions() {
        //néttoyage des variables de session de la livraison 
        if ($this->getUser()->getAttribute('current_delivery_project_ref') != $this->project_ref ||
                $this->getUser()->getAttribute('current_delivery_project_id') != $this->project_id)
            $this->cleanDeliverySessionVar();
        //néttoyage des variables de session du sujet
        if ($this->getUser()->getAttribute('current_subject_project_ref') != $this->project_ref ||
                $this->getUser()->getAttribute('current_subject_project_id') != $this->project_id)
            $this->cleanSubjectSessionVar();
    }

    /**
     * Effectue le rechargement des projets en récupérant leur noms.
     * La liste des projets auxquel l'utilisateur a accés est donc mise à jour.
     * Il est ensuite redirigé vers la liste des projets (retour à la page elle même)
     * @param sfWebRequest $request
     * @return type
     */
    public function executeDownloadKalProjet(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated())
            $this->login = $this->getUser()->getGuardUser()->getUsername();
        else
            $this->login = null;
        if ($this->login != null) {
            $this->login = $this->login;
            $this->xmlfile = Doctrine_Core::getTable('EiProjet')->downloadKalProjet($this->login, $request);

            if ($this->xmlfile != null) {//si le fichier xml obtenu n'est pas vide
                try {
                    //Transaction de rechargement d'un projet
                    $r = Doctrine_Core::getTable('EiProjet')->transactionToLoadProject($this->xmlfile, $this->getUser()->getGuardUser()->id);
                } catch (Exception $e) {
                    //throw $e;
                    $this->getUser()->setFlash('reload_error', $e->getMessage());
                    return $this->redirect('eiprojet/index');
                }
                $this->getUser()->setFlash('reload_success', 'Projects status has been checked successfully.', true);
            } else
                $this->getUser()->setFlash('reload_error', 'Error occured when trying to update projects.', true);
        }
        else {
            // si le user n'est plus connecté, on le redirige sur la page de connexion.
            $this->getUser()->setFlash('error_connexion', 'You are not logged in anymore.');
            return $this->redirect('homepage');
        }

        //Nettoyage des variables de session 
        $this->cleanSessionVar();
        return $this->redirect('eiprojet/index');
    }

    /**
     * Effectue le rechargement d'un projet spécifique.
     * Pour cela la requete doit comporter les paramètres
     * project_id et project_ref
     * @param sfWebRequest $request
     */
    private function reloadProject(sfWebRequest $request) {
        $this->checkProject($request); //Recherche du projet

        $this->xmlfile = $this->ei_project->downloadKalFonctions($request);

        if ($this->xmlfile != null) {
            //si le fichier xml obtenu n'est pas vide
            $r = $this->ei_project->transactionToLoadObjectsOfProject($this->xmlfile);
            $this->getUser()->setFlash('reload_success', 'Project updated successfuly.', true);
        } else
            $this->getUser()->setFlash('reload_error', "An error occurred while trying to update the project.", true);
    }

    /**
     * Action appelée toutes les n secondes depuis l'édition de scénario.
     * On vérie s'il faut recharger l'arbre ou non.
     * Si true est renvoyé, alors l'arbre sera rechargé, sinon aucun retour ne sera effectué.
     * 
     * @param sfWebRequest $request
     * @return type 
     */
    public function executeCheckVersion(sfWebRequest $request) {
        $this->checkProject($request); //Recherche du projet
        $JSONResponse = array();
        //si le projet a besoin d'être MAJ, alors on renvoie vrai pour le reload
        // et faux sinon.
        if ($this->ei_project->needsReload())
            $JSONResponse['reload'] = true;
        else
            $JSONResponse['reload'] = false;
        return $this->renderText(json_encode($JSONResponse));
    }

    public function executeDownloadKalFonctions(sfWebRequest $request) {
        $this->reloadProject($request);

        if ($request->getParameter('redirect'))
            return $this->redirect($request->getParameter('redirect'));
        else {
            $this->reloadProjet = false;
            return $this->renderComponent('eiprojet', 'arbreProjet');
        }
    }

    /**
     * Recharge le projet si cela est nécessaire dés lors qu'on le consulte.
     * @param sfWebRequest $request
     * @return type 
     */
    public function executeReloadAfterShow(sfWebRequest $request) {
        $this->reloadProject($request);

        $this->setProjetAndProfil($request);
        $JSONResponse = array();
        $JSONResponse['redirect'] = $this->generateUrl('projet_eiscenario', array('project_id' => $this->ei_project->getProjectId(),
            'project_ref' => $this->ei_project->getRefId(),
            'profile_id' => $this->profile_id,
            'profile_ref' => $this->profile_ref,
            'profile_name' => $this->profile_name,
            'action' => 'index'));

        return $this->renderText(json_encode($JSONResponse));
    }

    protected function getLastScenarioExecution(EiProjet $ei_project) {
        $q = Doctrine_Core::getTable('EiLogFunction')->getLastScenarioExecution($ei_project)->execute();
        if (count($q) > 0)
            return $q->getFirst();
        return null;
    }

    public function executeShow(sfWebRequest $request) {
        $this->setProjetAndProfil($request);
        if ($this->profile_id == 0 || $this->profile_ref == 0)
            return $this->redirect('@project_first_load?project_id=' . $this->project_id . '&project_ref=' . $this->project_ref);

        if ($this->ei_project == null)
            $this->forward404('Project not found');

        $this->guardUser = $this->getUser()->getGuardUser();
        //Si un seul résultat est trouvé , on redirige vers l 
        //Création des statuts par défaut du projet
        $this->ei_project->createDefaultStates(); 

        /* On récupère tous les statuts de bugs différent de "Close" et on les passe en paramètre pour la recherche */
        $states = Doctrine_Core::getTable('EiSubjectState')->getSubjectStateForProjectQuery(
                        $this->ei_project->getProjectId(), $this->ei_project->getRefId())->execute(); 
        $this->projectBugsStates=$states;
        $stateTabTodolist = array();
        if (count($states) > 0):
            foreach ($states as $i => $state): 
                if ($state->getDisplayInTodolist())
                    $stateTabTodolist[$i] = $state->getId(); //Statuts de bug à ramener dans la todolist utilisateur
            endforeach;

        endif; 
        /* Récupération des livraisons ouvertes  dans l'ordre décroissante des date de livraison */
//        $this->openDeliveries = $this->checkOpenDeliveries($this->ei_project);
        /* Recherche de la prochaine livraison */

        //Récupération des livraisons ouvertes du projet 
        $dashBoardProjDels=Doctrine_core::getTable('EiDelivery')->getDashBoardProjDels($this->ei_project->getProjectId(),$this->ei_project->getRefId(),$this->guardUser->getId());
        $conn = Doctrine_Manager::connection();
        $projDelIds=$conn->fetchAll("select d.id from ei_delivery d inner join ei_delivery_state ds on d.delivery_state_id=ds.id where ds.close_state=0 and d.project_id=".$this->ei_project->getProjectId()." and d.project_ref=".$this->ei_project->getRefId()." order by d.delivery_date desc;");
        
        if(count($projDelIds) > 0): //Des livraisons existent sur le projet
            $tabDels=array();
            
            if(count($dashBoardProjDels)>0):  
                foreach($projDelIds as $projDelId):
                    foreach($dashBoardProjDels as $del):
                        if($projDelId['id']==$del['d_id']):
                            $tabDels[$projDelId['id']][$del['st_id']]=$del;
                        endif;
                    
                    endforeach;
                endforeach;    
            endif;
            $this->tabDels=$tabDels;
        endif;
        
         

        /* Récupération des bugs à traiter de l'utilisateur courant par type "kalifast" , "defects" , etc ... dans la limite de 10 chacun */
        $this->searchSubjectCriteriaToDoList = array();
        $this->searchSubjectCriteriaToDoList['assignment'] = $this->guardUser->getUsername();  
        $this->searchSubjectCriteriaToDoList['state'] = $stateTabTodolist;
        if (count($stateTabTodolist) > 0):  //Statuts de bug à ramener dans la todolist utilisateur
            $this->kalifastUserBugs = $this->checkKalifastUserBugs($this->ei_project, $this->searchSubjectCriteriaToDoList);
            $this->defectsUserBugs = $this->checkDefectsUserBugs($this->ei_project, $this->searchSubjectCriteriaToDoList);
            $this->serviceRequestUserBugs = $this->checkServiceRequestUserBugs($this->ei_project, $this->searchSubjectCriteriaToDoList);
            $this->enhancementUserBugs = $this->checkEnhancementUserBugs($this->ei_project, $this->searchSubjectCriteriaToDoList);
        endif;

        $this->cleanDeliveryAndSubjectSessions(); //On néttoie les variables de session des livraisons et des sujets en cas de changement du projet courant
    }
 
 
 
 
    public function executeFirstLoad(sfWebRequest $request) {
        $this->checkProject($request); //Recherche du projet

        $this->reloadProjet = true;
        $this->setLayout(false);
        return $this->setTemplate("firstLoad");
    }

    /**
     * Fonction permettant l'initialisation des valeurs des variables
     * project_id, profile_id, profile_name, ei_project et defaultProfil
     * @param sfWebRequest $request 
     */
    protected function setProjetAndProfil(sfWebRequest $request) {
        $this->checkProject($request); //Recherche du projet
        $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();
        $this->profile_id = $request->getParameter('profile_id');
        $this->profile_ref = $request->getParameter('profile_ref');
        $this->profile_name = $request->getParameter('profile_name');
        if (!(isset($this->profile_id) && isset($this->profile_name)) || ($this->profile_id == 0 )) : //Aucun profil courant 
            $this->defaultUserProfil = $this->ei_user->getDefaultProfile($this->ei_project); //recherche d'un profil par défaut pour l'utilisateur
            if ($this->defaultUserProfil != null) :
                $this->profile_id = $this->defaultUserProfil->getProfileId();
                $this->profile_ref = $this->defaultUserProfil->getProfileRef();
                $this->profile_name = $this->defaultUserProfil->getName();
             else : //Aucun profil par défaut définit pas l'utilisateur : 
                $this->defaultProfil = $this->ei_project->getDefaultProfil();
                if($this->defaultProfil != null): // Recherche d'un profil par défaut au niveau du projet
                    $this->profile_id = $this->defaultProfil->getProfileId();
                    $this->profile_ref = $this->defaultProfil->getProfileRef();
                    $this->profile_name = $this->defaultProfil->getName();
                else: //Pas de profil utilisateur par défaut et pas de profil par défaut sur le projet
                $this->profile_id = 0;
                $this->profile_ref = 0;
                $this->profile_name = 'profil';
                endif;
            endif;
        endif;
    }

    /**
     * Action appelée lorsque l'utilisateur change de projet depuis la liste déroulante du menu
     * gauche. Elle redirige sur l'action show. 
     * 
     * @param sfWebRequest $request
     * @return type 
     */
    public function executeShowOnProjetListChange(sfWebRequest $request) {
        $this->setProjetAndProfil($request);
        //Sauvegarde du profil en session utilisateur  
        $this->setProfileSession($this->profile_name, $this->profile_id, $this->profile_ref);
        return $this->redirect($this->generateUrl('projet_show', array('project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name)));
    }

}
