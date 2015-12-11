<?php if(isset($ei_subject) && isset($ei_project) && isset($field_type)) ?>
<div id="subjectContent"> 
    <div id="alertMsg">
        
    </div>
    <form id="detailOrSolutionOrMigrationForm"  method="post" 
          action="<?php echo url_for2('updateDetailOrSolutionOrMigration', array(
              'id'=>$ei_subject->getId(),
              'project_id'=>$ei_project->getProjectId(),
              'project_ref' => $ei_project->getRefId(),
              'field_type'=> $field_type)) ?>"> 
                <?php if($field_type=='Details'): ?>
                <textarea class="tinyMceSubject" name="field_name"><?php echo $ei_subject->getDetails() ?></textarea>
                <?php endif; ?>
                <?php if($field_type=='Migration'): ?>
                <textarea class="tinyMceSubject" name="field_name"><?php echo $ei_subject->getMigration() ?></textarea>
                <?php endif; ?>
                <?php if($field_type=='Solution'): ?>
                <textarea class="tinyMceSubject" name="field_name"><?php echo $ei_subject->getSolution() ?></textarea>
                <?php endif; ?>  
                <button class="btn btn-sm btn-success pull-right updateDetailOrSolutionOrMigration" type="submit">
                    <i class="fa fa-check"></i> Save 
                </button>
    </form>  
</div>
