<?php if (isset($ei_delivery)): ?>
<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'delivery_id'=>$ei_delivery->getId() ); 
    ?>
<div class="row" id="subjectContent"> 
<div class="panel panel-default eiPanel" >
    <div class="panel-heading"> 
            <h2><strong><i class="fa fa-wrench"></i>Properties </strong> /   <?php echo ei_icon('ei_show') ?>  </h2> 
        <div class="panel-actions"> 
            <?php $delivery_edit=$url_tab;  $delivery_edit['action']='edit' ?>
                    <a id="editDelivery" class=" btn-default " href="<?php
                    echo url_for2('delivery_edit', $delivery_edit)  ?>"> 
                        <?php echo ei_icon('ei_edit') ?> 
                    </a> 
        </div>
    </div>
    <div class="panel-body">
        <div  class="row">
                <div class="col-lg-6 col-md-6 table-responsive">
                    <table class="table table-bordered table-striped dataTable"> 
                        <tbody> 
                            <tr>
                                <th>Title</th>
                                <td><?php echo $ei_delivery->getName() ?></td>
                            </tr>
                            <tr>
                                <th>Author</th>
                                <td><?php echo $ei_delivery->getSfGuardUser()->getUsername() ?></td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>
                                    <?php $delState=$ei_delivery->getEiDeliveryState(); ?>
                                    <?php if(isset($delState)):   ?>
                                    <span style="background-color:<?php echo $delState->getColorCode() ?> " class="label">  <?php echo $delState->getName(); ?> </span>     
                                    <?php endif; ?>  
                                </td>
                            </tr>
                        </tbody>
                    </table> 
                </div>
                <div class="col-lg-6 col-md-6 table-responsive">
                    <table class="table table-bordered table-striped dataTable"> 
                        <tbody> 
                            <tr>
                                <th>Delivery date </th>
                                <td>
                                    <?php  echo $ei_delivery->getDeliveryDate(); ?> 
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td> 
                                    <?php echo $ei_delivery->getCreatedAt()  ?> 
                                </td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>
                                    <?php   $ei_delivery->getUpdatedAt()  ?>  
                                </td>
                                
                            </tr>
                            
                        </tbody>
                    </table> 
                </div> 
            </div>    
    </div>
</div>
    <div class="panel panel-default eiPanel" id="deliveryContentDescription">
    <div class="panel-heading">
        <h2>
            <i class="fa fa-text-width "></i>
            <span class="break"></span>  Description 
        </h2>
        <div class="panel-actions">  
        </div>
    </div>
    <div class="panel-body">  
            <?php echo  $ei_delivery->getDescription() ?> 
    </div>
</div>
</div>
<?php endif; ?>