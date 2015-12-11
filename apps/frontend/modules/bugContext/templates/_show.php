<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name)
?>

<div id="bugContextContent">
    <div class="panel panel-default eiPanel" id="subjectContentProperties">
        <div class="panel-heading">
            <h2>  
                <i class="fa fa-ellipsis-h"></i>Context Properties
            </h2>
            <div class="panel-actions">
                <?php
                $edit_Bug_Context = $url_params;
                $edit_Bug_Context['subject_id'] = $ei_context->getSubjectId();
                $edit_Bug_Context['id'] = $ei_context->getId();
                $edit_Bug_Context['action'] = 'edit';
                ?>
                <a id="editBugContext" class=" btn-default "
                   href="<?php echo url_for2('edit_Bug_Context', $edit_Bug_Context) ?>"> 
                    <?php echo ei_icon('ei_edit') ?>
                </a> 
            </div>
        </div> 
        <div class="panel-body table-responsive">
            <div class="col-lg-6 col-md-6 col-sm-11 col-xs-11">
                <table class="table table-bordered table-striped dataTable"> 
                    <tbody> 
                        <tr class="bugContextAuthor">
                            <th>Author</th>
                            <td><?php echo $ei_context->getbugContextAuthor()->getUsername() ?></td>
                        </tr>
                        <tr class="bugContextCampaign">
                            <th>Campaign</th>
                            <td>

                                <?php if (($ei_campaign = $ei_context->getBugContextCampaign()) != null): ?>
                                    <?php
                                    $campaign_edit = $url_params;
                                    $campaign_edit['campaign_id'] = $ei_campaign->getId();
                                    ?>
                                    <a href="<?php echo url_for2('campaign_edit', $campaign_edit) ?>">
                                        <?php echo $ei_campaign->getName() ?> 
                                    </a> 
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="bugContextScenario">
                            <th>Scenario</th>
                            <td> 
                                <?php
                                if (($ei_scenario = $ei_context->getBugContextScenario()) != null):
                                    $projet_new_eiversion = $url_params;
                                    $projet_new_eiversion['ei_scenario_id'] = $ei_scenario->getId();
                                    $projet_new_eiversion['action'] = 'editVersionWithoutId';
                                    echo (link_to2($ei_scenario, 'projet_new_eiversion', $projet_new_eiversion) );
                                endif;
                                ?>
                            </td>
                        </tr>
                        <tr class="bugContextProfile">
                            <th>Environment</th>
                            <td>  <?php echo $profile_name ?> </td>
                        </tr>
                    </tbody>
                </table> 
            </div>
            <div class="col-lg-6 col-md-6 col-sm-11 col-xs-11">
                <table class="table table-bordered table-striped dataTable"> 
                    <tbody> 
                        <tr class="bugContextDelivery">
                            <th>Delivery</th>
                            <td>
                                <?php if (($ei_delivery = $ei_context->getBugContextDelivery()) != null): ?>
                                    <?php
                                    $delivery_edit = $url_params;
                                    $delivery_edit['delivery_id'] = $ei_delivery->getId();
                                    ?>
                                    <a href="<?php echo url_for2('delivery_edit', $delivery_edit) ?>">
                                    <?php echo $ei_delivery->getName() ?> 
                                    </a> 
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="bugContextCampaignStep"> 
                            <th>Step</th>
                            <td>
                                <?php if (($ei_campaign_step = $ei_context->getBugContextCampaignStep()) != null): ?>
                                        <?php
                                        $campaign_edit = $url_params;
                                        $campaign_edit['campaign_id'] = $ei_campaign_step->getCampaignId();
                                        ?>
                                    <a href="<?php echo url_for2('campaign_edit', $campaign_edit) ?>">
                                        <?php echo 'Step ID / ' . $ei_campaign_step->getId() ?> 
                                    </a> 
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="bugContextDataSet">
                            <th>Data Set</th>
                            <td>
                            <?php if (($ei_data_set = $ei_context->getBugContextJdd()) != null): ?> 
                                <?php echo $ei_data_set->getName() ?>
                            <?php else: echo '  No data set... '; ?>
                            <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="bugContextTestSet">
                            <th>Test Set</th>
                            <td>
                            <?php if (($ei_test_set = $ei_context->getBugContextTestSet()) != null): ?> 
                                <?php echo $ei_test_set->getId() ?>
                            <?php else: echo 'No test set... '; ?>
                            <?php endif; ?> 
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>  
        </div>

    </div>       

</div> 