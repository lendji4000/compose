<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>   
 <div class="panel panel-default eiPanel" id="deliverySubjectsList">
    <div class="panel-heading">
        <h2> 
            <?php echo ei_icon('ei_subject') ?>
            <span class="break"></span>  
            Delivery bugs (<?php echo (isset($ei_delivery_subjects) &&(count($ei_delivery_subjects)>0)?count($ei_delivery_subjects):0) ?>)
        </a>
        </h2>
        <div class="panel-actions">  
        </div>
    </div>
    <div class="panel-body table-responsive">  
        <table class="table small-font bootstrap-datatable table-condensed table-striped dataTable" id="EiPaginateList">
                <thead>
                    <tr>
                        <th>  Id </th>
                        <th>  Type </th>
                        <th>  Title </th>
                        <th> Author </th>
                        <th> Assignments </th>
                        <th>State </th>  
                        <th> Priority</th>
                        <th> Delivery  </th> 
                        <th>Updated At</th>
                        <th> Coverage</th>
                        <th>External ID  </th> 

                    </tr> 
                </thead>
                <tbody>
                    <?php if (count($ei_delivery_subjects)>0): ?>
                    <?php foreach ($ei_delivery_subjects as $ei_subject): ?>
                    <?php $subjectLineUri=$url_tab; $subjectLineUri['ei_subject']=$ei_subject ?>
                    <?php include_partial('eisubject/subjectLine',$subjectLineUri) ?> 
                    <?php endforeach; ?> 
                    <?php endif; ?> 
                </tbody> 
            </table>   
    </div> 
</div>  