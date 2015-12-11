<?php

/**
 * subjectfunction actions.
 *
 * @package    kalifastRobot
 * @subpackage subjectfunction
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class subjectfunctionActions extends sfActionsKalifast {
    public function preExecute() {
        parent::preExecute();

        // Récupération de l'utilisateur.
        $this->guard_user = $this->getUser()->getGuardUser();
        $this->ei_user = $this->guard_user->getEiUser();
    }
    /* Récupération des fonctions d'un intervention */

    public function executeIndex(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkSubject($request, $this->ei_project);        
       /* Recherche pour la livraison des fonctions exécutées */
        $this->exFunctions=$this->ei_subject->getExFunctions($this->exec);   
        $this->modName="EiSubject";
        $this->setTemplate('impacts','eidelivery');
    }

    /* Sujets d'une fonction */

    public function executeFunctionSubjects(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        //sujets d'une fonction
        $this->ei_function_subjects = $this->kal_function->getFunctionSubjects();
    }

    /* Sujets d'une fonction */

    public function executeShowFunctionSubjects(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkFunction($request, $this->ei_project);
        /* Récupération des sujets de la fonction */
        $this->ei_function_subjects = $this->kal_function->getFunctionSubjects();
        /* Contenu d'une fonction */
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eicampaigngraph/rightSideFunctionBloc', array(
                        "ei_project" => $this->ei_project,
                        "kal_function" => $this->kal_function,
                        "ei_profile" => $this->ei_profile,
                        "ei_subjects" => $this->ei_function_subjects,
                        "ajax_request" => true)),
                    'success' => true)));
        return sfView::NONE;
    }

    /* Ajout d'une liaison intervention-fonction à partir du package par défaut */

    public function executeLinkFunctionWithDefPack(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest()); //Opréation exclusive en ajax
        $this->html = "Error in process.";
        $this->success = false;
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $paramLink = $this->urlParameters;
        $this->checkFunction($request, $this->ei_project); //Fonction à lier au intervention du package par défaut
        $this->defPack = $this->getDefaultPackage($this->ei_user, $this->ei_project); //Récupération du package par défaut
        
        /* Recherche du sujet par rapport au package par défaut */
        if ($this->defPack != null): //Le package par défaut existe bien
            $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneByPackageIdAndPackageRefAndProjectIdAndProjectRef(
                    $this->defPack->getTicketId(), $this->defPack->getTicketRef(), $this->ei_project->getProjectId(), $this->ei_project->getRefId());
         
            /* On se rassure d'avoir bien la fonction et le intervention à lier */
            if ($this->kal_function != null && $this->ei_subject != null): //La fonction et le intervention sont bien présents en base
                $paramLink['function_id'] = $this->kal_function->getFunctionId();
                $paramLink['function_ref'] = $this->kal_function->getFunctionRef();
                // On vérifie l'existence du lien 
                $link = Doctrine_Core::getTable('EiSubjectFunctions')->findOneBySubjectIdAndFunctionIdAndFunctionRef(
                        $this->ei_subject->getId(), $this->kal_function->getFunctionId(), $this->kal_function->getFunctionRef());
                /* Si la liaison existe déjà , on vérifie si elle est forte : auquel cas on renvoi une erreur interdisant la suppréssion (tentative d'intrusion) */
                if ($link != null): //La liaison existe
                    if (!$link->getAutomate()): //La liaison n'est pas forte : on la supprime
                        $link->delete();
                        $this->html = $this->getPartial('subjectfunction/link', $paramLink); //On retourne le partiel resultat
                        $this->success = true;
                    else:
                        $this->html("You can delete link ..."); //On indique à l'utilisateur l'impossibilité de supprimer le lien
                    endif;
                else: //La liaison n'existe pas et dans ce cas on la crée
                    $this->subject_function = new EiSubjectFunctions();
                    $this->subject_function->setKalFunction($this->kal_function);
                    $this->subject_function->setEiSubject($this->ei_subject);
                    $result = $this->subject_function->addFunction();
                    if ($result): //La liaison a bien été ajoutée
                        $paramLink['exist_link'] = true;
                        $this->html = $this->getPartial('subjectfunction/link', $paramLink);
                        $this->success = true;
                    else: //Erreur lors de l'ajout du lien
                        $this->html = "Failed to create link...";
                    endif;
                endif;

            else: //On renvoi une erreur pour le process
                $this->html = "Function or intervention not found ...";
            endif;
        else:
            $this->html = "Default package not found. Please set it in user settings or choose an intervention ...";
        endif;

        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'success' => $this->success)));
        return sfView::NONE;
    }

    public function __call($method, $arguments) {
        parent::__call($method, $arguments);
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new EiSubjectFunctionsForm();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->form = new EiSubjectFunctionsForm();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($ei_subject_functions = Doctrine_Core::getTable('EiSubjectFunctions')->find(array($request->getParameter('subject_id'),
            $request->getParameter('function_id'),
            $request->getParameter('function_ref'))), sprintf('Object ei_subject_functions does not exist (%s).', $request->getParameter('subject_id'), $request->getParameter('function_id'), $request->getParameter('function_ref')));
        $this->form = new EiSubjectFunctionsForm($ei_subject_functions);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($ei_subject_functions = Doctrine_Core::getTable('EiSubjectFunctions')->find(array($request->getParameter('subject_id'),
            $request->getParameter('function_id'),
            $request->getParameter('function_ref'))), sprintf('Object ei_subject_functions does not exist (%s).', $request->getParameter('subject_id'), $request->getParameter('function_id'), $request->getParameter('function_ref')));
        $this->form = new EiSubjectFunctionsForm($ei_subject_functions);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($ei_subject_functions = Doctrine_Core::getTable('EiSubjectFunctions')->find(array($request->getParameter('subject_id'),
            $request->getParameter('function_id'),
            $request->getParameter('function_ref'))), sprintf('Object ei_subject_functions does not exist (%s).', $request->getParameter('subject_id'), $request->getParameter('function_id'), $request->getParameter('function_ref')));
        $ei_subject_functions->delete();

        $this->redirect('subjectfunction/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $ei_subject_functions = $form->save();

            $this->redirect('subjectfunction/edit?subject_id=' . $ei_subject_functions->getSubjectId() . '&function_id=' . $ei_subject_functions->getFunctionId() . '&function_ref=' . $ei_subject_functions->getFunctionRef());
        }
    }

}
