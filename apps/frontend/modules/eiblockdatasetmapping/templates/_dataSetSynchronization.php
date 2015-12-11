<div class="panel panel-default eiPanel">
    <div class="panel-heading"> 
        <h2><i class="fa fa-refresh"></i> Data Set's Synchronization</h2>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <table id="blockParametersMappingOut" class="table table-striped">
                    <thead>
                    <tr>
                        <th width="45%">Block Parameter</th>
                        <th width="5%"><i class="fa fa-arrow-right"></th>
                        <th width="45%">Data Set's Attribute</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /** @var EiBlockParam $param */
                    foreach( $blockParams as $key => $param ){
                        include_partial("eiblockdatasetmapping/lineBlockMapping", array(
                            "key" => $key,
                            "param" => $param,
                            'project_id' => $project_id,
                            'project_ref' => $project_ref,
                            'profile_name' => $profile_name,
                            'profile_id' => $profile_id,
                            'profile_ref' => $profile_ref,
                            'ei_scenario_id' => $ei_scenario_id,
                            'tree' => $tree,
                            "fleche" => '<i class="fa fa-arrow-right">',
                            'type' => EiBlockDataSetMapping::$TYPE_OUT
                        ));
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>