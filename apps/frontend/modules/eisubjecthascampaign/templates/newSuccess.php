<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
    'subject_id' => $subject_id )
?> 
<?php $url_form= $url_params ;
$url_form['form']=$form;
?>
<div id="subjectContent" class="row">
    <div class="panel panel-default eiPanel" id="subjectCampaignsList">
        <div class="panel-heading">
            <h2>
                <?php echo ei_icon('ei_campaign') ?>
                <span class="break"></span> Add Subject campaigns
            </h2>
            <div class="panel-actions">  
            </div>
        </div>
        <div class="panel-body"> 
            <?php include_partial('form', $url_form) ?>
        </div> 
    </div>   
</div> 
 