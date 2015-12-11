<?php if(isset($project_id) && isset($project_ref)   && isset($profile_id) && isset($profile_ref) && isset($profile_name) && isset($ex)): ?>
<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref);

    ?>
<tr  class="subjectFunctionLine odd" role="row">
                    <td style="width: 5%"><?php echo $ex['t_id'] ?></td>
                    <td style="width: 27%">
                        <?php if(isset($ex['t_path']) && count($arrayPath=json_decode(html_entity_decode($ex['t_path']),true))>0): ?>
                        <?php //$arrayPath=  json_decode(html_entity_decode($ex['tr_path']),true);  ?>
                        <ol class="breadcrumb"> 
                        <?php foreach($arrayPath as $item): ?>
                        <li>
                            <?php if($item['type']=="View"): ?>
                            <?php echo ei_icon('ei_folder',null,'ei-folder').'  '.$item['name']; endif;?>
                            <?php if($item['type']=="Function"):?> 
                            <?php  //Url de gestions des statistiques de fonction
                            $itemReportsUri = $url_tab; $itemReportsUri['function_id']=$item['obj_id'];$itemReportsUri['function_ref']=$item['ref_obj'];
                            $itemReportsUri['action']='statistics'; ?>
                            <a href="<?php echo url_for2('functionActions',$itemReportsUri  ) ?>" title="Function reports..."  target="_blank">
                                <?php echo ei_icon('ei_function'); ?>  
                                <?php echo $item['name'] ?> 
                            </a>
                           <?php endif;?> 
                        </li> 
                        <?php endforeach; ?>  
                        </ol>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php  //Url de gestions des statistiques de fonction
                            $tab_stats = $url_tab; $tab_stats['function_id']=$ex['t_obj_id'];$tab_stats['function_ref']=$ex['t_ref_obj'];
                            //$tab_stats['execution_id']=$campaign_execution_id;
                            $tab_stats['action']='statistics'; ?>
                        <a href="<?php echo url_for2('functionActions',$tab_stats  ) ?>" title="Function reports..."  target="_blank">
                                <?php echo ei_icon('ei_function') ?> <?php echo $ex['t_name'] ?>
                             </a>
                    </td>
                    <td><?php echo $ex['f_criticity'] ?></td>
                    <td><?php echo $ex['nbCamp'] ?></td>
                    <td><?php echo $ex['nbScenario'] ?></td>
                    <td><?php echo $ex['last_ex'] ?></td> 
                    <td>
                        <?php $is_automate=false; ?> 
                    <!-- Suivant que le lien intervention-fonction ait été crée automatiquement ou non, on autorise la suppression du lien-->
                    <?php if( !((isset($ex['sf_automate']) && $ex['sf_automate']) ||  ($ex['s_id']!=null))): ?>
                    <a class="btn btn-danger btn-sm removeFunctionFormSubject " href="<?php
                    echo url_for2('subjectFunction', array(
                        "project_id" => $project_id,
                        "project_ref" => $project_ref,
                        "function_id" => $ex['t_obj_id'],
                        "function_ref" => $ex['t_ref_obj'],
                        "subject_id" => isset($ex['s2_id'])?$ex['s2_id']:$ex['s_id'],
                        "profile_id" => $profile_id,
                        "profile_ref" => $profile_ref,
                        "profile_name" => $profile_name,
                        "action" => "removeFunction"))
                    ?>">
                        <i class="fa fa-times-circle-o fa-lg "></i>
                    </a>
                    <?php endif; ?> 
                    </td>
                </tr>  
<?php endif; ?>
