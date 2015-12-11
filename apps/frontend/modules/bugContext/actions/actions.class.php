<?php

/**
 * bugContext actions.
 *
 * @package    kalifastRobot
 * @subpackage bugContext
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class bugContextActions extends sfActionsKalifast {

    //On recherche le noeud de campagne du graphe
    public function checkCampaignGraph(sfWebRequest $request) {
        $this->campaign_graph_id = $request->getParameter('campaign_graph_id');

        if ($this->campaign_graph_id == null):
            $this->forward404('Campaign parameter not found');
        else:
            $this->ei_campaign_graph = Doctrine_Core::getTable('EiCampaignGraph')
                    ->getCampaignGraphStep($this->campaign_graph_id);
            if (count($this->ei_campaign_graph) == null):
                $this->forward404('Campaign node not found');
            endif;
        endif;
    }

    //Recherche du sujet d'un contexte
    public function checkSubject(sfWebRequest $request, EiProjet $ei_project) {
        $this->subject_id = $request->getParameter('subject_id');
        if ($this->subject_id == null)
            $this->forward404('Missing subject parameters');
        //Recherche du sujet tout en s'assurant qu'elle corresponde au projet courant 
        $this->ei_subject_with_relation = Doctrine_Core::getTable('EiSubject')
                ->getSubject($ei_project->getProjectId(),$ei_project->getRefId(),$this->subject_id);
        $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneById($this->subject_id);
        if ($this->ei_subject == null)
            $this->forward404('Subject not found');
        if ($this->ei_subject->getProjectId() != $ei_project->getProjectId() ||
                $this->ei_subject->getProjectRef() != $ei_project->getRefId())
            $this->forward404('Subject is not part of project . Access denied ...'); 
    }

    //Recherche du contexte
    public function checkContext(sfWebRequest $request, EiSubject $ei_subject) {
        $this->ei_context_id = $request->getParameter('id');
        if ($this->ei_context_id == null)
            $this->forward404('Missing context parameters');
        //Recherche du contexte tout en s'assurant qu'elle corresponde au sujet courant 
        $this->ei_context = Doctrine_Core::getTable('EiBugContext')->getContext($this->ei_context_id);
        if ($this->ei_context == null)
            $this->forward404('Subject not found');
        //Vérification de la possibilité d'accès 
        if ($this->ei_context->getSubjectId() != $ei_subject->getId())
            $this->forward404('Context is not part of subject . Access denied ...');
    }

    //Création d'un widget de step de campagne pour les mises à jour de liste déroulante de steps
    public function executeRenderCampaignStepWidget(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        if ($this->campaign_id == null)
            $this->ei_campaign == null;
        else
            $this->checkCampaign($request, $this->ei_project);
        $form = new EiBugContextForm();
        $form->createWidgetForCampaignStep($this->ei_project, $this->ei_campaign);
        return
                $this->renderText(json_encode(array(
                    'html' => $this->getPartial('bugContext/stepWidget', array('form' => $form)),
                    'success' => true)));

        return sfView::NONE;
    }

    public function executeIndex(sfWebRequest $request) {
        $this->ei_bug_contexts = Doctrine_Core::getTable('EiBugContext')
                ->createQuery('a')
                ->execute();
    }

    //Visuel d'un context sur la page d'un bug
    public function executeShowForSubject(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkSubject($request, $this->ei_project); 
        $this->checkContext($request, $this->ei_subject);

        $this->setTemplate('show');
    }

    //Création d'un contexte par défaut sur un bug
    public function executeCreateDefaultBugContext(sfWebRequest $request) {
        $this->guardUser = $this->getUser()->getGuardUser();
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkSubject($request, $this->ei_project); 
        //On vérifie qu'il existe aucun contexte pr le subjet
        $ei_subject_contexts = $this->ei_subject->getContexts();
        if (count($ei_subject_contexts) == 0):
            $context = new EiBugContext();
            $context->setSubjectId($this->ei_subject->getId());
            $context->setProfileId($this->ei_profile->getProfileId());
            $context->setProfileRef($this->ei_profile->getProfileRef());
            $context->setAuthorId($this->guardUser->getId());
            $context->save();
            $this->ei_context = Doctrine_Core::getTable('EiBugContext')->getContext($context->getId());
        else: //On récupère le 1er contexte trouvé en espérant qu'il y en ait qu'un
            $this->ei_context = Doctrine_Core::getTable('EiBugContext')
                    ->getContext($ei_subject_contexts->getFirst()->getId());
        endif;


        $this->setTemplate('show');
    }

    public function executeNew(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->form = new EiBugContextForm();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);

        $this->form = new EiBugContextForm();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeNewContext(sfWebRequest $request) {
        $this->guardUser = $this->getUser()->getGuardUser();
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkCampaignGraph($request);
        $context = new EiBugContext();
        $context->setCampaignId($this->ei_campaign_graph->getCampaignId());
        $context->setScenarioId($this->ei_campaign_graph->getScenarioId());
        $context->setCampaignGraphId($this->ei_campaign_graph->getId());
        $context->setEiDataSetId($this->ei_campaign_graph->getDataSetId());
        $context->setProfileId($this->ei_profile->getProfileId());
        $context->setProfileRef($this->ei_profile->getProfileRef());
        $context->setAuthorId($this->guardUser->getId());

        $subject = new EiSubject();
        $subject->setProjectId($this->ei_project->getProjectId());
        $subject->setProjectRef($this->ei_project->getRefId());
        $subject->setAuthorId($this->guardUser->getId());

        $this->form = new EiSubjectForm($subject, array(
            'ei_project' => $this->ei_project,
            'guardUser' => $this->guardUser,
            'ei_bug_context' => $context));

        $newContextForm = $this->urlParameters;
        $newContextForm['form'] = $this->form;
        $newContextForm['campaign_graph_id'] = $this->campaign_graph_id;
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('bugContext/newContextForm', $newContextForm),
                    'success' => true)));

        return sfView::NONE;
    }

    public function executeCreateContext(sfWebRequest $request) {
        $this->guardUser = $this->getUser()->getGuardUser();
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkCampaignGraph($request);
        $context = new EiBugContext();
        $context->setCampaignId($this->ei_campaign_graph->getCampaignId());
        $context->setScenarioId($this->ei_campaign_graph->getScenarioId());
        $context->setCampaignGraphId($this->ei_campaign_graph->getId());
        $context->setEiDataSetId($this->ei_campaign_graph->getDataSetId());
        $context->setProfileId($this->ei_profile->getProfileId());
        $context->setProfileRef($this->ei_profile->getProfileRef());
        $context->setAuthorId($this->guardUser->getId());

        $subject = new EiSubject();
        $subject->setProjectId($this->ei_project->getProjectId());
        $subject->setProjectRef($this->ei_project->getRefId());
        $subject->setAuthorId($this->guardUser->getId());

        $this->form = new EiSubjectForm($subject, array(
            'ei_project' => $this->ei_project,
            'guardUser' => $this->guardUser,
            'ei_bug_context' => $context));



        $this->processForm($request, $this->form);
        if ($this->success):
            $show = $this->urlParameters;
            $show['ei_context'] = $this->ei_context;
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('bugContext/show', $show),
                        'success' => $this->success)));
        else:
            $newContextForm = $this->urlParameters;
            $newContextForm['form'] = $this->form;
            $newContextForm['campaign_graph_id'] = $this->campaign_graph_id;
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('bugContext/newContextForm', $newContextForm),
                        'success' => $this->success)));
        endif;
        return sfView::NONE;
    }

    public function executeEdit(sfWebRequest $request) {
        $this->guardUser = $this->getUser()->getGuardUser();
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        //Récupération du root folder du projet 
        $this->root_folder = Doctrine_Core::getTable('EiNode')
                ->getRootFolder($this->project_ref, $this->project_id);
        //Récupération des noeuds enfants du dossier
        $this->ei_nodes = $this->root_folder->getNodes(false, false);
        $this->checkSubject($request, $this->ei_project); 
        $this->checkContext($request, $this->ei_subject);
        $this->form = new EiBugContextForm($this->ei_context, array(
            'ei_project' => $this->ei_project));
        $this->form->setDefault('author', $this->ei_context->getBugContextAuthor()->getEmailAddress());

        $this->form->setDefault('profile', $this->ei_profile->getProfileId() . '_' . $this->ei_profile->getProfileRef());

        //Récupération des livraisons pour le typehead de modification
        $this->guardUsersForTypeHead = Doctrine_Core::getTable('EiProjectUser')->getProjectUsers($this->ei_project);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->guardUser = $this->getUser()->getGuardUser();
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkSubject($request, $this->ei_project);
        $this->checkContext($request, $this->ei_subject);

        $this->form = new EiBugContextForm($this->ei_context, array(
            'ei_project' => $this->ei_project));

        $this->processFormForUpdate($request, $this->form);
        if ($this->success) :
            $show_Bug_Context = $this->urlParameters;
            $show_Bug_Context['id'] = $this->ei_context->getId();
            $show_Bug_Context['subject_id'] = $this->ei_subject->getId();
            
            $this->redirect($this->generateUrl('show_Bug_Context', $show_Bug_Context));
        else :
            //Récupération des livraisons pour le typehead de modification
            $this->guardUsersForTypeHead = Doctrine_Core::getTable('EiProjectUser')->getProjectUsers($this->ei_project);
            
            $this->setTemplate('edit');
        endif;
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($ei_bug_context = Doctrine_Core::getTable('EiBugContext')->find(array($request->getParameter('id'))), sprintf('Object ei_bug_context does not exist (%s).', $request->getParameter('id')));
        $ei_bug_context->delete();

        $this->redirect('bugContext/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $this->ei_new_subject = $form->save();
            $this->ei_contexts = Doctrine_Core::getTable('EiBugContext')->getSubjectContexts($this->ei_new_subject->getId());
            $this->ei_context = $this->ei_contexts->getFirst();
            $this->success = true;
            $this->getUser()->setFlash('alert_bug_context_form',
                    array('title' => 'Success' ,
                        'class' => 'alert-success' ,
                        'text' => 'Well done ...'));
        }
        else{
            $this->getUser()->setFlash('alert_bug_context_form',
                    array('title' => 'Error' ,
                        'class' => 'alert-danger' ,
                        'text' => 'An error occurred while trying to save this intervention\'s context. Check requirements'));
            $this->success = false;
        }
            
    }

    /*
     * Mise à jour d'un contexte
     */

    protected function processFormForUpdate(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $this->ei_bug_context = $form->save();
            $this->ei_context = Doctrine_Core::getTable('EiBugContext')->getContext($this->ei_bug_context->getId()); 
            $this->success = true;
            $this->getUser()->setFlash('alert_bug_context_form',
                    array('title' => 'Success' ,
                        'class' => 'alert-success' ,
                        'text' => 'Well done ...'));
            
        }
        else{
            $this->getUser()->setFlash('alert_bug_context_form',
                    array('title' => 'Error' ,
                        'class' => 'alert-danger' ,
                        'text' => 'An error occurred while trying to save this intervention\'s context. Check requirements'));
            $this->success = false;
        }
    }

}
