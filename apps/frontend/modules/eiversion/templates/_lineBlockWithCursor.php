<?php
/** @var EiVersionStructure $child */
include_partial('eiversion/lineBlock', array(
    'child' => $child,
    'project_ref' => $paramsForUrl["project_ref"],
    'project_id' => $paramsForUrl["project_id"],
    'profile_ref' => $paramsForUrl["profile_ref"],
    'profile_id' => $paramsForUrl["profile_id"],
    'profile_name' => $paramsForUrl["profile_name"],
    'ei_scenario_id' => $child->getEiVersion()->getEiScenarioId(),
    'ei_version_id' => $paramsForUrl["ei_version_id"]
));
?>
<?php include_partial('eiversion/curseurRow', array('paramsUrl' => $paramsForUrl, 'insert_after' => $child->getId())) ?>