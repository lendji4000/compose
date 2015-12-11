<?php
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
    'contextRequest' => (isset($contextRequest) ? $contextRequest : null),
    'is_ajax_request' => (isset($is_ajax_request) && $is_ajax_request) ? true:false
        )
?>
<table class="table table-striped bootstrap-datatable dataTable  small-font" id="<?php echo ((isset($paginateList) && $paginateList) ? 'EiPaginatelist' : '') ?>">
    <thead> 
<?php $isDeliveryClosed = $url_tab;
$isDeliveryClosed['action'] = 'isDeliveryClosed'; ?>
    <input type="hidden" id="isDeliveryClosed" itemref="<?php echo url_for2('delivery_actions', $isDeliveryClosed) ?>" />
    <tr>

        <th width="10%"> 
            <?php if (isset($module_context) && $module_context == 'EiSubject'): ?>
                <input type="checkbox" id="check_all_subject_for_mult_act" />
            <?php endif; ?>
            Id
        </th>

        <th> Type </th>
        <th> Title </th>
        <th> Author </th>
        <th> Assignments </th>
        <th>  State </th>  
        <th> Priority</th> 
        <th>  Delivery  </th> 
        <?php if ( !(isset($is_ajax_request) &&   $is_ajax_request)): ?>
        <th>Updated At  </th>
        <th> Creation</th>
        <th>Dev time</th>
        <th>Test time</th>
        <th> Coverage</th>
        <th>  Ext ID  </th>  
        <?php endif; ?>
    </tr> 
</thead>
<tbody>
    <?php $subjectLine = $url_tab;
    $subjectLine['module_context'] = $module_context; ?>
    <?php if (count($ei_subjects) > 0): ?>
        <?php foreach ($ei_subjects as $ei_subject): ?>
        <?php $subjectLine['ei_subject'] = $ei_subject ?>
        <?php include_partial('eisubject/subjectLine', $subjectLine) ?> 
    <?php endforeach; ?> 
<?php endif; ?> 
</tbody> 
</table> 