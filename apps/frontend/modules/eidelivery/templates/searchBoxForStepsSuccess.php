<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name )?> 
<?php 
$searchBox=$url_tab;
$searchBox['deliverySearchForm']=$deliverySearchForm;
$searchBox['deliveryStates']= $deliveryStates;
$searchBox['deliveryAuthors'] = $deliveryAuthors;
$searchBox['deliveryTitles'] = $deliveryTitles;
$searchBox['is_ajax_request'] = true; ?>
<div class="row">   
    <?php include_partial('searchBox', $searchBox)  ?>  

    <div id="deliveryList" class="table-responsive ">
        <table class="table small-font table-condensed table-striped dataTable" id="eiDeliveryListTable">
            <thead>
                <tr>
                    <th>  Id </th>
                    <th>    Title </th>
                    <th>   Author </th>
                    <th>   Delivery state </th>  
                    <th>Description</th>
                    <th>Updated at</th>
                    <th>Delivery date</th> 

                </tr> 
            </thead>
            <tbody>
                <?php if (count($deliverys) > 0): ?>
                    <?php foreach ($deliverys as $ei_delivery): ?>
                        <tr class="ei_delivery_id">
                            <td><?php echo 'D' . $ei_delivery->getId() ?>
                                <input type="hidden" class="delivery_id" value="<?php echo $ei_delivery->getId() ?>"/>
                            </td> 
                            <td class="ei_delivery_name"> 
                                <?php 
                                $showDeliveryCampaigns=$url_tab;
                                $showDeliveryCampaigns['delivery_id']=$ei_delivery->getId();  ?>
                              <a href="<?php echo url_for2('showDeliveryCampaigns', $showDeliveryCampaigns) ?>"
                                 class=" select_del_for_steps chooseDel" >  
                              <?php   echo $ei_delivery->getName()  ?> 
                              </a> 
                            </td>
                            <td class="ei_delivery_assignment"><?php echo $ei_delivery->getSfGuardUser()->getUsername() ?></td>
                            <td class="ei_delivery_state"><?php echo $ei_delivery->getEiDeliveryState()->getName() ?></td> 
                            <td class="ei_delivery_desc"><?php echo $ei_delivery->getDescription() ?></td>  
                            <td class="ei_delivery_updated_at">  <?php echo date('Y-m-d', strtotime($ei_delivery->getUpdatedAt())); ?> </td>
                            <td class="ei_delivery_date"><?php echo $ei_delivery->getDeliveryDate() ?></td>
                        </tr>
                    <?php endforeach; ?> 
                <?php endif; ?> 
            </tbody> 
        </table>
        <?php 
        $pagerMenu=$url_tab;
        $pagerMenu['current_page']=$current_page; 
        $pagerMenu['nb_pages']=$nb_pages; 
        $pagerMenu['nbEnr']=$nbEnr; 
        $pagerMenu['max_delivery_per_page']=$max_delivery_per_page; 
        $pagerMenu['searchDeliveryCriteria']=$searchDeliveryCriteria;
        $pagerMenu['is_ajax_request']=true;?>
        <?php  include_partial('eidelivery/pagerMenu',$pagerMenu); ?>  
    </div>
</div>
