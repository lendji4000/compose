<?php if(isset($kal_functions) && count($kal_functions)>0): ?>
<?php  $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref );  ?>
<table class="table table-striped ">
    <thead>
        <tr> 
            <th>Path</th>
            <th>Name</th>
            <th>Criticity</th> 
        </tr>
    </thead>
    <tbody> 
        <?php foreach ($kal_functions as $fct):
                $minifyVar = $url_tab;
                $minifyVar['kal_function'] = $fct;
                include_partial('kalfonction/minifyTr', $minifyVar);
            endforeach; ?>  
    </tbody>
</table>
<?php endif; ?>