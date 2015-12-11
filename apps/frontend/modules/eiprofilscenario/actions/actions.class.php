<?php

/**
 * eiprofilscenario actions.
 *
 * @package    kalifast
 * @subpackage eiprofilscenario
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiprofilscenarioActions extends sfActions {

    //Recherche du profil
    public function checkProfile(sfWebRequest $request) {
        if (($this->profile_ref = $request->getParameter('profile_ref')) != null &&
                ($this->profile_id = $request->getParameter('profile_id')) != null) {
            //Recherche du projet en base
            $this->ei_profile = Doctrine_Core::getTable('EiProfil')->findOneByProfileIdAndProfileRef(
                    $this->profile_id, $this->profile_ref);
            //Si le projet n'existe pas , alors on retourne un erreur 404
            if ($this->ei_profile == null)
                $this->forward404('Environment not found with these parameters ...'); 
        }

        else {
            $this->forward404('Missing Environment parameters  ...');
        }
    }
    
    
    public function executeIndex(sfWebRequest $request) {
        $this->ei_profil_scenarios = Doctrine_Core::getTable('EiProfilScenario')
                ->createQuery('a')
                ->execute();
    }

    /**
     * Format les réponses JSON de succès.
     * 
     * @param type $action
     * @param type $status
     * @return string
     */
    private function createJSONResponse($status, $message) {
        $JSONResponse['status'] = $status;
        $JSONResponse['message'] = $message;

        return $JSONResponse;
    }

    /**
     * Associe un nouveau profil à la version du scenario.
     * @param sfWebRequest $request
     * @return 
     */
    public function executeNewProfScen(sfWebRequest $request) {
        $this->getResponse()->setContentType('application/json');
        $this->checkProfile($request);
        $this->scenario_id = $request->getParameter('ei_scenario_id');
        $this->version_id = $request->getParameter('ei_version_id');
        /* Recherche d'une liaison entre un package et la version */
        $ei_scenario_package=Doctrine_Core::getTable('EiScenarioPackage')->findOneByEiScenarioIdAndEiVersionId(
                $this->scenario_id,$this->version_id);
//        si aucune liaison n'existe , on retourne une erreur
        if($ei_scenario_package==null): 
            $JSONResponse = $this->createJSONResponse("error", "version is not associate to a package.You consequently can't do this...");
            return $this->renderText(json_encode($JSONResponse));
        endif;
        $profscen = Doctrine_Core::getTable('EiProfilScenario')
                ->findOneByProfileIdAndProfileRefAndEiScenarioId(
                $this->profile_id, $this->profile_ref, $this->scenario_id);

        if ($profscen->getEiVersionId() == $this->version_id) {
            $JSONResponse = $this->createJSONResponse("error", "Environment is already assign to the version.");
        } else {
            $profscen->setEiVersionId($this->version_id);
            $profscen->save();
            $JSONResponse = $this->createJSONResponse('ok', 'Profil ' . $profscen->getEiProfil()->getName() . ' is now assigned to the version.');
        } 
        return $this->renderText(json_encode($JSONResponse));
    }

    public function executeNew(sfWebRequest $request) {

        $profils = $request->getParameter('profils');
        if ($profils != null) {
            echo 'Vous avez choisi comme option';
            for ($i = 0; $i < count($request->getParameter('profil')); $i++)
                echo $profils[$i] . " - ";
            $this->redirect('eifonction/index?ei_scenario_id=' . $request->getParameter('ei_scenario_id') . '&id_version=' . $request->getParameter('id_version'));
        } else {
            $this->form = new EiProfilScenarioForm();
        }
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->form = new EiProfilScenarioForm();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($ei_profil_scenario = Doctrine_Core::getTable('EiProfilScenario')->find(array($request->getParameter('profile_id'),
            $request->getParameter('profile_ref'), $request->getParameter('ei_scenario_id'))), sprintf('Object ei_profil_scenario does not exist (%s).', $request->getParameter('profile_id'), $request->getParameter('profile_ref'), $request->getParameter('ei_scenario_id')));
        $this->form = new EiProfilScenarioForm($ei_profil_scenario);
    }

    public function executeMajProfilScenario(sfWebRequest $request) {
        //recuperation des paramètres
        $profile_id = $request->getParameter('profile_id');
        $profile_ref = $request->getParameter('profile_ref');
        $id_version = $request->getParameter('id_version');
        $ei_scenario_id = $request->getParameter('ei_scenario_id');

        //traitement
        $profilscenario = Doctrine_Core::getTable('EiProfilScenario')->findOneByProfileIdAndProfileRefAndEiScenarioId($profile_id, $profile_ref, $ei_scenario_id);
        if ($profilscenario != null) {
            //est-ce ma version 
            if ($profilscenario->id_version != $id_version) {
                $profilscenario->id_version = $id_version;
                $profilscenario->save();
            }
        } else {
            $profilscenario = new EiProfilScenario();
            $profilscenario->profile_id = $profile_id;
            $profilscenario->profile_ref = $profile_ref;
            $profilscenario->id_version = $id_version;
            $profilscenario->ei_scenario_id = $ei_scenario_id;
            $profilscenario->save();
        }
        return sfView::NONE;
    }

    public function executeUpdate(sfWebRequest $request) {

        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($ei_profil_scenario = Doctrine_Core::getTable('EiProfilScenario')->find(array($request->getParameter('profile_id'),
            $request->getParameter('profile_ref'), $request->getParameter('ei_scenario_id'))), sprintf('Object ei_profil_scenario does not exist (%s).', $request->getParameter('profile_id'), $request->getParameter('profile_ref'), $request->getParameter('ei_scenario_id')));
        $this->form = new EiProfilScenarioForm($ei_profil_scenario);

        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $id_version = $request->getParameter('id_version');
        if ($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT)) {
            $request->checkCSRFProtection();
        }

        if ($id_version != null) { //si la version est spécifiée
            Doctrine_Query::create()->delete()->from('EiProfilScenario ps')
                    ->where('ps.id_version=?' . $id_version);
        } else {
            $this->forward404Unless($ei_profil_scenario = Doctrine_Core::getTable('EiProfilScenario')->find(array($request->getParameter('profile_id'),
                $request->getParameter('profile_ref'), $request->getParameter('ei_scenario_id'))), sprintf('Object ei_profil_scenario does not exist (%s).', $request->getParameter('profile_id'), $request->getParameter('profile_ref'), $request->getParameter('ei_scenario_id')));
            $ei_profil_scenario->delete();

            $this->redirect('eiprofilscenario/index');
        }
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $ei_profil_scenario = $form->save();

            $this->redirect('eiprofilscenario/edit?profile_id=' . $ei_profil_scenario->getProfileId() . '&profile_ref=' . $ei_profil_scenario->getProfileRef() . '&ei_scenario_id=' . $ei_profil_scenario->getEiScenarioId());
        }
    }

}
