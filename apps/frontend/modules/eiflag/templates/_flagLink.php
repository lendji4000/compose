
    <?php if(isset($project_id) && isset($project_ref)   && isset($obj_id) && isset($flagType)):  ?>
<?php $flag_param=array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name, 
    'module' => 'eiflag',
    'action' => 'setState',
    'obj_id' => $obj_id,
    'flagType' => $flagType);

$new_state="Blank";
$state_icon='<i class="fa fa-circle-o   fa-lg"></i>'; 
        ?>

<?php if(isset($state)):
 switch ($state) {
    case 'Blank': $new_state="Ok";
        $state_icon='<i class="fa fa-circle-o   fa-lg"></i>'; 
        break;
    case 'Ok':   $new_state="Warning";
        $state_icon='<i class="fa fa-thumbs-up fa-green fa-lg"></i>';
        break;
    case 'Warning': $new_state="Ko";
       $state_icon='<i class="fa fa-hand-o-right fa-warning-yellow fa-lg"></i>';
        break;
    case 'Ko': $state_icon='<i class="fa fa-thumbs-down fa-red fa-lg"></i>';
        $new_state="Blank";
        break; 
    default:
        $new_state=$state;
        break;
}
endif; 
$flag_param['state']=$new_state;
?>

<a href='<?php echo url_for2('setFlagForCampaign', $flag_param) ?>' 
   class='pull-right setFlagForCampaign' title="Set state"> 
    <?php echo $state_icon ?>
</a>
<?php endif; ?>