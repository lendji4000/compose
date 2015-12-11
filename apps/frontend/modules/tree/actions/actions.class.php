<?php

/**
 * tree actions.
 *
 * @package    kalifastRobot
 * @subpackage tree
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class treeActions extends sfActionsKalifast
{
    public function preExecute() {
        parent::preExecute();

        // Récupération de l'utilisateur.
        $this->guard_user = $this->getUser()->getGuardUser();
        $this->ei_user = $this->guard_user->getEiUser();
    }
   
  public function executeSendTree (sfWebRequest $request){
      $this->checkProject($request);
      $this->checkProfile($request, $this->ei_project);
      if ($request->isXmlHttpRequest()) {
          //test des paramètres.
            if (!$request->getParameter('ref_obj') || !$request->getParameter('obj_id') ||
                    !$request->getParameter('project_ref') || !$request->getParameter('project_id')||
                    !$request->getParameter('tree_type') || !$request->getParameter('root_id')) {
                return $this->renderText('Erreur !! paramètre manquant');
            } else {
                $this->showFunctionContent=$request->getParameter('showFunctionContent');
                $this->is_function_context=$request->getParameter('is_function_context');
                $this->is_step_context=$request->getParameter('is_step_context'); 
                //ouverture du noeud
                $this->ei_tree=Doctrine_Core::getTable('EiTree')->openNode(
                        $request->getParameter('ref_obj'),$request->getParameter('obj_id'),$request->getParameter('project_id')
                        ,$request->getParameter('project_ref'),$request->getParameter('tree_type'), $this->getUser()->getGuardUser()->getEiUser());
               
                
                $arboTree = $this->urlParameters;
                $arboTree['ei_tree']= $this->ei_tree; 
                $arboTree['tree_childs']=$this->ei_tree->getNodesWithChildsInf();
                $arboTree['showFunctionContent']=$this->showFunctionContent;
                $arboTree['is_function_context']=$this->is_function_context;
                $arboTree['is_step_context']=$this->is_step_context;
                return $this->renderPartial('tree/arboTree', $arboTree);
            }
        }
        return sfView::NONE;
  }
  
  /* Récupération des détails sur un noeud (De fonction ) */
  public function executeGetNodeDetails(sfWebRequest $request){
      $this->checkProject($request);
      $this->checkProfile($request, $this->ei_project);
      $tree_type=$request->getParameter('tree_type');
       $obj_id=$request->getParameter('obj_id');
        $ref_obj=$request->getParameter('ref_obj');
      if ($request->isXmlHttpRequest()) {
          //test des paramètres.
            if ($tree_type==null || $obj_id==null ||  $ref_obj==null ) {
                return $this->renderText('Error ! Missing parameters...');
            } else { 
                //ouverture du noeud
                $this->ei_tree = Doctrine_Core::getTable('EiTree')->findOneByRefObjAndObjIdAndProjectIdAndProjectRefAndType($ref_obj,$obj_id, $this->project_id, $this->project_ref, $tree_type); 
                $nodeDetails = $this->urlParameters;
                $nodeDetails['ei_tree']= $this->ei_tree;
                $nodeDetails['ei_project']= $this->ei_project;
                $nodeDetails['ei_profile']= $this->ei_profile;
                $nodeDetails['tree_childs']=$this->ei_tree->getNodesWithChildsInf(); 
                /* Récupération de la fonction ou vue suivant son type*/
                if($tree_type=="Function"):
                    $this->ei_function=Doctrine_Core::getTable("KalFunction")->findOneByFunctionIdAndFunctionRef($obj_id,$ref_obj);
                    $nodeDetails['ei_function']=$this->ei_function;
                    $partial= $this->getPartial('tree/functionDetails', $nodeDetails);
                    $header=$this->getPartial("tree/functionDetailsHead",$nodeDetails);
                endif;
                if($tree_type=="View"):
                    $this->ei_view=Doctrine_Core::getTable("EiView")->findOneByViewIdAndViewRef($obj_id,$ref_obj);
                    $nodeDetails['ei_view']=$this->ei_view;
                    $partial= $this->getPartial('tree/viewDetails', $nodeDetails);
                    $header=$this->getPartial("tree/viewDetailsHead",$nodeDetails);
                endif;  
                
                return $this->renderText(json_encode(array(
                    'html' => $partial,
                    'header' => $header,
                    'success' => true))); 
            }
        }
        return $this->renderText(json_encode(array(
                    'html' => "Error in process",
                    'success' => false))); 
        return sfView::NONE;
  }
  /*Fonction d'ouverture d'un noeud  (renvoi de l'arbre complet après l'opération) .
  Cette fonction permettra d'ouvrir une fonction sur script et d'indiquer son chemin 
   * dans l'arborescence sous compose
   */
  public function executeOpenNode(sfWebRequest $request){
      $this->checkProject($request);
      $this->checkProfile($request, $this->ei_project);
      // On vérfie si l'on est dans le contexte d'une version de scénario 
      if(($this->ei_version_id=$request->getParameter('ei_version_id'))!=null):
          $this->ei_version = Doctrine_Core::getTable("EiVersion")->findOneById($this->ei_version_id);
      endif;
      if ($request->isXmlHttpRequest()) { 
          //test des paramètres.
            if (!$request->getParameter('ref_obj') || !$request->getParameter('obj_id') || !$request->getParameter('tree_type') ) {
                return $this->renderText('Error ! Missing parameters...');
            } else {
                //ouverture du noeud
                $noeud = Doctrine_Core::getTable('EiTree')
                ->findOneByRefObjAndObjIdAndProjectIdAndProjectRefAndType($request->getParameter('ref_obj'),
                        $request->getParameter('obj_id'), $this->project_id, $this->project_ref, 
                        $request->getParameter('tree_type')); 
                $this->ei_tree=Doctrine_Core::getTable('EiTree')->RecursivelyOpenNode($noeud,$this->getUser()->getGuardUser()->getEiUser());
             }
        }
          $menu = $this->urlParameters;
        $menu['ei_project'] = $this->ei_project;
        $menu['ei_version'] = (isset($this->ei_version)? $this->ei_version : null);
        $menu['showFunctionContent'] = $request->getParameter('showFunctionContent');
        $menu['is_function_context'] = $request->getParameter('is_function_context');
        $menu['is_step_context'] = $request->getParameter('is_step_context');
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('global/menu', $menu),
                    'success' => true))); 
        return sfView::NONE;
         
   }
  
  public function executeCloseTree(sfWebRequest $request){
      $this->checkProject($request);
      $this->checkProfile($request, $this->ei_project);      
      if ($request->isXmlHttpRequest()) {
          //test des paramètres.
            if (!$request->getParameter('ei_tree_id')) {
                return $this->renderText('Ei_tree_id parameter missing.');
            } else {
                //ouverture du noeud
                Doctrine_Core::getTable('EiTreeOpenedBy')
                    ->closeNode($request->getParameter('ei_tree_id'), $this->getUser()->getGuardUser()->getEiUser());
                $this->ei_tree=Doctrine_Core::getTable('EiTree')->findOneByIdAndProjectIdAndProjectRef(
                        $request->getParameter('ei_tree_id'),$this->ei_project->getProjectId(),$this->ei_project->getRefId());
                $arboTree = $this->urlParameters;
                $arboTree['ei_tree']= $this->ei_tree;
                $arboTree['tree_childs']=$this->ei_tree->getNodesWithChildsInf();
                $arboTree['showFunctionContent']=$request->getParameter('showFunctionContent');
                $arboTree['is_function_context']=$request->getParameter('is_function_context');
                $arboTree['is_step_context']=$request->getParameter('is_step_context');
                return $this->renderPartial('tree/arboTree',$arboTree);
            }
        }
      return sfView::NONE;
  }
  
  //Récupération de la racine de l'arbre des fonctions.  Utilisé plus souvent lors des rechargements
  public function executeGetRootTree(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        if ($request->isXmlHttpRequest()) { 
            $this->reload = intval($request->getParameter('reload'));
            if($this->ei_version !== null )
                $this->class_action = "get_path_function";
            else
            {
                if($this->reload && $this->reload==1)
                    $this->class_action = "get_path_function";
                else
                    $this->class_action = "statistics";
            }
            /* On vérifie si le projet nécessite d'être recharger . Si oui on le recharge , sinon on continue */
            if($this->ei_project->needsReload()):
                $this->xmlfile = $this->ei_project->downloadKalFonctions();
                if ($this->xmlfile != null) :
                    //si le fichier xml obtenu n'est pas vide
                    $r = $this->ei_project->transactionToLoadObjectsOfProject($this->xmlfile);
                    $this->getUser()->setFlash('reload_success', 'Project updated successfuly.', true);
                else:
                    $this->getUser()->setFlash('reload_error', "An error occurred while trying to update the project.", true);
                endif;
            endif;


            $this->opened_ei_nodes = Doctrine_Core::getTable('EiTreeOpenedBy')->getOpenedNodes($this->ei_user, $this->ei_project->getProjectId(), $this->ei_project->getRefId());
                //Noeud racine de l'arbre
                $this->root_tree = Doctrine_Core::getTable('EiView')->getRootView($this->ei_project->getRefId(), $this->ei_project->getProjectId());
                //Noeuds fils du noeud racine 
                if($this->root_tree!=null):
                $this->tree_childs=$this->root_tree->getNodesWithChildsInf();
                endif;
                $this->impactContext=$request->getParameter('impactContext')?true:false; 
                $arbreProjet = $this->urlParameters;
                $arbreProjet['ei_project']= $this->ei_project; 
                $arbreProjet['root_tree']= $this->root_tree; 
                $arbreProjet['tree_childs']= $this->tree_childs; 
                $arbreProjet['reloadProjet']= false; 
                $arbreProjet['opened_ei_nodes']= $this->opened_ei_nodes; 
                $arbreProjet['class_action']= $this->class_action; 
                $arbreProjet['showFunctionContent']= $request->getParameter('showFunctionContent'); 
                $arbreProjet['is_function_context']= $request->getParameter('is_function_context'); 
                $arbreProjet['is_step_context']= $request->getParameter('is_step_context');  
                return $this->renderPartial('eiprojet/arbreProjet', $arbreProjet); 
        }
        return sfView::NONE;
    }
  
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EiTreeForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new EiTreeForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ei_tree = Doctrine_Core::getTable('EiTree')->find(array($request->getParameter('id'))), sprintf('Object ei_tree does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiTreeForm($ei_tree);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ei_tree = Doctrine_Core::getTable('EiTree')->find(array($request->getParameter('id'))), sprintf('Object ei_tree does not exist (%s).', $request->getParameter('id')));
    $this->form = new EiTreeForm($ei_tree);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ei_tree = Doctrine_Core::getTable('EiTree')->find(array($request->getParameter('id'))), sprintf('Object ei_tree does not exist (%s).', $request->getParameter('id')));
    $ei_tree->delete();

    $this->redirect('tree/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ei_tree = $form->save();

      $this->redirect('tree/edit?id='.$ei_tree->getId());
    }
  }
}
