<div id="deliveryProcess">
    
<div class="panel panel-default eiPanel" id="deliveryProcessTitle">
    <div class="panel-heading">
        <h2>
            <i class="fa fa-text-width "></i>
            <span class="break"></span>  Delivery process 
        </h2>
        <div class="panel-actions">  
        </div>
    </div>
    
</div>

    <div class="row">
        <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2">
            
        </div>
        <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10">
            <?php if(isset($delivery_process_lines) && count($delivery_process_lines)>0): ?>
            <?php foreach($delivery_process_lines as $delivery_process): ?>
            <div class="panel panel-default eiPanel processLine">
                <div class="panel-heading">
                    <h2>
                        <?php echo ei_icon('ei_subject') ?>
                        <span class="break"></span>  <?php echo "S_".$delivery_process['subject_id'].'    /   ' ?></strong> <?php echo $delivery_process['subject_name'] ?>
                    </h2>
                    <div class="panel-actions">  
                    </div>
                </div>
                <div class="panel-body">   
                        <p><?php echo  html_entity_decode($delivery_process['sm_migration'], ENT_QUOTES, "UTF-8") ?></p>   
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>    

</div>