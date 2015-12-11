
<?php $url_params = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref );
?>
  
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_form' )) ?> 
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_migration_form' )) ?> 
<div class="row"  id="subjectContent">
    <div class="panel panel-default eiPanel" >
        <div class="panel-heading">
            <h2>
                <i class="fa fa-globe"></i>
                <span class="break"></span> Migration
            </h2>
            <div class="panel-actions"> 
                <?php $subject_migration_edit=$url_params;
                    $subject_migration_edit['subject_id']=$ei_subject_migration->getSubjectId();
                    $subject_migration_edit['id']=$ei_subject_migration->getId();
                    $subject_migration_edit['action']='edit';?>
                    <a class="btn-default " 
                       href="<?php echo url_for2('subject_migration_edit', $subject_migration_edit)  ?>"> 
                            <?php echo ei_icon('ei_edit') ?> 
                    </a>  
            </div>
        </div>
        <div class="panel-body">  
            <div class="   col-lg-12 col-md-12" contenteditable="false" designMode="on">
             <?php echo html_entity_decode($ei_subject_migration->getMigration(), ENT_QUOTES , "UTF-8")  ?>
            </div>
        </div>
    </div>
    <div class="panel panel-default eiPanel" >
        <div class="panel-heading">
            <h2>
                <i class="fa fa-upload"></i>
                <span class="break"></span>  Migration attachments
            </h2>
            <div class="panel-actions">
                <a href="#uploadAttachment" role="button" class="btn-default" data-toggle="modal">
                    <?php echo ei_icon('ei_add' ) ?>
                </a> 
            </div>
        </div>
        <div class="panel-body">   
            <?php $eisubjectattachment_list=$url_params;
                    $eisubjectattachment_list['subjectAttachments']=$subjectAttachments;
                    $eisubjectattachment_list['form']=$newAttachForm;
                    $eisubjectattachment_list['subject_id']=$subject_id; 
                    include_partial('eisubjectattachment/list',$eisubjectattachment_list)  ?> 
        </div>
    </div>
            
        
</div> 

