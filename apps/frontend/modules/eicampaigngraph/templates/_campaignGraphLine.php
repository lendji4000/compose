<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name );?>
<?php if (isset($ei_campaign_graph)): ?>
<?php $is_automatizable=$ei_campaign_graph->getEiCampaignGraphType()->getAutomate() ?>
<?php $execution_graphs = isset($campaignExecutionGraphs) && $campaignExecutionGraphs != null ? $campaignExecutionGraphs:null; ?>

<?php
    $trClass = "";

    if( $execution_graphs != null && isset($execution_graphs[$ei_campaign_graph->getId()]) ){
        $etatStep = $execution_graphs[$ei_campaign_graph->getId()]->getState();
        $executionGraphStep = $execution_graphs[$ei_campaign_graph->getId()];
        $first_execution_graphs = $execution_graphs[$campaignExecutionGraphsKeys[0]];
        $last_execution_graphs = $execution_graphs[$campaignExecutionGraphsKeys[$execution_graphs->count()-1]];
    }
    else{
        $etatStep = $ei_campaign_graph->getState();
        $executionGraphStep = null;
        $first_execution_graphs = null;
        $last_execution_graphs = null;
    }

    switch($etatStep){
        case "Ok":
            $trClass = "success";
            break;

        case "Ko":
            $trClass = "error";
            break;

        case "Processing":
            $trClass = "process";
            break;

        case "Aborted":
            $trClass = "aborted";
            break;
    }
?>
    <tr id="campaignGraphStep<?php echo $ei_campaign_graph->getId() ?>" class="<?php echo $trClass; ?>">
        <?php
        if( !($ei_campaign_graph->getEiCampaignGraphType()->getAutomate() == true && $ei_campaign_graph->getEiScenario() != null) ):
            $complementChoixBlock = 'disabled="disabled"';
        else:
            $complementChoixBlock = "";
        endif;
        ?>

        <td class="radioStart">
            <input type="radio" name="eicampaigngraph_start" value="<?php echo $ei_campaign_graph->getId() ?>" <?php echo $complementChoixBlock ?>
                <?php if( $first_execution_graphs != null && $ei_campaign_graph->getId() == $first_execution_graphs->getGraphId() ): ?>
                    checked="checked"
                <?php endif; ?>
            />
        </td>
        <td class="radioEnd">
            <input type="radio" name="eicampaigngraph_end" value="<?php echo $ei_campaign_graph->getId() ?>" <?php echo $complementChoixBlock ?>
            <?php if( $last_execution_graphs != null && $ei_campaign_graph->getId() == $last_execution_graphs->getGraphId() ): ?>
                checked="checked"
            <?php endif; ?>
        </td>
        <td class="index">
            <?php echo $ei_campaign_graph->getId() ?>
            <input type="hidden" class="node_id" value="<?php echo $ei_campaign_graph->getId()  ?>" />
        </td> 
        <td> 
            <ul class="breadcrumb"> 
                <?php if(!$is_automatizable): ?>
                
                <li>
                    <?php if($ei_campaign_graph->getFilename()!=null): ?>
                    <?php $downloadCampaignNodeAttachment=$url_tab ?>
                    <?php $downloadCampaignNodeAttachment['campaign_id']=$ei_campaign_graph->getCampaignId(); ?>
                    <?php $downloadCampaignNodeAttachment['id']=$ei_campaign_graph->getId(); ?>
                     
                    <a href="<?php echo url_for2('downloadCampaignNodeAttachment', $downloadCampaignNodeAttachment) ?>" 
                       class="tooltipObjTitle troncObjName"   data-placement="top" data-toggle="tooltip"
                       data-original-title="<?php echo $ei_campaign_graph->getFilename() ?>">  
                        <?php echo ei_icon('ei_function') ?>
                    <?php echo  $ei_campaign_graph->getFilename()  ?>
                    </a> 
                    <?php endif; ?>  
                </li>  
                <?php else: $ei_scenario=$ei_campaign_graph->getEiScenario(); ?>
                
                <li class="active">
                    <?php $projet_new_eiversion=$url_tab ;
                          $projet_new_eiversion['ei_scenario_id']=$ei_scenario->getId() ;
                          $projet_new_eiversion['action']='editVersionWithoutId'; ?>
                    <?php if ($ei_scenario!=null):
                        $uri=url_for2('projet_new_eiversion',$projet_new_eiversion ); 
                        $scenText=$ei_scenario;
                        else: $uri="#";  $scenText='No scenario'; 
                        endif; ?> 
                    <a href="<?php  echo $uri ?>" class="tooltipObjTitle troncObjName"   data-placement="top" data-toggle="tooltip"
                       data-original-title="<?php echo $scenText ?>"> 
                         <?php echo ei_icon('ei_scenario') ?>
                    <?php echo $scenText  ?>
                    </a> 
                </li> <?php $dt=$ei_campaign_graph->getEiDataSet() ?>
                <?php if( !is_null($dt) ): ?>   
                <li class="active">
                     <?php if($dt instanceof  sfOutputEscaperIteratorDecorator &&
                            !count(sfOutputEscaper::unescape($dt)->getId())>0 ): ?>
                    <?php else : ?> 
                     
                    <a href="#" class="tooltipObjTitle troncObjName"    data-placement="top" data-toggle="tooltip"
                       data-original-title="<?php echo $dt ?>">  
                        <?php echo ei_icon('ei_dataset') ?>
                    <?php echo    $dt  ?>
                    </a> 
                    
                    
                    <?php endif; ?>
                </li>   
                <?php endif; ?>
                <?php endif; ?>
            </ul> 
        </td>      
        <td class="stepState"> <?php echo $etatStep; ?></td>
        <td> 
            <a  class="popoverObjDesc  eiObjDesc" title="Description"  data-placement="top" data-toggle="popover"  data-trigger="focus" 
                href="#campaignGraphStep<?php echo $ei_campaign_graph->getId() ?>"
             data-content="<?php echo $ei_campaign_graph->getDescription() ?>" >
            <?php  echo  $ei_campaign_graph->getDescription()  ?>
                
        </a> 
        </td>
        <td>
            <ul class="breadcrumb">  
                <li class="bug_icon">
                  <!-- Button to trigger modal -->
                  <?php $campaign_graph_new_bug_context=$url_tab ?>
                     <?php $campaign_graph_new_bug_context['campaign_graph_id']=$ei_campaign_graph->getId(); ?>
                    <a title="Create intervention"
                        href="<?php echo url_for2('campaign_graph_new_bug_context', $campaign_graph_new_bug_context) ?>" 
                       class="newBugContext"   >
                        <?php echo ei_icon('ei_subject') ?> 
                    </a>  
                  
                </li> 
                <?php if($is_automatizable): ?> 
                <?php if($ei_scenario!=null): ?>
                <li class="liOracleButton"> 

                    <?php

                    if( $executionGraphStep != null && $executionGraphStep->getEiTestSetId() != null ):

                        echo link_to2( ei_icon('ei_show') ,
                            'eitestset_oracle', array(
                                'project_id' => $project_id,
                                'project_ref' => $project_ref,
                                'profile_id' => $profile_id,
                                'profile_ref' => $profile_ref,
                                'profile_name' => $profile_name,
                                'ei_scenario_id' => $ei_scenario->getId(),
                                'ei_test_set_id' => $executionGraphStep->getEiTestSetId()),
                            array('target' => '_blank',
                                'title' => 'See oracle'));
        
                    else:
                        if( $executionGraphStep == null && $execution_graphs == null ):

                        echo link_to2( ei_icon('ei_show') ,
                            'getLastOracle', array(
                                'project_id' => $project_id,
                                'project_ref' => $project_ref,
                                'profile_id' => $profile_id,
                                'profile_ref' => $profile_ref,
                                'profile_name' => $profile_name,
                                'ei_scenario_id' => $ei_scenario->getId()),
                            array('target' => '_blank',
                                'title' => 'See oracle'));
                    else:
                        echo '<a href="#" class="disabledOracle" target="_blank">'.ei_icon('ei_show') .'</a>';
                    endif;
                    endif;
                    ?> 
                </li>
                <?php endif; ?> 
                <?php endif; ?>
            </ul> 
        </td>
    </tr>
<?php endif; ?> 