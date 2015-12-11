    <!-- Ligne d'une campagne de tests pour insertion dans une livraison-->
<?php if(isset($ei_campaign) && isset($ei_delivery) && isset($project_id) && isset($project_ref)): ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
    <tr>
    <td><?php echo $ei_campaign->getId() ?></td> 
    <td>
        <?php $eicampaign_edit =$url_tab;
                  $eicampaign_edit['campaign_id']=$ei_campaign->getId();   ?>
        <a href="<?php echo url_for2('campaign_edit',$eicampaign_edit) ?>">
            <?php echo $ei_campaign->getName() ?> 
        </a> 
    </td>
    <td>
        <a class="tooltipUser" data-placement="left" data-toggle="tooltip" href="#" 
           data-original-title="<?php echo $ei_campaign->getSfGuardUser()->getEmailAddress() ?>">
        <?php echo $ei_campaign->getSfGuardUser()->getUsername() ?>
        </a>  
    </td>
    <td><?php echo  $ei_campaign->getDescription() ?></td> 
    <td  >
        <?php $coverage=$ei_campaign->getCoverage();
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