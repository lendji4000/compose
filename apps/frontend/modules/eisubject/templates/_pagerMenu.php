<!--Cette page permet de mieux définir le menu de pagination des Sujets.
Elle dépend du projet et des variables de pagination
-->
<?php if (isset($current_page) && isset($nb_pages) && isset($project_id) && isset($project_ref) ): ?>
<?php $url_tab=$searchSubjectCriteria->getRawValue();
      $url_tab['project_id']= $project_id; 
      $url_tab['project_ref']= $project_ref; 
      $url_tab['profile_id']= $profile_id; 
      $url_tab['profile_ref']= $profile_ref; 
      $url_tab['profile_name']= $profile_name;
      $url_tab['contextRequest']= (isset($contextRequest)?$contextRequest:null);
      $url_tab['is_ajax_request']= (isset($is_ajax_request) && $is_ajax_request) ?true:false;
      $routeToCall="subjects_list";
    /* On génère l'url de recherche suivant le contexte de navigation (delivery, subject, functions ,etc...) */
    if(isset($contextRequest) && $contextRequest=="EiDelivery" && isset($ei_delivery) && $ei_delivery!=null):
        $url_tab['delivery_id']=$ei_delivery->getId();
        $routeToCall="getDeliverySubjects";
    endif;
    if(isset($contextRequest) && $contextRequest=="EiFunction" && isset($kal_function) && $kal_function!=null):
        $url_tab['function_id']=$kal_function->getFunctionId();
        $url_tab['function_ref']=$kal_function->getFunctionRef();
        $routeToCall="subjectFunction";
        $url_tab['action']='getFunctionSubjects';
    endif; 
    
    $loadSubForStepsLink="";
    if(isset($is_ajax_request) && $is_ajax_request):
        $loadSubForStepsLink=  (isset($contextRequest) && $contextRequest=="interventionLink")?"loadIntForMigrationLink":"loadSubForStepsLink" ;
    endif;
      
       ?>  
<!--Calcul du classeur de pagination-->
<!--Si on a plus de 15 pages alors on affiche au moins 15 liens--> 
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 "> 
        <ul class="pagination eiPagination"> 
        <?php if(isset($offset)):   $url_tab['offset']=$offset; endif; ?>
        <?php if($current_page > 15): //Si la page est supérieur à 15 ?> 
            <li><?php $url_tab['page']=1 ?>
                <a  href="<?php echo (isset($loadSubForStepsLink)?url_for2($routeToCall,$url_tab ):"#") ?>" 
                    itemref="<?php echo url_for2($routeToCall,$url_tab ) ?>" 
                    title="Subject List ?"  alt="Subject List" 
                    class=" subjectList <?php echo $loadSubForStepsLink  ?>" >
                    First
                </a> 
            </li>
            <li><?php $url_tab['page']=$current_page - 1 ?>
                <a  href="<?php echo (isset($loadSubForStepsLink)?url_for2($routeToCall,$url_tab ):"#") ?>" 
                    itemref="<?php echo url_for2($routeToCall,$url_tab ) ?>" 
                    title="Subject List ?"  alt="Subject List" 
                    class=" subjectList <?php echo $loadSubForStepsLink  ?>" >
                    Prev
                </a> 
            </li>
            <?php for($i= $current_page-14 ; $i<=$current_page ; $i++): ?>
            <li class="<?php echo($i==$current_page)?'active':'' ?>"><?php $url_tab['page']=$i ?>
                <a  href="<?php echo (isset($loadSubForStepsLink)?url_for2($routeToCall,$url_tab ):"#") ?>"
                    itemref="<?php echo url_for2($routeToCall,$url_tab ) ?>" 
                    title="Subject List ?"  alt="Subject List" 
                    class=" subjectList <?php echo $loadSubForStepsLink  ?>" >
                    <?php echo $i ?> 
                </a> 
            </li>
            <?php  endfor; ?> 



        <?php  else: ?>   
         <?php for ($i = 1 ; $i <= $nb_pages ; $i++): ?>
            <li class="<?php echo($i==$current_page)?'active':'' ?>"><?php $url_tab['page']=$i ?>
                <a  href="<?php echo (isset($loadSubForStepsLink)? url_for2($routeToCall,$url_tab ):"#") ?>" 
                    itemref="<?php echo url_for2($routeToCall,$url_tab ) ?>" 
                    title="Subject List ?"  alt="Subject List" 
                    class=" subjectList <?php echo $loadSubForStepsLink  ?>" >
                    <?php echo $i ?> 
                </a> 
            </li>
         <?php endfor; ?> 
        <?php endif; ?>




            <?php if($nb_pages > $current_page): ?>
            <li><?php $url_tab['page']=$current_page + 1 ?>
                <a  href="<?php echo (isset($loadSubForStepsLink)?url_for2($routeToCall,$url_tab ):"#") ?>" 
                    itemref="<?php echo url_for2($routeToCall,$url_tab ) ?>" 
                    title="Subject List?"  alt="Subject List"     class=" subjectList <?php echo $loadSubForStepsLink  ?>" >
                    Next
                </a>  
            </li>
            <?php endif;  ?>
            <li><?php $url_tab['page']=$nb_pages ?>

                <a  href="<?php echo (isset($loadSubForStepsLink)?url_for2($routeToCall,$url_tab ):"#") ?>"   title="Subject List ?"  alt="Subject List" 
                    itemref="<?php echo url_for2($routeToCall,$url_tab ) ?>" 
                    class=" subjectList <?php echo $loadSubForStepsLink  ?>" >
                    Last
                </a>  
            </li>
        </ul> 
        </div> 
        <div class=" col-lg-4 col-md-4 col-sm-3 col-xs-3  ">
            <div class="input-group"> 
                <input type="text" class="form-control" placeholder="Offset" value=" <?php  echo $max_subject_per_page?>">
                 <div class="input-group-btn">
                    <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button">Offset <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <?php $url_tab['page']=$current_page ?>
                            <li><?php $url_tab['offset']=30 ?>
                                <a href="<?php echo (isset($loadSubForStepsLink)?url_for2($routeToCall,$url_tab ):"#") ?>" 
                                   itemref="<?php echo url_for2($routeToCall,$url_tab ) ?>" 
                                   class=" <?php echo $loadSubForStepsLink ?>" > 30 </a>
                            </li>
                            <li><?php $url_tab['offset']=50 ?>
                                <a href="<?php echo (isset($loadSubForStepsLink)?url_for2($routeToCall,$url_tab ):"#") ?>" 
                                   itemref="<?php echo url_for2($routeToCall,$url_tab ) ?>" 
                                   class=" <?php echo $loadSubForStepsLink ?>" >50</a>
                            </li>
                            <li><?php $url_tab['offset']=100 ?>
                                <a href="<?php echo (isset($loadSubForStepsLink)?url_for2($routeToCall,$url_tab ):"#") ?>" 
                                   itemref="<?php echo url_for2($routeToCall,$url_tab ) ?>" 
                                   class=" <?php echo $loadSubForStepsLink ?>" >100</a>
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