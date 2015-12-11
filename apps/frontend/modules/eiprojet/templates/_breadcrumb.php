 
<?php if (isset($ei_project) && isset($urlParameters)):   ?>
<?php include_partial('global/breadcrumb',array(
    'ei_project'=> isset($ei_project)?$ei_project:null,
    'urlParameters' => isset($urlParameters)?$urlParameters:null,
    'breadcrumb' => $breadcrumb
)); ?> 
<?php  else: // On est dans le listing des projets ?>
<ol class="breadcrumb">
    <li>
        <ol class="breadcrumb" id="headerBreadcrumb" >
        <li>  <?php echo ei_icon('ei_project','lg') ?>  Projects    </li> 
        </ol>  
    </li>  
</ol> 
    
<?php endif; ?>
