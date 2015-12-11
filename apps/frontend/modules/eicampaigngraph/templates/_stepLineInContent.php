<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>
<?php if (isset($ei_campaign_graph) ): ?>
<?php $is_automatizable=$ei_campaign_graph->getEiCampaignGraphType()->getAutomate();
      $ei_campaign_graph_id=$ei_campaign_graph->getId();
      $ei_scenario=$ei_campaign_graph->getEiScenario(); 
    ?>
<!-- Ligne d'une step de campagne dans le contenu d'une campagne -->
<div class="stepLineInContentBox">
<div class="stepLineInContent bordered">
    <fieldset class="hiddenFields"> 
        <input type="hidden" class="node_id" value="<?php echo $ei_campaign_graph_id ?>" />
        <span class="stepLineInContentBoxIndex"><?php echo $ei_campaign_graph_id ?></span>
    </fieldset>

        <div class="row ">
            
            <div class='col-lg-4 col-md-4'>  
                <h6 class="entete_fonction">
                    <!--<a class="openFunctionInScript" href="#" target="_blank">-->
                        <!--<input class="openNodeUri" type="hidden" itemref="#"/>--> 
                        <?php if(!$is_automatizable): ?>
                    <?php $downloadCampaignNodeAttachment=$url_tab;
                    $downloadCampaignNodeAttachment['campaign_id']=$ei_campaign_graph->getCampaignId();
                    $downloadCampaignNodeAttachment['id']=$ei_campaign_graph_id;?>
                    <?php if($ei_campaign_graph->getFilename()!=null):$dtText=$ei_campaign_graph->getFilename(); else : $dtText=''; ;endif; ?>
                                         
                        <a class="tooltipObjTitle <?php echo 'manualStepFile'.$ei_campaign_graph_id ?>" href="<?php echo(($ei_campaign_graph->getFilename()!=null)? 
                                url_for2('downloadCampaignNodeAttachment', $downloadCampaignNodeAttachment): '#') ?>"
                                  data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $dtText ?>">
                                <?php echo ei_icon('ei_function') ?>
                                <input class="step_file_name" type="hidden" value="<?php echo $dtText ?>"/>
                                <?php  echo MyFunction::troncatedText($dtText,17) ;?>
                            </a> &nbsp;&nbsp;  &nbsp;  
                            <?php $campaign_graph_edit=$url_tab; 
                            $campaign_graph_edit['id']=$ei_campaign_graph_id;?>
                        <a title="Edit" class="editCampaignStep btn btn-default btn-xs" 
                           href="<?php echo url_for2('campaign_graph_edit',$campaign_graph_edit)  ?>  " >  
                            <?php echo ei_icon('ei_search') ?> 
                        </a>  
                        <?php else: ?> 
                        <?php if($ei_scenario!=null):
                            $projet_new_eiversion=$url_tab; 
                            $projet_new_eiversion['ei_scenario_id']=$ei_scenario->getId(); 
                            $projet_new_eiversion['action']='editVersionWithoutId'; ?>
                            
                            <a class="tooltipObjTitle" href="<?php echo url_for2('projet_new_eiversion', $projet_new_eiversion) ?>"
                               data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $ei_scenario ?>">
                               <input type="hidden" class="scenarioInSteps" value ="<?php echo $ei_scenario->getId()?>"/>
                                <?php echo ei_icon('ei_scenario') ?>
                               <?php echo MyFunction::troncatedText(htmlentities($ei_scenario),17); ?>
                               <input class="step_scenario_id" type="hidden" value="<?php echo $ei_scenario->getId() ?>"/>
                            </a>     
                            <?php else: ?>
                                 <?php echo ei_icon('ei_scenario') ?> No scenario
                        <?php endif;   ?> 
                        <?php endif; ?>
                      </a> 

                </h6>
                </div>
                <div class='col-lg-5 col-md-5 stepLineInContentDataSet'>
                    <?php if($is_automatizable): ?> 
                    <?php $dt=$ei_campaign_graph->getEiDataSet() ?>   
                    <div class="btn-group">
                        <?php
                        /** @var EiDataSet $dt */
                        if( !is_null($dt) && $ei_scenario!=null && $dt->getName()!=""):
                            $eidataset_edit=$url_tab; 
                            $eidataset_edit['ei_scenario_id']=$ei_scenario->getId(); 
                            $eidataset_edit['action']='editVersionWithoutId';
//                            $eidataset_edit['ei_data_set_id']=$dt->getId();
                        ?>
                            <a class="tooltipObjTitle btn btn-link btn-xs stepLineInContentDataSetTitle" target='_blank' data-parent="<?php echo $ei_scenario->getId() ?>"
                               href="<?php echo url_for2('projet_new_eiversion', $eidataset_edit) ?>" data-id="<?php echo $dt->getEiDataSetTemplate()->getId() ?>"
                               data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $dt->getEiDataSetTemplate()->getName() ?>">
                               <input class="step_jdd_id" type="hidden" value="<?php echo $dt->getId() ?>"/>
                                 <?php echo ei_icon('ei_dataset','lg') ?>
                                <?php echo MyFunction::troncatedText($dt->getEiDataSetTemplate()->getName(),17); ?>
                            </a>  
                         <?php else: ?> 
                        <button class="btn btn-sm btn-link stepLineInContentDataSetTitle" type="button">
                             <?php echo ei_icon('ei_dataset','lg') ?>
                        </button>
                         <?php endif; ?>
                        
                        <?php if($ei_scenario!=null && $ei_scenario->getId()!=null ): 
                            $getScenarioDataSets=$url_tab; 
                            $getScenarioDataSets['ei_scenario_id']=$ei_scenario->getId();   ?>
                        <a class="btn  btn-default  btn-sm editDataSetStepBox"    href="<?php 
                            echo url_for2('getScenarioDataSets', $getScenarioDataSets)?>">
                            <?php echo ei_icon('ei_search') ?>
                        </a>
                         
                        <?php endif; ?> 
                    </div> 
                    <?php endif; ?> 
                </div>
            <div class="col-lg-3 col-md-3 no-padding">
                <div class="btn-group pull-right"> 
                    <a class="btn btn-default  stepLineInContentBoxMoinsInfo" alt="Hide parameters" title="Hide parameters" href="#">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="btn btn-default stepLineInContentBoxPlusInfo" alt="Show parameters all" title="Show parameters all" href="#">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                        <?php 
                        $campaign_graph_delete=$url_tab; 
                                $campaign_graph_delete['id']=$ei_campaign_graph_id;
                                $campaign_graph_delete['redirect']=false;
                        ?>
                    <a class="btn btn-danger deleteCampaignStepWithoutRedirect "  title = 'Delete step ?'
                       href="<?php echo url_for2('campaign_graph_delete',  $campaign_graph_delete) ?>">
                        <?php echo ei_icon('ei_delete') ?>
                    </a> 
                </div>
            </div>
             
        </div> 
        <div class="row stepLineInContentDesc">
            <?php 
                    $majStepDesc=$url_tab; 
                            $majStepDesc['id']=$ei_campaign_graph_id; 
                    ?>
            <input class ="stepLineInContentDescHref" type="hidden" 
                   itemref="<?php echo url_for2('majStepDesc',$majStepDesc) ?>"/>
                <div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 '> 
                    <textarea class='col-lg-12 col-md-12 col-sm-12 col-xs-12 stepLineInContentDescContent'>
                        <?php echo $ei_campaign_graph->getDescription() ?>
                    </textarea> 
                </div>
                <div class='col-lg-2 col-md-2 alert stepLineInContentDescNotif'>     
                </div> 
        </div>
</div>
    <div class="checked_place_to_add_in_camp_content " id="<?php echo($is_lighter ? 'lighter_in_camp_content' : '' ) ?>">
            <a class="add_step_link" href="#"> 
                <input class="lighter_in_camp_content_value" type='hidden' value="<?php echo $ei_campaign_graph_id ?>" />
            </a>
    </div>
</div>
<?php endif; ?>