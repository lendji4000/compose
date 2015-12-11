<?php

/**
 * kalfonction actions.
 *
 * @package    kalifast
 * @subpackage kalfonction
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class kalfonctionActions extends sfActionsKalifast {
    /* Récupération de la campagne courante en cas d'édition d'une step */

    //On recherche la  campagne 
    public function checkCurrentCampaign(sfWebRequest $request, $campaign_id, EiProjet $ei_project) {
        $this->current_campaign_id = $campaign_id;

        if ($this->current_campaign_id == null):
            $this->forward404('Campaign parameter not found');
        else:
            $this->ei_current_campaign = Doctrine_Core::getTable('EiCampaign')
                    ->findOneByIdAndProjectIdAndProjectRef(
                    $this->current_campaign_id, $ei_project->getProjectId(), $ei_project->getRefId());
            if ($this->ei_current_campaign == null):
                $this->forward404('Campaign not found');
            endif;
        endif;
    }

    /* Cette fonction permet de rechercher la vue avec les paramètres renseignés.  */

    public function checkParentTree(sfWebRequest $request, EiProjet $ei_project) {
        $this->parent_id = $request->getParameter('parent_id');
        if ($this->parent_id == null)
            $this->forward404('Parent node parameter not found ...');
        $this->ei_parent_tree = Doctrine_Core::getTable('EiTree')->findOneByIdAndProjectIdAndProjectRef(
                $this->parent_id, $ei_project->getProjectId(), $ei_project->getRefId());
        if ($this->ei_parent_tree == null)
            $this->forward404('Parent node not found');
    }

    /* Sujets d'une fonction */

    public function executeFunctionSubjects(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        //sujets d'une fonction
        $this->ei_function_subjects = $this->kal_function->getFunctionSubjects();
    }

    //Récupération des sujets d'une fonction
    public function executeGetFunctionSubjects(sfWebRequest $request) {

        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $this->subjectStates = Doctrine_Core::getTable('EiSubjectState')->getSubjectStateForSearchBox($this->ei_project);
        if (count($this->subjectStates) > 0):
            foreach ($this->subjectStates as $state):
                $defState[] = $state->getId();
            endforeach;
        endif;
//      Sujets d'une livraison
        $request->setParameter("function_id", $this->kal_function->getFunctionId());
        $request->setParameter("function_ref", $this->kal_function->getFunctionRef());
        $request->setParameter("state", $defState);
        $request->setParameter("contextRequest", 'EiFunction'); //On injecte le contexte à la demande pour pouvoir le garder par la suite 
        $content = $this->getController()->getPresentationFor("eisubject", "index");
        return $this->renderText($content);
    }

    /* Scénarios dans lesquels on retrouve la fonction */

    public function executeScenariosFunction(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        /* Récupération du nombre de scénarios dans le projet */
        $this->total_scenario = count($this->ei_project->getScenarios());
        /* Récupération des scénarios dans lesquels la fonction est utilisée */
        $this->scenarios_function = $this->kal_function->getScenariosFunction();
        /* Récupération des campagnes de test de la fonction */
        $this->ei_function_campaigns = $this->kal_function->getFunctionCampaigns();
    }

    //Recherche des sujets d'une fonction
    public function executeSearchFunctionSubjects(sfWebRequest $request) {

        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
//      Sujets d'une livraison
        $request->setParameter("function_id", $this->kal_function->getFunctionId());
        $request->setParameter("function_ref", $this->kal_function->getFunctionRef());
        $request->setParameter("contextRequest", 'EiFunction'); //On injecte le contexte à la demande pour pouvoir le garder par la suite 
        $content = $this->getController()->getPresentationFor("eisubject", "searchSubjects");
        return $this->renderText($content);
    }

    //Changement de la criticité d'une fonction
    public function executeChangeCriticity(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $this->change_criticity_result = $this->kal_function->changeCriticity();
        if ($this->change_criticity_result)
            return $this->renderText(json_encode(array(
                        'html' => 'success',
                        'success' => true)));
        else
            return $this->renderText(json_encode(array(
                        'html' => 'error',
                        'success' => false)));
        return sfView::NONE;
    }

    //Récupération des profils actifs d'une fonction pour un ticket donné
    public function executeGetProfileForTicket(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkTicket($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        // Récupération de l'utilisateur. (Pour la sauvegarde de la résolution  du conflit)
        $this->guard_user = $this->getUser()->getGuardUser();
        //Récupération de la livraison dans laquelle le conflit  a été découvert
        $this->checkDelivery($request, $this->ei_project);
        $this->ei_profiles = $this->ei_project->getProfils(); //Récupération des profils
        /* Résolution du conflit découvert sur la fonction */
        Doctrine_Core::getTable('EiPackageFunctionConflict')->createItem($this->kal_function, $this->ei_delivery, $this->ei_ticket, $this->guard_user);

        //Récupération des relations script-profil impliquant le ticket en question
        $this->TabScriptProfiles = $this->ei_ticket->getAssociatedProfiles();
        $this->scriptProfiles = array();
        if (!empty($this->TabScriptProfiles)) {
            foreach ($this->TabScriptProfiles as $scriptProfile):
                $tab[$scriptProfile['function_id'] . '_' . $scriptProfile['function_ref'] . '_' .
                        $scriptProfile['profile_id'] . '_' . $scriptProfile['profile_ref']] = $scriptProfile['script_id'];
            endforeach;
            $this->scriptProfiles = $tab;
        }

        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('kalfonction/profilesForTicket', array(
                        'ticket_id' => $this->ticket_id,
                        'ticket_ref' => $this->ticket_ref,
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'function_id' => $this->function_id,
                        'function_ref' => $this->function_ref,
                        'ei_profiles' => $this->ei_profiles,
                        'scriptProfiles' => $this->scriptProfiles
                    )),
                    'success' => true)));
        return sfView::NONE;
    }

    public function executeIndex(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
    }

    /* Contenu d'une fonction (campagnes et sujets) */

    public function executeShowContent(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);

        /* Récupération de la campagne courante car on se trouve dans l'édition des steps d'une campagne */
        $this->checkCurrentCampaign($request, $request->getParameter('current_campaign_id'), $this->ei_project); //Campagne courante
        /* Récupération des sujets de la fonction */
        $this->ei_function_subjects = $this->kal_function->getFunctionSubjects();
        /* Récupération des campagnes de la fonction */
        $this->ei_function_campaigns = $this->kal_function->getFunctionCampaigns();
        /* Contenu d'une fonction */
        //Construction de l'url de retour
        $rightSideFunctionBloct = $this->urlParameters;
        $rightSideFunctionBloct['kal_function'] = $this->kal_function;
        $rightSideFunctionBloct['ei_subjects'] = $this->ei_function_subjects;
        $rightSideFunctionBloct['ei_campaigns'] = $this->ei_function_campaigns;
        $rightSideFunctionBloct['ei_current_campaign'] = $this->ei_current_campaign;
        $rightSideFunctionBloct['ajax_request'] = true;
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eicampaigngraph/rightSideFunctionBloc', $rightSideFunctionBloct),
                    'success' => true)));
        return sfView::NONE;
    }

    public function executeList(sfWebRequest $request) {
        $this->kal_fonctions = array();
        foreach (Doctrine_Core::getTable('KalFunction')->findAll() as $kal_fonction) {
            $this->kal_fonctions[] = $kal_fonction->asArray();
        }
    }

    public function executeShow(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        if ($this->kal_function != null):
            $this->ei_tree = Doctrine_Core::getTable('EiTree')->findOneByObjIdAndRefObj(
                    $this->kal_function->getFunctionId(), $this->kal_function->getFunctionRef());
        endif;
        /* Stats de la fonction */
        //Dernière execution 
        $this->last_exec = $this->kal_function->getLastExec();
        /* Stats de campagne */
        /* Campagnes d'une fonction */
        $this->ei_function_campaigns = $this->kal_function->getFunctionCampaigns();
        /* Campagnes de test du projet */
        $conn = Doctrine_Manager::connection();
        $this->ei_project_campaigns = $this->ei_project->getAllProjectCampaigns($conn);

        $q = "select distinct(campaign_id) from ei_campaign_graph where scenario_id 
                  in (select  s_id from ei_scenarios_function_vw  Where kf_project_id =" . $this->ei_project->getProjectId() . " And kf_project_ref=" . $this->ei_project->getRefId() .
                " And kf_function_id =" . $this->kal_function->getFunctionId() . " And kf_function_ref=" . $this->kal_function->getFunctionRef() . ')';
        $this->ei_occurences_function = $conn->fetchAll($q);
        /* Scenarios */
        /* Récupération du nombre de scénarios dans le projet */
        $this->total_scenario = count($this->ei_project->getScenarios());
        /* Récupération des scénarios dans lesquels la fonction est utilisée */
        $this->scenarios_function = $this->kal_function->getScenariosFunction();
        /* Récupération d'executions par statut */
        $this->exByStateFunctions = $conn->fetchAll("select st.id, st.name,st.color_code,st.state_code ,count(1) as nbEx from ei_test_set_function_simply_vw nbExt , ei_test_set_state st 
                                                    where nbExt.tsf_function_id=" . $this->kal_function->getFunctionId() . " and nbExt.tsf_function_ref=" . $this->kal_function->getFunctionRef() . "
                                                    and st.project_id=" . $this->ei_project->getProjectId() . " and st.project_ref=" . $this->ei_project->getRefId() . "  
                                                    and nbExt.tsfs_project_id=st.project_id and nbExt.tsfs_project_ref= st.project_ref and  
                                                    st.state_code= CASE WHEN (nbExt.tsf_status = 'blank' and nbExt.ts_termine=1 )  THEN 'AB' ELSE nbExt.tsfs_state_code END
                                                    group by st.id, st.name,st.color_code,st.state_code");
    }

    /* Statistiques d'une fonction */

    public function executeStatistics(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $conn = Doctrine_Manager::connection();

        /* Gestion des données éventuellement récupérées dans le formulaire de recherche */
        $this->functionReportForm = new functionReportForm();
        $params = $request->getParameter($this->functionReportForm->getName());
        $q = "select ts.id from ei_test_set_function ts left join ei_test_set_state tsfs on  UPPER(CASE WHEN (ts.status = 'blank')  THEN 'AB' ELSE ts.status END)=tsfs.state_code
                where tsfs.project_id=" . $this->ei_project->getProjectId() . " and tsfs.project_ref=" . $this->ei_project->getRefId() . " and
                ts.function_id=" . $this->kal_function->getFunctionId() . " and ts.function_ref=" . $this->kal_function->getFunctionRef();

        if ((isset($params['iteration_id']) && ($iteration_id = intval($params['iteration_id'])) != null) || ($iteration_id = $request->getParameter('iteration_id')) != null):
            $this->functionReportForm->setDefault('iteration_id', $iteration_id);
            $q.=" and ts.iteration_id=" . $iteration_id;
        endif;

        if ((isset($params['execution_id']) && ($execution_id = intval($params['execution_id'])) != null) || ($execution_id = $request->getParameter('execution_id')) != null):
            $this->functionReportForm->setDefault('execution_id', $execution_id);
            $q.=" and ts.ei_test_set_id In (select DISTINCT(ei_test_set_id) from ei_campaign_execution_graph cg left join ei_campaign_execution ce on ce.id=cg.execution_id where cg.execution_id=" . $execution_id . " ) ";
        endif;


        $this->ids = $conn->fetchAll($q . " order by ts.id Desc limit 50");


        if (count($this->ids) > 0):
            foreach ($this->ids as $id):
                $tab[] = $id['id'];
            endforeach;
            $str_ids = implode(',', $tab);
        endif;

        if (isset($str_ids)): //Si on a des executions
            $this->exFunctions = $conn->fetchAll("select * from ei_test_set_function_vw where   num_ex IN (" . $str_ids . ")   and
                tsfs_project_id=" . $this->ei_project->getProjectId() . " and tsfs_project_ref=" . $this->ei_project->getRefId() . "
                and tsf_function_id=" . $this->kal_function->getFunctionId() . " and tsf_function_ref=" . $this->kal_function->getFunctionRef() . ' order by num_ex DESC');
        endif;

        /* Récupération d'executions par statut */
        $this->exByStateFunctions = $conn->fetchAll("select st.id, st.name,st.color_code,st.state_code ,count(1) as nbEx from ei_test_set_function_simply_vw nbExt , ei_test_set_state st 
                                                    where nbExt.tsf_function_id=" . $this->kal_function->getFunctionId() . " and nbExt.tsf_function_ref=" . $this->kal_function->getFunctionRef() . "
                                                    and st.project_id=" . $this->ei_project->getProjectId() . " and st.project_ref=" . $this->ei_project->getRefId() . "  
                                                    and nbExt.tsfs_project_id=st.project_id and nbExt.tsfs_project_ref= st.project_ref and  
                                                    st.state_code= CASE WHEN (nbExt.tsf_status = 'blank' and nbExt.ts_termine=1 )  THEN 'AB' ELSE nbExt.tsfs_state_code END
                                                    group by st.id, st.name,st.color_code,st.state_code");
    }

    public function executeNew(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->html = 'Error ...';
        $this->success = false;
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkParentTree($request, $this->ei_project);
        /* Vérification du package par défaut de l'utilisateur */
        $this->defPack = Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByProjectIdAndProjectRefAndUserIdAndUserRef(
                $this->ei_project->getProjectId(), $this->ei_project->getRefId(), $this->ei_user->getUserId(), $this->ei_user->getRefId());
        if ($this->defPack == null):
            return $this->renderText(json_encode(array(
                        'html' => "You have to set default intervention before.Please do it and try again ...",
                        'is_default_intervention_null' => true,
                        'success' => $this->success)));
        endif;
        $kal_function = new KalFunction();
        $kal_function->setProjectId($this->ei_project->getProjectId());
        $kal_function->setProjectRef($this->ei_project->getRefId());
        $this->form = new KalFunctionForm($kal_function);
        $this->success = true;
        $this->html = $this->getPartial('kalfonction/form', array(
            'form' => $this->form,
            'ei_parent_tree' => $this->ei_parent_tree,
            'ei_project' => $this->ei_project,
            'ei_profile' => $this->ei_profile));
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'success' => $this->success)));

        return sfView::NONE;
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->html = 'Error ...';
        $this->success = false;
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkParentTree($request, $this->ei_project);
        /* Vérification du package par défaut de l'utilisateur */
        $this->defPack = Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByProjectIdAndProjectRefAndUserIdAndUserRef(
                $this->ei_project->getProjectId(), $this->ei_project->getRefId(), $this->ei_user->getUserId(), $this->ei_user->getRefId());
        if ($this->defPack == null):
            return $this->renderText(json_encode(array(
                        'html' => "You have to set default intervention before.Please do it and try again ...",
                        'is_default_intervention_null' => true,
                        'success' => $this->success)));
        endif;
        $kal_function = new KalFunction();
        $kal_function->setProjectId($this->ei_project->getProjectId());
        $kal_function->setProjectRef($this->ei_project->getRefId());
        $this->form = new KalFunctionForm($kal_function);

        $this->processForm($request, $this->form);

        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'success' => $this->success)));

        return sfView::NONE;
    }

    public function executeAddFunction(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkSubject($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);

        $this->subject_function = new EiSubjectFunctions();
        $this->subject_function->setKalFunction($this->kal_function);
        $this->subject_function->setEiSubject($this->ei_subject);
        $result = $this->subject_function->addFunction();
        /* Recherche pour la livraison des fonctions exécutées */
        $this->exFunctions = $this->ei_subject->getExFunctions(true);
        $this->modName = "EiSubject";
        return $this->setTemplate('impacts', 'eidelivery');
        return sfView::NONE;
    }

    public function executeRemoveFunction(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkSubject($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);

        $this->subject_function = new EiSubjectFunctions();
        $this->subject_function->setKalFunction($this->kal_function);
        $this->subject_function->setEiSubject($this->ei_subject);
        $result = $this->subject_function->removeFunction();

        if ($result)
            return $this->renderText(json_encode(array(
                        'html' => 'Well done ...',
                        'success' => true)));
        else
            return $this->renderText(json_encode(array(
                        'html' => 'Function already exist in subject ...',
                        'success' => false)));

        return sfView::NONE;
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        /* Vérification du package par défaut de l'utilisateur */
        $this->defPack = Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByProjectIdAndProjectRefAndUserIdAndUserRef(
                $this->ei_project->getProjectId(), $this->ei_project->getRefId(), $this->ei_user->getUserId(), $this->ei_user->getRefId());
        if ($this->defPack == null):
            return $this->renderText(json_encode(array(
                        'html' => "You have to set default intervention before.Please do it and try again ...",
                        'is_default_intervention_null' => true,
                        'success' => $this->success)));
        endif;
        $this->node_function = Doctrine_core::getTable("EiTree")->findOneByObjIdAndRefObjAndTypeAndProjectIdAndProjectRef(
                $this->function_id, $this->function_ref, "Function", $this->project_id, $this->project_ref);
        $this->forward404Unless($this->node_function);
        $this->ei_parent_tree = $this->node_function->getNodeParent();
        $this->form = new KalFunctionForm($this->kal_function);
        $this->form->setDefault('name', $this->node_function->getName());
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('form', array(
                        'form' => $this->form,
                        'ei_project' => $this->ei_project,
                        'ei_profile' => $this->ei_profile,
                    )),
                    'success' => true)));

        return sfView::NONE;
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        $this->success = false;
        /* Vérification du package par défaut de l'utilisateur */
        $this->defPack = Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByProjectIdAndProjectRefAndUserIdAndUserRef(
                $this->ei_project->getProjectId(), $this->ei_project->getRefId(), $this->ei_user->getUserId(), $this->ei_user->getRefId());
        if ($this->defPack == null):
            return $this->renderText(json_encode(array(
                        'html' => "You have to set default intervention before.Please do it and try again ...",
                        'is_default_intervention_null' => true,
                        'success' => $this->success)));
        endif;
        $this->node_function = Doctrine_core::getTable("EiTree")->findOneByObjIdAndRefObjAndTypeAndProjectIdAndProjectRef(
                $this->function_id, $this->function_ref, "Function", $this->project_id, $this->project_ref);
        $this->forward404Unless($this->node_function);
        $this->ei_parent_tree = $this->node_function->getNodeParent();
        $this->form = new KalFunctionForm($this->kal_function);
        $this->processForm($request, $this->form);

        if ($this->success):
            $this->kal_function = Doctrine_Core::getTable("KalFunction")->findOneByFunctionIdAndFunctionRef($this->function_id, $this->function_ref);
            $mainInfParams = $this->urlParameters;
            $mainInfParams['kal_function'] = $this->kal_function;
            $this->html = $this->getPartial('mainInf', $mainInfParams);
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('mainInf', $mainInfParams),
                        'success' => $this->success)));
        else:
            return $this->renderText(json_encode(array(
                        'html' => $this->html,
                        'success' => $this->success)));
        endif;


        return sfView::NONE;
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($kal_fonction = Doctrine_Core::getTable('KalFonction')->find(array($request->getParameter('id'))), sprintf('Object kal_fonction does not exist (%s).', $request->getParameter('id')));
        $kal_fonction->delete();

        $this->redirect('kalfonction/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) :
            $this->html = $request->getParameter($form->getName());
            //Si les données sont valides, on envoi en format json la fonction et ses éventuels paramètres pour insertion.
            /* On recherche le package par défaut de l'utilisateur qu'on envoi dans la requête à la plate forme centrale */

            if (!$form->getObject()->isNew()):
                $this->ei_parent_tree = null;
            endif;

            $this->result = KalFunction::createDistantFunction($this->ei_project, json_encode($this->html), $this->defPack, $this->ei_parent_tree);

            $this->success = $this->result;

        else: // Echec du bind .
            $this->success = false;
            if ($form->getObject()->isNew()):
                $this->html = $this->getPartial('kalfonction/form', array(
                    'form' => $form,
                    'ei_parent_tree' => $this->ei_parent_tree,
                    'ei_project' => $this->ei_project,
                    'ei_profile' => $this->ei_profile));
            else:
                $this->html = $this->getPartial('kalfonction/form', array(
                    'form' => $form,
                    'ei_project' => $this->ei_project,
                    'ei_profile' => $this->ei_profile));
            endif;
        endif;
    }

    public function initReloadFunctionVariable(sfWebRequest $request) {

        // Initialisation des variables de rechargement de la fonction

        if ($request->getParameter('login') != null)
            $this->login = $request->getParameter('login');
        else
            $this->login = null;

        if ($request->getParameter('pwd') != null)
            $this->pwd = $request->getParameter('pwd');
        else
            $this->pwd = null;

        if ($this->login == null || $this->pwd == null)
            return 0;
        else
            return 1;
    }

}
