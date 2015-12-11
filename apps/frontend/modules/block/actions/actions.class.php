<?php

/**
 * block actions.
 *
 * @package    kalifastRobot
 * @subpackage block
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class blockActions extends sfActionsKalifast
{
 public function preExecute() {
        parent::preExecute();

        // Récupération de l'utilisateur.
        $this->guard_user = $this->getUser()->getGuardUser();
        $this->ei_user = $this->guard_user->getEiUser();
    }

    /**
     * @param sfWebRequest $request
     * @return string
     *
     * @deprecated
     * TODO: A SUPPRIMER
     */
    public function executeNew(sfWebRequest $request)
    {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->form = new EiBlockForm();

        $this->forward404Unless($ei_scenario_id = $request->getParameter('ei_scenario_id'), "ei_scenario_id missing");

        $ei_block_parent = Doctrine_Core::getTable('EiBlock')->find($request->getParameter('ei_block_parent_id'));

        $this->forward404Unless($ei_block_parent, "Block not found.");

        $this->ei_block_parent_id = $ei_block_parent->getId();

        $this->ei_scenario_id = $ei_block_parent->getEiScenarioId();

        $this->forward404Unless($this->ei_scenario_id == $request->getParameter('ei_scenario_id'), "The ei_scenario_id retrieve from database
        is not the one provided in client's request.");


        if ($request->getParameter('insert_after'))
            $this->insert_after = $request->getParameter('insert_after');

        $this->setLayout(false);

        if( $request->isXmlHttpRequest() ){

            $html = $this->getPartial("form");

            $return = $this->renderText(json_encode(array(
                'html' =>  $html,
                'success' => true )));
        }
        else{
            $return = $this->renderPartial('form');
        }

        return $return;
    }

    /**
     * Effectue l'action de création d'un block.
     * 
     * @param sfWebRequest $request
     * @return type
     *
     * @deprecated
     * TODO: A SUPPRIMER
     */
    public function executeCreate(sfWebRequest $request) 
    {
        $this->forward404Unless($request->isMethod(sfRequest::POST) && $request->isXmlHttpRequest());         
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->forward404Unless($ei_scenario_id = $request->getParameter('ei_scenario_id'));
        $this->forward404Unless($this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($ei_scenario_id));
        $this->forward404Unless($ei_version_id = $request->getParameter('ei_version_id')); 
        $this->insert_after = $request->getParameter('insert_after');

        $this->ei_block_parent_id = $request->getParameter('ei_block_parent_id');

        // Tables utilisées.
        /** @var EiVersionStructureTable $tableVersionStr */
        $tableVersionStr = Doctrine_Core::getTable("EiVersionStructure");

        $this->ei_block = new EiBlock();

        //si insert after est précisé, alors ei_block_parent devient en fait
        //le block "précédent".
        if ($this->insert_after) {
            $this->ei_block_parent = $tableVersionStr->findBlock($this->insert_after);

            $this->ei_block->setInsertAfter($this->insert_after);
        }
        else {
            $this->ei_block_parent = $tableVersionStr->findBlock($this->ei_block_parent_id);
        }

        $this->forward404Unless($this->ei_block_parent);
         

        $this->ei_block->setEiScenario($this->ei_scenario);
        $this->ei_block->setEiVersionStructureParentId($this->ei_block_parent_id);

        $this->form = new EiBlockForm($this->ei_block);


        if (!($JSONResponse = $this->processForm($request, $this->form))) {

            $this->renderPartial('block');

            $JSONResponse = $this->createJSONResponse('created', 'ok');
            $JSONResponse['content'] = $this->getResponse()->getContent();

            if ($this->insert_after) {
                $JSONResponse['insert_after'] = $this->insert_after;
            } else {
                $JSONResponse['insert_after'] = 0;
                $JSONResponse['insert_first'] = $this->ei_block_parent_id;
            }
            $createLiElemParams=$this->urlParameters;
            $createLiElemParams['ei_scenario_id']=$this->ei_scenario->getId();
            $createLiElemParams['ei_version_id']=$ei_version_id;
            $JSONResponse['item_tree'] = $this->ei_block->createLiElem($createLiElemParams,'eiscenario'); 

            $this->getResponse()->setContent("");
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * Méthode permettant d'ajouter un bloc complet (nom, description + paramètres) et de l'ajouter
     * potentiellement à une position déterminée.
     *
     * @param sfWebRequest $request
     */
    public function executeAjouter(sfWebRequest $request)
    {
        // Méthode uniquement pour les requêtes AJAX. Si la condition n'est pas respectée, on lève une erreur 404.
        $this->forward404Unless($request->isXmlHttpRequest());

        if( $request->getMethod() === "POST" )
        {
            return $this->forward("block", "createForVersion");
        }
        else
        {
            /** @var EiVersionStructure $ei_version_block_parent */
            $ei_version_block_parent = Doctrine_Core::getTable("EiVersionStructure")->find($request->getParameter("ei_version_structure_id"));
            $type = $request->getParameter("type");

            // Création de l'instance de Bloc selon le type sélectionné.
            if( $type == null ){
                /** @var EiBlock $block */
                $block = new EiBlock();

                // On détermine les données déjà en notre possession.
                $block->setEiVersionStructureParentId($ei_version_block_parent->getId());

                // Création du formulaire.
                $this->form = new EiBlockForm($block);
            }
            elseif( $type == EiVersionStructure::$TYPE_FOREACH ){
                /** @var EiBlockForeach $block */
                $block = new EiBlockForeach();

                // On détermine les données déjà en notre possession.
                $block->setEiVersionStructureParentId($ei_version_block_parent->getId());

                // Création du formulaire.
                $this->form = new EiBlockForeachForm($block);
            }

            $this->success = true;

            $this->html = $this->getPartial('block/formFull',array('form' => $this->form));

            return $this->renderText(
                json_encode(array(
                    'html' => $this->html,
                    'success' => $this->success
                ))
            );
        }
    }

    /**
     * Méthode permettant de créer un block avec ses paramètres, de l'insérer au bon endroit dans la structure et
     * de l'afficher dans la structure.
     *
     * @param sfWebRequest $request
     */
    public function executeCreateForVersion(sfWebRequest $request)
    {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        // Tables utilisées.
        /** @var EiVersionStructureTable $tableVersionStr */
        $tableVersionStr = Doctrine_Core::getTable("EiVersionStructure");

        // Initialisation des variables de retour.
        $html = "";
        $success = false; 
        $type = $request->getPostParameter("typeBlock");

        // Récupération des informations principales.
        //=> ID de l'élément de la structure de la version précédant le block.
        $this->insertAfter = $request->getParameter('insert_after');
        //=> ID du block parent au sein de la structure de la version.
        $this->ei_block_parent_id = $request->getParameter('ei_version_structure_id');
        //=> ID de la version du scénario.
        $this->ei_version_id = $request->getParameter("ei_version_id");

        // Récupération des objets à partir des informations principales.
        /** @var EiVersion $ei_version */
        $ei_version = Doctrine_Core::getTable("EiVersion")->find($this->ei_version_id);
        /** @var EiVersionStructure $ei_version_block_parent */
        $ei_version_block_parent = $tableVersionStr->find($this->ei_block_parent_id);
        /** @var EiVersionStructure $ei_block_parent */
        $ei_block_parent = $ei_version_block_parent;
        /** @var EiVersionStructure $ei_version_structure_precedent */
        $ei_version_structure_precedent = $tableVersionStr->find($this->insertAfter);
        $ei_version_structure_precedent = !is_bool($ei_version_structure_precedent) ? $ei_version_structure_precedent:null;

        $this->forward404Unless($ei_version);
        $this->forward404Unless($ei_block_parent);

        // Création de l'instance de Bloc selon le type sélectionné.
        if( $type == null ){
            /** @var EiBlock $ei_block */
            $ei_block = new EiBlock();

            // On détermine les données déjà en notre possession.
            $ei_block->setEiVersionStructureParentId($ei_version_block_parent->getId());

            // Création du formulaire.
            $this->form = new EiBlockForm($ei_block);
        }
        elseif( $type == EiVersionStructure::$TYPE_FOREACH ){
            /** @var EiBlockForeach $ei_block */
            $ei_block = new EiBlockForeach();

            // On détermine les données déjà en notre possession.
            $ei_block->setEiVersionStructureParentId($ei_version_block_parent->getId());

            // Création du formulaire.
            $this->form = new EiBlockForeachForm($ei_block);
        }
        else{
            $ei_block = new EiBlock();

            // On détermine les données déjà en notre possession.
            $ei_block->setEiVersionStructureParentId($ei_version_block_parent->getId());

            // Création du formulaire.
            $this->form = new EiBlockForm($ei_block);
        }

        $ei_block->setEiVersionStructureParentId($ei_block_parent);
        $ei_block->setRootId($ei_block_parent->getRootId());
        $ei_block->setInsertAfter($ei_version_structure_precedent != null ? $ei_version_structure_precedent->getId():null);
        $ei_block->setEiVersionId($ei_block_parent->getEiVersionId());

        $this->ei_block = $ei_block;
        $this->ei_block_parent = $ei_block_parent;
        $this->element_structure_precendent = $ei_version_structure_precedent;
        $this->ei_block_precedent = null;
        $this->ei_vs_precedent = $ei_version_structure_precedent;

        if( $ei_version_structure_precedent != null && !$ei_version_structure_precedent->isEiBlock() )
        {
            $node = $ei_version_structure_precedent;

            /** @var EiVersionStructure $node */
            while( ($node = $node->getNode()->getPrevSibling()) && $this->ei_block_precedent == null ){
                if( $node->isEiBlock() ){
                    $this->ei_block_precedent = $node;
                }
            }
        }
        else{
            $this->ei_block_precedent = $ei_version_structure_precedent;
        }

        // Création du formulaire.
        $this->isFull = true;

        //*****     VERIFICATION DU FORMULAIRE
        //=> Si OK, on prépare la réponse à retourner, à savoir le noeud à ajouter dans la structure de la version + dans
        // le menu contenant la structure du scénario.
        if (!($JSONResponse = $this->processForm($request, $this->form)))
        {
            // Récupération du block créé au sein de la structure.
            /** @var EiVersionStructure $blockVersionStruct */
            $blockVersionStruct = Doctrine_Core::getTable("EiVersionStructure")
                ->findOneByIdAndEiVersionId(
                    $this->ei_block->getId(),
                    $ei_version->getId()
                )
            ;

            // Récupération de la ligne à insérer dans la structure de la version avec le curseur.
            $html = $this->getPartial("eiversion/lineBlockWithCursor", array(
                'child' => $blockVersionStruct,
                'insert_after' => $blockVersionStruct->getId(),
                'paramsForUrl' => array(
                    'ei_version_id' => $ei_version->getId(),
                    'ei_version_structure_id' => $ei_version_block_parent->getId(),
                    'project_id' => $this->project_id,
                    'project_ref' => $this->project_ref,
                    'profile_id' => $this->profile_id,
                    'profile_ref' => $this->profile_ref,
                    'profile_name' => $this->profile_name,
                    'default_notice_lang' => $this->ei_project->getDefaultNoticeLang())
            ));

            // On vérifie où le noeud a été inséré.
            if($this->element_structure_precendent != null && $this->ei_block_precedent != null)
            {
                //$this->element_structure_precendent->getId();
                $insertAfter = $this->ei_block_precedent->getId();
                $insertFirst = false;
            }
            else
            {
                $insertAfter = false;
                $insertFirst = $this->ei_block->getEiVersionStructureParentId();
            }

            // On récupère le noeud à ajouter dans le menu de la structure.
            $createLiElemParams=$this->urlParameters;
            $createLiElemParams['ei_scenario_id']=$ei_version->getEiScenarioId();
            $createLiElemParams['ei_version_id']=$ei_version->getId();
            $itemTree= $this->ei_block->createLiElem($createLiElemParams,'eiversion');  

            // On indique que tout c'est bien passé.
            $success = true;
        }
        //=> SINON, on retourne le formulaire avec les erreurs.
        else{
            $html = $this->getPartial('block/formFull',array('form' => $this->form));
            $itemTree = null;
            $insertAfter = null;
            $insertFirst = null;
        }

        return $this->renderText(
            json_encode(array(
                'html' => $html,
                'success' => $success,
                'itemTree' => $itemTree,
                'insert_after' => $insertAfter,
                'insert_first' => $insertFirst,
                'type' => $type
            ))
        );
    }

    /**
     * Action permettant de mettre à jour un block complet (avec ses paramètres).
     *
     * @param sfWebRequest $request
     */
    public function executeUpdate(sfWebRequest $request)
    {
        $this->getContext()->getConfiguration()->loadHelpers(array('Url','I18N','Date', 'Tag','Number','Text') );

        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->forward404Unless($request->isMethod(sfRequest::PUT) || $request->isMethod(sfRequest::POST));

        // Initialisation des variables de retour.
        $success = false;
        $itemTree = null;

        // Récupération des paramètres
        //=> ID du block au sein de la structure de la version.
        $this->ei_block_id = $request->getParameter('ei_version_structure_id');
        //=> ID de la version du scénario.
        $this->ei_version_id = $request->getParameter("ei_version_id");

        // Récupération des objets à partir des informations principales.
        /** @var EiVersion $ei_version */
        $ei_version = Doctrine_Core::getTable("EiVersion")->find($this->ei_version_id);
        /** @var EiBlock $ei_version_block */
        $ei_version_block = Doctrine_Core::getTable("EiVersionStructure")->findBlock($this->ei_block_id);

        $this->forward404Unless($ei_version);
        $this->forward404Unless($ei_version_block);

        $this->ei_block = $ei_version_block;
        $blockParams = $this->ei_block->getParams();

        $formClass = "EiBlockForm";
        $extra = array();

        /** @var EiBlockForeach $ei_version_block */
        if( $ei_version_block instanceof EiBlockForeach ){
            $formClass = "EiBlockForeachForm";
            $extra["mapping"] = $ei_version_block->getIteratorMapping();
        }

        // Création du formulaire.
        $this->form = new $formClass($this->ei_block, array_merge(array(
            "size" => count($blockParams),
            "elements" => $blockParams
        ), $extra));
        $this->isFull = true;

        //*****     VERIFICATION DU FORMULAIRE
        //=> Si OK, on prépare la réponse à retourner, à savoir le noeud à ajouter dans la structure de la version + dans
        // le menu contenant la structure du scénario.
        if (!($JSONResponse = $this->processForm($request, $this->form))) {
            $blockParams = $this->ei_block->getParamsWithQuery();

            $this->form = new $formClass($this->ei_block, array(
                "size" => count($blockParams),
                "elements" => $blockParams
            ));

            // On récupère le noeud à ajouter dans le menu de la structure.
            $createLiElemParams=$this->urlParameters;
            $createLiElemParams['ei_scenario_id']=$ei_version->getEiScenarioId();
            $createLiElemParams['ei_version_id']=$ei_version->getId();
            $itemTree= $this->ei_block->createLiElem($createLiElemParams,'eiversion',true); 

            // On indique que tout c'est bien passé.
            $success = true;

            $message = "Block ".$this->ei_block->getName()." has been updated successfully.";
        }
        else{
            $message = $JSONResponse["message"];
        }

        $html = $this->getComponent('block', 'showParams', array(
            'form' => $this->form,
            'project_id' => $request->getParameter("project_id"),
            'project_ref' => $request->getParameter("project_ref"),
            'profile_name' => $request->getParameter("profile_name"),
            'profile_id' => $request->getParameter("profile_id"),
            'profile_ref' => $request->getParameter("profile_ref"),
            'ei_version_structure_id' => $request->getParameter("ei_version_structure_id"),
            'ei_version_id' => $request->getParameter("ei_version_id"),
            'ei_scenario_id' => $ei_version->getEiScenarioId()
        ));

        return $this->renderText(
            json_encode(array(
                'id' => $this->ei_block_id,
                'html' => $html,
                'success' => $success,
                'itemTree' => $itemTree,
                'message' => $message
            ))
        );
    }

    /**
     * Retourne tout le formulaire d'édition d'un block
     * 
     * @param sfWebRequest $request
     * @return type
     */
    public function executeEdit(sfWebRequest $request)
    {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiBlock($request);       
        
        if( $this->ei_block instanceof EiBlockForeach ){
            $this->ei_block_root_form = new EiBlockForeachForm($this->ei_block);
        }
        else{
            $this->ei_block_root_form = new EiBlockForm($this->ei_block);
        }

        $this->ei_block_parameters = Doctrine_Core::getTable('EiBlockParam')->findByEiVersionStructureParentId($this->ei_block->getId());

        if ($this->ei_block_parameters) {
            $aux = array();
            foreach ($this->ei_block_parameters as $ei_block_param) {
                $aux[] = new EiBlockParamForm($ei_block_param);
            }
            $this->ei_block_parameters = $aux;
        }

        $JSONResponse['path'] = $this->ei_block->getPathTo();


        $this->ei_block_children = Doctrine_Core::getTable('EiBlock')
                ->getEiBlockChildrenAccordingToParent($this->ei_block);

        $this->renderPartial('edit');

        $JSONResponse['content'] = $this->getResponse()->getContent();

        $this->getResponse()->setContent("");

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @return string
     *
     * @deprecated
     *
     * TODO: A SUPPRIMER
     */
    public function executeMove(sfWebRequest $request) {
        $this->checkEiBlock($request);

        $this->insert_after = $request->getParameter('insert_after');
           

        try {

            if ($this->insert_after) {
                
                $ei_block = Doctrine_Core::getTable('EiBlock')->find($this->insert_after);
                
                $this->ei_block->getNode()->moveAsNextSiblingOf($ei_block);
                
                $blocks_referer =  Doctrine_Core::getTable('EiVersionStructure')
                        ->getEiVersionStructuresToMove($this->insert_after);
                
            } else {
                
                $ei_block = $this->ei_block->getNode()->getParent();
                
                $this->ei_block->getNode()->moveAsFirstChildOf($ei_block);
                
            }

            $blocks = Doctrine_Core::getTable('EiVersionStructure')
                        ->getEiVersionStructuresToMove($this->ei_block->getId());
      
            foreach ($blocks as $i => $version_str) {
                if ($this->insert_after)
                    $version_str->getNode()->moveAsNextSiblingOf($blocks_referer->get($i));
                else
                    $version_str->getNode()->moveAsFirstChildOf($version_str->getNode()->getParent());
            }

            $JSONResponse = $this->createJSONResponse('moved', 'ok');
        } catch (Exception $e) {
            $JSONResponse['status'] = "error";
            $JSONResponse['message'] = $e->getMessage();
        }

        $this->getResponse()->setContentType('application/json');

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * Supprime un block
     * 
     * @param sfWebRequest $request
     * @return type
     */
    public function executeDelete(sfWebRequest $request) {
        $this->checkEiBlock($request);

        if ($this->ei_block->getNode()->isRoot()) {

            $JSONResponse['status'] = "error";

            $JSONResponse['message'] = "You can not delete root block.";
        } else {

            /** @var EiBlock $ei_block */
            $ei_block = $this->ei_block;

            $ei_block->getNode()->delete();

            $this->getResponse()->setContentType('application/json');

            $JSONResponse = $this->createJSONResponse('deleted', 'ok');
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * Format les réponses JSON de succès.
     * 
     * @param type $action
     * @param type $status
     * @return string
     */
    private function createJSONResponse($action, $status) {
        $JSONResponse['status'] = $status;
        $JSONResponse['message'] = "Block " . $this->ei_block->getName() . " has been $action successfully.";

        return $JSONResponse;
    }

    /**
     * Vérifie si le block existe et si l'utilisateur a les droits desssus.
     * 
     * @param sfWebRequest $request
     */
    protected function checkEiBlock(sfWebRequest $request) {
        $this->ei_block = Doctrine_Core::getTable('EiVersionStructure')->findBlock($request->getParameter('ei_block_id'));
        
        $this->forward404Unless($this->ei_block); 
        $this->ei_version=$this->ei_block->getEiVersion();
        $this->forward404Unless($this->ei_version); 
        $this->ei_scenario=$this->ei_version->getEiScenario();
        $this->forward404Unless($this->ei_scenario); 
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $this->getResponse()->setContentType('application/json');

        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        if ($form->isValid())
        {
            $isNew = $this->ei_block->isNew();

            $this->ei_block = $form->save();


            if ($isNew && !$this->isFull)
            {
                if ($this->insert_after)
                    $this->ei_block->getNode()->insertAsNextSiblingOf($this->ei_block_parent);
                else
                    $this->ei_block->getNode()->insertAsFirstChildOf($this->ei_block_parent);
            }
            elseif( $isNew && $this->isFull && $this->ei_vs_precedent != null )
            {
                $this->ei_block->getNode()->insertAsNextSiblingOf($this->ei_vs_precedent);
            }
            elseif( $isNew && $this->isFull && $this->ei_block_parent != null && $this->ei_vs_precedent == null )
            {
                $this->ei_block->getNode()->insertAsFirstChildOf($this->ei_block_parent);
            }

            if( $isNew && $this->isFull && array_key_exists("EiBlockParams", $form->getEmbeddedForms()) && !$this->ei_block->isEiLoop() ){
                $forms = $form->getEmbeddedForm("EiBlockParams")->getEmbeddedForms();
                $prev = null;

                /** @var EiBlockParamForm $form */
                foreach( $forms as $form ){

                    if( $prev == null ){
                        $form->getObject()->getNode()->insertAsLastChildOf($this->ei_block);
                    }
                    else{
                        $form->getObject()->getNode()->insertAsNextSiblingOf($prev);
                    }

                    $prev = $form->getObject();
                }
            }
            elseif( $isNew && $this->isFull && $this->ei_block->isEiLoop() ){
                $this->ei_block->createAutoMapping();
            }
            elseif( !$isNew && $this->isFull && array_key_exists("EiBlockParams", $form->getEmbeddedForms()) ){
                $forms = $form->getEmbeddedForm("EiBlockParams")->getEmbeddedForms();
                $prev = null;
                $nouvelleCollection = array();

                /** @var EiBlockParamForm $form */
                foreach( $forms as $form ){

                    $nouvelleCollection[] = $form->getObject()->getId();

                    if( $prev == null && $form->getObject()->getLft() == null ){
                        $form->getObject()->getNode()->insertAsLastChildOf($this->ei_block);
                    }
                    elseif( $form->getObject()->getLft() == null ){
                        $form->getObject()->getNode()->insertAsNextSiblingOf($prev);
                    }

                    $prev = $form->getObject();
                }

                foreach( $this->ei_block->getEiVersionStructures() as $key => $structureElt ){

                    if( $structureElt instanceof EiBlockParam && !in_array($structureElt->getId(), $nouvelleCollection) ){
                        $this->ei_block->getEiVersionStructures()->remove($key);
                    }
                }

                $this->ei_block->save();
            }


            return false;
        }
        else {
            $JSONResponse['status'] = "error";
            $errors  = $form->getErrorSchema()->getErrors();

            if( isset($errors["name"]) ){
                $nomErreur = $errors["name"];
            }
            else{
                $nomErreur = "";
            }

            $JSONResponse['message'] = "Unable to save the block. ". $nomErreur;
        }

        return $JSONResponse;
    }

}
