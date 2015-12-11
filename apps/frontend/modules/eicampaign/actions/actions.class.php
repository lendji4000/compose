<?php

/**
 * eicampaign actions.
 *
 * @package    kalifastRobot
 * @subpackage eicampaign
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eicampaignActions extends sfActionsKalifast {
 
    //Recherche d'une éventuelle livraison associée à la campagne
    public function checkCampaignDelivery(sfWebRequest $request, EiProjet $ei_project,EiCampaign $ei_campaign=null) {
        $this->delivery_id = $request->getParameter('delivery_id');
        if ($this->delivery_id == null) :
            $this->ei_delivery=null;
            else:
            //Recherche de la livraison tout en s'assurant qu'elle corresponde au projet courant 
            $this->ei_delivery = Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
                $this->delivery_id, $ei_project->getProjectId(), $ei_project->getRefId());
                if ($this->ei_delivery != null && $ei_campaign!=null) :
                    //On vérifie l'association entre la campagne et la livraison
                    $link=Doctrine_Core::getTable('EiDeliveryHasCampaign')->findOneByDeliveryIdAndCampaignId(
                           $this->delivery_id,$ei_campaign->getId() );
                if($link==null):
                    throw new Exception ('This campaign is not associate to the delivery ...');
                endif;
                endif;
        endif; 
    }
    //Recherche d'un éventuel sujet associée à la campagne
    public function checkCampaignSubject(sfWebRequest $request, EiProjet $ei_project,EiCampaign $ei_campaign=null) {
        $this->subject_id = $request->getParameter('subject_id');
        if ($this->subject_id == null) :
            $this->ei_subject=null;
            else:
            //Recherche du sujet tout en s'assurant qu'elle corresponde au projet courant 
            $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneByIdAndProjectIdAndProjectRef(
                $this->subject_id, $ei_project->getProjectId(), $ei_project->getRefId());
                if ($this->ei_subject != null && $ei_campaign!=null) :
                    //On vérifie l'association entre la campagne et le sujet
                    $link=Doctrine_Core::getTable('EiSubjectHasCampaign')->findOneBySubjectIdAndCampaignId(
                           $this->subject_id,$ei_campaign->getId() );
                if($link==null):
                    throw new Exception ('This campaign is not associate to the intervention ...');
                endif;
                endif;
        endif; 
    }
    /* Initialisation du formulaire de recherche des campagnes de tests */

    public function initializeCampaignSearchForm(EiProjet $ei_project) {
        //Récupération des statuts de livraison pour un projet 
        $this->campaignAuthors = Doctrine_Core::getTable('EiCampaign')->getCampaignAuthorsForProject($ei_project);
        $this->projectDeliveries = Doctrine_Core::getTable('EiDelivery')->getProjectDeliveriesForSearchBox($ei_project);
        $this->campaignSearchForm = new campaignSearchForm(array(), array('projectDeliveries' => $this->projectDeliveries));
    }

    /*  Récupération du menu de droite pour l'édition des steps de campagnes */

    public function getCampaignSearchCriteria(sfWebRequest $request, campaignSearchForm $campaignSearchForm) {
        $this->searchCampaignCriteria =
                array('title' => null,
                    'author' => null,
                    'delivery' => null);
        $criterias = $request->getParameter($campaignSearchForm->getName());
        if ($criterias):
            if (isset($criterias['title'])): $this->searchCampaignCriteria['title'] = $criterias['title'];
            endif;
            if (isset($criterias['author'])): $this->searchCampaignCriteria['author'] = $criterias['author'];
            endif;
            if (isset($criterias['delivery'])): $this->searchCampaignCriteria['delivery'] = $criterias['delivery'];
            endif;
        endif;
    }

    //Recherche rapide d'une campagne par son numero ou son nom
    public function executeSearchCampaignByIdOrName(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du projet
        $this->checkCampaignDelivery($request, $this->ei_project);
        $this->checkCampaignSubject($request, $this->ei_project);
        $this->nameOrId = $request->getParameter('nameOrId');
        $this->ei_campaign = Doctrine_Core::getTable('EiCampaign')->searchCampaignByIdOrName(
                $this->nameOrId, $this->project_id, $this->project_ref);
        if ($this->ei_campaign != null)
            $this->form = new EiCampaignForm($this->ei_campaign);
        else {
            $this->getUser()->setFlash('campaign_not_found', 'Campaign not found');
            $this->checkCampaign($request, $this->ei_project);
            $this->form = new EiCampaignForm($this->ei_campaign);
        }

        $this->setTemplate('edit');
    }

    //Listing des campagnes de tests d'un projet
    public function executeIndex(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du projet
        $this->checkCampaignDelivery($request, $this->ei_project);
        $this->checkCampaignSubject($request, $this->ei_project);
        
        //Initilisation du formulaire de recherche d'une livraison
        $this->initializeCampaignSearchForm($this->ei_project);
        //Récupération des critères de recherche
        $this->getCampaignSearchCriteria($request, $this->campaignSearchForm);
        //on remplit le formulaire de recherche avec les critères précedemment renseignés
        $this->campaignSearchForm->setDefault('title', $this->searchCampaignCriteria['title']);
        $this->campaignSearchForm->setDefault('author', $this->searchCampaignCriteria['author']);
        $this->campaignSearchForm->setDefault('delivery', $this->searchCampaignCriteria['delivery']);
        /* Controle de l'offset pour la détermination du nombre d'enregistrement à afficher */
        //Si l'offset est spécifié, alors il prime sur le nombre d'enregistrement par défaut à afficher.
        $this->offset = intval($request->getParameter('offset'));
        if ($this->offset != 30 && $this->offset != 50 && $this->offset != 100)
            $this->offset = 30;
        $this->max_campaign_per_page = sfConfig::get('app_max_campaign_per_page');
        if ($request->getParameter('offset')) {
            $this->max_campaign_per_page = $this->offset;
        } else {
            $this->offset = $this->max_campaign_per_page;
        }
        /*
         * Gestion de la pagination des résultats 
         */
        //Calcul du nombre de pages 
        $q = Doctrine_Core::getTable('EiCampaign')
                        ->sortCampaignByCriterias(Doctrine_Core::getTable('EiCampaign')
                                ->getProjectCampaignsList($this->project_id, $this->project_ref), $this->searchCampaignCriteria)->execute();
        $this->nbEnr = count($q);

        //La fonction ceil retourne l'arrondi d'ordre supérieur (0.5 => 1 , 0.3 => 1 , 0.7 =>1)
        $this->nb_pages = ceil($this->nbEnr / $this->max_campaign_per_page);

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
        $this->first_entry = ($this->current_page - 1) * $this->max_campaign_per_page;

        //À cette étape , on a tous les éléments pour éffectuer notre requête 
        //Récupération de la liste des objets
        $this->ei_campaigns = $this->ei_project->getPaginateCampaignsList(
                        $this->first_entry, $this->max_campaign_per_page, $this->searchCampaignCriteria)
                ->execute();
        if ($request->isXmlHttpRequest())
            $this->setTemplate('searchBoxForSteps');
    }

    //Listing des campagnes de tests d'un projet
    public function executeSearch(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du projet
        $this->checkCampaignDelivery($request, $this->ei_project);
        $this->checkCampaignSubject($request, $this->ei_project);
        //Initilisation du formulaire de recherche d'une livraison
        $this->initializeCampaignSearchForm($this->ei_project);
        //Récupération des critères de recherche
        $this->getCampaignSearchCriteria($request, $this->campaignSearchForm);
        //on remplit le formulaire de recherche avec les critères précedemment renseignés
        $this->campaignSearchForm->setDefault('title', $this->searchCampaignCriteria['title']);
        $this->campaignSearchForm->setDefault('author', $this->searchCampaignCriteria['author']);
        $this->campaignSearchForm->setDefault('delivery', $this->searchCampaignCriteria['delivery']);
        /* Controle de l'offset pour la détermination du nombre d'enregistrement à afficher */
        //Si l'offset est spécifié, alors il prime sur le nombre d'enregistrement par défaut à afficher.
        $this->max_campaign_per_page = sfConfig::get('app_max_campaign_per_page');
        if ($request->getParameter('offset')) {
            $this->max_campaign_per_page = intval($request->getParameter('offset'));
        }
        /*
         * Gestion de la pagination des résultats 
         */
        //Calcul du nombre de pages 
        $q = Doctrine_Core::getTable('EiCampaign')
                        ->sortCampaignByCriterias(Doctrine_Core::getTable('EiCampaign')
                                ->getProjectCampaigns($this->project_id, $this->project_ref), $this->searchCampaignCriteria)->execute();
        $this->nbEnr = count($q);

        //La fonction ceil retourne l'arrondi d'ordre supérieur (0.5 => 1 , 0.3 => 1 , 0.7 =>1)
        $this->nb_pages = ceil($this->nbEnr / $this->max_campaign_per_page);

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
        $this->first_entry = ($this->current_page - 1) * $this->max_campaign_per_page;

        //À cette étape , on a tous les éléments pour éffectuer notre requête 
        //Récupération de la liste des objets
        $this->ei_campaigns = $this->ei_project->getPaginateCampaigns(
                        $this->first_entry, $this->max_campaign_per_page, $this->searchCampaignCriteria)
                ->execute();
        if ($request->isXmlHttpRequest())
            $this->setTemplate('searchBoxForSteps');
    }

    public function executeNew(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du projet 
        $campaign = new EiCampaign();
        $campaign->setProjectId($this->ei_project->getProjectId());
        $campaign->setProjectRef($this->ei_project->getRefId());
        $campaign->setAuthorId($this->getUser()->getGuardUser()->getId());
        $this->form = new EiCampaignForm($campaign);
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST)); 
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du projet
        $campaign = new EiCampaign();
        $campaign->setProjectId($this->ei_project->getProjectId());
        $campaign->setProjectRef($this->ei_project->getRefId());
        $campaign->setAuthorId($this->getUser()->getGuardUser()->getId());
        $this->form = new EiCampaignForm($campaign);
        $this->processForm($request, $this->form);
        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du projet
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne
        $this->checkCampaignDelivery($request, $this->ei_project,$this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project,$this->ei_campaign);
        $this->form = new EiCampaignForm($this->ei_campaign);
    }
    public function executeShow(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du projet
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne
        $this->checkCampaignDelivery($request, $this->ei_project,$this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project,$this->ei_campaign);
        //Recherche d'une éventuelle livraison associée à la campagne
        
        $this->form = new EiCampaignForm($this->ei_campaign);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du projet
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne
        $this->checkCampaignDelivery($request, $this->ei_project,$this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project,$this->ei_campaign);
        $this->form = new EiCampaignForm($this->ei_campaign);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du projet
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne
        $this->checkCampaignDelivery($request, $this->ei_project,$this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project,$this->ei_campaign); 
         $this->ei_campaign->delete();

        $this->redirect('eicampaign/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $ei_campaign = $form->save();
            //Construction de l'url de retour
            $campaign_edit=$this->urlParameters;
            $this->getUser()->setFlash('alert_campaign_form',
                    array('title' => 'Success' ,
                            'class' => 'alert-success' ,
                            'text' => 'Well done ...'));
            //On vérifie si la campagne appartient à une sujet/bug ou à une livraison , auquel cas on éffectue la redirection en consequence
            if($this->ei_delivery!=null) $campaign_edit['delivery_id']=$this->ei_delivery->getId();
            if($this->ei_subject!=null) $campaign_edit['subject_id']=$this->ei_subject->getId();
            $campaign_edit['campaign_id']=$ei_campaign->getId(); 
            $this->redirect($this->generateUrl('campaign_edit', $campaign_edit));
        }
        else{
            $this->getUser()->setFlash('alert_campaign_form', array('title' => 'Error',
                    'class' => 'alert-danger',
                    'text' => 'Error when trying to save campaign ...')); 
    }
    }

    /* Changement du block_type d'une step de campagne (stop, continue , etc ... ) */

    public function executeChangeBlocTypeId(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request,$this->ei_project); //Récupération du projet
        $this->checkCampaign($request, $this->ei_project); //Recherche de la campagne
        $this->block_type_id = $request->getParameter('block_type_id');

        //Si le block_type_id=0 alors on met null dans le block type Id
        if ($this->block_type_id == 0)
            $this->block_type_id = null;
        $result = $this->ei_campaign->setNewOnErrorValue($this->block_type_id);
        return $this->renderText(json_encode(array(
                    'html' => 'well done',
                    'success' => $result)));
        return sfView::NONE;
    }

}
