 <?php if ($sf_user->hasFlash('reload_success')): ?>
    <p class="flash_msg_success"> 
    <?php echo $sf_user->getFlash('reload_success', ESC_RAW) ?>
    </p>
    <?php endif; ?>

<?php if ($sf_user->hasFlash('reload_error')): ?>
    <p class="flash_msg_error"> 
    <?php echo $sf_user->getFlash('reload_error', ESC_RAW) ?>
    </p>
    <?php endif; ?>
<?php if ($sf_user->hasFlash('alert_home_page_error')): $alert_tab = $sf_user->getFlash('alert_home_page_error'); ?>  
    <div id="">
        <div class="alert <?php echo $alert_tab['class'] ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <strong><?php echo $alert_tab['title'] ?> !</strong> <?php echo $alert_tab['text'] ?>
        </div>
    </div>
<?php endif; ?>     
<div class="row">		
    <div class="col-lg-12">
        <div class="panel panel-default eiPanel">
            <div class="panel-heading" data-original-title>
                <h2 class="title_project"><i class="fa fa-desktop"></i><span class="break"></span>Projects</h2>
                <div class="panel-actions"> 
                    <?php echo link_to1('<i class="fa fa-refresh "></i> Refresh', "@recharger_projet", 
                            array('id' => 'verifiedProjectState','class' => 'btn-primary',
                                  'title' => 'Refresh projects')); ?>  
                </div>
            </div>
            <div class="panel-body table-responsive"> 
                <table class="table table-striped table-bordered bootstrap-datatable dataTable  " id="eiProjectList">
                    <thead>
                        <tr>
                            <th class="libelle_projet">Name</th>
                            <th class="description_projet">Description</th>
                            <th>Checked at</th>
                            <th>Updated at </th>
                            <th>State</th>
                        </tr>
                    </thead> 
                    <?php if (isset($ei_projets)) : ?>
                        <?php include_partial('list', array('ei_projets' => $ei_projets)); ?>
                    <?php else : //affichage du message flash correspondant ?>
                        <?php if ($sf_user->hasFlash('undefine_project')): ?>
                            <p class="flash_msg"><?php echo $sf_user->getFlash('undefine_project') ?></p>
                        <?php endif; ?>
                    <?php endif; ?> 
                </table>            
            </div>
        </div>
    </div><!--/col-->  
      
</div><!--/row-->	
 
<input type="hidden" name="url_depart" value="<?php echo $sf_request->getUri(); ?>" class="url_depart" />
 