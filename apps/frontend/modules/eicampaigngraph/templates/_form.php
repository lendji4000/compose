<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?> 
<?php $url_params=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name ); 
        ?>
<?php
if (!$form->getObject()->isNew()):
    $url_form = 'campaign_graph_update';
    $url_params['id'] = $form->getObject()->getId();
else:
    $url_params['campaign_id'] = $campaign_id;
    $url_params['parent_id'] = $parent_id;
    $url_form = 'campaign_graph_create';
endif;
?> 
<?php $is_automatizable=Doctrine_Core::getTable('EiCampaignGraphType')->findOneByProjectIdAndProjectRefAndId(
        $project_id,$project_ref,$form['step_type_id']->getValue()) ?>
 
<form class="form-horizontal " id="campaignGraphForm" action="<?php echo url_for2($url_form, $url_params) ?>" 
      method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>
      target="result_form" >
          <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
    <div>
        <?php echo $form->renderHiddenFields(false) ?>
        <?php echo $form->renderGlobalErrors() ?> 
    </div>
        <div style="display: none">
            <div class="controls"> 
                <?php echo $form['step_type_id']->renderError() ?>
                <?php echo $form['step_type_id'] ?>
            </div>
        </div>
        
    <div class="row ">  
        <div id="campaignGraphAttachment" class="col-lg-6 col-md-6 well well-sm" 
             style=" display:<?php  echo (($is_automatizable!=null && $is_automatizable->getAutomate()) ? 'none':'') ?>"> 
            <h5>&nbsp;<?php if (!$form->getObject()->isNew()): echo $form->getObject()->getFilename(); endif; ?></h5>
            <script>
            $(document).ready(function(){
               var uploader = new qq.FileUploader({
                    element: document.getElementById('btnFileUpload'), 
                    action: '/uploadCampaignGraphAttachment.php',
                    onSubmit : function(id,fileName){
                        $('#filePath').val(fileName);
                    },
                    onProgress : function(id,fileName,loaded,total)   {
                     var pourcent=Math.ceil((100/total)*loaded);
                     $("loadBar").css({'width' : pourcent + '%'}).text(pourcent +'%');
                    },
                    onComplete : function(id,fileName,responseJSON){ 
                        $(":input[id=filePath]").val(responseJSON.tmpPath);
                        $(":input[id=fileName]").val(responseJSON.filename);
                    }
                }); 
            });
            </script>
                 
            <div id="file-uploader">       
                <noscript>          
                    <p>Please enable JavaScript to use file uploader.</p>
                    <!-- or put a simple form for upload here -->
                </noscript>         
            </div>
            <div id="contenu"> 
                <div id="btnFileUpload">  </div> 
                <div id="loadBar"> </div>
            </div>
        </div>
    </div> 
         
</form> 




 