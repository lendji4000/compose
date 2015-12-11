<?php

/**
 * eisubjectmessage actions.
 *
 * @package    kalifastRobot
 * @subpackage eisubjectmessage
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eisubjectmessageActions extends sfActionsKalifast {

    public function preExecute() {
        $this->guardUser = $this->getUser()->getGuardUser();
        parent::preExecute();
    }

    public function preAct(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); //Récupération du profil courant
        $this->checkSubject($request, $this->ei_project);
    }
 

    public function executeIndex(sfWebRequest $request) {
        $this->ei_subject_messages = Doctrine_Core::getTable('EiSubjectMessage')
                ->createQuery('a')
                ->execute();
    }

    /* Ajout d'un méssage rapide sur un sujet */

    public function executeAddQuickMsg(sfWebRequest $request) {
        $this->preAct($request);
        $ei_subject_message = new EiSubjectMessage();
        $ei_subject_message->setGuardId($this->guardUser->getId());
        $ei_subject_message->setSubjectId($this->ei_subject->getId());
        $this->parent_id = intval($request->getParameter('parent_id'));        
        $this->message_type_id = $request->getParameter('message_type_id');
        $this->ei_message_text = $request->getParameter('ei_message_text');
        $this->ei_message_type = $request->getParameter('ei_message_type');
        $this->success = false;
        /* Si le message est vide , on retourne une erreur */
        if ($this->ei_message_text && trim($this->ei_message_text) != ""):
            $ei_subject_message->setMessage($this->ei_message_text);
        else:
            return $this->renderText(json_encode(array(
                        'html' => "Empty message",
                        'success' => $this->success)));
        endif;
        /* Récupération du type de message bug_description_message ou bug_details_message ou bug_solution_message ou  bug_migration_message */
        if ($this->ei_message_type && trim($this->ei_message_type) != ""):
            $ei_subject_message->setType($this->ei_message_type);
        else:
            return $this->renderText(json_encode(array(
                        'html' => "Empty message type ",
                        'success' => $this->success)));
        endif;
        /* On se rassure que le parent existe : sinon on set le parent à null */
        
        if ($this->parent_id==0 || $this->parent_id != null):   
            $parent = Doctrine_Core::getTable('EiSubjectMessage')->findOneById($this->parent_id);
            if ($parent != null):
                $ei_subject_message->setDirectParent($parent); 
            endif;
        else:
            return $this->renderText(json_encode(array(
                        'html' => "Error : Parent not found",
                        'success' => $this->success)));
        endif;
        // On se rassure que le type de méssage est bien définit et existe pour le projet
        if ($this->message_type_id != null):
            $msgType = Doctrine_Core::getTable('EiSubjectMessageType')->findOneByIdAndProjectIdAndProjectRef(
                    $this->message_type_id, $this->ei_project->getProjectId(), $this->ei_project->getRefId());
            if ($msgType != null):
                $ei_subject_message->setMessageTypeId($msgType->getId());
            else:
                return $this->renderText(json_encode(array(
                            'html' => "Error : no msg type specified",
                            'success' => $this->success)));
            endif;
        else:
            return $this->renderText(json_encode(array(
                        'html' => "Error : no msg type specified",
                        'success' => $this->success)));
        endif;

        $res = $ei_subject_message->save();
        //throw new Exception($res);
        if ($res):
            $item=Doctrine_Core::getTable('EiSubjectMessage')->fetchBranchMsg($ei_subject_message->getId(), sfConfig::get("app_bug_description_message"));
            $this->success = true;
            $partialParam = $this->urlParameters;
            $partialParam['ei_subject_message'] =$item[0];
            $this->html = $this->getPartial('eisubjectmessage/item', $partialParam);
        else:
            $this->html = "Error : unable to save message";
        endif;
        /* Tout s'est bien passé */
        return $this->renderText(json_encode(array(
                    'html' => $this->html,
                    'success' => $this->success)));
        return sfView::NONE;
    }

    public function executeNew(sfWebRequest $request) {
        $this->guardUser = $this->getUser()->getGuardUser();
        $this->parent_id = $request->getParameter('parent_id');
        $this->type = $request->getParameter('type');
        $this->checkProject($request);
        $this->checkSubject($request, $this->ei_project);
        $subjectMessage = new EiSubjectMessage();
        $subjectMessage->setGuardId($this->guardUser->getId());
        $subjectMessage->setSubjectId($this->ei_subject->getId());
        $subjectMessage->setMessageTypeId(1);
        $subjectMessage->setPosition(1);
        $subjectMessage->setType($this->type);
        $this->form = new EiSubjectMessageForm($subjectMessage);
        $this->form->setDefault('parent_id', $this->parent_id);
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eisubjectmessage/form', array(
                        'form' => $this->form,
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'subject_id' => $this->subject_id,
                        'parent_id' => $this->parent_id,
                        'type' => $this->type)),
                    'success' => true)));
        return sfView::NONE;
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->guardUser = $this->getUser()->getGuardUser();
        $this->checkProject($request);
        $this->checkSubject($request, $this->ei_project);

        $this->parent_id = $request->getParameter('parent_id');
        $this->type = $request->getParameter('type');

        $subjectMessage = new EiSubjectMessage();
        $subjectMessage->setGuardId($this->guardUser->getId());
        $subjectMessage->setSubjectId($this->ei_subject->getId());
        $subjectMessage->setMessageTypeId(1);
        $subjectMessage->setPosition(1);
        $subjectMessage->setType($this->type);
        $this->form = new EiSubjectMessageForm($subjectMessage);
        $this->form->setDefault('parent_id', $this->parent_id);
        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($ei_subject_message = Doctrine_Core::getTable('EiSubjectMessage')->find(array($request->getParameter('guard_id'),
            $request->getParameter('subject_id'))), sprintf('Object ei_subject_message does not exist (%s).', $request->getParameter('guard_id'), $request->getParameter('subject_id')));
        $this->form = new EiSubjectMessageForm($ei_subject_message);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($ei_subject_message = Doctrine_Core::getTable('EiSubjectMessage')->find(array($request->getParameter('guard_id'),
            $request->getParameter('subject_id'))), sprintf('Object ei_subject_message does not exist (%s).', $request->getParameter('guard_id'), $request->getParameter('subject_id')));
        $this->form = new EiSubjectMessageForm($ei_subject_message);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($ei_subject_message = Doctrine_Core::getTable('EiSubjectMessage')->find(array($request->getParameter('guard_id'),
            $request->getParameter('subject_id'))), sprintf('Object ei_subject_message does not exist (%s).', $request->getParameter('guard_id'), $request->getParameter('subject_id')));
        $ei_subject_message->delete();

        $this->redirect('eisubjectmessage/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $ei_subject_message = $form->save();

            $this->redirect('eisubjectmessage/edit?guard_id=' . $ei_subject_message->getGuardId() . '&subject_id=' . $ei_subject_message->getSubjectId());
        }
    }

}
