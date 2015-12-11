<?php
    $tree->importAssets();

    $treeID = $options["id"];
    $rootType = $options["types"]["root"];
    $nodeType = $options["types"]["node"];
    $attrType = isset($options["types"]["attr"]) ? $options["types"]["attr"]:null;
    $leafType = $options["types"]["leaf"];

    $nodeFormat = $options["formats"]["node"];
    $attrFormat = isset($options["formats"]["attr"]) ? $options["formats"]["attr"]:null;
    $leafFormat = $options["formats"]["leaf"];

    $actionRename = isset($options["actions"]["rename"]) ? $options["actions"]["rename"]:null;
    $actionNew = isset($options["actions"]["new"]) ? $options["actions"]["new"]:null;
    $actionRemove = isset($options["actions"]["remove"]) ? $options["actions"]["remove"]:null;
    $actionDragNDrop = isset($options["actions"]["dragndrop"]) ? $options["actions"]["dragndrop"]:null;

    $initJsTree = isset($options["init"]) ? $options["init"]->getRawValue():array();

    $styleMessages = isset($options["styleMessageResultat"]) ? $options["styleMessageResultat"]:1;
    $styleMessagesO = isset($options["styleMessageResultatObject"]) ? $options["styleMessageResultatObject"]:"";

    $authorizationsRename = isset($options["authorizations"]) && isset($options["authorizations"]["rename"]) ?
        $options["authorizations"]["rename"]->getRawValue():array("root", "node", "attr", "leaf");
    $authorizationsNew = isset($options["authorizations"]) && isset($options["authorizations"]["new"]) ?
        $options["authorizations"]["new"]->getRawValue():array("root", "node", "attr", "leaf");
    $authorizationsRemove = isset($options["authorizations"]) && isset($options["authorizations"]["remove"]) ?
        $options["authorizations"]["remove"]->getRawValue():array("root", "node", "attr", "leaf");
    $authorizationsDragNDrop = isset($options["authorizations"]) && isset($options["authorizations"]["dragndrop"]) ?
        $options["authorizations"]["dragndrop"]->getRawValue():array("root", "node", "attr", "leaf");

    $authorizedPlugins = array("html_data", "types", "state", "dnd", "contextmenu", "wholerow");

    if( count($authorizationsDragNDrop) == 0 ){
        $authorizedPlugins = MyFunction::array_delete($authorizedPlugins, "dnd");
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 cold-md-offset-1 col-xs-2 col-xs-offset-1">
            <strong>Tree Legend</strong>
        </div>

        <div class="col-md-2 cold-md-offset-1 col-xs-2 col-xs-offset-1">
            <img src="<?php echo $rootType["icon"] ?>" alt="" />&nbsp;&nbsp;<?php echo ucwords($rootType["name"]) ?>
        </div>

        <div class="col-md-2">
            <img src="<?php echo $nodeType["icon"] ?>" alt="" />&nbsp;&nbsp;<?php echo ucwords($nodeType["name"]) ?>
        </div>

        <?php if( isset($attrType) ): ?>
            <div class="col-md-2">
                <img src="<?php echo $attrType["icon"] ?>" alt="" />&nbsp;&nbsp;<?php echo ucwords($attrType["name"]) ?>
            </div>
        <?php endif; ?>

        <div class="col-md-2">
            <img src="<?php echo $leafType["icon"] ?>" alt="" />&nbsp;&nbsp;<?php echo ucwords($leafType["name"]) ?>
        </div>
    </div>
</div>

<div id="<?php echo $treeID ?>">
    <?php echo $html; ?>
</div>

<div id="remove_node_<?php echo $treeID ?>_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="intitule">Delete #{nodeType} "#{nodeName}"</h3>
            </div>
            <div class="modal-body modal-body-visible-overflow">
                You are about to delete #{nodeType} <strong>"#{nodeName}"</strong>.
                All its children will be deleted.<br /> Do you really want to delete "#{nodeName}" ?
            </div>
            <div class="modal-footer">
                <a href="#" id="btn_cancel_remove_node_<?php echo $treeID ?>" class="btn" data-dismiss="modal">Cancel</a>
                <a href="#" id="btn_remove_node_<?php echo $treeID ?>" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">

    if( typeof jsTreesOptions == "undefined" ){
        var jsTreesOptions = {};
    }

    jsTreesOptions["<?php echo $treeID ?>"] = {
        "id" : "<?php echo $treeID ?>",
        "treeType" : "editMode",
        "loaded": false,
        "baseID": "",
        "initTree": <?php echo json_encode($initJsTree); ?>,
        "styleMessageResultat": <?php echo json_encode($styleMessages); ?>,
        "styleMessageResultatObject": <?php echo json_encode($styleMessagesO); ?>,
        "types": {
            "root" : {
                "name" : "<?php echo $rootType["name"] ?>",
                "valid_children": ["node", "leaf"],
                "icon" : "<?php echo $rootType["icon"] ?>"
            },
            "node" : {
                "name" : "<?php echo $nodeType["name"] ?>",
                "valid_children": ["node", "attr", "leaf"],
                "icon" : "<?php echo $nodeType["icon"] ?>"
            },
            "attr" : {
                "name" : "<?php echo $attrType["name"] ?>",
                "valid_children": ["leaf"],
                "icon" : "<?php echo $attrType["icon"] ?>"
            },
            "leaf" : {
                "name" : "<?php echo $leafType["name"] ?>",
                "valid_children": [],
                "icon" : "<?php echo $leafType["icon"] ?>"
            }
        },
        authorizations: {
            "dragndrop" : <?php echo json_encode($authorizationsDragNDrop) ?>,
            "remove" : <?php echo json_encode($authorizationsRemove) ?>,
            "rename": <?php echo json_encode($authorizationsRename) ?>,
            "create": <?php echo json_encode($authorizationsNew) ?>
        },
        authorizedPlugins: <?php echo json_encode($authorizedPlugins); ?>,
        actions: {
            <?php if(isset($actionDragNDrop)): ?>
            "dragndrop" : {
                "url" : "<?php echo url_for2($actionDragNDrop["route"], $actionDragNDrop["parameters"]->getRawValue()) ?>"
            },
            <?php endif; ?>
            <?php if(isset($actionRemove)): ?>
            "delete" : {
                "url" : "<?php echo url_for2($actionRemove["route"], $actionRemove["parameters"]->getRawValue()) ?>",
                "target": "<?php echo $actionRemove["target"] ?>"
            },
            <?php endif; ?>
            <?php if(isset($actionRename)): ?>
            "rename": {
                "url" : "<?php echo url_for2($actionRename["route"], $actionRename["parameters"]->getRawValue()) ?>",
                "target" : "<?php echo $actionRename["target"] ?>"
            },
            <?php endif; ?>
            <?php if(isset($actionNew)): ?>
            "create": {
                "url" : "<?php echo url_for2($actionNew["route"], $actionNew["parameters"]->getRawValue()) ?>",
                "target" : "<?php echo $actionNew["target"] ?>"
            }
            <?php endif; ?>
        },
        "root" : "<?php echo $rootType["name"] ?>",
        "node" : "<?php echo $nodeType["name"] ?>",
        "leaf" : "<?php echo $leafType["name"] ?>"
    };

    var lockRename = false;
    var lockRemove = false;
    var nodeFormFormat = "<?php echo $nodeFormat ?>";
    var leafFormFormat = "<?php echo $leafFormat ?>";
</script>

<?php

if( $sf_request->isXmlHttpRequest() ){
    javascript_tag();
    echo file_get_contents("js/jstree/js/jstree.min.js");
    end_javascript_tag();

    javascript_tag();
    echo file_get_contents("js/treeManagement/jstree/common.js");
    end_javascript_tag();

    javascript_tag();
    echo file_get_contents("js/treeManagement/jstree/editMode/editMode.js");
    end_javascript_tag();

    echo "<style type='text/css'>";
    echo file_get_contents("css/jstree/themes/default/style.min.css");
    echo file_get_contents("css/jstree/custom/custom.css");
    echo "</style>";
}
else{
    $tree->importAssets();
}
?>