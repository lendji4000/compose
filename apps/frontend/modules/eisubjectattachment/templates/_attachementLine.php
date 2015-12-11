<?php 
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name, 
);
?>

<tr>    
    <td>
        <?php 
            $fileName = $attach->getFileName();
            $fileExtension = strtolower(substr(strrchr($fileName, '.') ,1));

            switch ($fileExtension) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                ?>
                    <a data-lightbox="imagesAttachements" href="<?php echo '/uploads/subjectAttachements/'.$attach->getPath();?>">
                        <img src="<?php echo '/uploads/subjectAttachements/'.$attach->getPath();?>" height="45px"/>
                    </a>
                <?php
                    break;
                default:
                    break;
            }
        ?>
    </td>
    
    <td>
        <?php echo $attach->getFilename(); ?>
    </td>

    <td>
        <?php
            if($attach->getDescription() != ''):
                echo $attach->getDescription();
            endif;
        ?>
    </td>
    
    <td width="8%">
        <div class="btn-group">
            <?php $downloadSubjectAttachment=$url_params;
                  $downloadSubjectAttachment['id']=$attach->getId();    ?>
            <a href="<?php echo url_for2('downloadSubjectAttachment', $downloadSubjectAttachment)  ?>" class="btn btn-success" title="Download attachement">
                <i class="fa fa-download">  </i>
            </a>
            <?php $remove_subject_attachment=$url_params;
                  $remove_subject_attachment['id']=$attach->getId();    ?>
            <a class="btn btn-danger removeSubjectAttachment" href="<?php  echo url_for2('remove_subject_attachment',$remove_subject_attachment)?>" title="Delete attachement">
                <?php echo ei_icon('ei_delete') ?>
            </a>
        </div>
    </td>  
</tr>