<!--Cette page permet de mieux définir le menu de pagination des Livraisons.
Elle dépend du projet et des variables de pagination
-->
<?php if (isset($current_page) && isset($nb_pages) && isset($project_id) && isset($project_ref) ): ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name );
 $loadDelForStepsLink= ((isset($is_ajax_request) && $is_ajax_request)? 'loadDelForStepsLink':'') ?> 
<!-- Ajout des critères éventuels de recherche -->
<?php if(isset($searchDeliveryCriteria)): ?>
<?php 
if (isset($searchDeliveryCriteria['title'])):
    $url_tab['title'] = $searchDeliveryCriteria['title'];
endif;
if (isset($searchDeliveryCriteria['author'])): 
    $url_tab['author'] = $searchDeliveryCriteria['author'];
endif;
if (isset($searchDeliveryCriteria['state'])): 
    $url_tab['state'] = $searchDeliveryCriteria['state'];
endif;
if (isset($searchDeliveryCriteria['start_date'])): 
    $url_tab['start_date'] = $searchDeliveryCriteria['start_date'];
endif;
if (isset($searchDeliveryCriteria['end_date'])): 
    $url_tab['end_date'] = $searchDeliveryCriteria['end_date'] ;

endif;
            ?>
<?php endif; ?>
<!--Calcul du classeur de pagination-->
<!--Si on a plus de 15 pages alors on affiche au moins 15 liens--> 
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"> 
        <ul class="pagination eiPagination">  
       
        <?php if($current_page > 15): //Si la page est supérieur à 15 ?> 
            
            <li><?php $url_tab['page']=1 ?>
                <a  href="<?php echo url_for2('delivery_list',$url_tab ) ?>" 
                    title="Delivery List ?"  alt="Delivery List" 
                    class=" deliveryList <?php echo $loadDelForStepsLink  ?>" >
                    First
                </a> 
            </li>
            <li><?php $url_tab['page']=$current_page - 1 ?>
                <a  href="<?php echo url_for2('delivery_list',$url_tab ) ?>" 
                    title="Delivery List ?"  alt="Delivery List" 
                    class=" deliveryList <?php echo $loadDelForStepsLink  ?>" >
                    Prev
                </a> 
            </li>
            <?php for($i= $current_page-14 ; $i<=$current_page ; $i++): ?>
            <li class="<?php echo($i==$current_page)?'active':'' ?>"><?php $url_tab['page']=$i ?>
                <a  href="<?php echo url_for2('delivery_list',$url_tab ) ?>"
                    title="Delivery List ?"  alt="Delivery List" 
                    class=" deliveryList <?php echo $loadDelForStepsLink ?>" >
                    <?php echo $i ?> 
                </a> 
            </li>
            <?php  endfor; ?> 



        <?php  else: ?>   
         <?php for ($i = 1 ; $i <= $nb_pages ; $i++): ?>
            <li class="<?php echo($i==$current_page)?'active':'' ?>"><?php $url_tab['page']=$i ?>
                <a  href="<?php echo url_for2('delivery_list',$url_tab ) ?>" 
                    title="Delivery List ?"  alt="Delivery List" 
                    class=" deliveryList <?php  echo $loadDelForStepsLink ?>" >
                    <?php echo $i ?> 
                </a> 
            </li>
         <?php endfor; ?> 
        <?php endif; ?>




            <?php if($nb_pages>$current_page): ?>
            <li><?php $url_tab['page']=$current_page + 1 ?>
                <a  href="<?php echo url_for2('delivery_list',$url_tab ) ?>" 
                    title="Delivery List?"  alt="Delivery List" 
                    class=" deliveryList <?php echo $loadDelForStepsLink ?>" >
                    Next
                </a>  
            </li>
            <?php endif;  ?>
            <li><?php $url_tab['page']=$nb_pages ?>

                <a  href="<?php echo url_for2('delivery_list',$url_tab ) ?>" 
                    title="Delivery List ?"  alt="Delivery List" 
                    class=" deliveryList <?php echo $loadDelForStepsLink?>" >
                    Last
                </a>  
            </li>
        </ul> 
        </div> 
        <div class=" col-lg-4 col-md-4 col-sm-3 col-xs-3 ">
            <div class="input-group"> 
                <input type="text" class="form-control" placeholder="Offset" value=" <?php  echo $max_delivery_per_page?>">
                 <div class="input-group-btn ">
                    <button data-toggle="dropdown" class="btn btn-primary eiBtnPrimary dropdown-toggle" type="button">Offset <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right">  
                            <li><?php $url_tab['offset']=15 ?>
                                <a href="<?php echo url_for2('delivery_list',$url_tab ) ?>" class=" <?php echo $loadDelForStepsLink?>" >15</a>
                            </li>
                            <li><?php $url_tab['offset']=30 ?>
                                <a href="<?php echo url_for2('delivery_list',$url_tab ) ?>" class=" <?php echo $loadDelForStepsLink?>" >30</a>
                            </li>
                            <li><?php $url_tab['offset']=50 ?>
                                <a href="<?php echo url_for2('delivery_list',$url_tab ) ?>" class=" <?php echo $loadDelForStepsLink?>" >50</a>
                            </li>
                            <li><?php $url_tab['offset']=100 ?>
                                <a href="<?php echo url_for2('delivery_list',$url_tab ) ?>" class=" <?php echo $loadDelForStepsLink?>" >100</a>
                            </li>
                        </ul>
                </div>
                
            </div> 
        </div>
        <div class=" col-lg-4 col-md-4 col-sm-5 col-xs-5" >
            <ul class="pull-right pagination eiPagination">
                <li><a href="#"><?php echo 'Total : '.$nbEnr ?></a></li>
                <li >
                    <a href="#"> <?php echo $current_page.'  pages / '.$nb_pages ?></a>
                </li>
            </ul>
        </div> 
    </div>
<?php endif;  ?>