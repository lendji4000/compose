<?php

/**
 *
 * @author Lenine DJOUATSA
 */
class eicampaigngraphComponents extends sfComponentsKalifast {

    public function executeGraphLine(sfWebRequest $request) {
        $graph_node_id = $request->getParameter('graph_node_id ');
        $this->graph_node = Doctrine_Core::getTable('EiCampaignGraph')->findOneById($graph_node_id);
        $this->graph_node_children = Doctrine_Core::getTable('EiCampaignGraph')->getGraphNodeChildren($this->graph_node);
    }

    //Recherche d'une campagne avec les paramètres de requête
    public function checkEiCampaign(sfWebRequest $request, EiProjet $ei_project) {
        if (($this->campaign_id = $request->getParameter('campaign_id')) != null) {
            //Recherche de la campagne en base
            $this->ei_campaign = Doctrine_Core::getTable('EiCampaign')->findOneByIdAndProjectIdAndProjectRef(
                    $this->campaign_id, $ei_project->getProjectId(), $ei_project->getRefId());
        }
        else
            $this->ei_campaign = null;
    }

    //Recherche d'une éventuelle livraison associée à la campagne
    public function checkCampaignDelivery(sfWebRequest $request, EiProjet $ei_project, EiCampaign $ei_campaign = null) {
        $this->delivery_id = $request->getParameter('delivery_id');
        if ($this->delivery_id == null) :
            $this->ei_delivery = null;
        else:
            //Recherche de la livraison tout en s'assurant qu'elle corresponde au projet courant 
            $this->ei_delivery = Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
                    $this->delivery_id, $ei_project->getProjectId(), $ei_project->getRefId());
            if ($this->ei_delivery != null && $ei_campaign != null) :
                //On vérifie l'association entre la campagne et la livraison
                $link = Doctrine_Core::getTable('EiDeliveryHasCampaign')->findOneByDeliveryIdAndCampaignId(
                        $this->delivery_id, $ei_campaign->getId());
                if ($link == null):
                    throw new Exception('This campaign is not associate to the delivery ...');
                endif;
            endif;
        endif;
    }

    //Recherche d'un éventuel sujet associée à la campagne
    public function checkCampaignSubject(sfWebRequest $request, EiProjet $ei_project, EiCampaign $ei_campaign = null) {
        $this->subject_id = $request->getParameter('subject_id');
        if ($this->subject_id == null) :
            $this->ei_subject = null;
        else:
            //Recherche du sujet tout en s'assurant qu'elle corresponde au projet courant 
            $this->ei_subject = Doctrine_Core::getTable('EiSubject')->findOneByIdAndProjectIdAndProjectRef(
                    $this->subject_id, $ei_project->getProjectId(), $ei_project->getRefId());
            if ($this->ei_subject != null && $ei_campaign != null) :
                //On vérifie l'association entre la campagne et le sujet
                $link = Doctrine_Core::getTable('EiSubjectHasCampaign')->findOneBySubjectIdAndCampaignId(
                        $this->subject_id, $ei_campaign->getId());
                if ($link == null):
                    throw new Exception('This campaign is not associate to the intervention ...');
                endif;
            endif;
        endif;
    }

    public function executeSideBarCampaign(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiCampaign($request, $this->ei_project);
        $this->checkCampaignDelivery($request, $this->ei_project, $this->ei_campaign);
        $this->checkCampaignSubject($request, $this->ei_project, $this->ei_campaign);
    }

    /**
     * Composant permettant d'afficher le player button et le choix de l'action à effectuer en cas d'erreur.
     *
     * @param sfWebRequest $request
     */
    public function executePlayButton(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiCampaign($request, $this->ei_project);

        $this->campaignGraphBlockType = Doctrine_Core::getTable('EiBlockType')->findAll();
    }

    /* Composant permettant de retourner la barre de menu objet d'une version */

    public function executeSideBarHeaderObject(sfWebRequest $request)
    {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiCampaign($request, $this->ei_project);

        $this->mod = $request->getParameter('module');
        $this->act = $request->getParameter('action');
        $this->objMenu = array();

        switch ($this->mod):
            case 'eicampaigngraph':
                switch ($this->act):
                    case 'editContent':
                    case 'update':
                    case 'show':
                        $this->objTitle = "Edit Campaign Steps";
                        $this->logoTitle =  ei_icon('ei_campaign');
                        $this->objMenu[] = array(
                            'logo' => "<i class='fa fa-remove'></i>",
                            'title' => '',
                            'uri' => $this->generateUrl("graphHasChainedList", array(
                                "profile_id" => $this->profile_id,
                                "profile_ref" => $this->profile_ref,
                                "profile_name" => $this->profile_name,
                                "project_id" => $this->project_id,
                                "project_ref" => $this->project_ref,
                                "campaign_id" => $this->campaign_id
                            )),
                            'active' => ($this->mod == 'eiversion' && ($this->act == 'edit' || $this->act == 'update' || $this->act == 'show' )) ? true : false,
                            'class' => "",
                            'id' => "",
                            'tab' => '',
                            'titleAttr' => "Close edition and return to campaign execution"
                        );
                        break;
                        break;
                        break;
                    default :

                        break;
                endswitch;
                break;

            default :
                break;
        endswitch;
    }
}

?>
