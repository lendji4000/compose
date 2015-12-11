<?php

/**
 * eiflag actions.
 *
 * @package    kalifastRobot
 * @subpackage eiflag
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiflagActions extends sfActionsKalifast {

    /* Mise à jour du flag d'un objet pour une campagne courante */

    public function executeSetState(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest()); 
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkCampaign($request, $this->ei_project);
        $this->success = false;
        $this->obj_id = $request->getParameter('obj_id');
        $this->state = $request->getParameter('state'); 
        $this->flagType = $request->getParameter('flagType');
        $this->conn = Doctrine_Manager::connection();
        try {
            /* Si tous les paramètres ont été bien définis , on recherche l'objet en base */
            $this->conn->beginTransaction();
            if ($this->obj_id == null)
                throw new Exception('Object Id not found ...');
            if ($this->state == null)
                throw new Exception('State  not found ...');
            /* Scan du type de flag*/
            $this->obj=$this->findObjectByType($this->ei_project, $this->flagType, $this->obj_id);  
            //Vérification de l'existence de l'objet
            if ($this->obj == null)
                throw new Exception('Object not found with giving parameters ...');
            /* Recherche du flag par rapport au type d'objet en base */
            $this->flag=$this->findFlagByType($this->flagType,$this->campaign_id, $this->obj_id);
            /* On crèe ou met à jour le flag suivant qu'il existe ou non */    
            if ($this->flag != null)
                $this->updateState($this->flagType,$this->state, $this->conn);
            else  $this->createState($this->flagType,$this->campaign_id, $this->obj_id, $this->state, $this->conn);
            $this->success = true;
            $this->conn->commit();
            //Contruction des paramètres de sortie
            
            $flagLink=$this->urlParameters;
            $flagLink['obj_id']=$this->obj_id;
            $flagLink['state']=$this->flag->getState();
            $flagLink['flagType']=$this->flagType;
            
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eiflag/flagLink',$flagLink),
                        'success' => $this->success)));
        } catch (Exception $e) {
            $this->conn->rollback();
            return $this->renderText(json_encode(array(
                        'html' => $e->getMessage(),
                        'success' => $this->success)));
        }
        return sfView::NONE;
    }

    /* Mise à jour du flag d'un objet pour une campagne courante */

    public function executeSetComment(sfWebRequest $request) {
        $this->forward404unless($request->isXmlHttpRequest()); 
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkCampaign($request, $this->ei_project);
        $this->success = false;
        $this->obj_id = $request->getParameter('obj_id');
        $this->comment = $request->getParameter('comment');
        $this->flagType = $request->getParameter('flagType');
        $this->conn = Doctrine_Manager::connection();
        try {
            $this->conn->beginTransaction();

            /* Si tous les paramètres ont été bien définis , on recherche l'objet en base */

            if ($this->obj_id == null)
                throw new Exception('Object Id not found ...');
            /* Scan du type de flag et recherche de l'objet en base */
            $this->obj=$this->findObjectByType($this->ei_project, $this->flagType, $this->obj_id); 
            
            if ($this->obj == null)
                throw new Exception('Campaign not found with giving parameters ...');
            /* Recherche du flag par rapport au type d'objet en base */
            $this->flag=$this->findFlagByType($this->flagType,$this->campaign_id, $this->obj_id); 
                
            if ($this->flag != null)
                $this->updateComment($this->flagType,$this->comment, $this->conn);
            else
                $this->createComment($this->flagType,$this->campaign_id, $this->obj_id, $this->comment, $this->conn);
            $this->success = true;
            $this->conn->commit();

            //Contruction des paramètres de sortie
            $commentLink=$this->urlParameters;
            $commentLink['obj_id']=$this->obj_id;
            $commentLink['comment']=$this->flag->getDescription();
            $commentLink['flagType']='EiCampaign';
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eiflag/commentLink',$commentLink),
                        'success' => $this->success)));
        } catch (Exception $e) {
            $this->conn->rollback();
            return $this->renderText(json_encode(array(
                        'html' => $e->getMessage(),
                        'success' => $this->success)));
        }
        return sfView::NONE;
    }

    public function updateState($flagType,$state, Doctrine_Connection $conn = null) {
        if ($conn == null) $this->conn = Doctrine_Manager::connection();
        $this->flag->setState($state);
        $this->flag->save($conn);
    }

    public function createState($flagType,$campaign_id, $obj_id, $state, Doctrine_Connection $conn = null) {
        /* Création d'un flag et définition du "statut" par rapport au type d'objet (subject,delivery,campaign ...) */
            $this->flag=$this->createFlagByType($flagType, $obj_id); 
            $this->flag->setCampaignId($campaign_id); 
            $this->flag->setState($state);
            $this->flag->save($conn);
    }

    public function updateComment($flagType,$comment, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $this->conn = Doctrine_Manager::connection();
        $this->flag->setDescription($comment);
        $this->flag->save($conn);
    }

    public function createComment($flagType,$campaign_id, $obj_id, $comment, Doctrine_Connection $conn = null) {
        /* Création d'un flag et définition du commentaire par rapport au type d'objet (subject,delivery,campaign ...) */
        $this->flag=$this->createFlagByType($flagType, $obj_id);
        $this->flag->setCampaignId($campaign_id); 
        $this->flag->setDescription($comment);
        $this->flag->save($conn);
    }
    /* Création d'un flag par rapport au type de flag passé en paramètre */
    public function createFlagByType($flagType,$obj_id){
        if($obj_id==null) throw new Exception('Object Id is null ...');
        switch ($flagType):
                case ('EiCampaign') :  
                    $flag = new EiFlag();
                    $flag->setFlagCampaignId($obj_id);
                break;
                case ('EiDelivery') :  
                    $flag = new EiFlagDelivery();
                    $flag->setDeliveryId($obj_id);
                break;
                case ('EiSubject') :  
                    $flag = new EiFlagSubject();
                    $flag->setSubjectId($obj_id);
                break;
                default :
                    throw new Exception('Flag type is wrong...');
                break;
            endswitch; 
            return $flag;
    }
    
    /* Recherche d'un flag de campagne par rapport au type d'objet */
    public function findFlagByType($flagType,$campaign_id,$obj_id){
        if($campaign_id==null || $obj_id==null) throw new Exception('Campaign id or object id is null ...');
        switch ($flagType):
                case ('EiCampaign') : //Recherche de l'objet
                    $flag = Doctrine_Core::getTable('EiFlag')->findOneByCampaignIdAndFlagCampaignId(
                                                                            $campaign_id, $obj_id);
                break;
                case ('EiDelivery') : //Recherche de l'objet
                    $flag = Doctrine_Core::getTable('EiFlagDelivery')->findOneByCampaignIdAndDeliveryId(
                                                                            $campaign_id, $obj_id);
                break;
                case ('EiSubject') : //Recherche de l'objet
                    $flag = Doctrine_Core::getTable('EiFlagSubject')->findOneByCampaignIdAndSubjectId(
                                                                            $campaign_id, $obj_id);
                break;
                default :
                    throw new Exception('Flag type is wrong...');
                break;
            endswitch;
            return $flag;
    }
    /* Recherche d'un objet  par rapport au type (subject,delivery,campaign) */
    public function findObjectByType(EiProjet $ei_project,$flagType,$obj_id){
        if( $obj_id==null) throw new Exception('Object id  is null ...');
        switch ($flagType):
                case ('EiCampaign') : //Recherche de l'objet
                    $obj = Doctrine_Core::getTable('EiCampaign')->findOneByProjectIdAndProjectRefAndId(
                    $ei_project->getProjectId(), $ei_project->getRefId(), $obj_id);
                break;
                case ('EiDelivery') : //Recherche de l'objet
                    $obj = Doctrine_Core::getTable('EiDelivery')->findOneByProjectIdAndProjectRefAndId(
                    $ei_project->getProjectId(), $ei_project->getRefId(), $obj_id);
                break;
                case ('EiSubject') : //Recherche de l'objet
                    $obj = Doctrine_Core::getTable('EiSubject')->findOneByProjectIdAndProjectRefAndId(
                    $ei_project->getProjectId(), $ei_project->getRefId(), $obj_id);
                break;
                default :
                    throw new Exception('Flag type is wrong...');
                break;
            endswitch;
            return $obj;
    }
    
    public function executeIndex(sfWebRequest $request) { 
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new EiFlagForm();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->form = new EiFlagForm();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($ei_flag = Doctrine_Core::getTable('EiFlag')->find(array($request->getParameter('id'))), sprintf('Object ei_flag does not exist (%s).', $request->getParameter('id')));
        $this->form = new EiFlagForm($ei_flag);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($ei_flag = Doctrine_Core::getTable('EiFlag')->find(array($request->getParameter('id'))), sprintf('Object ei_flag does not exist (%s).', $request->getParameter('id')));
        $this->form = new EiFlagForm($ei_flag);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($ei_flag = Doctrine_Core::getTable('EiFlag')->find(array($request->getParameter('id'))), sprintf('Object ei_flag does not exist (%s).', $request->getParameter('id')));
        $ei_flag->delete();

        $this->redirect('eiflag/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $ei_flag = $form->save();

            $this->redirect('eiflag/edit?id=' . $ei_flag->getId());
        }
    }

}
