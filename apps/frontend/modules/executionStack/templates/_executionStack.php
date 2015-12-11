<?php
    $url = url_for2("get_user_execution_stack", array(
        "project_ref" => $project_ref,
        "project_id" => $project_id,
        "profile_ref" => $profile_ref,
        "profile_id" => $profile_id,
        "profile_name" => $profile_name
    ));
?>
<div id="executionStackPanel" data-url="<?php echo $url; ?>">
    <h2>
        <?php ei_icon("ei_execution_stack"); ?>
        Execution Stack
        <a href="#" class="pull-right" title="Close panel" id="btnCloseExecutionStackPanel"><i class="fa fa-2x fa-caret-square-o-right"></i></a>
    </h2>

    <div class="content">
        <?php include_component("executionStack", "getList"); ?>
    </div>

    <p class="text-center">
        <img id="loaderExecutionStack" src="/images/icones/ajax-loader-transparent.gif" alt="Loading..." />
    </p>
</div>