<?php
$server = sfConfig::get("app_plugin_ide_plateforme");
$scriptPath = sfConfig::get("project_prefix_path");
$vOptimal = sfConfig::get("app_plugin_ide_version_optimal");
$vRequired = sfConfig::get("app_plugin_ide_version_required");
$fDir = sfConfig::get("app_plugin_ide_download_dir");
$fPattern = sfConfig::get("app_plugin_ide_download_pattern");
$fKey = sfConfig::get("app_plugin_ide_download_key");

$version = array(
    "version" => array(
        "required" => $vRequired,
        "optimal" => $vOptimal,
        "source" => $server.$fDir.str_replace($fKey, $vOptimal, $fPattern)
    )
);

if( isset($projets) && $projets->getFirst() )
{
    $return = $projets->toArray()->getRawValue();
    $return = array_merge($version,$return);
}
else
{
    $return = array(array("erreur" => "e"));
}

echo json_encode($return);
?>