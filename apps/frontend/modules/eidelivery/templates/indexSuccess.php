 
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
<?php 
$searchBox=$url_tab;
$searchBox['deliverySearchForm']=$deliverySearchForm;
$searchBox['deliveryStates']= $deliveryStates;
$searchBox['deliveryAuthors'] = $deliveryAuthors;
$searchBox['deliveryTitles'] = $deliveryTitles;
?>
    <div class="row" id="eiDeliveryListBox"> 
        <?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_index' )) ?>
        <?php include_partial('searchBox',$searchBox) ?>
        <div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2><?php echo ei_icon('ei_list') ?> List </h2>
                <div class="panel-actions"> 
                </div>
            </div>
            <div class="panel-body">
                <?php
                $pagerMenu = $url_tab;
                $pagerMenu['current_page'] = $current_page;
                $pagerMenu['nb_pages'] = $nb_pages;
                $pagerMenu['nbEnr'] = $nbEnr;
                $pagerMenu['max_delivery_per_page'] = $max_delivery_per_page;
                $pagerMenu['searchDeliveryCriteria'] = $searchDeliveryCriteria;
                ?>
                <?php
                    if($pagerMenu['nbEnr'] >= 10)
                    {
                        include_partial('eidelivery/pagerMenu', $pagerMenu); 
                    }
                ?>
            </div>
            <div class="panel-body table-responsive" >
                <table class="table small-font   table-condensed table-striped dataTable" id="eiDeliveryListTable">
                    <thead>
                    <tr>
                        <th>  Id </th>
                        <th>    Title </th>
                        <th>   Author </th>
                        <th>   State </th>
                        <th>Description</th>
                        <th>Updated At</th>
                        <th>Delivery date</th> 
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($deliverys)>0): ?>
                        <?php foreach ($deliverys as $ei_delivery): ?>
                            <tr>
                                <td class="ei_delivery_id"><?php echo 'D'.$ei_delivery->getId() ?></td>
                                <td class="ei_delivery_name"> 
                                    <?php
                                    $getDeliverySubjects=$url_tab; 
                                    $getDeliverySubjects['delivery_id']=$ei_delivery->getId(); 
                                    ?>
                                    <a href="<?php echo url_for2('getDeliverySubjects',$getDeliverySubjects) ?>"
                                       class="tooltipObjTitle"   data-placement="top" data-toggle="tooltip"
                                        data-original-title="<?php echo $ei_delivery ?>">
                                        <?php echo MyFunction::troncatedText($ei_delivery , 50) ?>
                                    </a>
                                    
                                </td>
                                <td class="ei_delivery_assignment">
                                    <a href="#"
                                       class="tooltipObjTitle"   data-placement="top" data-toggle="tooltip"
                                        data-original-title="<?php echo $ei_delivery->getSfGuardUser()->getUsername() ?>"> 
                                        <?php echo MyFunction::troncatedText($ei_delivery->getSfGuardUser()->getUsername(),25) ?>
                                    </a>
                                </td>
                                <td class="ei_delivery_state">
                                    <?php $delState=$ei_delivery->getEiDeliveryState(); ?>
                                    <?php if(isset($delState)):   ?>
                                    <span style="background-color:<?php echo $delState->getColorCode() ?> " class="label">  <?php echo $delState->getName(); ?> </span>     
                                    <?php endif; ?> 
                                </td>
                                <td class="ei_delivery_desc">
                                    <a  class="popoverObjDesc" title="Description"  data-trigger="focus"  data-placement="top" data-toggle="popover" href="#"
                                    data-content="<?php echo $ei_delivery->getDescription() ?>"  >
                                   <?php echo MyFunction::troncatedText( $ei_delivery->getDescription(),100)   ?>
                                    </a>
                                </td>
                                <td class="ei_delivery_updated_at">  <?php echo $ei_delivery->getUpdatedAt(); ?> </td>
                                <td class="ei_delivery_date"><?php echo  $ei_delivery->getDeliveryDate() ; ?> </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <?php include_partial('eidelivery/pagerMenu', $pagerMenu); ?>
            </div>
        </div>
    </div>
