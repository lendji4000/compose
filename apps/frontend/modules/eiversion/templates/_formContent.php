<?php
/**
 * Paramètres d'entrées du partial:
 * 
 * @author Grégory Elhaimer
 * @param $version  Le formulaire de version sous sa forme d'objet
 * @param $form     Le formulaire de version sous sa forme de tableau
 * @param $chemin   un tableau où chaque index contient la position du formulaire conteneur. 
 *                  On obtient ainsi un chemin jusqu'à l'élément à ajouter.
 */
$selected= true;
$paramsForUrl = $paramsForUrl->getRawValue();
$paramsForUrlFct = $paramsForUrl;
//unset($paramsForUrlFct['profile_name'], $paramsForUrlFct['profile_ref'], $paramsForUrlFct['profile_id'], $paramsForUrlFct['ei_scenario_id']);
unset($paramsForUrlFct['ei_scenario_id']);

$paramsUrlAjoutElem = array(
    'ei_version_id' => $ei_version_id, 
    'ei_version_structure_id' => $ei_version_structure_id, 
    'project_id' => $paramsForUrl['project_id'], 
    'project_ref' => $paramsForUrl['project_ref'],
    'profile_id' => $paramsForUrl['profile_id'], 
    'profile_ref' => $paramsForUrl['profile_ref'],
    'profile_name' => $paramsForUrl['profile_name'])
;   
?>
<!-- Contenu de la version, comprenant fonctions et sous versions -->
<div class="content_version" >
    <?php
        include_partial('eiversion/curseurRow', array(
                        'paramsUrl' => $paramsUrlAjoutElem,
                        'is_selected' => !(count($children) > 0)));
        if (count($children) > 0):
            foreach ($children as $c => $child): 

                $selected = ($c == count($children)-1);
            
                if($child->isEiFonction()):
                    $paramsForUrl['ei_fonction_id'] = $child->getEiFonctionId();
                    if(count($fonctions)>0):  
                        foreach($fonctions as $fonction):
                        if($fonction['id']==$paramsForUrl['ei_fonction_id']):  $obj=$fonction; endif;
                        endforeach;
                    endif;
                    include_partial('eifonction/formContent', array(
                        'eiFonction' => $fonctionsForms[$c],
                        'paramsForUrl' => $paramsForUrlFct,
                        'obj' => (isset($obj)?$obj:null),
                        'is_editable'=> (isset($is_editable) && $is_editable)?true:false
                        )); 
                
                else:
                    include_partial("eiversion/lineBlock", array(
                        'child' => $child,
                        'project_ref' => $paramsForUrl["project_ref"],
                        'project_id' => $paramsForUrl["project_id"],
                        'profile_ref' => $paramsForUrl["profile_ref"],
                        'profile_id' => $paramsForUrl["profile_id"],
                        'profile_name' => $paramsForUrl["profile_name"],
                        'ei_scenario_id' => $paramsForUrl["ei_scenario_id"],
                        'ei_version_id' => $ei_version_id,
                        'is_editable'=> (isset($is_editable) && $is_editable)?true:false
                    ));
                    
                      
                endif;
                $paramsUrlAjoutElem['insert_after'] = $child->getId();
                include_partial('eiversion/curseurRow', array('paramsUrl' => $paramsUrlAjoutElem, 'is_selected' => $selected));

            endforeach;
        endif;
    ?>
</div>

<script type="text/javascript">
    ei_block_params = <?php echo html_entity_decode($ei_block_parameters); ?>;
</script>
    