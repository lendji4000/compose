<?php
/**
 * Template de mise en forme d'une fonction au sein d'une version.
 * 
 * @author Grégory Elhaimer 
 * 
 * @param array             $eiFonction         Le formulaire de fonction a afficher  
 * 
 */
?>


<?php
    $paramsForUrl = $paramsForUrl->getRawValue();
    $params = $paramsForUrl;
    $params['ei_fonction_id'] = $eiFonction->getObject()->getId();
    $ei_version_id=$params['ei_version_id'];
    unset($params['ei_version_id'], $params['ei_version_structure_id']);
    $tab_properties = $params;
    $tab_properties['action'] = 'show';
    $tab_properties['function_id'] = $eiFonction->getObject()->getFunctionId();
    $tab_properties['function_ref'] = $eiFonction->getObject()->getFunctionRef();
    unset($tab_properties['default_notice_lang'], $tab_properties['ei_fonction_id']);
    $url_properties = url_for2('showFunctionContent', $tab_properties);
    $url = url_for2("updateFonction", $params);
?> 

<form action="<?php echo $url ?>" method="POST" class="fonction_form">
    <div class="panel panel-default eiPanel fonction bordered">
        <fieldset class="hiddenFields">
            <?php echo $eiFonction->renderHiddenFields(); ?> 
        </fieldset>
        <div class="panel-heading ">
            <h2 class="entete_fonction">  
                <?php echo ei_icon('ei_function') ?> 
                <a class="openFunctionInScript"  href="<?php echo $url_properties ?>" target="_blank">
                    <?php echo ($eiFonction->getObject()->getName()!=''? $eiFonction->getObject()->getName() : 'undefined ...' ); ?> 
                    <input class="openNodeUri" type="hidden" itemref="<?php echo url_for2('ei_tree_open_node',array(
                        'ei_version_id' => $ei_version_id,
                        'project_id'=>$params['project_id'],
                        'project_ref' => $params['project_ref'],
                        'profile_id' => $params['profile_id'],
                        'profile_ref' => $params['profile_ref'],
                        'profile_name' => $params['profile_name'],
                        'obj_id' =>$eiFonction->getObject()->getFunctionId(),
                        'ref_obj'=> $eiFonction->getObject()->getFunctionRef(),
                        'tree_type' => 'Function'))?> "/> 
                  </a> 
            </h2>
            <div class="panel-actions">
                <?php                
                $paramLink = $params;
                unset($paramLink['ei_fonction_id']);
                $paramLink['function_ref'] = $eiFonction->getObject()->getFunctionRef();
                $paramLink['function_id'] = $eiFonction->getObject()->getFunctionId();
                if (isset($obj) && $obj != null):
                    $subsFonct = $obj->getSubjectFunctions();  //var_dump($subsFonct) 
                    if ((count($subsFonct) > 0) && isset($subsFonct[0]['sf_subject_id'])):
                        $paramLink['exist_link'] = 1;
                        if ($subsFonct[0]['sf_automate']): $paramLink['automate'] = $subsFonct[0]['sf_automate'];
                        endif;
                    endif;
                endif;?>
                <?php include_partial('subjectfunction/link',$paramLink) ?>
                <a href="<?php echo url_for2('showFunctionNotice',
                        array('ei_version_id'=>$ei_version_id,
                              'ei_fonction_id'=> $params['ei_fonction_id'],
                              'lang' => $params['default_notice_lang'],
                              'profile_id' => $params['profile_id'],
                              'profile_ref' => $params['profile_ref'],
                              'profile_name' => $params['profile_name']) ) ?>" 
                              class="  btn-link showFunctionNotice" title="Notice"> 
                    <?php echo ei_icon('ei_notice' ) ?>
                </a> 
                 <?php if(isset($is_editable) && $is_editable):  ?>
                <a class=" btn-link  fonction_delete"
                   href="<?php echo url_for2('deleteFonction', $params)?> ">
                    <?php echo ei_icon('ei_delete') ?>
                </a>
                <?php endif; ?>
                <a class="btn-minimize" href="#"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div> 
        <div class="panel-body ">
                <div class="detail_fonction">
                    <?php if(isset($is_editable) && $is_editable): //Si on est sur le package courant ?> 
                    <?php  echo $eiFonction['description']->render()  ?>
                    <?php else: ?>
                    <?php echo $eiFonction['description']->getValue()  ?>
                    <?php endif; ?>
                </div> 
            <!--si la fonction possède des formulaires, alors pour chacun d'entre eux,-->
            <?php if (isset($eiFonction['params']) && count($eiFonction['params']) > 0): ?>
                <div class="params_fonction ">

                    <div>In parameters</div>

                    <!--<table >--> 
                        <?php $params = $eiFonction->getObject()->getEiParams();?>

                        <?php foreach ($eiFonction['params'] as $p => $param): ?>

                        <?php
                            $content = "<p><h5>Name : </i></h5><div>".htmlentities($params[$p]->getName())."</div><br/>";
                            $content .= "<p><h5>Description : </h5></p><div>".htmlentities($params[$p]->getDescription())."</div><br/>";
                            $content .= "<p><h5>Default value : </h5></p><div>".htmlentities($params[$p]->getDefaultValue())."</div>"
                        ?>
                        <div class="row param_fonction"  >
                            <div class="col-lg-4 col-md-4 col-sm-5">
                                <label> 
                                    <i class="fa fa-info-circle infos_param" data-trigger="hover" data-html="true" data-title="<?php echo $params[$p]->getName()?>" data-content="<?php echo $content ?>"></i> 
                                    <span><?php echo MyFunction::troncatedText($params[$p]->getDescription(),30) ?></span>
                                </label>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                <i class="fa fa-arrow-left"></i>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6">
                                <?php if(isset($is_editable) && $is_editable):  //Si on est sur le package courant ?> 
                                <?php echo $param['valeur']->render(); ?>
                                <?php else: ?>
                                <?php  echo $param['valeur']->getValue();  ?>
                                <?php endif; ?>
                            </div>
                        </div> 
                        <?php endforeach; ?>
                    <!--</table>-->
                </div>
            <?php endif; ?>

            <?php if( isset($eiFonction["mappings"]) && count($eiFonction["mappings"]) > 0 ): ?>
                <div class="params_out_fonction_sync">
                    <div>Out parameters synchronization</div>

                    <?php $mappings = $eiFonction->getObject()->getEiFunctionMapping(); ?>

                    <?php

                    /** @var EiParamBlockFunctionMappingForm $mappingForm */
                    foreach( $eiFonction["mappings"] as $m => $mappingForm )
                    {
                        /** @var EiParamBlockFunctionMapping $mapping */
                        $mapping = $mappings[$m];

                        /** @var EiFunctionHasParam $param */
                        $param = $mapping->getEiFunctionParamMapping();

                        $content = "<p><h5>Name : </i></h5><div>".htmlentities($param->getName())."</div><br/>";
                        $content.= "<p><h5>Description : </h5></p><div>".htmlentities($param->getDescription())."</div><br/>";

                        ?>
                        <div class="row param_out_fonction_sync">
                            <div class="col-lg-4 col-md-4 col-sm-5">
                                <label>
                                    <i class="fa fa-info-circle infos_param" data-trigger="hover" data-html="true" data-title="<?php echo $param->getName()?>" data-content="<?php echo $content ?>"></i>
                                    <span><?php echo MyFunction::troncatedText($param->getDescription(),30) ?></span>
                                </label>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                <i class="fa fa-arrow-right"></i>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6">
                                <?php echo $mappingForm["ei_param_block_id"]->render(); ?>
                            </div>
                        </div>

                    <?php
                    }

                    ?>
                </div>
            <?php endif; ?>

        </div>      
    </div>   
</form>