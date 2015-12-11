<?php  $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref );   ?>
<tr class="iterationBlock <?php echo "iteration_num" . $iteration['iteration_id'] ?>">
    <td>
            <?php $ei_iteration_uri = $url_tab;
            $ei_iteration_uri['iteration_id'] = $iteration['iteration_id'];
            $ei_iteration_uri['action'] = 'show'
            ?>
        <strong>
             <?php echo ei_icon('ei_iteration') ?> 
            <?php echo $iteration['iteration_id'] ?> 
        </strong>   
    </td>
    <td><?php echo $iteration['created_at']   ?></td>
    <td><?php echo $iteration['updated_at']   ?></td>
    <td><?php echo ei_icon('ei_user').' '.$iteration['username']   ?></td>
    <td>
        <p>
            <?php  echo $iteration['description']; ?>
        </p> 
    </td>
    <td>
        <i class="saveLoader" ></i>
        <i class="fa fa-thumbs-o-up fa-2x thumbsWell"></i> 
                <?php $editIterationUri = $url_tab;
                $editIterationUri['action'] = "edit";
                $editIterationUri['iteration_id'] = $iteration['iteration_id']   ?>
        <a class="btn btn-sm btn-link editIteration" itemref="<?php echo url_for2("ei_iteration_actions", $editIterationUri) ?>" href="#iterationModal"  data-toggle="modal">
            <?php echo ei_icon('ei_edit') ?>
        </a>
        <?php $statsIterationUri = $url_tab;
                $statsIterationUri['action'] = "statistics";
                $statsIterationUri['iteration_id'] = $iteration['iteration_id']   ?>
        <a class="btn btn-sm btn-link  " href="<?php echo url_for2("ei_iteration_actions", $statsIterationUri) ?>"   >
            <?php echo ei_icon('ei_stats') ?>
        </a> 
        
            <?php if ($iteration['is_active']): ?>
                <?php $setActiveIterationUri = $url_tab;
                $setActiveIterationUri['action'] = "setAsActiveIteration";
                $setActiveIterationUri['iteration_id'] = $iteration['iteration_id']   ?>
            <span  itemref="<?php echo url_for2("ei_iteration_actions", $setActiveIterationUri) ?>" 
                   title="<?php echo($iteration['is_active'] ? 'Active iteration' : 'Set as active?') ?>"
                   class="label  ei-label <?php echo($iteration['is_active'] ? 'label-success activeIteration' : ' setIterationAsDefault') ?>">
                <i class="fa fa-check"></i> <?php echo($iteration['is_active'] ? 'Active iteration' : 'Set as active') ?> 
            </span>
            <?php endif; ?>
    </td>
</tr> 