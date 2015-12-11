<?php

/**
 * eifonction actions.
 *
 * @package    kalifast
 * @subpackage eifonction
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eifonctionActions extends sfActionsKalifast {

    public function preExecute() {
        parent::preExecute();

        // Récupération de l'utilisateur.
        $this->guard_user = $this->getUser()->getGuardUser();
        $this->ei_user = $this->guard_user->getEiUser();
    }

    /* Cette fonction permet de rechercher la fonction (EiFonction) avec les paramètres renseignés.  */

    public function checkEiFonction(sfWebRequest $request, EiProjet $ei_project) {
        $this->ei_fonction_id = $request->getParameter('ei_fonction_id');

        if ($this->ei_fonction_id != null) {
            //Recherche de la fonction en base
            $this->ei_fonction = Doctrine_Core::getTable('EiFonction')
                    ->findOneByIdAndProjectIdAndProjectRef(
                    $this->ei_fonction_id, $ei_project->getProjectId(), $ei_project->getRefId());
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->ei_fonction == null)
                $this->ei_fonction = null;
        }
        else {
            $this->ei_fonction_id = null;
        }
    }

    public function executeShow(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);  //Récupération du profil
        $this->ei_fonction = Doctrine_Core::getTable('EiFonction')->findOneBy('id', $request->getParameter('id'));
        if ($this->ei_fonction == null) {
            //gestion de l'erreur en cas d 'absence de la fonction
            if ($request->getPathInfoPrefix() != null)
                $this->forward404('fonction  introuvable!! l\' identificateur n\'est pas spécifié');
            //on est en mode dev
            $message = 'Fonction  introuvable!! l\' identificateur n\'est pas spécifié';
            $request->setParameter('msg', $message);
            $request->setParameter('back_link', $request->getReferer());
            $this->forward('erreur', 'error404');
        }
        $this->ei_version = Doctrine_Core::getTable('EiVersion')->findOneBy('id', $this->ei_fonction->getEiVersion()->id);
        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->findOneBy('id', $this->ei_fonction->getEiVersion()->getEiScenario()->id);
    }

    public function executeMajPos(sfWebRequest $request) {

        $this->tab = $request->getParameter("tab");
        $id_version = $request->getParameter('id_version');
        $ei_scenario_id = $request->getParameter('ei_scenario_id');
        echo $id_version . '    ' . $ei_scenario_id;
        echo $this->tab;

        if (($this->tab != null) && ($id_version != null) && ($ei_scenario_id != null)) {
            Doctrine_Core::getTable('EiFonction')->majPos($this->tab);
        }
    }

    public function executePlayOnRobot(sfWebRequest $request) {
        
    }

    public function executeGenerateRobotCode(sfWebRequest $request) {
        // recuperation du tableau des  paramètres
        $tab = $request->getParameter('playOnRobotForm');
        if ($tab['id_fonction'] != null) {
            //verifie l'existance d'un paramètre de jeu de la fonction ( url de depart, navigateur, version ou ip du robot
            if (($tab['url_depart'] != null) || ($tab['navigateur'] != null) || ($tab['version_navigateur'] != null) || ($tab['ip_robot'] != null) || ($tab['vitesse_jeu'] != null) || ($tab['environnement'] != null)) {
                $this->code_robot = Doctrine_Core::getTable('EiFonction')->generateRobotCode($tab);
                $this->playOnRobotForm = new playOnRobotForm($tab);
            } else {
                echo 'Pas de paramètre d\'entrée!!';
                $this->playOnRobotForm = new playOnRobotForm(null);
            }
        } else {
            $this->playOnRobotForm = new playOnRobotForm(null);
        }
    }

    public function executeGenerateXML(sfWebRequest $request) {

        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);  //Récupération du profil
        $this->checkEiFonction($request, $this->ei_project); //Récupération de la EiFonction

        if ($this->ei_profile != null && $this->ei_fonction != null) {
            $this->kal_function = $this->ei_fonction->getKalFonction(); //Récupération de la KalFunction
            //Récupération des paramètres de la EiFonction
            $this->ei_params = Doctrine_Core::getTable('EiParam')->findByIdFonction($this->ei_fonction->getId());
            //Récupération des paramètres du profil
            $this->profileParams = $this->ei_profile->getParams();
            //Conctruction du fichier xml
            $this->xmlfile = Doctrine_Core::getTable('EiFonction')
                    ->generateXMLForPHP($this->ei_fonction, $this->kal_function, $this->ei_profile, $this->ei_params, $this->profileParams, $this->prefix);
        } else {
            $this->xmlfile = null;
        }
    }

    public function executeListByVersion(sfWebRequest $request) {
        $form = $request->getParameter("choiceVersion");
        if ($form != null) {
            $this->redirect('eifonction/index?id_version=' . $form['version']);
        }
    }

    public function executeNew(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);  //Récupération du profil
        $id_version = $request->getParameter('id_version');
        $ei_scenario_id = $request->getParameter('ei_scenario_id');
        $niveau = $request->getParameter('niveau');
        $ei_fonction = new EiFonction();
        $ei_fonction->ei_scenario_id = $ei_scenario_id;
        $ei_fonction->id_version = $id_version;
        $ei_fonction->project_id = $this->project_id;
        $ei_fonction->project_ref = $this->project_ref;
        $ei_fonction->niveau = $niveau;
        $this->form = new EiFonctionForm($ei_fonction, array('project_id' => $project_id, 'project_ref' => $project_ref), null);
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);  //Récupération du profil
        if ($request->isXmlHttpRequest()) {
            if ($request->getParameter('ei_scenario_id') == null || $request->getParameter('id_version') == null ||
                    $request->getParameter('kal_fonction') == null) {
                $this->forward404Unless('Error . Missing parameters...');
            } else {
                $fonct = new EiFonction();
                $fonct->id_version = $request->getParameter('id_version');
                $fonct->ei_scenario_id = $request->getParameter('ei_scenario_id');
                $fonct->project_id = $this->project_id;
                $fonct->project_ref = $this->project_ref;
                $fonct->kal_fonction = $request->getParameter('kal_fonction');
                if ($request->getParameter('observation') != null)
                    $fonct->observation = $request->getParameter('observation');
                $fonct->save();
            }
            return sfView::NONE;
        }
        else {
            $this->form = new EiFonctionForm();

            $this->processForm($request, $this->form);

            $this->setTemplate('new');
        }
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($ei_fonction = Doctrine_Core::getTable('EiFonction')->find(array($request->getParameter('ei_fonction_id'))), sprintf('Object ei_fonction does not exist (%s).', $request->getParameter('id')));
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);  //Récupération du profil
        $this->form = new EiFonctionForm($ei_fonction);
        $this->fonction = $ei_fonction;
    }

    public function executeUpdate(sfWebRequest $request) {

        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);  //Récupération du profil

        $ei_fonction = Doctrine_Core::getTable('EiFonction')
                ->createQuery('fct')
                ->leftJoin('fct.EiParams p')
                ->leftJoin('fct.EiFunctionMapping mapping')
                ->addWhere("fct.id =  ?", $request->getParameter('ei_fonction_id'))
                ->execute();

        $this->forward404Unless($ei_fonction
                , sprintf('Object ei_fonction does not exist (%s).', $request->getParameter('ei_fonction_id')));

        $this->form = new EiFonctionForm($ei_fonction[0]);
        $this->form->embedParameters($ei_fonction[0]['EiParams']);
        $this->form->embedMappings($ei_fonction[0]['EiFunctionMapping']);

        return $this->processForm($request, $this->form);
    }

    /**
     * Vérirife les paramètres 
     * @param sfWebRequest $request
     */
    protected function getUrlParameters(sfWebRequest $request) {
        $ei_version_id = $request->getParameter('ei_version_id');
        $this->ei_version = Doctrine_Core::getTable('EiVersion')->find($ei_version_id);
    }

    /**
     * Ajouter une fonction à une version/sous_version
     * @author Grégory Elhaimer
     * @param sfWebRequest $request
     * @return type 
     */
    public function executeAjouter(sfWebRequest $request) {
        $this->getUrlParameters($request);
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);  //Récupération du profil
        $this->forward404Unless($this->ei_version, "Version not found.");
        $this->checkFunction($request, $this->ei_project);
        $this->forward404Unless($this->kal_function);
        /* Récupération du User courant et du package par défaut */
        $this->defaultPackage = $this->getDefaultPackage($this->ei_user, $this->ei_project);
        
        $function_ref = $request->getParameter('function_ref');
        $function_id = $request->getParameter('function_id');
        
         
        $subjectFunctions = array();
        /* Récupération d'une liaison éventuelle liaison existente entre la fonction et le bug */
        if ($function_id != null && $function_ref != null && $this->defaultPackage != null):

            /* Recherche du sujet par rapport au package par défaut.
             * Ici, on vérifie que plusieurs bugs ne sont pas associés au même package , auquel cas on déclenche un exception pour contacter l'administrateur .
             */
            $this->defaultPackageSubjects = Doctrine_Core::getTable('EiSubject')->findByPackageIdAndPackageRefAndProjectIdAndProjectRef(
                    $this->defaultPackage->getTicketId(), $this->defaultPackage->getTicketRef(), $this->ei_project->getProjectId(), $this->ei_project->getRefId());
            if (count($this->defaultPackageSubjects) > 1): //Plusieurs sujets avec la même intervention.On déclenche une alerte système.
                throw new Exception('Please contact administrator.There many intervention with the same package ...');
            elseif (count($this->defaultPackageSubjects) == 1):
                $this->ei_subject = $this->defaultPackageSubjects->getFirst();
                $link = Doctrine_Core::getTable('EiSubjectFunctions')->findOneBySubjectIdAndFunctionIdAndFunctionRef(
                        $this->ei_subject->getId(), $function_id, $function_ref);
                 
                if ($link != null): 
                    $subjectFunctions[] = array(
                        'sf_subject_id' => $link->getSubjectId(),
                        'sf_function_id' => $link->getFunctionId(),
                        'sf_function_ref' => $link->getFunctionRef(),
                        'sf_automate' => $link->getAutomate(),
                    );
                endif;
            endif;

        endif;
        

        //création et initialisation de la fonction
        $ei_fonction = new EiFonction();
        $ei_fonction->setName($this->kal_function->getNodeTree()->getName());
        $ei_fonction->setProjectId($this->project_id);
        $ei_fonction->setProjectRef($this->project_ref);
        $ei_fonction->setFunctionId($this->kal_function->getFunctionId());
        $ei_fonction->setFunctionRef($this->kal_function->getFunctionRef());
        $ei_fonction->setSubjectFunctions($subjectFunctions);
        $this->ei_version_structure = Doctrine_Core::getTable('EiVersionStructure')
                ->find($request->getParameter('ei_version_structure_id'));

        $this->forward404Unless($this->ei_version_structure && $this->ei_version_structure->getEiVersionId() == $this->ei_version->getId());

        $ei_version_structure = new EiVersionStructure();
        $ei_version_structure->setType('EiFonction');
        $ei_version_structure->setEiVersion($this->ei_version);
        $ei_version_structure->setEiVersionStructureParentId($this->ei_version_structure->getId());

        $ei_fonction->setEiVersionStructure($ei_version_structure);

        $ei_fonction = $ei_fonction->save();

        if ($insert_after = $request->getParameter('insert_after')) {

            $aft_version = Doctrine_Core::getTable('EiVersionStructure')->find($insert_after);
            $this->forward404Unless($aft_version);

            $ei_version_structure->getNode()->insertAsNextSiblingOf($aft_version);
        } else {
            $ei_version_structure->getNode()->insertAsFirstChildOf($this->ei_version_structure);
        }

        $form = new EiFonctionForm($ei_fonction, array('params' => $ei_fonction->getEiParams(), 'mappings' => $ei_fonction->getEiFunctionMapping()));

        return $this->renderPartial('ajouterFonction', array(
                    'form' => $form,
                    'insert_after' => $ei_version_structure->getId(),
                    'obj' => $ei_fonction,
                    'paramsForUrl' => array(
                        'ei_version_id' => $this->ei_version->getId(),
                        'ei_version_structure_id' => $this->ei_version_structure->getId(),
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'profile_name' => $this->profile_name,
                        'default_notice_lang' => $this->ei_project->getDefaultNoticeLang()), //Langue par défaut des notices du projet
        ));
    }

    /**
     * Supprime une fonction.
     * @param sfWebRequest $request
     * @return type
     */
    public function executeDelete(sfWebRequest $request) {
        $this->getUrlParameters($request);
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);  //Récupération du profil
        $this->checkEiFonction($request, $this->ei_project); //Récupération de la EiFonction
        $this->getResponse()->setContentType('application/json');
        $JSONResponse = array();

        if ($this->ei_fonction != null) {

            $this->ei_fonction->delete();

            $JSONResponse['status'] = "ok";
            $JSONResponse['message'] = "The function has been deleted successfully.";
        } else {
            $JSONResponse['status'] = "error";
            $JSONResponse['message'] = "The function could not be deleted. Either the function does not exist anymore, or the project has been not found in the database.";
        }

        return $this->renderText(json_encode($JSONResponse));
        return sfView::NONE;
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        $this->getResponse()->setContentType('application/json');
        $JSONResponse = array();

        if ($form->isValid()) {
            $ei_fonction = $form->save();

            $JSONResponse['status'] = "ok";
            $JSONResponse['message'] = "The function has been saved successfully.";
        } else {
 

            $JSONResponse['status'] = "error";
            $JSONResponse['message'] = "The function could not be saved.";
        }

        return $this->renderText(json_encode($JSONResponse));
    }

}
