<?php

/**
 * eisubject actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubject
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjectActions extends sfActionsKalifast { 
    /* Recherche d'un sujet par le biais de son package */

    public function checkSubjectWithPackage(sfWebRequest $request, EiProjet $ei_project) {
        $this->package_id = $request->getParameter('package_id');
        $this->package_ref = $request->getParameter('package_ref');
        if ($this->package_id == null || $this->package_ref == null)
            $this->forward404('Missing package parameters');
        //Recherche du sujet tout en s'assurant qu'elle corresponde au projet courant  
        $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneByPackageIdAndPackageRef($this->package_id, $this->package_ref);
        if ($this->ei_subject == null)
            $this->forward404('Subject not found');
    }

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

    /* Définition du package d'un bug comme package par défaut */

    public function executeSetInterventionAsDefault(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkSubject($request, $this->ei_project); //Recherche d'un sujet 
        $this->setInterventionAsDefault($this->ei_subject);
        /* Récupération de l'intervention par défaut */
        $defaultIntervention=$this->ei_user->getDefaultIntervention($this->ei_project) ;
              $defaultIntLinkParams=$this->urlParameters; 
              $defaultIntLinkParams['defaultIntervention']=$defaultIntervention; 
        $this->getUser()->setFlash('alert_intervention_default', array('title' => 'Success',
                'class' => 'alert-success',
                'text' => 'Current intervention is now S'.$this->ei_subject->getId().' ...'));      
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eisubject/defaultIntLink',$defaultIntLinkParams),
                    'alertPart' => $this->getPartial('global/alertBox',array('flash_string'=>"alert_intervention_default")),
                    'success' => true)));
        return sfView::NONE;
    } 
    /* Définition du package d'un bug comme package par défaut */

    public function executeSetBugPackageAsDefault(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkSubjectWithPackage($request, $this->ei_project); //Recherche d'un sujet en utilisant son package 
        $this->setInterventionAsDefault($this->ei_subject);
        return $this->renderText(json_encode(array(
                    'html' => 'Success',
                    'success' => true)));
        return sfView::NONE;
    }

    /*
     * Récupération des campagnes d'un sujet pour l'édition des steps d'une campagne
     */

    public function executeShowSubjectCampaigns(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkSubject($request, $this->ei_project); //Recherche du sujet  
        $this->checkCurrentCampaign($request, $request->getParameter('current_campaign_id'), $this->ei_project); //Campagne courante
        //Campagnes du sujet
        $this->ei_subject_campaigns = $this->ei_subject->getSubjectCampaigns();
        /* Construction des tableaux de paramètres de partiel */
        //Paramètres du partiel eicampaigngraph/rightSideSubjectBloc 
        $rightSideSubjectBloc = $this->urlParameters;
        $rightSideSubjectBloc['ei_subject'] = $this->ei_subject_with_relation;
        $rightSideSubjectBloc['ei_campaigns'] = $this->ei_subject_campaigns;
        $rightSideSubjectBloc['ei_current_campaign'] = $this->ei_current_campaign;
        $rightSideSubjectBloc['ajax_request'] = true;
        //Paramètres du partiel eicampaigngraph/stepSearchGlobalBox 
        $stepSearchGlobalBox = $this->urlParameters;
        $stepSearchGlobalBox['ei_delivery'] = null;
        $stepSearchGlobalBox['ei_campaign'] = null;
        $stepSearchGlobalBox['ei_subject'] = $this->ei_subject;
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eicampaigngraph/rightSideSubjectBloc', $rightSideSubjectBloc),
                    'searchBox' => $this->getPartial('eicampaigngraph/stepSearchGlobalBox', $stepSearchGlobalBox),
                    'success' => true)));
        return sfView::NONE;
    }

    //Récupération des critères renseignés dans le formulaire de recherche
    public function getSubjectSearchCriteriaForm(sfWebRequest $request, subjectSearchForm $subjectSearchForm) {
        $this->searchSubjectCriteria = array('title' => null, 'author' => null, 'state' => null, 'delivery',
            'external_id' => null, 'assignment' => null, 'type' => null, 'priority' => null);
        $criterias = $request->getParameter($subjectSearchForm->getName());
        if ($criterias):
            if (isset($criterias['title']))
                $this->searchSubjectCriteria['title'] = $criterias['title'];
            if (isset($criterias['author']))
                $this->searchSubjectCriteria['author'] = $criterias['author'];
            if (isset($criterias['assignment']))
                $this->searchSubjectCriteria['assignment'] = $criterias['assignment'];
            if (isset($criterias['state']))
                $this->searchSubjectCriteria['state'] = $criterias['state'];
            if (isset($criterias['external_id']))
                $this->searchSubjectCriteria['external_id'] = $criterias['external_id'];
            if (isset($criterias['delivery']))
                $this->searchSubjectCriteria['delivery'] = $criterias['delivery'];
            if ($request->getParameter('function_id') != null && $request->getParameter('function_ref') != null):
                $this->searchSubjectCriteria['function_id'] = $request->getParameter('function_id');
                $this->searchSubjectCriteria['function_ref'] = $request->getParameter('function_ref');
            endif;
            if (isset($criterias['type']))
                $this->searchSubjectCriteria['type'] = $criterias['type'];
            if (isset($criterias['priority']))
                $this->searchSubjectCriteria['priority'] = $criterias['priority'];
        endif;
    }

    //Recupération des critères de recherche d'un bug  se trouvant dans l'url
    public function getSubjectSearchCriteria(sfWebRequest $request, subjectSearchForm $subjectSearchForm) {
        $default_state = $subjectSearchForm->getDefault('state');
        $default_priority = $subjectSearchForm->getDefault('priority');
        $default_type = $subjectSearchForm->getDefault('type');
        $default_assign = $subjectSearchForm->getDefault('assignment');
        $this->searchSubjectCriteria = array('title' => null, 'author' => null, 'state' => null, 'assignment' => null,
            'type' => null, 'priority' => null, 'external_id' => null, 'delivery' => null);
        if ($request->getParameter('title') != null)
            $this->searchSubjectCriteria['title'] = $request->getParameter('title');
        if ($request->getParameter('external_id') != null)
            $this->searchSubjectCriteria['external_id'] = $request->getParameter('external_id');
        if ($request->getParameter('author') != null)
            $this->searchSubjectCriteria['author'] = $request->getParameter('author');
        if ($request->getParameter('assignment') != null)
            $this->searchSubjectCriteria['assignment'] = $request->getParameter('assignment');
        else
            $this->searchSubjectCriteria['assignment'] = $default_assign;
        if ($request->getParameter('state') != null)
            $this->searchSubjectCriteria['state'] = $request->getParameter('state');
        else
            $this->searchSubjectCriteria['state'] = $default_state;
        if ($request->getParameter('delivery') != null) // La livraison a été spécifiée
            $this->searchSubjectCriteria['delivery'] = $request->getParameter('delivery');
        if ($request->getParameter('function_id') != null && $request->getParameter('function_ref') != null): // La fonction(KalFunction) a été spécifiée
            $this->searchSubjectCriteria['function_id'] = $request->getParameter('function_id');
            $this->searchSubjectCriteria['function_ref'] = $request->getParameter('function_ref');
        endif;

        if ($request->getParameter('type') != null)
            $this->searchSubjectCriteria['type'] = $request->getParameter('type');
        else
            $this->searchSubjectCriteria['type'] = $default_type;
        if ($request->getParameter('priority') != null)
            $this->searchSubjectCriteria['priority'] = $request->getParameter('priority');
        else
            $this->searchSubjectCriteria['priority'] = $default_priority;
    }

    /* Initialisation du formulaire de recherche des sujets */

    public function initializeSubjectSearchForm(EiProjet $ei_project, EiDelivery $ei_delivery = null) {
        //Récupération des statuts de subjet pour un projet
        $this->subjectStates = Doctrine_Core::getTable('EiSubjectState')->getSubjectStateForSearchBox($ei_project);
        $this->subjectPriorities = Doctrine_Core::getTable('EiSubjectPriority')->getSubjectPriorityForSearchBox($ei_project);
        $this->subjectTypes = Doctrine_Core::getTable('EiSubjectType')->getSubjectTypeForSearchBox($ei_project);
        $this->subjectsAuthors = Doctrine_Core::getTable('EiSubject')->getSubjectAuthorsForProject($ei_project);
        $this->assignUsers = Doctrine_Core::getTable('EiSubject')->getUsersAssignOnSubjectsForProject($ei_project);
        $this->projectUsers = $ei_project->getProjectUsers();
        $this->subjectsTitles = Doctrine_Core::getTable('EiSubject')->getSubjectTitlesForProject($ei_project); 
        $this->deliveries = Doctrine_Core::getTable('EiDelivery')->getAllProjectDeliveriesForSearchBox($ei_project);
        $this->subjectSearchForm = new subjectSearchForm(array(), array(
            'subjectStates' => $this->subjectStates,
            'deliveries' => $this->deliveries,
            'ei_delivery' => $ei_delivery,
            'subjectPriorities' => $this->subjectPriorities,
            'subjectTypes' => $this->subjectTypes,
        ));
    }

    //Recherche rapide d'un sujet par son numero ou son nom
    public function executeSearchSubjectByIdOrName(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->nameOrId = $request->getParameter('nameOrId');
        $this->ei_subject = Doctrine_Core::getTable('EiSubject')->searchSubjectByIdOrName(
                $this->nameOrId, $this->project_id, $this->project_ref);
        if ($this->ei_subject != null) {
            $this->form = new EiSubjectForm($this->ei_subject); 
        } else {
            $this->getUser()->setFlash('subject_not_found', 'Subject not found');
            $this->checkSubject($request, $this->ei_project);
            $this->form = new EiSubjectForm($this->ei_subject);
        }
        //Recherche des utilisateurs assignés et non-assignés au subjet
        $this->getAssignAndNonAssignUserToSubject($this->ei_subject);
        $this->projectUsers = Doctrine_Core::getTable('sfGuardUser')->getProjectUsers($this->ei_project);

        $this->setTemplate('edit');
    }

    //on remplit le formulaire de recherche avec les critères précedemment renseignés
    public function setDefaultValuesForSearchForm() {
        $this->subjectSearchForm->setDefault('title', $this->searchSubjectCriteria['title']);
        $this->subjectSearchForm->setDefault('author', $this->searchSubjectCriteria['author']);
        $this->subjectSearchForm->setDefault('assignment', $this->searchSubjectCriteria['assignment']);
        $this->subjectSearchForm->setDefault('state', $this->searchSubjectCriteria['state']);
        $this->subjectSearchForm->setDefault('delivery', $this->searchSubjectCriteria['delivery']);
        $this->subjectSearchForm->setDefault('external_id', $this->searchSubjectCriteria['external_id']);
        $this->subjectSearchForm->setDefault('type', $this->searchSubjectCriteria['type']);
        $this->subjectSearchForm->setDefault('priority', $this->searchSubjectCriteria['priority']);
    }

    //Mise à jour des champs tinyMce d'un sujet (details , solution , migration )
    public function executeGetSubjectDetailOrSolutionOrMigration(sfWebRequest $request) {
        //On attends les données sur le projet, le sujet et le type de champ à traiter (migration, solution ou details)
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkSubject($request, $this->ei_project); 
        $this->field_type = $request->getParameter('field_type');
        //Retour de la notice par défaut définie sur la centrale (script.kalifast)
        //Paramètres du partiel eisubject/detailOrSolutionOrMigrationForm
        $detailOrSolutionOrMigrationForm = $this->urlParameters;
        $detailOrSolutionOrMigrationForm['ei_subject'] = $this->ei_subject;
        $detailOrSolutionOrMigrationForm['field_type'] = $this->field_type;
        $detailOrSolutionOrMigrationForm['ei_project'] = $this->ei_project;
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eisubject/detailOrSolutionOrMigrationForm', $detailOrSolutionOrMigrationForm),
                    'success' => true)));

        return sfView::NONE;
    }

    /* Changement du statut, priorité ou type d'un groupe de sujets */

    public function executeChangeGroupSubjectAction(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->act = $request->getParameter('act');
        $this->selectSubjects = $request->getParameter('selectSubjectTab');
        $this->selectSubjectTab = MyFunction::parseSimpleStringToTab($this->selectSubjects);

        $conn = Doctrine_Manager::connection();
        try {
            $conn->beginTransaction();
            switch ($this->act) {
                case 'Type':
                    $this->new_type_for_many = $request->getParameter('new_type_for_many');
                    $this->field_value = $this->new_type_for_many;
                    $this->field = "subject_type_id";
                    break;
                case 'State':
                    $this->new_state_for_many = $request->getParameter('new_state_for_many');
                    $this->field_value = $this->new_state_for_many;
                    $this->field = "subject_state_id";

                    break;
                case 'Priority':
                    $this->new_priority_for_many = $request->getParameter('new_priority_for_many');
                    $this->field_value = $this->new_priority_for_many;
                    $this->field = "subject_priority_id";
                    break;

                default:
                    $this->html = "Error. No such action exist ... ";
                    $this->success = false;
                    break;
            }
            if ($this->selectSubjectTab != null && count($this->selectSubjectTab) > 0):
                $q = 'UPDATE ei_subject 
                             SET ' . $this->field . ' =' . $this->field_value;
                $q.='  WHERE id IN (' . $this->selectSubjects . ')';
                $q.='  AND delivery_id NOT IN (select d.id from ei_delivery d ,ei_delivery_state ds WHERE d.delivery_state_id=ds.id and ds.close_state=1)';
                $q.='  AND project_id = ' . $this->project_id;
                $q.='  AND project_ref = ' . $this->project_ref;
                $conn->execute($q);
                $conn->commit();
                $this->html = "success";
                $this->success = true;
            else:
                $this->html = "Error. No subject selected ... ";
                $this->success = false;
            endif;

            return $this->renderText(json_encode(array(
                        'html' => $this->html,
                        'success' => true,
                        'referer' => $request->getReferer())));
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
            return $this->renderText(json_encode(array(
                        'html' => 'Error on process ... ',
                        'success' => false,
                        'referer' => $request->getReferer())));
        }

        return sfView::NONE;
    }

    public function executeChooseDelForManySub(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->delivery_id = intval($request->getParameter('delivery_id'));
        if ($this->delivery_id == null)
            return $this->renderText(json_encode(array(
                        'html' => 'Error .Missing delivery parameters ...',
                        'success' => false)));
        $this->ei_delivery = doctrine_core::getTable('EiDelivery')->findOneByProjectIdAndProjectRefAndId(
                $this->project_id, $this->project_ref, $this->delivery_id);
        if ($this->ei_delivery == null)
            return $this->renderText(json_encode(array(
                        'html' => 'Error .Delivery nor found with theses parameters ...',
                        'success' => false)));
        $this->selectSubjects = $request->getParameter('selectSubjectTab');
        $this->selectSubjectTab = MyFunction::parseSimpleStringToTab($this->selectSubjects);
        $this->html = "Error on process ... ";
        $this->success = false;
        $conn = Doctrine_Manager::connection();
        try {
            $conn->beginTransaction();
            if ($this->selectSubjectTab != null && count($this->selectSubjectTab) > 0):
                $this->field = 'delivery_id';
                $q = 'UPDATE ei_subject 
                             SET ' . $this->field . ' =' . $this->delivery_id;
                $q.='  WHERE id IN (' . $this->selectSubjects . ')';
                $q.='  AND project_id = ' . $this->project_id;
                $q.='  AND project_ref = ' . $this->project_ref;

                $conn->execute($q);
                $conn->commit();
                $this->html = "success";
                $this->success = true;
            else: $this->html = "Error. No subject selected ... ";
            endif;

            return $this->renderText(json_encode(array(
                        'html' => $this->html,
                        'success' => $this->success,
                        'referer' => $request->getReferer())));
        } catch (Exception $e) {
            $conn->rollback();
            return $this->renderText(json_encode(array(
                        'html' => $this->html,
                        'success' => $this->success,
                        'referer' => $request->getReferer())));
        }

        return sfView::NONE;
    }

    //Mise à jour d'un des champs tinyMce d'un sujet (details , solution , migration )
    public function executeUpdateDetailOrSolutionOrMigration(sfWebRequest $request) {
        //On attends les données sur le projet, le sujet et le type de champ à traiter (migration, solution ou details)
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkSubject($request, $this->ei_project);
        $this->field_type = $request->getParameter('field_type');
        if (($this->field_value = $request->getParameter('field_name')) != null):
            //Sauvegarde du champ récupéré 
            $this->ei_subject->updateTinyMceField($this->field_type, $this->field_value);
        endif;
        //Retour de la notice par défaut définie sur la centrale (script.kalifast)
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eisubject/alertMsg', array('msg' => 'Field has been well modifed',
                        'msgClass' => 'alert-success',
                        'msgTitle' => 'Well done ! ')),
                    'success' => true)));
        return sfView::NONE;
    }

    //Récupération des sujets relativement aux différents critères  renseignés
    public function getSubjectsByCriteria(sfWebRequest $request) {
        /* Controle de l'offset pour la détermination du nombre d'enregistrement à afficher */
        //Si l'offset est spécifié, alors il prime sur le nombre d'enregistrement par défaut à afficher.
        $this->offset = intval($request->getParameter('offset'));
        if ($this->offset != 30 && $this->offset != 50 && $this->offset != 100)
            $this->offset = 30;
        $this->max_subject_per_page = sfConfig::get('app_max_subject_per_page');
        if ($this->offset != null) {
            $this->max_subject_per_page = $this->offset;
        } else {
            $this->offset = $this->max_subject_per_page;
        }

        /*
         * Gestion de la pagination des résultats 
         */
        //Calcul du nombre de pages 
        $q = Doctrine_Core::getTable('EiSubject')
                ->sortSubjectByCriterias(Doctrine_Core::getTable('EiSubject')
                        ->getProjectSubjects($this->project_id, $this->project_ref), $this->searchSubjectCriteria)
                ->fetchArray();
        $this->nbEnr = count($q); //Nombre d'enregistrements
        //Si un seul résultat est trouvé , on redirige vers la page d'édition de l'objet 
        //La fonction ceil retourne l'arrondi d'ordre supérieur (0.5 => 1 , 0.3 => 1 , 0.7 =>1)
        $this->nb_pages = ceil($this->nbEnr / $this->max_subject_per_page);

        //Récupération de la variable page représentant la page courante

        if ($request->getParameter('page')) { // Si le nombre de page à afficher à été spécifié...
            //On récupère le numéro de la page à afficher 
            $this->current_page = intval($request->getParameter('page'));
            // Si la valeur de $current_page (le numéro de la page) est plus grande que $nbpages(nombre de pages totale)...
            if ($this->current_page > $this->nb_pages) {
                $this->current_page = $this->nb_pages;
            }
        } else { // Sinon la page n'a pas été spécifiée et dans ce cas , on retourne la première page
            $this->current_page = 1; // La page actuelle est la n°1    
        }
        //A cette étape, le numéro de page à afficher est connu.
        //Il reste à déterminer la plage de données à retourner . (LIMIT debut , nombre d'enregistrements )

        /* l'enregistrement à partir duquel on va éffectuer notre requête est déterminé
         *  par le numéro de page et le nombre d'enregistrements par page soit :
         */
        if ($this->nb_pages == 0 || $this->current_page == 0): $this->current_page = 1;
            $this->nb_pages = 1;
        endif;

        $this->first_entry = ($this->current_page - 1) * $this->max_subject_per_page;



        //À cette étape , on a tous les éléments pour éffectuer notre requête 
        //Récupération de la liste des objets
        $this->ei_subjects = $this->ei_project->paginateSubjects(
                        $this->first_entry, $this->max_subject_per_page, $this->searchSubjectCriteria)
                ->fetchArray();
    }

    //Parser une référence pour extraire le préfixe et l'id à rechercher
    public function parseReference($reference, sfWebRequest $request) {
        if ($reference == null && $reference == ""):
            $this->getUser()->setFlash('search_global_form', array('title' => 'Error ',
                'class' => 'alert-warning',
                'text' => 'Please enter criteria in search form ...'));
            return $this->redirect($request->getReferer());
        endif;
        $this->prefixe = strtoupper($reference[0]);
        $this->id = substr($reference, 1);
    }

    public function redirectWithinPrefixe($prefixe, $id, EiProjet $ei_project) {
        switch ($prefixe) {
            case 'D':
                //c'est une recherche de livraison , on recherche et on
                // va vers l'édition si on trouve l'objet .Sinon on redirige sur la recherche de livraison
                $del = Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
                        intval($id), $ei_project->getProjectId(), $ei_project->getRefId());
                if ($del == null):
                    $this->getUser()->setFlash('search_global_form', array('title' => 'No_result',
                        'class' => 'alert-warning',
                        'text' => 'Please enter criteria in search form ...'));
                    return $this->redirect($this->generateUrl('delivery_list', $this->urlParameters));
                else:
                    $delivery_edit = $this->urlParameters;
                    $delivery_edit['delivery_id'] = $del->getId();
                    $delivery_edit['action'] = 'show';
                    return $this->redirect($this->generateUrl('delivery_edit', $delivery_edit));
            endif;

            case 'S':
                //c'est une recherche de sujet , on recherche et on
                // va vers l'édition si on trouve l'objet .Sinon on redirige sur la recherche de livraison
                $sub = Doctrine_Core::getTable('EiSubject')->findOneByIdAndProjectIdAndProjectRef(
                        intval($id), $ei_project->getProjectId(), $ei_project->getRefId());
                if ($sub == null):
                    $this->getUser()->setFlash('search_global_form', array('title' => 'No_result',
                        'class' => 'alert-warning',
                        'text' => 'Please enter criteria in search form ...'));

                    return $this->redirect($this->generateUrl('subjects_list', $this->urlParameters));
                else:
                    $subject_show = $this->urlParameters;
                    $subject_show['subject_id'] = $sub->getId();
                    return $this->redirect($this->generateUrl('subject_show', $subject_show));
                endif;
                break;

            default:
                $this->getUser()->setFlash('search_global_form', array('title' => 'No_result',
                    'class' => 'alert-warning',
                    'text' => 'Please enter criteria in search form ...'));
                break;
        }
    }

    //Recherche globale d'objets (bug , delivery, campaign ) suivant la référence entrée (préfixe+id)
    public function executeBugManagementSearch(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant

        $this->parseReference($request->getParameter('reference'), $request);
        $this->redirectWithinPrefixe($this->prefixe, $this->id, $this->ei_project);
        $this->redirect($request->getReferer());
    }

    //Définition  des status, priorités et types de sujet par défaut 
    public function defineDefaultSubjectStatesPrioritiesAndTypes($subjectStates, $subjectPriorities, $subjectTypes) {
        /* Statuts par défaut */
        $defState = array();
        if (count($subjectStates) > 0):
            foreach ($subjectStates as $state):
                if ($state->getDisplayInSearch()):
                    $defState[] = $state->getId();
                endif;
            endforeach;
        endif;
        $this->subjectSearchForm->setDefault('state', $defState);

        /* Priorités par défaut */
        $defPrior = array();
        if (count($subjectPriorities) > 0):
            foreach ($subjectPriorities as $prior):
                if ($prior->getDisplayInSearch()):
                    $defPrior[] = $prior->getId();
                endif;
            endforeach;
        endif;
        $this->subjectSearchForm->setDefault('priority', $defPrior);

        /* Types  par défaut */
        $defTypes = array();
        if (count($subjectTypes) > 0):
            foreach ($subjectTypes as $type):
                if ($type->getDisplayInSearch()):
                    $defTypes[] = $type->getId();
                endif;
            endforeach;
        endif;
        $this->subjectSearchForm->setDefault('type', $defTypes);
    }

    //Recherche de sujets avec les critères du formulaire de recherche  
    public function executeSearchSubjects(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant

        $this->contextRequest = "EiSubject"; //Contexte d'execution de l'action
        if ($request->getParameter('contextRequest') != null):
            $this->contextRequest = $request->getParameter('contextRequest');
        endif;
        /* Si la livraison a été spécifiée */
        if ($request->getParameter('delivery_id')): //L'action est invoqué dépuis le module de gestion des livraisons 
            $this->contextRequest = $request->getParameter('contextRequest');
            $this->checkDelivery($request, $this->ei_project);
        else: $this->ei_delivery = null;
        endif;
        /* Si la fonction (KalFuntion) a été spécifiée. Contexte spécifié. */
        if ($request->getParameter('function_id') && $request->getParameter('function_ref')): //L'action est invoqué dépuis le module de gestion des livraisons 
            $this->contextRequest = $request->getParameter('contextRequest');
            $this->checkFunction($request, $this->ei_project);
        else: $this->kal_function = null;
        endif;
        //Initilisation du formulaire de recherche d'un sujet
        $this->initializeSubjectSearchForm($this->ei_project);
        //Récupération des critères de recherche dans le formulaire de recherche
        $this->getSubjectSearchCriteriaForm($request, $this->subjectSearchForm);
        //on remplit le formulaire de recherche avec les critères précedemment renseignés
        $this->setDefaultValuesForSearchForm();
        //Récupération des sujets relativement aux différents critères 
        $this->getSubjectsByCriteria($request);
        //Retour de template
        if ($request->isXmlHttpRequest()):
            $this->setTemplate('searchBoxForSteps');
            $this->is_ajax_request=true;
        else:
            $this->setTemplate('index');
        endif;
    }

    //To do list de l'utilisateur
    public function executeToDoList(sfWebRequest $request) {

        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant 
        $this->guardUser = $this->guard_user;

        /* On récupère tous les statuts de bugs différent de "Close" et on les passe en paramètre pour le recherche */
        $states = Doctrine_Core::getTable('EiSubjectState')->getSubjectStateForProjectQuery(
                        $this->ei_project->getProjectId(), $this->ei_project->getRefId())->execute();
        $stateTab = array();
        if (count($states) > 0):
            foreach ($states as $i => $state):
                if ($state->getDisplayInTodolist())
                    $stateTab[$i] = $state->getId();
            endforeach;

        endif;
        $subjects_list = $this->urlParameters;
        $subjects_list['state'] = $stateTab;
        $subjects_list['assignment'] = $this->guardUser->getUsername();
        $this->redirect($this->generateUrl('subjects_list', $subjects_list));
    }

    //Listing des sujets d'un projet
    public function executeIndex(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->contextRequest = "EiSubject"; //Contexte d'execution de l'action
        if ($request->getParameter('contextRequest') != null):
            $this->contextRequest = $request->getParameter('contextRequest');
        endif;
        /* Si la livraison a été spécifiée. Contexte spécifié. */
        if ($request->getParameter('delivery_id')): //L'action est invoqué dépuis le module de gestion des livraisons 
            $this->contextRequest = $request->getParameter('contextRequest');
            $this->checkDelivery($request, $this->ei_project);
        else: $this->ei_delivery = null;
        endif;
        /* Si la fonction (KalFuntion) a été spécifiée. Contexte spécifié. */
        if ($request->getParameter('function_id') && $request->getParameter('function_ref')): //L'action est invoqué dépuis le module de gestion des livraisons 
            $this->contextRequest = $request->getParameter('contextRequest');
            $this->checkFunction($request, $this->ei_project);
        else: $this->kal_function = null;
        endif;
        //Initilisation du formulaire de recherche d'un sujet
        $this->initializeSubjectSearchForm($this->ei_project, $this->ei_delivery); //On passe éventuellement la livraison choisie si l'utilisateur en fait la demande dépuis le module des livraisons
        //Définition des statuts par défaut à ramener
        $this->defineDefaultSubjectStatesPrioritiesAndTypes($this->subjectStates, $this->subjectPriorities, $this->subjectTypes);

        //Récupération des critères de recherche
        $this->getSubjectSearchCriteria($request, $this->subjectSearchForm);
        //on remplit le formulaire de recherche avec les critères précedemment renseignés
        $this->setDefaultValuesForSearchForm();
        //Récupération des sujets relativement aux différents critères 
        $this->getSubjectsByCriteria($request);
        /* Si c'est une action ajax (recherche des sujets pour les steps de campagne par ex ) */
        if ($request->isXmlHttpRequest()):
            $this->is_ajax_request=true;
            $this->setTemplate('searchBoxForSteps');
        endif;
    }

    /* Changement du package d'une ligne de migration (Cas d'une ligne de fonction) */
    public function processChangeInterventionMigrationLineForFunction(){
        $this->html = "can't found  script whith theses  parameters ..."; 
        $this->ei_script = Doctrine_Core::getTable("EiScript")->findOneByScriptId($this->script_id);
            if ($this->ei_script != null):
                /* Avant l'envoi de la reqûete au système central , on s'assure que la fonction ne possède pas de script pour le package/ticket concerné */
                $exist_script = Doctrine_Core::getTable("EiScript")->findByFunctionIdAndFunctionRefAndTicketIdAndTicketRef($this->ei_script->getFunctionId(), $this->ei_script->getFunctionRef(), $this->ei_ticket->getTicketId(), $this->ei_ticket->getTicketRef());
                if (count($exist_script) == 0):
                    $result = $this->ei_script->changeInterventionInMigrationLine($this->ei_ticket); //On invoque la méthode de la classe ei_script permettant de changer l'intervention
                    
                    $this->html = $result["message"];
                    $this->success = $result["success"];
                else:
                    $this->html = "Warning! there is a function already  link to this intervention ...";
                endif;
            endif;
    }
    /* Changement du package d'une ligne de migration (Cas d'une ligne de scenario) */
    public function processChangeInterventionMigrationLineForScenario(){
        $this->html = "can't found  scenario version whith theses  parameters ..."; 
        $this->ei_version = Doctrine_Core::getTable("EiVersion")->findOneById($this->scenario_version_id);
            if ($this->ei_version != null):
                /* Avant l'envoi de la reqûete au système  , on s'assure que le scenario ne possède pas de version pour le package/ticket concerné */
                $exist_scenario_version = Doctrine_Core::getTable("EiScenarioPackage")->findByEiScenarioIdAndPackageIdAndPackageRef($this->ei_version->getEiScenarioId(),
                         $this->ei_ticket->getTicketId(), $this->ei_ticket->getTicketRef());
                if (count($exist_scenario_version) == 0):
                    //On crée la ligne permettant de lier la version de scénario à l'intervention
                    $ei_scenario_package=Doctrine_Core::getTable("EiScenarioPackage")->findOneByEiScenarioIdAndEiVersionId(
                            $this->ei_version->getEiScenarioId(),$this->ei_version->getId() );
            
                    $ei_scenario_package->setPackageId($this->ei_ticket->getTicketId());
                    $ei_scenario_package->setPackageRef($this->ei_ticket->getTicketRef()); 
                    $ei_scenario_package->save();
                    $this->html = "Transaction maded successfully ...";
                    $this->success =true;
                else:
                    $this->html = "Warning! there is a scenario already  link to this intervention ...";
                endif;
            endif;
    }
    /* Changement du package d'une ligne de migration */
    public function executeChangeInterventionInMigrationLine(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil 
        $this->checkSubject($request, $this->ei_project);
        $this->ei_subject->save();
        $this->ei_ticket = $this->ei_subject->getEiPackage();
        $this->html = "Missing object parameters for request..."; 
        $this->success = false;
        /* Récupération du script concerné */
        $this->script_id = $request->getParameter("script_id");
        $this->scenario_version_id = $request->getParameter("scenario_version_id");
        if ($this->script_id != null && $this->scenario_version_id ==null ): 
            $this->processChangeInterventionMigrationLineForFunction();   
        endif;
        if ($this->scenario_version_id != null && $this->script_id ==null):
            $this->processChangeInterventionMigrationLineForScenario();      
        endif;
        
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'success' => $this->success)));
        return sfView::NONE;
    }

    //Page d'administration des migrations de fonction d'un bug 
    public function executeAdminMigration(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil 
        $this->checkSubject($request, $this->ei_project); 
        $this->ei_ticket=$this->ei_subject->getEiPackage();
        $implicatedTikets = "((" . $this->ei_ticket->getTicketId() . "," . $this->ei_ticket->getTicketRef() . "))";
        $this->ei_profiles = $this->ei_project->getProfils(); //Récupération des profils 

        $criteria = array(
            'project_id' => $this->project_id,
            'project_ref' => $this->project_ref,
            'ticket_id' => $this->ei_ticket->getTicketId(),
            'ticket_ref' => $this->ei_ticket->getTicketRef(),
            'script_ticket_id' => $this->ei_ticket->getTicketId(),
            'script_ticket_ref' => $this->ei_ticket->getTicketRef(),
            'subject_id' => $this->ei_subject->getId(),
        );
        $criteriaScenarios = array(
            'project_id' => $this->project_id,
            'project_ref' => $this->project_ref,
            'subject_id' => $this->ei_subject->getId(),
        );
        if ($this->ei_subject->getDeliveryId() != null):
            $criteria['delivery_id'] = $this->ei_subject->getDeliveryId();
            $criteriaScenarios['delivery_id'] = $this->ei_subject->getDeliveryId();
        endif;
        /* Gestion de la migration des fonctions */
        $this->migrateFuncts = Doctrine_Core::getTable('EiDelivery')
                ->getFunctionsToMigrate($criteria, "SELECT COUNT(t_id) as nb_occurences,df_vw.*");
        $this->migrateFunctsWithoutCount = Doctrine_Core::getTable('EiDelivery')
                ->getFunctionsToMigrate($criteria, "SELECT df_vw.*", false);

        //Récupération des relations script-profil impliquant le ticket en question
        $this->TabScriptProfiles = Doctrine_Core::getTable('EiTicket')->getAssociatedProfilesForDelivery($implicatedTikets);
        $this->scriptProfiles = array();
        if (!empty($this->TabScriptProfiles)) {
            foreach ($this->TabScriptProfiles as $scriptProfile):
                $tab[$scriptProfile['function_id'] . '_' . $scriptProfile['function_ref'] . '_' .
                        $scriptProfile['profile_id'] . '_' . $scriptProfile['profile_ref']] = $scriptProfile['script_id'];
            endforeach;
            $this->scriptProfiles = $tab;
        }

        /* Gestion des scénarios à migrer */
        $this->TabScenarioProfiles = Doctrine_Core::getTable('EiTicket')->getVersionsScenarioProfiles($implicatedTikets);

        if (!empty($this->TabScenarioProfiles)) {
            foreach ($this->TabScenarioProfiles as $versionProfile):
                $tab2[$versionProfile['ei_scenario_id'] . '_' . $versionProfile['profile_id'] . '_' . $versionProfile['profile_ref']] = $versionProfile['ei_version_id'];
            endforeach;
            $this->versionsProfiles = $tab2;
        }
        $this->scenariosToMigrate = Doctrine_Core::getTable('EiDelivery')->getScenariosToMigrate($criteriaScenarios, "SELECT COUNT(sc_id) as nb_occurences,ds_vw.*");
        $this->scenariosToMigrateWithoutCount = Doctrine_Core::getTable('EiDelivery')->getScenariosToMigrate($criteriaScenarios, "SELECT ds_vw.*", false);
    }

    //Migration d'un scénario de bug 
    public function executeMigrateBugScenario(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du projet 
        $this->checkEiScenario($request, $this->ei_project);
        $this->checkPackage($request, $this->ei_project);
        $guardUser = MyFunction::getGuard();
        //On invoque la fonction qui appelera le webservice et effectura la migration
        $result = $this->ei_package->migrateBugScenario($this->ei_project, $this->ei_profile, $this->ei_scenario, $guardUser);
        return $this->renderText(json_encode(array(
                    'html' => $result,
                    'success' => ($result ? true : false))));
        return sfView::NONE;
    }

    //Migration de plusieurs scénario d'un bug à la fois 
    public function executeMigrateManyBugScenario(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil  
        $this->ei_profiles = $this->ei_project->getProfils(); //Récupération des profils 
        if ($request->getParameter('subject_id') != null):
            $this->checkSubject($request, $this->ei_project); 
            $this->ei_ticket=$this->ei_subject->getEiPackage();
            $implicatedTikets = "((" . $this->ei_ticket->getTicketId() . "," . $this->ei_ticket->getTicketRef() . "))";
        endif;

        if ($request->getParameter('delivery_id') != null): //On migre les scénarios d'une livraison
            $this->checkDelivery($request, $this->ei_project);
        endif;

        $guardUser = MyFunction::getGuard();
        $this->html = 'Error';
        //Traitement éffective de la migration 
        $result = Doctrine_Core::getTable('EiTicket')->MigrateManyScenarios($this->ei_project, $this->ei_profile, $guardUser, $request->getParameter('tab'));
        /* Préparation de la reponse json */
        if ($result):
            $criteriaScenarios = array(
                'project_id' => $this->project_id,
                'project_ref' => $this->project_ref
            );
            if ($this->ei_delivery && $this->ei_delivery != null):
                $criteriaScenarios['delivery_id'] = $this->ei_delivery->getId();
            endif;

            if ($this->ei_subject):
                $criteriaScenarios['subject_id'] = $this->ei_subject->getId();
                if ($this->ei_subject->getDeliveryId() != null):
                    $criteriaScenarios['delivery_id'] = $this->ei_subject->getDeliveryId();
                endif;
            endif;

            /* Gestion des scénarios à migrer */

            $this->scenariosToMigrate = Doctrine_Core::getTable('EiDelivery')->getScenariosToMigrate($criteriaScenarios, "SELECT COUNT(sc_id) as nb_occurences,ds_vw.*");
            $this->scenariosToMigrateWithoutCount = Doctrine_Core::getTable('EiDelivery')->getScenariosToMigrate($criteriaScenarios, "SELECT ds_vw.*", false);
            if ($this->ei_delivery && $this->ei_delivery != null):
                $implicatedTikets = '(';
                $k = count($this->scenariosToMigrate);
                $i = 1;
                foreach ($this->scenariosToMigrate as $migrateScen):
                    $implicatedTikets.="(" . $migrateScen['s_package_id'] . "," . $migrateScen['s_package_ref'] . ")";
                    if ($k != $i):
                        $implicatedTikets.=",";
                    endif;
                    $i++;
                endforeach;
                $implicatedTikets.=")";
            endif;
            $this->TabScenarioProfiles = array();
            if (isset($implicatedTikets)):
                $this->TabScenarioProfiles = Doctrine_Core::getTable('EiTicket')->getVersionsScenarioProfiles($implicatedTikets);
            endif;

            if (!empty($this->TabScenarioProfiles)) {
                foreach ($this->TabScenarioProfiles as $versionProfile):
                    $tab2[$versionProfile['ei_scenario_id'] . '_' . $versionProfile['profile_id'] . '_' . $versionProfile['profile_ref']] = $versionProfile['ei_version_id'];
                endforeach;
                $this->versionsProfiles = $tab2;
            }

            //Récupération des conflits de scenarios résolus sur la livraison 
            if ($this->ei_delivery && $this->ei_delivery != null):
                $resolved_conflicts_scenario_list = $this->ei_delivery->getResolvedConflictsOnScenarios();
                $resolved_conflicts_scenarios = array();
                if (count($resolved_conflicts_scenario_list) > 0):
                    foreach ($resolved_conflicts_scenario_list as $conflict):
                        if (array_key_exists($conflict['ei_scenario_id'] . '_' . $conflict['delivery_id'], $resolved_conflicts_scenarios)):
                            $resolved_conflicts_scenarios[$conflict['ei_scenario_id'] . '_' . $conflict['delivery_id']]['profile'][] = array(
                                'profile_id' => $conflict['profile_id'],
                                'profile_ref' => $conflict['profile_ref']
                            );
                        else:
                            $resolved_conflicts_scenarios[$conflict['ei_scenario_id'] . '_' . $conflict['delivery_id']] = array(
                                "package_id" => $conflict['package_id'],
                                'package_ref' => $conflict['package_ref'],
                                'profile' => array(0 => array('profile_id' => $conflict['profile_id'], 'profile_ref' => $conflict['profile_ref'])
                            ));
                        endif;

                    endforeach;
                endif;
            endif;
            $scenariosToMigrateList = $this->urlParameters;
            $scenariosToMigrateList['scenariosToMigrate'] = $this->scenariosToMigrate;
            $scenariosToMigrateList['scenariosToMigrateWithoutCount'] = $this->scenariosToMigrateWithoutCount;
            $scenariosToMigrateList['ei_project'] = $this->ei_project;
            $scenariosToMigrateList['ei_profiles'] = $this->ei_profiles;
            $scenariosToMigrateList['ei_profile'] = $this->ei_profile;
            $scenariosToMigrateList['versionsProfiles'] = $this->versionsProfiles;
            $scenariosToMigrateList['ei_delivery'] = (($this->ei_delivery && $this->ei_delivery != null) ? $this->ei_delivery : null);
            $scenariosToMigrateList['resolved_conflicts'] = isset($resolved_conflicts_scenarios) ? $resolved_conflicts_scenarios : array(); // Liste des conflits de scenarios résolus sur la livraison
            $this->html = $this->getPartial('eidelivery/scenariosToMigrateList', $scenariosToMigrateList);
        else:
            $this->html = "Error in process ...";
        endif;

        /* Retour json */
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'success' => ($result ? true : false))));
        return sfView::NONE;
    }

    //Migration d'une fonction 
    public function executeMigrateBugFunction(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du projet 
        $this->checkFunction($request, $this->ei_project);
        $this->checkTicket($request, $this->ei_project);
        $guardUser = MyFunction::getGuard();
        //On invoque la fonction qui appelera le webservice et effectura la migration
        $result = $this->ei_ticket->migrateBugFunction($this->ei_project, $this->ei_profile, $this->kal_function, $guardUser);
        if ($result == true):
            $xml = $this->ei_project->downloadKalFonctions();
            if ($xml != null): $this->ei_project->transactionToLoadObjectsOfProject($xml);
            endif;
        endif;
        return $this->renderText(json_encode(array(
                    'html' => $result,
                    'success' => ($result ? true : false))));
        return sfView::NONE;
    }

    //Migration de plusieurs fonctions d'un bug
    public function executeMigrateManyBugFunction(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du projet 
        $this->checkFunction($request, $this->ei_project);
        if ($request->getParameter('subject_id') != null):
            $this->checkSubject($request, $this->ei_project); 
        endif;
        if ($request->getParameter('delivery_id') != null)
            $this->checkDelivery($request, $this->ei_project);

        $guardUser = MyFunction::getGuard();
        $this->html = 'Error';
        //Traitement éffective de la migration 
        $result = Doctrine_Core::getTable('EiTicket')->MigrateManyFunctions($this->ei_project, $this->ei_profile, $guardUser, $request->getParameter('tab'));
        if ($result == true):
            $xml = $this->ei_project->downloadKalFonctions();
            if ($xml != null): $this->ei_project->transactionToLoadObjectsOfProject($xml);
            endif;
        endif;
        if ($result):
            $criteria = array(
                'project_id' => $this->project_id,
                'project_ref' => $this->project_ref,
            );
            if ($this->ei_delivery && $this->ei_delivery != null):
                $criteria['delivery_id'] = $this->ei_delivery->getId();
            endif;
            if ($this->ei_subject && $this->ei_subject != null):
                $criteria['ticket_id'] = $this->ei_subject->getPackageId();
                $criteria['ticket_ref'] = $this->ei_subject->getPackageRef();
                $criteria['subject_id'] = $this->ei_subject->getId();
                if ($this->ei_subject->getDeliveryId() != null):
                    $criteria['delivery_id'] = $this->ei_subject->getDeliveryId();
                endif;
            endif;
            $this->migrateFuncts = Doctrine_Core::getTable('EiDelivery')
                    ->getFunctionsToMigrate($criteria, "SELECT COUNT(t_id) as nb_occurences,df_vw.*");
            $this->migrateFunctsWithoutCount = Doctrine_Core::getTable('EiDelivery')
                    ->getFunctionsToMigrate($criteria, "SELECT df_vw.*", false);

            $this->ei_profiles = $this->ei_project->getProfils(); //Récupération des profils 
            if ($this->ei_subject && $this->ei_subject != null):
                $implicatedTikets = "((" . $this->ei_subject->getPackageId() . "," . $this->ei_subject->getPackageRef() . "))";
            else:
                $implicatedTikets = '(';
                $k = count($this->migrateFuncts);
                $i = 1;
                foreach ($this->migrateFuncts as $migrateFunc):
                    $implicatedTikets.="(" . $migrateFunc['s_package_id'] . "," . $migrateFunc['s_package_ref'] . ")";
                    if ($k != $i):
                        $implicatedTikets.=",";
                    endif;
                    $i++;
                endforeach;
                $implicatedTikets.=")";
            endif;

            //Récupération des relations script-profil impliquant le ticket en question
            $this->TabScriptProfiles = Doctrine_Core::getTable('EiTicket')->getAssociatedProfilesForDelivery($implicatedTikets);
            $this->scriptProfiles = array();
            if (!empty($this->TabScriptProfiles)):
                foreach ($this->TabScriptProfiles as $scriptProfile):
                    $tab[$scriptProfile['function_id'] . '_' . $scriptProfile['function_ref'] . '_' .
                            $scriptProfile['profile_id'] . '_' . $scriptProfile['profile_ref']] = $scriptProfile['script_id'];
                endforeach;
                $this->scriptProfiles = $tab;
                //Récupération des conflits résolus sur la livraison  (si on est sur une livraison
                if ($this->ei_delivery && $this->ei_delivery != null):
                    $resolved_conflicts_list = $this->ei_delivery->getResolvedConflictsOnFunctions();
                    $resolved_conflicts = array();
                    if (count($resolved_conflicts_list) > 0):
                        foreach ($resolved_conflicts_list as $conflict):
                            if (array_key_exists($conflict['function_id'] . '_' . $conflict['function_ref'] . '_' . $conflict['delivery_id'], $resolved_conflicts)):
                                $resolved_conflicts[$conflict['function_id'] . '_' . $conflict['function_ref'] . '_' . $conflict['delivery_id']]['profile'][] = array(
                                    'profile_id' => $conflict['profile_id'],
                                    'profile_ref' => $conflict['profile_ref']
                                );
                            else:
                                $resolved_conflicts[$conflict['function_id'] . '_' . $conflict['function_ref'] . '_' . $conflict['delivery_id']] = array("package_id" => $conflict['package_id'], 'package_ref' => $conflict['package_ref'], 'profile' => array(0 => array('profile_id' => $conflict['profile_id'],
                                            'profile_ref' => $conflict['profile_ref'])
                                ));
                            endif;

                        endforeach;
                    endif;
                endif;
                
                $functionsToMigrateListUri=$this->urlParameters;
                $functionsToMigrateListUri['migrateFuncts']=$this->migrateFuncts;
                $functionsToMigrateListUri['migrateFunctsWithoutCount']=$this->migrateFunctsWithoutCount;
                $functionsToMigrateListUri['ei_project']=$this->ei_project;
                $functionsToMigrateListUri['ei_profiles']=$this->ei_profiles;
                $functionsToMigrateListUri['scriptProfiles']=$this->scriptProfiles;
                $functionsToMigrateListUri['ei_delivery']= (($this->ei_delivery && $this->ei_delivery != null) ? $this->ei_delivery : null);
                $functionsToMigrateListUri['resolved_conflicts']= isset($resolved_conflicts) ? $resolved_conflicts : array() ;// Liste des conflits de fonction résolus sur la livraison 
                $this->html = $this->getPartial('eidelivery/functionsToMigrateList', $functionsToMigrateListUri );
            endif;
        endif;
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'success' => ($result ? true : false))));
        return sfView::NONE;
    }

    //Changement du profil de migration ( pour migrer plusieurs fonctions d'un bug d'un coup)
    public function executeChangeMigrationProfile(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); // Récupération du profil 
        $this->checkSubject($request, $this->ei_project); //Récupération du sujet concerné 
        $this->ei_profiles = $this->ei_project->getProfils(); //Récupération des profils
        $migrationCase = $request->getParameter('migrationCase');
        //Retour du partiel avec la reponse json 
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eisubject/profilesForMigration', array('ei_profiles' => $this->ei_profiles,
                        'ei_project' => $this->ei_project,
                        'ei_subject' => $this->ei_subject,
                        'current_profile' => $this->ei_profile,
                        'migrationCase' => ($migrationCase != null ? $migrationCase : null))),
                    'success' => true)));
        return sfView::NONE;
    }

    //Récupération des campagnes de tests liées à un sujet 
    public function executeGetSubjectCampaigns(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkSubject($request, $this->ei_project); 
        //Campagnes d'un  sujet
        $this->ei_subject_campaigns = $this->ei_subject->getSubjectCampaigns();
    }

    public function executeNew(sfWebRequest $request) {
        $this->guardUser = $this->guard_user;
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $subject = new EiSubject();
        $subject->setProjectId($this->ei_project->getProjectId());
        $subject->setProjectRef($this->ei_project->getRefId());
        $subject->setAuthorId($this->guardUser->getId());
        $this->form = new EiSubjectForm($subject, array(
            'ei_project' => $this->ei_project,
            'guardUser' => $this->guardUser));
        unset($this->form[$this->form->getCSRFFieldName()]);
    }

    public function executeCreate(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        if (!$request->isMethod(sfRequest::POST)):
            $this->getUser()->setFlash('alert_version_form', array('title' => 'Warning',
                'class' => 'alert-warning',
                'text' => 'Form has been reinitialized. Need posts parameters'));
            $this->redirect($this->generateUrl('subject_new', $this->urlParameters));
        endif;
        $this->guardUser = $this->guard_user;
        $subject = new EiSubject();
        $subject->setProjectId($this->ei_project->getProjectId());
        $subject->setProjectRef($this->ei_project->getRefId());
        $subject->setAuthorId($this->guardUser->getId());
        $this->form = new EiSubjectForm($subject, array(
            'ei_project' => $this->ei_project,
            'guardUser' => $this->guardUser));
        unset($this->form[$this->form->getCSRFFieldName()]);
        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function getAssignAndNonAssignUserToSubject(EiSubject $ei_subject) {
        $this->alreadyAssignUsers = $ei_subject->getAssignUsers();
        $this->usersToAssignToSubject = $ei_subject->getNonAssignUsers($this->alreadyAssignUsers);
    }

    public function executeEdit(sfWebRequest $request) {
        $this->guardUser = $this->guard_user;
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkSubject($request, $this->ei_project); 
        $this->subjectAttachments = Doctrine_Core::getTable('EiSubjectAttachment')->findBySubjectIdAndType(
                $this->ei_subject->getId(), sfConfig::get('app_bug_attachment_description'));

        //Recherche des utilisateurs assignés et non-assignés au subjet
        $this->getAssignAndNonAssignUserToSubject($this->ei_subject);
        //On réccupère éventuellement le contexte de création du bug
        $this->ei_context = $this->ei_subject->getBugContextSubject()->getFirst();
        $this->projectUsers = Doctrine_Core::getTable('sfGuardUser')->getProjectUsers($this->ei_project);
        $this->form = new EiSubjectForm($this->ei_subject, array(
            'ei_project' => $this->ei_project,
            'guardUser' => $this->guardUser));
    }

    public function executeShow(sfWebRequest $request) {
        $this->guardUser = $this->guard_user; 
        $this->checkProject($request); //Récupération du projet
        $this->defaultIntervention=$this->ei_user->getDefaultIntervention($this->ei_project); //Récupération de l'intervention par défaut
        //Récupération du type de méssage question et réponse
        $this->msgQuestion = Doctrine_Core::getTable('EiSubjectMessageType')->findOneByNameAndProjectIdAndProjectRef("Question", $this->ei_project->getProjectId(), $this->ei_project->getRefId());
        $this->msgAnswer = Doctrine_Core::getTable('EiSubjectMessageType')->findOneByNameAndProjectIdAndProjectRef("Answer", $this->ei_project->getProjectId(), $this->ei_project->getRefId());
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkSubject($request, $this->ei_project); 
        /* Recupération de l'historique des assignations au bug */
        $this->bugAssignmentHistorys = $this->ei_subject->getAssignmentsHistory();
        $this->subjectAuthor = Doctrine_Core::getTable('sfGuardUser')->findOneById($this->ei_subject->getAuthorId());
        $this->subjectAttachments = Doctrine_Core::getTable('EiSubjectAttachment')->findBySubjectIdAndType(
                $this->ei_subject->getId(), sfConfig::get('app_bug_attachment_description'));

        //Recherche des utilisateurs assignés et non-assignés au subjet
        $this->getAssignAndNonAssignUserToSubject($this->ei_subject);
        $this->projectUsers = Doctrine_Core::getTable('sfGuardUser')->getProjectUsers($this->ei_project);
        //Méssages sur la description du sujet
        $this->subjectMessages = Doctrine_Core::getTable('EiSubjectMessage')
                ->getMessages($this->subject_id, sfConfig::get("app_bug_description_message"));
        //On réccupère éventuellement le contexte de création du bug
        $this->ei_context = $this->ei_subject->getBugContextSubject()->getFirst();
        //Formulaire d'ajout d'un nouveau attachment 
        $attach = new EiSubjectAttachment();
        $attach->setSubjectId($this->ei_subject->getId());
        $attach->setType(sfConfig::get('app_bug_attachment_description'));
        $attach->setAuthorId($this->guardUser->getId());
        $this->newAttachForm = new EiSubjectAttachmentForm($attach);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->guardUser = $this->guard_user;
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkSubject($request, $this->ei_project);
        $this->subjectAttachments = Doctrine_Core::getTable('EiSubjectAttachment')->findBySubjectIdAndType(
                $this->ei_subject->getId(), sfConfig::get('app_bug_attachment_description'));

        $this->form = new EiSubjectForm($this->ei_subject, array(
            'ei_project' => $this->ei_project,
            'guardUser' => $this->guardUser));

        //Recherche des utilisateurs assignés et non-assignés au subjet
        $this->getAssignAndNonAssignUserToSubject($this->ei_subject);
        $this->projectUsers = Doctrine_Core::getTable('sfGuardUser')->getProjectUsers($this->ei_project);
        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->forward404Unless($ei_subject = Doctrine_Core::getTable('EiSubject')->find(array($request->getParameter('id'))), sprintf('Object ei_subject does not exist (%s).', $request->getParameter('id')));
        $this->cleanSubjectSessionVar();
        $ei_subject->delete();

        $this->redirect('eisubject/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $ei_subject = $form->save();
            $this->getUser()->setFlash('alert_form', array('title' => 'Success',
                'class' => 'alert-success',
                'text' => 'Well done ...'));
            $subject_show = $this->urlParameters;
            $subject_show['subject_id'] = $ei_subject->getId();
            $this->redirect($this->generateUrl('subject_show', $subject_show));
        } else {
            $this->getUser()->setFlash('alert_form', array('title' => 'Error',
                'class' => 'alert-danger',
                'text' => 'An error occurred while trying to save this intervention. Check requirements'));
        }
    }

}
