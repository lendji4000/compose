<?php
    $tree->importAssets();

    $objects = $options["objects"];
    $baseID = $options["baseId"];
    $treeID = $options["id"];

    $rootType = $options["types"]["root"];
    $nodeType = $options["types"]["node"];
    $leafType = $options["types"]["leaf"];

    $nodeFormat = $options["formats"]["node"];
    $leafFormat = $options["formats"]["leaf"];

    $actionSelect = $options["actions"]["select"];
    $actionDoSelect = $options["actions"]["doSelect"];

    $urlSelect = str_replace($actionSelect["target"], $objects->get($key)->getId(), urldecode(url_for2($actionSelect["route"], $actionSelect["parameters"]->getRawValue())));
    $urlDoSelect = str_replace($actionDoSelect["target"], $objects->get($key)->getId(), urldecode(url_for2($actionDoSelect["route"], $actionDoSelect["parameters"]->getRawValue())));
?>

<a href="<?php echo $urlSelect ?>" data-id="<?php echo $objects->get($key)->getId() ?>" data-name="<?php echo $objects->get($key)->getName() ?>" class="btn btn-select-param-mapping">
    <?php echo ei_icon('ei_search') ?>
</a>

<div id="select_data_set_mapping_modal_<?php echo $treeID ?>" data-tree-id="<?php echo $treeID ?>" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="intitule">Select data set's attribute to map with #{name}</h3>
            </div>
            <div class="modal-body  modal-body-visible-overflow">
                <div id="<?php echo $treeID ?>">
                    <?php echo $html; ?>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-cancel" data-dismiss="modal">Cancel</a>
                <a href="#" class="btn btn-success btn-confirm-choice">Select</a>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">

    if( typeof jsTreesOptions == "undefined" ){
        var jsTreesOptions = {};
    }

    jsTreesOptions["<?php echo $treeID ?>"] = {
        "treeType" : "select",
        "baseID": "<?php echo $baseID . "_" ?>",
        "types": {
            "root" : {
                "name" : "<?php echo $rootType["name"] ?>",
                "valid_children": ["node", "leaf"],
                "icon" : "<?php echo $rootType["icon"] ?>"
            },
            "node" : {
                "name" : "<?php echo $nodeType["name"] ?>",
                "valid_children": ["node","leaf"],
                "icon" : "<?php echo $nodeType["icon"] ?>"
            },
            "leaf" : {
                "name" : "<?php echo $leafType["name"] ?>",
                "valid_children": [],
                "icon" : "<?php echo $leafType["icon"] ?>"
            }
        },
        selected: <?php echo json_encode($options["selected"]) ?>,
        actions: {
            "select" : {
                "url" : "<?php echo $urlSelect ?>"
            },
            "doSelect" : {
                "url" : "<?php echo $urlDoSelect ?>"
            }
        },
        "root" : "<?php echo $rootType["name"] ?>",
        "node" : "<?php echo $nodeType["name"] ?>",
        "leaf" : "<?php echo $leafType["name"] ?>"
    };
</script>