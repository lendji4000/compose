<?php if (isset($ei_campaign) && isset($project_id) && isset($project_ref)): ?>
<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
    'campaign_id' => $ei_campaign->getId());
if(isset($ei_delivery) && $ei_delivery!=null)  : //Campagne d'une livraison
    $delivery_id=$ei_delivery->getId(); 
    $url_params['delivery_id']=$delivery_id;
endif; 
if(isset($ei_subject) && $ei_subject!=null)  : //Campagne d'un sujet
    $subject_id=$ei_subject->getId();
    $url_params['subject_id']=$subject_id;
endif; 
?> 
<tr>
    <td  ><?php echo $ei_campaign->getId() ?></td> 
    <td >
        <?php $edit_params=$url_params;
            $edit_url='campaign_edit';  ?>
        <?php if(isset($ei_delivery) || isset($ei_subject)):  ?> 
            <?php if(isset($ei_subject)): 
                $edit_params['subject_id']=$subject_id; $edit_params['action']='edit';
                $edit_url='editSubjectCampaign'; ?>
            <?php endif; ?>
            <?php if(isset($ei_delivery)): 
                $edit_params['delivery_id']=$delivery_id; $edit_params['action']='edit'; 
                $edit_url='editDeliveryCampaign'; ?>
            <?php endif; ?>
        <?php endif; ?> 
        <a class="tooltipCampaignName eiObjName <?php echo((isset($is_ajax_request) && $is_ajax_request)? 'select_camp_for_steps': '') ?>" data-placement="left" data-toggle="tooltip"
           data-original-title="<?php echo $ei_campaign->getName() ?>"
            href="<?php echo((isset($is_ajax_request) && $is_ajax_request)?
                            url_for2('showCampaignSteps', $url_params):
                            url_for2('graphHasChainedList', $url_params) )
                                    ?>"> 
            <?php echo  $ei_campaign ?>
        </a>
         
        
        
    </td>
    <td  >
        <a class="tooltipUser" data-placement="top" data-toggle="tooltip" href="#" 
           data-original-title="<?php echo $ei_campaign->getSfGuardUser()->getEmailAddress() ?>">
        <?php echo $ei_campaign->getSfGuardUser()->getUsername() ?>
        </a>  
     </td>
    <td  > 
        <a  class="popoverObjDesc eiObjDesc" title="Description"  data-trigger="focus"  data-placement="top" data-toggle="popover" href="#"
             data-content="<?php echo $ei_campaign->getDescription() ?>" data-original-title="Popover on top"> 
            <?php echo  $ei_campaign->getDescription()  ?>
        </a>
    </td>
    <td>
        <?php echo date('Y-m-d', strtotime($ei_campaign->getUpdatedAt())); ?>
    </td>
    <td  >
        <?php $coverage=$ei_campaign->getCoverage(); 
                if($coverage==null) $coverage=0; // Si la couverture n'est pas définie
                if ($coverage <= 50)
                    $bgColor= "rgb(255,".ceil(($coverage * 2 * 255) / 100) . ",0)";

                else
                    $bgColor = "rgb(" . ceil(((100 - $coverage) * 2 * 255) / 100) . ", 255,0)";
                
                ?>
                <div id="progressbar">
                    <div id="ei_campaign_coverage_indicator" class="indicator" 
                         style="<?php echo 'width : '.$coverage.'%; background-color: '.$bgColor ?>">
                        <?php   echo $coverage.'%' ?>
                    </div> 
                </div>  
    </td>
</tr>
<?php endif; ?>