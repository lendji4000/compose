<?php if(isset($ei_project) && isset($ei_delivery)): ?>
<?php 
 $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'delivery_id'=>$ei_delivery->getId() );  
?>
<div class="row" id="deliveryIterationsByProfile"> 
        <?php foreach ($iterations_by_profiles as $profile): ?>
            <div class="panel panel-default eiPanel iterationsByProfile" >
                <div class="panel-heading">
                    <h2> <?php echo ei_icon('ei_profile') ?>   <span class="break"></span>  <?php echo $profile['profile_name'] ?>    </h2>
                    <div class="panel-actions">    </div>
                </div>
                <div class="panel-body " >  
                    <table class="table table-striped dataTable <?php echo 'iteration_profile_num'.$profile['profile_id'].$profile['profile_ref'] ?>">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Created at </th>
                                <th>Updated at </th>
                                <th>Author</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($profile['iterations']) && count($profile['iterations']) > 0): ?>
                                <?php foreach ($profile['iterations'] as $iteration): ?> 
                                    <?php $iterationLineParams = $url_tab;
                                    $iterationLineParams['iteration'] = $iteration;
                                    ?>
                                    <?php include_partial('iterationLine', $iterationLineParams) ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody> 
                    </table>

                </div>
                <div class="panel panel-footer">
                    <?php
                    $ei_iteration_global_add = $url_tab;
                    $ei_iteration_global_add['it_profile_id'] = $profile['profile_id'];
                    $ei_iteration_global_add['it_profile_ref'] = $profile['profile_ref'];
                    $ei_iteration_global_add['action'] = "new";
                    ?> 
                    <a href="#iterationModal" class="btn btn-sm btn-success addIterationForProfile" data-toggle="modal"
                       itemref="<?php echo url_for2('ei_iteration_create', $ei_iteration_global_add) ?>" >
                    <?php echo ei_icon('ei_add') ?> Add
                    </a>
                </div>    
            </div> 
    <?php endforeach; ?> 
    </div> 



<?php if(isset($url_tab)): ?>
<!--
 * Box de création et edition d'une itération
-->
<div id="iterationModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 id="iterationModalTitle" ><?php echo ei_icon('ei_iteration') ?> Iteration properties</h3> 
                <i class="eiLoading" ></i>
            </div>
            <div class="modal-body" id="iterationModalContent">

            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-success pull-right" id="saveIteration" type="submit">
                    <i class="fa fa-check"></i> Save
                </button>
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">
                    Close
                </a> 
            </div>
        </div>
    </div>
</div>

<?php endif; ?> 
<?php endif; ?>
