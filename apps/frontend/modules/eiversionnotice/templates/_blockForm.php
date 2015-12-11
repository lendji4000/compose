<?php if(isset($form) && isset($url_form) && $inParameters && isset($outParameters)): ?>
<div class="panel panel-default eiPanel " id="versionNoticeForm" > 
            <div class="panel-heading"> 
                <h2><?php echo ei_icon('ei_properties') ?>Properties</h2> 
            </div> 
        <div class="panel-body clearfix" id="versionNoticeFormContent">  
            <?php include_partial('eiversionnotice/form', array('form' => $form,
                'url_form' => $url_form,
                'inParameters' => $inParameters,
                'outParameters' => $outParameters));
            ?> 
        </div>	 
        <div class="panel panel-footer">
            <div class="row"> 
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                    <a class="btn btn-success" id="saveVersionNotice" href="#">
                        <i class="fa fa-check"></i> Save
                    </a>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><i id="noticeLoader" ></i></div>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-8" id="alertNoticeMsg" > 
                    <div class=" row alert alert-success" ><strong>Well done!</strong> datas successfully saved...  </div>
                    <div class="row alert alert-danger"><strong>Warning !</strong>Errors in form. datas wasn't saved...  </div>
                    
                </div>
            </div>
            
            
        </div>
    </div>
<?php endif; ?>