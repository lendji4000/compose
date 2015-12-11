<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);
 $stepLineInContent=$url_tab;    ?>

<?php if (($k=count($steps)) > 0): ?> 
<?php foreach ($steps as $step): ?>

    <?php
$stepLineInContent['ei_campaign_graph']=$step;
$stepLineInContent['is_lighter']=($k == 1 ? true : false );
include_partial('eicampaigngraph/stepLineInContent', $stepLineInContent)
        ?> 
    <?php  $k--; ?> 
<?php endforeach; ?>
<?php endif; ?> 