
<div>
    <?php $sensors = EiLogSensorTable::getInstanceWithEiLogFunctionId($key);?>
    <?php if($sensors != null):?>
    <strong>Sensors :</strong><br/>
    <table class="table-striped bootstrap-datatable dataTable">
        <thead>
            <tr>  
                <th>Application</th>
                <th>Data base</th>
                <th>Client</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>memory mean : <?php if($sensors[0]['app_memory_mean'] != null){echo $sensors[0]['app_memory_mean'];}else{echo '/';}?></td>
                <td>memory mean : <?php if($sensors[0]['db_memory_mean'] != null){echo $sensors[0]['db_memory_mean'];}else{echo '/';}?></td>
                <td>memory mean : <?php if($sensors[0]['client_memory_mean'] != null){echo $sensors[0]['client_memory_mean'];}else{echo '/';}?></td>
            </tr>
            <tr>
                <td>memory min : <?php if($sensors[0]['app_memory_min'] != null){echo $sensors[0]['app_memory_min'];}else{echo '/';}?></td>
                <td>memory min : <?php if($sensors[0]['db_memory_min'] != null){echo $sensors[0]['db_memory_min'];}else{echo '/';}?></td>
                <td>memory min : <?php if($sensors[0]['client_memory_min'] != null){echo $sensors[0]['client_memory_min'];}else{echo '/';}?></td>
            </tr>
            <tr>
                <td>memory max : <?php if($sensors[0]['app_memory_max'] != null){echo $sensors[0]['app_memory_max'];}else{echo '/';}?></td>
                <td>memory max : <?php if($sensors[0]['db_memory_max'] != null){echo $sensors[0]['db_memory_max'];}else{echo '/';}?></td>
                <td>memory max : <?php if($sensors[0]['client_memory_max'] != null){echo $sensors[0]['client_memory_max'];}else{echo '/';}?></td>
            </tr>
            <tr>
                <td>memory start : <?php if($sensors[0]['app_memory_start'] != null){echo $sensors[0]['app_memory_start'];}else{echo '/';}?></td>
                <td>memory start : <?php if($sensors[0]['db_memory_start'] != null){echo $sensors[0]['db_memory_start'];}else{echo '/';}?></td>
                <td>memory start : <?php if($sensors[0]['client_memory_start'] != null){echo $sensors[0]['client_memory_start'];}else{echo '/';}?></td>
            </tr>
            <tr>
                <td>memory end : <?php if($sensors[0]['app_memory_end'] != null){echo $sensors[0]['app_memory_end'];}else{echo '/';}?></td>
                <td>memory end : <?php if($sensors[0]['db_memory_end'] != null){echo $sensors[0]['db_memory_end'];}else{echo '/';}?></td>
                <td>memory end : <?php if($sensors[0]['client_memory_end'] != null){echo $sensors[0]['client_memory_end'];}else{echo '/';}?></td>
            </tr>
            <tr>
                <td>cpu mean : <?php if($sensors[0]['app_cpu_mean'] != null){echo $sensors[0]['app_cpu_mean'];}else{echo '/';}?></td>
                <td>cpu mean : <?php if($sensors[0]['db_cpu_mean'] != null){echo $sensors[0]['db_cpu_mean'];}else{echo '/';}?></td>
                <td>cpu mean : <?php if($sensors[0]['client_cpu_mean'] != null){echo $sensors[0]['client_cpu_mean'];}else{echo '/';}?></td>
            </tr>
            <tr>
                <td>cpu min : <?php if($sensors[0]['app_cpu_min'] != null){echo $sensors[0]['app_cpu_min'];}else{echo '/';}?></td>
                <td>cpu min : <?php if($sensors[0]['db_cpu_min'] != null){echo $sensors[0]['db_cpu_min'];}else{echo '/';}?></td>
                <td>cpu min : <?php if($sensors[0]['client_cpu_min'] != null){echo $sensors[0]['client_cpu_min'];}else{echo '/';}?></td>
            </tr>
            <tr>
                <td>cpu max : <?php if($sensors[0]['app_cpu_max'] != null){echo $sensors[0]['app_cpu_max'];}else{echo '/';}?></td>
                <td>cpu max : <?php if($sensors[0]['db_cpu_max'] != null){echo $sensors[0]['db_cpu_max'];}else{echo '/';}?></td>
                <td>cpu max : <?php if($sensors[0]['client_cpu_max'] != null){echo $sensors[0]['client_cpu_max'];}else{echo '/';}?></td>
            </tr>
            <tr>
                <td>cpu start : <?php if($sensors[0]['app_cpu_start'] != null){echo $sensors[0]['app_cpu_start'];}else{echo '/';}?></td>
                <td>cpu start : <?php if($sensors[0]['db_cpu_start'] != null){echo $sensors[0]['db_cpu_start'];}else{echo '/';}?></td>
                <td>cpu start : <?php if($sensors[0]['client_cpu_start'] != null){echo $sensors[0]['client_cpu_start'];}else{echo '/';}?></td>
            </tr>
            <tr>
                <td>cpu end : <?php if($sensors[0]['app_cpu_end'] != null){echo $sensors[0]['app_cpu_end'];}else{echo '/';}?></td>
                <td>cpu end : <?php if($sensors[0]['db_cpu_end'] != null){echo $sensors[0]['db_cpu_end'];}else{echo '/';}?></td>
                <td>cpu end : <?php if($sensors[0]['client_cpu_end'] != null){echo $sensors[0]['client_cpu_end'];}else{echo '/';}?></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>
</div>