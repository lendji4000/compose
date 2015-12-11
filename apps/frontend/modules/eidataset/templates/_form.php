<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php
$uriForm=$urlParameters->getRawValue();

if( isset($is_select_data_set) && $is_select_data_set == 1 ){
    $uriForm["is_select_data_set"] = 1;
}

if ($form->isNew()) {
    $route = "eidataset_create";
    $uriForm['action']='create';
    $title = "Create data set";
} else {
    $route = "eidataset_edit";
    $uriForm['action']='update';
    $title = "Edit data set";

    if( isset($uriForm["is_select_data_set"]) ){
        $oForm = $form->getObject();
        /** @var EiDataSetTemplate $oForm */
        $oForm = $oForm->getEiDataSet();
        /** @var EiDataSet $oForm */
    }
    else{
        $oForm = $form->getObject();
    }
}

$url = url_for2($route, $uriForm);
?>

<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_dataSet_form' )) ?>
<form action="<?php echo $url ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
    <?php echo $form->renderHiddenFields(false) ?>
    <div class="panel panel-default eiPanel">
        <div class="panel-heading">
            <h2><?php echo ei_icon('ei_dataset') ?> Data set properties </h2>
            <div class="panel-actions"> 
            <?php   $projet_new_version = $urlParameters->getRawValue();
                    $projet_new_version['ei_scenario_id'] = $ei_scenario->getId();
                    $projet_new_version['action'] = 'edit';
                    unset($projet_new_version['ei_data_set_id'] );
                    ?>
                    <a href="<?php echo url_for2('projet_new_eiversion', $projet_new_version); ?>" class="btn-close">
                        <i class="fa fa-times"></i>
                    </a>
            </div>
        </div> 
        <div class="panel-body" > 
                <div class="col-lg-8 col-md-8 " >  
                    <div class=" form-group">
                        <label class="control-label col-md-4" for="inputEmail">Name</label>
                        <div class="col-md-8"> 
                            <?php echo $form['name']->renderError() ?>
                            <?php echo $form['name'] ?>
                        </div>
                    </div> 
                    <div class=" form-group">
                        <label class="control-label col-md-4" for="inputEmail">Description</label>
                        <div class="col-md-8"> 
                            <?php echo $form['description']->renderError() ?>
                            <?php echo $form['description'] ?>
                        </div>
                    </div>
                    <br/>
                    <?php if ($form->isNew() && isset($form['EiDataSet']) && isset($form['EiDataSet']['file'])): ?>
                    <div class=" form-group">
                        <label class="control-label col-md-4"  >Upload file</label>
                        <div class="col-md-8"> 
                            <?php echo $form['EiDataSet']['file']->renderError() ?>
                            <?php echo $form['EiDataSet']['file'] ?>
                        </div>
                    </div>
                    <?php endif; ?>  
                </div>

                <div id ="xsl_helper" class="popover col-lg-4 col-md-4 no-margin">
                    <div class="arrow"></div>
                    <h3 class="popover-title">Download XSD</h3>
                    <div class="popover-content">
                        <?php if ($form->isNew()): ?>
                            <p>To create a data set, you need to upload an XML file matching the scenario's structure.</p>
                            <p>To make this task easier to you, you can download scenario's XSD file or an XML sample by clicking one of the links below. <br/></p>
                            <p>
                            <?php
                            $paramsDataSetStructure = $urlParameters->getRawValue();
                            unset($paramsDataSetStructure['parent_id']);

                            echo "<a href=\"".url_for2('eidatasetstructure_download_xsd', $paramsDataSetStructure)."\" title=\"Download XSD\"> Download scenario's XSD. </a>";
                            ?>
                            </p>
                            <p>
                            <?php
                            $paramsDataSetStructureSample = $urlParameters->getRawValue();
                            unset($paramsDataSetStructureSample['parent_id']);
                            $paramsDataSetStructureSample['sf_format'] = "xml";

                            echo "<a href=\"".url_for2('eidatasetstructure_download_xml_sample', $paramsDataSetStructureSample)."\" title=\"Download XML sample\"> Download XML sample. </a>";

                            ?>
                            </p>
                        <?php else: ?>
                            <p>You can generate the XML corresponding to the data set you created by clicking the link below. <br/></p>
                            <p><?php
                            $paramsDl = $urlParameters->getRawValue();
                            unset($paramsDl['parent_id'], $paramsDl['ei_scenario_id']);
                            $paramsDl['ei_data_set_id'] = $oForm->getId();
                            $paramsDl['sf_format'] = "xml";
                            echo "<a href=\"" . url_for2('eidataset_download', $paramsDl) . "\" title=\"Generate and download data set's XML\"> Generate and download data set's XML. </a>";
                                ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
 
        </div> 
        <div class="panel-footer"> 
            <button type="submit" class="btn btn-sm btn-success <?php if (!$form->getObject()->isNew()): ?>update<?php endif; ?>" id="eiSaveDataSet">
                <i class="fa fa fa-check"></i> Save    
            </button> 
        </div>
    </div>  
</form>
