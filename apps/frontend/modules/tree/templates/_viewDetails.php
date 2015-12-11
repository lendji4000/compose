<?php if(isset($ei_view) && isset($ei_project) && isset($ei_profile) && isset($ei_tree)): ?>
<table class="table table-bordered table-striped dataTable"> 
    <tbody> 
        <tr>
            <th>Title</th>
            <td><?php echo $ei_tree->getName() ?></td>
        </tr> 
        <tr>
            <th>Description</th>
            <td><?php echo $ei_view->getDescription() ?></td>
        </tr> 
    </tbody>
</table> 
<?php endif; ?>