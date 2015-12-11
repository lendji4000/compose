<?php

/**
 * einode actions.
 *
 * @package    kalifastRobot
 * @subpackage einode
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class einodeActions extends sfActionsKalifast {


    public function preExecute() {
        parent::preExecute();

        // Récupération de l'utilisateur.
        $this->guard_user = $this->getUser()->getGuardUser();
        $this->ei_user = $this->guard_user->getEiUser();
    }
    // TODO: Améliorer en externalisant checkEiScenario dans sfActionsKalifast.
    //Recherche d'un scénario avec les paramètres de requête
    public function checkEiScenario(sfWebRequest $request,EiProjet $ei_project) {
        if (($this->ei_scenario_id = $request->getParameter('ei_scenario_id')) != null ) {
            //Recherche du scénario en base
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')
                ->findOneByIdAndProjectIdAndProjectRef(
                    $this->ei_scenario_id,$ei_project->getProjectId(),$ei_project->getRefId());
            //Si le scénario n'existe pas , alors on retourne un erreur 404
            if ($this->ei_scenario == null){
                $message = 'Scénario  introuvable!! l identificateur n\'est pas spécifié';
                $request->setParameter('msg', $message);
                $request->setParameter('back_link', $request->getReferer());
                $this->forward('erreur', 'error404');
            }
        }

        else {
            $this->forward404('Missing scenario parameters  ...');
        }
    }

    public function executeIndex(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->ei_nodes = Doctrine_Core::getTable('EiNode')
                ->createQuery('a')
                ->execute();
    }
                    
    
    protected function getUrlParameters(sfWebRequest $request) {
            $this->ei_scenario_id = $request->getParameter('ei_scenario_id');  
             //Si on se trouve dans l'édition des steps d'une campagne 
            $this->is_step_context=$request->getParameter('is_step_context');
            $this->is_edit_step_case=$request->getParameter('is_edit_step_case');
            $this->current_step_id=$request->getParameter('current_step_id');
            
            $this->urlParameters['ei_scenario_id'] = $this->ei_scenario_id;
    }

    public function createJSONResponse(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->getUrlParameters($request);

        $this->parent_id = $request->getParameter('parent_id');

        $folder = Doctrine_Core::getTable('EiNode')->findOneByIdAndType($this->parent_id, 'EiDataSetFolder');

        $JSONResponse['status'] = "error";

        //le dossier parent existe
        if ($folder) {

            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($request->getParameter('ei_scenario_id'));
            //le scenario existe
            if ($this->ei_scenario) {

                $project = Doctrine_Core::getTable('EiProjectUser')->getEiProjet(
                        $this->ei_scenario->getProjectId(), $this->ei_scenario->getProjectRef(), $this->getUser()->getGuardUser()->getEiUser());

                //le projet existe pour l'utilisateur
                if ($project) {

                    $this->urlParameters['parent_id'] = $folder->getId();
                    $this->urlParameters['ei_scenario_id'] = $this->ei_scenario->getId();

                    $this->form = new EiDataSetFolderForm(null, array('ei_node_parent' => $folder));

                    $JSONResponse['status'] = "ok";

                    $this->renderPartial("form");

                    $JSONResponse['content'] = $this->getResponse()->getContent();

                    $this->getResponse()->setContent("");
                } else {

                    $JSONResponse['message'] = "You can not access to this project.";
                }
            } else {

                $JSONResponse['message'] = "Scenario not found.";
            }
        } else {

            $JSONResponse['message'] = "Parent node " . $this->parent_id . " not found.";
        }

        return $JSONResponse;
    }

    public function executeNewEiDataSetFolder(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        return $this->renderText(json_encode($this->createJSONResponse($request)));
    }

    /**
     * Créer un nouveau dossier.
     * @param sfWebRequest $request
     * @return type
     */
    public function executeCreateEiDataSetFolder(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);

        $JSONResponse = $this->createJSONResponse($request);

        if ($this->processForm($request, $this->form)) {

            $JSONResponse['status'] = "ok";

            $this->renderPartial("form");

            $JSONResponse['content'] = $this->getResponse()->getContent();
            $nodeParams=$this->urlParameters;
            $nodeParams['ei_node']=$this->ei_node; 
            $JSONResponse['dataSetNodeLine'] = $this->getPartial('eidataset/dataSetNodeLine',$nodeParams); 
            $JSONResponse['parent_id'] = $this->ei_node->getRootId();
            $JSONResponse['is_create_mode'] = true;
            $JSONResponse['message'] = "Folder created successfully.";
            $JSONResponse = $this->getUpdatedSidebar($JSONResponse);

            $this->getResponse()->setContent("");
        } else {
            $JSONResponse['status'] = "error";

            $this->renderPartial("form");

            $JSONResponse['content'] = $this->getResponse()->getContent();

            $JSONResponse['message'] = "The folder could not be saved. Check out form's errors.";

            $this->getResponse()->setContent("");
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * Edite le dossier.
     * @param sfWebRequest $request
     * @return type
     */
    public function executeEditEiDataSetFolder(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $JSONResponse = $this->createJSONResponse($request);

        $node = Doctrine_Core::getTable('EiNode')->findOneByIdAndType($request->getParameter('ei_node_id'), 'EiDataSetFolder');

        if ($node) {
            $this->form = new EiDataSetFolderForm($node);

            $JSONResponse['status'] = "ok";

            $this->renderPartial("form");

            $JSONResponse['content'] = $this->getResponse()->getContent();

            $this->getResponse()->setContent("");
        } else {
            $JSONResponse['status'] = 'error';
            $JSONResponse['message'] = 'The node you tried to edit does not exist.';
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    public function executeUpdateEiDataSetFolder(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);
        $JSONResponse = $this->createJSONResponse($request);

        $node = Doctrine_Core::getTable('EiNode')->findOneByIdAndType($request->getParameter('ei_node_id'), 'EiDataSetFolder');

        if ($node) {
            $this->form = new EiDataSetFolderForm($node);

            if ($this->processForm($request, $this->form)) {

                $JSONResponse['status'] = "ok";

                $this->renderPartial("form");

                $JSONResponse['content'] = $this->getResponse()->getContent();

                $JSONResponse['message'] = "Folder updated successfully.";

                $JSONResponse = $this->getUpdatedSidebar($JSONResponse);

                $this->getResponse()->setContent("");
            } else {
                $JSONResponse['status'] = "error";

                $this->renderPartial("form");

                $JSONResponse['content'] = $this->getResponse()->getContent();

                $JSONResponse['message'] = "The folder could not be saved. Check out form's errors.";

                $this->getResponse()->setContent("");
            }
        } else {
            $JSONResponse['status'] = 'error';
            $JSONResponse['message'] = 'The node you tried to edit does not exist.';
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    public function executeChangeNodeParent(sfWebRequest $request) {
        $this->forward404If(!$request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        
        $this->current_node_id = intval($request->getParameter('current_node_id'));
        $this->new_parent_id = intval($request->getParameter('new_parent_id'));
        $this->forward404If(!$this->new_parent_id || !$this->current_node_id);
        $findCurrentNode = Doctrine_Core::getTable('EiNode')->findOneBy('id', $this->current_node_id);
        $parent_id = $findCurrentNode->getRootId();
        $result = Doctrine_Core::getTable('EiNode')->ChangeNodeParent($this->current_node_id, $this->new_parent_id);
        if ($result == null):
            $this->textResult='Object can\'t be found with theses parameters ';
            $this->success=false;
        endif;
        if (!($result instanceof EiProjet) && $result == -10):
            $this->textResult='This node is Root . You can\'t change his parent.Try again ...';
            $this->success=false;
        endif;
        if (!($result instanceof EiProjet) && $result == -9):
            $this->textResult='Error : Object can\'t not be his own parent. Try again ...';
            $this->success=false;
        endif;
        if (!($result instanceof EiProjet) && $result == -8):
            $this->textResult='Error : New parent is a child of current object.Try again ...';
            $this->success=false;
        endif; 
        if (!($result instanceof EiProjet) && $result == -7):
            $this->textResult='Error : New parent is a descendant of current object.Try again ...';
            $this->success=false;
        endif; 
        if(!$this->textResult) :
            $this->textResult='The scenario or folder has been moved successfully ...' ;
            $this->success=true;
            //maj des positions
            $conn = Doctrine_Manager::connection();
            /* Pour le noeud parent, on trouve les id des fils */
            $stmt2 = $conn->prepare("SELECT id FROM ei_node WHERE root_id = :root_id ORDER BY position");    
            $stmt2->bindValue("root_id", $parent_id);
            $stmt2->execute(array());
            $result2 = $stmt2->fetchAll();

            $position = 0;
            foreach($result2 as $child_id) {
                /* update de la position du fils */
                $id = $child_id['id'];
                $position++;
                $stmt3 = $conn->prepare('UPDATE ei_node SET position = :position where id = :id');
                $stmt3->bindValue("position",$position);
                $stmt3->bindValue("id", $id);
                $stmt3->execute(array());
            }
        endif;  
        //Retour du résultat
        return  
            $this->renderText(json_encode(array(
                    'html' => $this->textResult,
                    'success' => $this->success)));
        
        return sfView::NONE;
    }
    
    public function executeReoderElts(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        //On echange les positions de deux éléments après un drag & drop
        $this->forward404If(!$request->isXmlHttpRequest());
        $this->node_id = $request->getParameter('node_id');
        $this->newPosition = $request->getParameter('new_position');

        $this->forward404If($this->node_id == null || $this->newPosition == null, "Paramètre id node ou position manquant: " . $this->node_id . '-' . $this->newPosition);

        $this->node = Doctrine_Core::getTable('EiNode')->find($this->node_id);

        Doctrine_Core::getTable('EiNode')->ReoderElts(
                $this->node, $this->newPosition);

        return sfView::NONE;
    }

    public function executeUpdatePosition(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->forward404If(!$request->isXmlHttpRequest());
        $ids = $request->getParameter('ids');
        if ($ids) {
            $tab = explode(',', $ids);
            if (is_array($tab)) {
                Doctrine_Core::getTable('EiNode')->updatePosition($tab);
            }
        }
        return sfView::NONE;
    }

    public function executeRenameNode(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->node_id = intval($request->getParameter('node_id'));
        $this->nodeType = $request->getParameter('nodeType');
        $this->new_node_name = $request->getParameter('new_node_name');
        $this->forward404If(!$this->node_id || !$this->nodeType || !$this->new_node_name);
        $node = Doctrine_Core::getTable('EiNode')->findOneByIdAndType($this->node_id, $this->nodeType);
        if ($node == null)
            $this->forward404('Object not found');
        $node->rename($this->new_node_name);

        $this->renderText($node->getName());
        return sfView::NONE;
    }

    /**
     * Renvoi les noeuds enfants du noeud courant
     * @param sfWebRequest $request
     * @return type
     */
    public function executeSendDiagram(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $this->checkProject($request);
            $this->checkProfile($request, $this->ei_project);
            $this->getUrlParameters($request); 
            $this->ei_node = Doctrine_Core::getTable('EiNode')
                    ->findOneByIdAndProjectIdAndProjectRefAndType(
                    $request->getParameter('ei_node_id'), $this->project_id, $this->project_ref, $request->getParameter('ei_node_type'));
                    
            // Permet de savoir si l'on souhaite sélectionner un jeu de données (fonctionnalité "PLAY" dans scénario).
            $this->urlParameters['choose_dataset'] = $request->getParameter('choose_dataset');
            $this->is_select_data_set = $this->urlParameters['choose_dataset'] !== null;
            $this->urlParameters['is_edit_step_case']=  $this->is_edit_step_case;
            $this->urlParameters['current_step_id']=  $this->current_step_id;
            $this->urlParameters['is_select_data_set']=  $request->getParameter('is_select_data_set');
            $this->urlParameters['is_step_context']=$this->is_step_context;
            if ($this->ei_node) { 
                $this->urlParameters['ei_node_id'] = $this->ei_node->getId();
                
                $JSONResponse['status'] = "ok";
                
                switch ($request->getParameter('ei_node_type')){
                    case 'EiDataSetFolder': 
                        $this->ei_data_set_children = Doctrine_Core::getTable('EiNode')
                            ->findByRootId($this->ei_node->getId());
                        $this->urlParameters['ei_data_set_children']=$this->ei_data_set_children;
                        $this->ei_root_folder = $this->ei_node;
                        $this->urlParameters['ei_root_folder']=$this->ei_node;
                        $this->renderPartial('eidataset/tree');
                        
                        break;
                    default:  
                        $this->renderPartial('einode/nodeDiagram');
                        break;
                }
                
                $JSONResponse['content'] = $this->getResponse()->getContent();

                $this->getResponse()->setContent("");
                
                $this->ei_node->openNode($this->getUser()->getGuardUser()->getEiUser());
                
            } else {
                
                $JSONResponse['message'] = "EiNode not found for project " .  $this->project_id . '-'. $this->project_ref.".";
                $JSONResponse['status'] = "error";
            }
        } else {
            
            $JSONResponse['message'] = "Illegal request.";
            $JSONResponse['status'] = "error";
            
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    public function executeCloseNode(sfWebRequest $request){
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->success=false;
        $this->html='Ei_node_id parameter missing.';
        if ($request->isXmlHttpRequest()) { 
          //test des paramètres.
            if ($request->getParameter('ei_node_id'))  {
                //fermeture du noeud
                Doctrine_Core::getTable('EiNodeOpenedBy')
                    ->closeNode($request->getParameter('ei_node_id'), $this->getUser()->getGuardUser()->getEiUser());
                    
                $this->success=true;
                $this->html='Success ... ';
            } 
            return $this->renderText(json_encode(array(
                    'html' => 'Ei_node_id parameter missing.' ,
                    'success' => false))); 
        }
      return sfView::NONE;
    }
    
    public function executeSendDiagramForCheck(sfWebRequest $request) {         
        if ($request->isXmlHttpRequest()) { 
            $this->checkProject($request);
            $this->checkProfile($request, $this->ei_project);
            if (!$request->getParameter('obj_id') ||
                    !$request->getParameter('node_type') ) {
                return $this->renderText('Error !! missing parameter');
            } else {
                //Récupération du noeud
                $this->ei_node = Doctrine_Core::getTable('EiNode')->findOneByObjIdAndProjectIdAndProjectRefAndType(
                        $request->getParameter('obj_id'), $this->project_id
                        , $this->project_ref, $request->getParameter('node_type'));
                //Récupération du noeud courant 
                $this->current_node=Doctrine_Core::getTable('EiNode')->findOneByIdAndProjectIdAndProjectRef(
                        $request->getParameter('current_node_id'), $this->project_id , $this->project_ref);
                //Récupération du profil courant

                $this->ei_profile = Doctrine_Core::getTable('EiProfil')
                        ->findOneByProfileIdAndProfileRef($this->profile_id, $this->profile_ref);
                if ($this->ei_profile != null) : 
                    $nodeDiagramForChecking=$this->urlParameters;
                    $nodeDiagramForChecking['ei_node']=$this->ei_node; 
                    $nodeDiagramForChecking['current_node']=$this->current_node; 
                    return $this->renderPartial('einode/nodeDiagramForChecking',$nodeDiagramForChecking);
                endif;
            }
        } 
        return sfView::NONE;
    }

    public function executeNew(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->form = new EiNodeForm();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->form = new EiNodeForm();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->forward404Unless($ei_node = Doctrine_Core::getTable('EiNode')->find(array($request->getParameter('id'))), sprintf('Object ei_node does not exist (%s).', $request->getParameter('id')));
        $ei_node->delete();

        $this->redirect('einode/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $this->ei_node = $form->save();
            $this->getUser()->setFlash('alert_node_form', array('title' => 'Success ',
                    'class' => 'alert-success',
                    'text' => 'Well done ...'));
            return true;
        }
        $this->getUser()->setFlash('alert_node_form', array('title' => 'Error ',
                    'class' => 'alert-danger',
                    'text' => 'Error occur when saving node ...'));
        return false;
    }

    /**
     * Méthode permettant de retourner la nouvelle sidebar.
     */
    private function getUpdatedSidebar($JSONResponse)
    {
        $this->urlParameters['ei_scenario_id'] = $this->ei_scenario->getId();
        $node = $this->ei_scenario->getEiNode();
        $ei_data_set_root_folder = Doctrine_Core::getTable('EiNode')->findOneByRootIdAndType($node->getId(), 'EiDataSetFolder');
        $ei_data_set_children = Doctrine_Core::getTable('EiNode')->findByRootId($ei_data_set_root_folder->getId());

        $JSONResponse["sidebar"] = $this->getPartial("eidataset/root", array(
            'urlParameters' => $this->urlParameters,
            'ei_scenario' => $this->ei_scenario,
            'ei_data_set_root_folder'=> $ei_data_set_root_folder,
            'ei_data_set_children' => $ei_data_set_children,
            'is_edit_step_case' => false,
            'is_select_data_set' => 1,
            'fullDisplay' => false
        ));

        return $JSONResponse;
    }

}
