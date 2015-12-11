
<?php if(isset($project_id) && isset($project_ref)   && isset($obj_id) && isset($flagType)):  ?>
<?php $flag_param=array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
    'module' => 'eiflag',
    'action' => 'setComment',
    'obj_id' => $obj_id,
    'flagType' => $flagType);
 
if(isset($comment) && $comment!=null):  
    $comment_icon='<i class="fa fa-comment-o fa-lg"></i>'. ei_icon('ei_edit','fa-mini') ; 
else:
    $comment="";
    $comment_icon='<i class="fa fa-comment-o fa-lg"></i>'.ei_icon('ei_add','fa-mini' );
endif;
        ?>
 

<a   class='pull-right setCommentForCampaign ' title="Set comment" 
   data-id="<?php echo url_for2('setFlagForCampaign', $flag_param) ?>" href='#'> 
    <?php echo $comment_icon ?> 
    <textarea style="display: none"> <?php echo $comment ?></textarea>
</a>
<?php endif; ?>