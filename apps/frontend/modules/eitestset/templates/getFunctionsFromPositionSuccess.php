<?php $prefix = 'http://' . $sf_request->getHost() ?>
<?php
// Début mesure.
$chronometre5->lancerChrono($keyword."GENERATION XML VUE");
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<tests>
    <?php
    if (isset($functions) && count($functions) > 0):
        echo '<chemin>' . $functions[0]['xpath'] . '</chemin>'
        ?>
    <?php foreach ($functions as $function): ?>

            <test id="<?php echo $function['id'] ?>" func-ref="<?php echo $function['function_ref']; ?>" func-id="<?php echo $function['function_id']; ?>">
                <parameters>
                    <user>
                        <?php echo '<fonction-' . $function['function_id'] . '_' . $function['function_ref'] . ">" ?>
                        <?php
                            foreach ($function['params'] as $param) {
                                echo "<parameter name=\"".$param["name"]."\">";
                                echo "  <name>".$param['name']."</name>";
                                echo "  <desc>".$param['desc']."</desc>";
                                echo "  <value>".$param['valeur']."</value>";
                                echo "</parameter>";
                            }
                        ?>
                        <?php echo '</fonction-' . $function['function_id'] . '_' . $function['function_ref'] . ">" ?>
                    </user>
                    <in>
                        <?php echo '<fonction-' . $function['function_id'] . '_' . $function['function_ref'] . ">" ?>
                        <?php
                        foreach ($params[$function['id']] as $param) {
                            if( $param["param_type"] == "IN" ){
                                echo "<parameter name=\"".$param["name"]."\">";
                                echo "  <name>".$param['name']."</name>";
                                echo "  <desc>".$param['description']."</desc>";
                                echo "</parameter>";
                            }
                        }
                        ?>
                        <?php echo '</fonction-' . $function['function_id'] . '_' . $function['function_ref'] . ">" ?>
                    </in>
                    <out>
                        <?php echo '<fonction-' . $function['function_id'] . '_' . $function['function_ref'] . ">" ?>
                        <?php
                        foreach ($params[$function['id']] as $param) {
                            if( $param["param_type"] == "OUT" )
                            {
                                echo "<parameter name=\"".$param["name"]."\">";
                                echo "  <name>".$param['name']."</name>";
                                echo "  <desc>".$param['description']."</desc>";
                                echo "</parameter>";
                            }
                        }
                        ?>
                        <?php echo '</fonction-' . $function['function_id'] . '_' . $function['function_ref'] . ">" ?>
                    </out>
                </parameters>
                
                <url_xsl>
                    <?php
                    echo $prefix . url_for2('fonction_downloadxsl', array(
                        'token' => $sf_user->getEiUser()->getTokenApi(),
                        'profile_id' => $profile_id,
                        'profile_ref' => $profile_ref,
                        'function_ref' => $function['function_ref'],
                        'function_id' => $function['function_id'],
                        'sf_format' => 'xml'
                    ));
                    ?>
                </url_xsl>
                <nom><?php echo $function['name'] ?></nom>
                <position><?php echo $function['position'] ?></position>
                <status><?php echo $function['status']  ?></status>
                <url_compose> <?php echo 'http://'.sfConfig::get('project_system_uri').url_for2('projet_edit_eiversion', array(
                    'profile_id'   => $profile_id,
                    'profile_ref'  => $profile_ref,
                    'profile_name' => $ei_profile->getName(),
                    'project_id'   =>  $ei_project->getProjectId(),
                    'project_ref'  => $ei_project->getRefId(),
                    'ei_scenario_id'=> $ei_scenario->getId(),
                    'ei_version_id' => $ei_version->getId(),
                    'action' => "edit"
                )) ?> </url_compose>
                <url_script> 
                <?php if(isset($defPack) && $defPack!=null): ?>
                 <?php echo 'http://'.sfConfig::get('project_prefix_path').
                         'Show/Script/Content/For/Profile/'.$profile_id.'/'.$profile_ref.'/Ticket/'.
                         $defPack->getTicketId().'/'.$defPack->getTicketRef().'/Project/'.
                        $ei_project->getProjectId().'/'.$ei_project->getRefId().'/direct/access/function/'.
                        $function['function_id'].'/'.$function['function_ref']; ?> 
                    <?php endif; ?>
                </url_script>
            </test>

        <?php endforeach; ?>

<?php endif; ?>
</tests>
<?php
// Fin mesure.
$chronometre5->arreterEtAfficherChrono();
?>