<!-- Edition du contenu d'une campagne (Page principale ) -->
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
<!-- 
Objets attendus : 
- Arbre des scénarios 
- Eventuellement le sujet ou la livraison de provenance
    - Si ce cas d'espèce se produit , on tombe par défaut sujet ou de la livraison
      et on ramène par défaut les campagnes du sujet ou de la livraison
- Si mode édition , les steps de la campagne à éditer
- 
--> 
<?php include_partial('eicampaigngraph/editContentAlerts') ?>
<?php include_component('eicampaigngraph','sideBarHeaderObject'); ?>
<div class="row">
    <!-- Contenu des steps de campagne -->
    <div class="col-lg-7 col-md-7 no-padding-left">
        <?php $campaignContent=$url_tab;
        $campaignContent['ei_campaign_graphs']=$ei_campaign_graphs;
        $campaignContent['ei_campaign']=$ei_campaign; ?>

        <div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2> <?php echo ei_icon('ei_campaign') ?>&nbsp;Campaign Content</h2>
                <div class="panel-actions"></div>
            </div>
            <div class="panel-body table-responsive">
                <?php  include_partial('eicampaigngraph/campaignContent',$campaignContent) ?>
            </div>
        </div>
    </div>

    <!-- Menu de droite -->
    <div class="col-lg-5 col-md-5" id="editCampaignContentMenu">
        <div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2> <?php echo ei_icon('ei_scenario') ?>&nbsp;Scenarios Selection</h2>
                <div class="panel-actions"></div>
            </div>
            <div class="panel-body table-responsive">
                <div class="tabbable"> <!-- Only required for left/right tabs -->
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <!-- Menu principal pour le choix entre la recherche dans les scénarios ou dans les campagnes -->
                        <?php include_partial('eicampaigngraph/editContentMainMenu',array(
                                'activeItem' => 'Campaigns'
                            )) ?>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-9">
                         <div class="tab-content">
                        <div class="tab-pane active" id="tabStepCampaigns">
                        <!-- Search box pour rechercher une campagne , livraison ou sujet-->
                        <?php $stepSearchGlobalBox=$url_tab; ?>
                        <?php if(isset($ei_delivery) && $ei_delivery!=null):
                                $stepSearchGlobalBox['ei_delivery']=$ei_delivery;
                             else: $stepSearchGlobalBox['ei_delivery']=null;
                            endif;?>
                         <?php if(isset($ei_subject) && $ei_subject!=null):
                                $stepSearchGlobalBox['ei_subject']=$ei_subject;
                             else: $stepSearchGlobalBox['ei_subject']=null;
                            endif;
                                $stepSearchGlobalBox['ei_campaign']=$ei_campaign; ?>
                        <?php include_partial('eicampaigngraph/stepSearchGlobalBox',$stepSearchGlobalBox) ?>
                        <!-- Contenu des campagnes par type d'objet (campagnes indépendantes , campagnes de livraison ou campagnes de sujet -->

                            <?php if(isset($ei_delivery) && $ei_delivery!=null): ?>
                            <?php $rightSideDeliveryBloc=$url_tab;
                                   $rightSideDeliveryBloc['ei_delivery']=$ei_delivery;
                                   $rightSideDeliveryBloc['ei_campaigns']=$ei_delivery_campaigns;
                                   $rightSideDeliveryBloc['ei_current_campaign']=$ei_campaign;
                                   $rightSideDeliveryBloc['ei_subjects']=$ei_delivery_subjects;
                                   include_partial('eicampaigngraph/rightSideDeliveryBloc',$rightSideDeliveryBloc)?>

                            <?php endif; ?>
                            <?php if(isset($ei_subject) && $ei_subject!=null): ?>
                            <?php $rightSideSubjectBloc=$url_tab;
                                   $rightSideSubjectBloc['ei_subject']=$ei_subject_with_relation;
                                   $rightSideSubjectBloc['ei_campaigns']=$ei_subject_campaigns;
                                   $rightSideSubjectBloc['ei_current_campaign']=$ei_campaign;    ?>
                            <div  id="rightSideCampaignsBloc">
                                <?php include_partial('eicampaigngraph/rightSideSubjectBloc',$rightSideSubjectBloc) ?>
                            </div>
                            <?php endif; ?>
                                    <!-- Si rien n'est spécifié , on ramène les campagnes du projet dans la limite de 10 (les dernières créées) -->
                                    <?php if(isset($lonelyCampaigns) && count($lonelyCampaigns)>0): ?>
                                    <?php $rightSideLonelyCampaignsBloc=$url_tab;
                                        $rightSideLonelyCampaignsBloc['ei_campaigns']=$lonelyCampaigns;
                                        $rightSideLonelyCampaignsBloc['ei_current_campaign']=$ei_campaign;
                                        include_partial('eicampaigngraph/rightSideLonelyCampaignsBloc',$rightSideLonelyCampaignsBloc) ?>
                                    <?php endif ; ?>
                                </div>

                                <div class="tab-pane" id="tabStepScenarios">
                                    <?php $getRootDiagram=$url_tab;
                                        $getRootDiagram['root_node']=$root_node;
                                        $getRootDiagram['opened_ei_nodes']=$opened_ei_nodes;
                                        $getRootDiagram['is_step_context']=1;
                                        include_partial('einode/getRootDiagram',$getRootDiagram) ?>
                                </div>
                                <div class="tab-pane" id="tabStepFunctions">
                                    <?php include_partial('eicampaigngraph/rightSideFunctionBloc',$url_tab) ?>
                                </div>
                            </div>
                        <!-- Action générales pour les steps de campagnes trouvés (Ajout d'une sélection ou ajout d'une action manuelle)  -->
                        <?php include_partial('eicampaigngraph/editContentGlobalActions',$url_tab) ?>
                    </div>
                </div>
        </div>
    </div> 
</div> 

<div id="campaignStep" class="modal " tabindex="-1" role="dialog"
aria-labelledby="newCampaignStepLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"> 
                 <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="newCampaignStepLabel">Add Step</h4> 
            </div>
            <div class="modal-body campaignStepBody"></div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"
                        id="closeCampaignStepBox">Close</button>
                <button class="btn btn-sm btn-success pull-right" id="saveCampaignStep"
                type="submit"> <i class="icon icon-ok-circle"></i> Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Box d'edition des flags -->
<div id="setCommentForCampaignBox" class="modal ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="setCommentForCampaignBoxTitle">Set flag</h4> 
                <input type="hidden" id="setCommentForCampaignBoxLink" itemref="" />
            </div>
            <div class="modal-body" id="setCommentForCampaignBoxContent"> 
                    <textarea class=" form-control"></textarea> 
            </div>
            <div class="modal-footer"> 
                <a href="#" id="saveCommentForCampaign" class="btn btn-sm btn-success"
                data-dismiss="modal" aria-hidden="true">  Save 
                </a>
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal"
                aria-hidden="true" id="closeSetCommentForCampaignBox">  Close  </a> 
            </div>
        </div>
    </div>
</div> 

<!-- Box de recherche d'une fonction -->
 
<div id="functionSearchBoxForSteps" class="modal ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"> 
                 <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="functionSearchBoxForStepsTitle">
                    <?php echo ei_icon('ei_function') ?> Choose Function</h4>  
                <input type="hidden" id="functionSearchBoxForStepsLink" itemref="#" />
            </div>
            <div class="modal-body" id="functionSearchBoxForStepsContent">
                <?php $menu=$url_tab; 
                        $menu['ei_project']=$ei_project; 
                        $menu['showFunctionContent']=false;
                        $menu['is_function_context']=false;
                        $menu['is_step_context']=true;   
                        include_partial('global/menu', $menu); ?> 
            </div>
            <div class="modal-footer"> 
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"
                   id="closeFunctionSearchBoxForSteps">
                    Close 
                </a>
            </div>
        </div>
    </div>
</div>