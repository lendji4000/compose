<?php
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref);
?>
<?php if (isset($openDeliveries) && count($openDeliveries) > 0): ?>
    <?php foreach ($openDeliveries as $i=> $del): ?>
        <tr>
            <td> 
                <a href="#" class=" popoverObjDesc" title="<?php echo 'D' . $del->getId() ?>"
                   data-trigger="focus"  data-placement="bottom" data-toggle="popover" data-html="true"
                   data-content="<div>  <p><small>ID : </small> <?php echo 'D' . $del->getId() ?> </p>
                   <p><small>BUGS : </small>  <?php echo $del->nbSub ?> </p>
                   <p><small>CLOSE BUGS  :</small> <?php echo $del->nbSubOpen ?>  </div>"  > 
                    <span class="text">   
                        <strong><?php echo 'D' . $del->getId() ?></strong> 
                    </span>
                </a>
            </td>
            <td>
                <?php
                $getDeliverySubjects = $url_tab;
                    $getDeliverySubjects['delivery_id']=$del->getId(); 
                ?>
                <a href="<?php echo url_for2('getDeliverySubjects', $getDeliverySubjects) ?>"
                   class="tooltipObjTitle"   data-placement="right" data-toggle="tooltip"
                   data-original-title="<?php echo $del ?>">
                       <?php echo MyFunction::troncatedText($del, 50) ?>
                </a>
            </td>
            <td>
                <a href="#"
                   class="tooltipObjTitle"   data-placement="top" data-toggle="tooltip"
                   data-original-title="<?php echo $del->getSfGuardUser()->getUsername() ?>"> 
                       <?php echo MyFunction::troncatedText($del->getSfGuardUser()->getUsername(), 25) ?>
                </a>
            </td>
            <td>
                <div class="row">
                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                       <div class="progress thin">
                            <?php $perc = ($del->nbSub != 0 ? $del->nbSubOpen / $del->nbSub : 0) * 100 ?>
                            <?php if ($perc < 25) : $progressClass = 'progress-bar-danger';
                            endif; ?>
                            <?php if ($perc >= 25 && $perc < 50) : $progressClass = 'progress-bar-warning';
                            endif;  ?>
                            <?php if ($perc >= 50 && $perc < 75) : $progressClass = 'progress-bar-info';
                            endif; ?>
                            <?php if (($perc >= 75 && $perc < 100) || $del->nbSub == 0) : $progressClass = 'progress-bar-success';
                                $perc = 100;
                            endif; ?>
                            <div class="progress-bar  <?php echo (isset($progressClass)?$progressClass:'') ?>" role="progressbar" 
                                 aria-valuenow="<?php echo $perc ?>" aria-valuemin="0" 
                                 aria-valuemax="100" style="width: <?php echo $perc ?>%">
                                <span class="sr-only">
                            <?php echo $perc ?>% Complete (success)</span>
                            </div> 
                        </div> 
                    </div> 
                    <div col-lg-1 col-md-1 col-sm-1 col-xs-1>
                        <a href="#" class=" popoverObjDesc " title="<?php echo 'D' . $del->getId() ?>"
                            data-trigger="focus"  data-placement="top" data-toggle="popover" data-html="true"
                            data-content="<div>  <p><small>ID : </small> <?php echo 'D' . $del->getId() ?> </p>
                            <p><small>BUGS : </small>  <?php echo $del->nbSub ?> </p>
                            <p><small>CLOSE BUGS  :</small> <?php echo $del->nbSubOpen ?>  </div>"  >  
                            <i class="fa fa-info"></i> 
                         </a>
                    </div>
                </div>
                 
            </td>
            <td class="  text-info"> 
                <?php echo  $del->getUpdatedAt() ?> 
            </td>
            <td class="  text-info"> 
                <?php  echo $del->getDeliveryDate(); ?> 
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?> 		