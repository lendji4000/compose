<?php
$treeID = $options["id"];

$rootType = $options["types"]["root"];
$nodeType = $options["types"]["node"];
$leafType = $options["types"]["leaf"];

$nodeFormat = $options["formats"]["node"];
$leafFormat = $options["formats"]["leaf"];

$selected = $options["selected"];
?>

<script type="application/javascript">

    if( typeof jsTreesOptions == "undefined" ){
        var jsTreesOptions = {};
    }

    jsTreesOptions["<?php echo $treeID ?>"] = {
        "treeType" : "selectInHiddenInput",
        "loaded": false,
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
        "target": "<?php echo $options["inputTarget"] ?>",
        "selected" : "<?php echo $selected ?>",
        "root" : "<?php echo $rootType["name"] ?>",
        "node" : "<?php echo $nodeType["name"] ?>",
        "leaf" : "<?php echo $leafType["name"] ?>"
    };
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
    echo file_get_contents("js/treeManagement/jstree/selectInHiddenInputMode/selectInHiddenInputMode.js");
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

<div id="<?php echo $treeID ?>">
    <?php echo $html; ?>
</div>