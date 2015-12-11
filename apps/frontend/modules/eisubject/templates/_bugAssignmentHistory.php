<?php if(isset($ei_subject) && isset($bugAssignmentHistorys)): ?>
<table class="table table-striped bootstrap-datatable  small-font dataTable " id="EiPaginateBugHistoryList">
    <thead>
        <tr>  
            <th>  Date</th>
            <th>  Author</th>
            <th> Assign to </th>   
        </tr> 
    </thead>
    <tbody> 
        <?php if(count($bugAssignmentHistorys)>0): ?>
        <?php foreach($bugAssignmentHistorys as $history): ?>
        <tr>
            <td><?php echo $history['sah_date'] ?></td>
            <td >  <?php echo ei_icon('ei_user') ?> <?php echo $history['uau_username'] ?></td>
            <td>
                <i class="fa <?php echo ($history['sah_is_assignment']?'fa-plus text-success':'fa-minus text-danger') ?>"></i></i> 
                <?php echo ei_icon('ei_user') ?> <?php echo $history['uas_username'] ?>
            </td>
             
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </tbody> 
</table> 
<?php endif; ?>