<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php 
$url_form='edit_Bug_Context';
$url_params=array('project_id' => $project_id,
                 'project_ref' => $project_ref,
                 'profile_id' => $profile_id,
                 'profile_ref' => $profile_ref,
                 'profile_name' => $profile_name);
$form_uri  = $url_params;
$form_uri['id'] = $ei_context->getId();
$form_uri['subject_id'] = $ei_context->getBugContextSubject()->getId();
$form_uri['action'] = 'update'; 
?> 
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_bug_context_form' )) ?> 
<form class="form-horizontal " id="subjectForm"
    action="<?php echo url_for2($url_form,$form_uri) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2>  
            <i class="fa fa-ellipsis-h"></i>Intervention Context
        </h2>
        <div class="panel-actions"> 
        </div>
    </div> 
    <div class="panel-body">  
        <div>
            <?php echo $form->renderHiddenFields() ?> 
            <?php echo $form->renderGlobalErrors() ?> 
        </div> 
        <div class="col-lg-6 col-md-6  "> 
            <div class=" form-group">
                <label class="control-label col-lg-2 col-md-2">Author</label>
                <div class="col-lg-10 col-md-10"> 
                     <?php echo $form['author_id']->renderError() ?>
                     <?php echo $form['author']->renderError() ?>
                     <?php echo $form['author'] ?>
                      <script>  
                          var ei_subjects_authors = <?php print_r(json_encode($guardUsersForTypeHead->getRawValue())); ?>   
                       </script>
                </div>
            </div>
            <div class=" form-group contextCampaignFormPart">
                <input class ="contextCampaignFormPartHref" type="hidden" 
                        itemref="<?php echo url_for2('renderCampaignStepWidget',$url_params) ?>" />
                <label class="control-label col-lg-2 col-md-2">Campaign</label>
                <div class="col-lg-10 col-md-10"> 
                     <?php echo $form['campaign_id']->renderError() ?>
                     <?php echo $form['campaign_id'] ?>
                </div>
            </div>  
                  
            <div id="campaignGraphTestSuite" class="  "  > 
                <?php echo $form['scenario_id']->renderError() ?>
                <?php echo $form['scenario_id'] ?>
                <div class=" form-group " >
                    <label class="control-label" for="campaignGraphTestSuite"></label>
                    <div class="controls input-append"> 
                        
                    </div>
                </div> 
                <div class=" form-group  "> 
                    <label class="control-label col-lg-2 col-md-2">Scenario</label>
                    <div class="col-lg-9 col-md-9"> 
                         <?php echo $form['scenario_id'] ?> 
                        <input  class="form-control" id="appendedInputTestSuiteButton" type="text" disabled="disabled"
                                value="<?php if ($form['scenario_id']->getValue() != null):
                            echo $form->getObject()->getBugContextScenario();
                        endif;
                        ?>">   
                    </div>
                    <div class="col-lg-1 col-md-1  ">
                        <button class="btn btn-default" id="campaignGraphTestSuiteAdd" type="button">
                            <?php echo ei_icon('ei_search') ?>
                        </button>
                    </div>
                </div> 
                <div id="campaignGraphTestSuiteAddBox">
                    <?php
                    $testSuiteTree = $url_params;
                    $testSuiteTree['root_folder'] = $root_folder;
                    $testSuiteTree['ei_nodes'] = $ei_nodes;
                    include_partial('eicampaigngraph/testSuiteTree', $testSuiteTree)
                    ?>
                </div>
            </div>
            <div id="campaignGraphDataSet" class="  " > 
                     <?php echo $form['ei_data_set_id']->renderError() ?>
                     <?php echo $form['ei_data_set_id'] ?> 
                    <div class=" form-group  "> 
                        <label class="control-label col-lg-2 col-md-2">Data set</label>
                        <div class="col-lg-9 col-md-9  "> 
                            <input class="form-control" id="appendedInputDataSetButton" type="text" disabled="disabled"
                                    value="<?php if($form['ei_data_set_id']->getValue()!=null):
                                        echo $form->getObject()->getBugContextJdd();  endif; ?>">
                            
                        </div>
                        <div class="col-lg-1 col-md-1  ">
                            <button class="btn btn-default" id="campaignGraphDataSetSearch" type="button">
                                <?php echo ei_icon('ei_search') ?>
                            </button>
                        </div>
                     </div> 
                    <div id="campaignGraphDataSetAddBox"> 
                        <div id="arbre_jdd">
                            <?php if(!$form->getObject()->isNew() && $form->getObject()->getBugContextScenario()!=null) :
                                    
                                $ei_node=$form->getObject()->getBugContextScenario()->getNode();
                                $ei_data_set_root_folder = Doctrine_Core::getTable('EiNode')
                                    ->findOneByRootIdAndType($ei_node->getId(), 'EiDataSetFolder');
                                $ei_data_set_children = Doctrine_Core::getTable('EiNode')
                                    ->findByRootId($ei_data_set_root_folder->getId());
                                //Construction du tableau de paramètres du partiel
                                $testSuiteJddRootFolder  = $url_params;
                                $testSuiteJddRootFolder['ei_data_set_children'] = $ei_data_set_children;
                                $testSuiteJddRootFolder['ei_data_set_root_folder'] =$ei_data_set_root_folder; 
                                include_partial('eicampaigngraph/testSuiteJddRootFolder',$testSuiteJddRootFolder);  

                            endif;
                            ?>
                        </div>
                    </div>
                </div>
                
         </div>
         <div class="col-lg-6 col-md-6  "> 
             <div class=" form-group  "> 
                <label class="control-label col-lg-2 col-md-2">Delivery</label>
                <div class="col-lg-10 col-md-10"> 
                    <?php echo $form['delivery_id']->renderError() ?>
                     <?php echo $form['delivery_id'] ?>
                </div>
             </div>
             <div class=" form-group  "> 
                <label class="control-label col-lg-2 col-md-2">Step ID</label>
                <div class="col-lg-10 col-md-10 contextCampaignStepFormPart"> 
                    <?php echo $form['campaign_graph_id']->renderError() ?>
                    <?php echo $form['campaign_graph_id'] ?>
                </div>
             </div>  
             <div class=" form-group  "> 
                <label class="control-label col-lg-2 col-md-2">Environment</label>
                <div class="col-lg-10 col-md-10"> 
                     <?php echo $form['profile_id']->renderError() ?>
                    <?php echo $form['profile_ref']->renderError() ?>
                    <?php echo $form['profile']->renderError() ?>
                    <?php echo $form['profile'] ?>
                </div>
            </div>       
             <div class=" form-group  "> 
                <!--<label class="control-label col-lg-2 col-md-2">Step ID</label>-->
                <div class="col-lg-10 col-md-10  "> 
                    <?php echo $form['ei_test_set_id']->renderError() ?>
                     <?php echo $form['ei_test_set_id'] ?>
                </div>
             </div> 
             <div class=" form-group">
                 <!--<label class="control-label col-lg-2 col-md-2">Function</label>-->
                 <div class="col-lg-10 col-md-10" > 
                     <?php echo $form['ei_fonction_id']->renderError() ?>
                     <?php echo $form['ei_fonction_id'] ?>
                 </div>
             </div>
         </div>
    </div>
    <div class="panel-footer">
        <button id="saveBugContext" class="btn btn-sm btn-success  " type="submit">
            <i class="fa fa-check"></i> Save 
        </button>
    </div>
</div> 
         
     
</form>
 