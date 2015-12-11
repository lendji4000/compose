<!-- Chemin de navigation-->
<?php 
$ei_project= $ei_project->getRawValue($ei_project);
    $urlParameters= $urlParameters->getRawValue($urlParameters);
    $breadcrumb= $breadcrumb->getRawValue($breadcrumb);
?>

<?php if (isset($breadcrumb)):   ?>
<ol class="breadcrumb">
    <li>
        <ol class="breadcrumb" id="headerBreadcrumb" >
        <li>
            <i class="fa fa-desktop fa-lg"> </i> 
                <a id="<?php echo'linkToProject_' . $ei_project->getProjectId() . '_' . $ei_project->getRefId() ?>"
                   href="<?php echo url_for2('projet_show', $urlParameters) ?>">
                       <?php echo $ei_project->getTroncatedName(30) ?>
                </a> 
        </li>
        <?php if(is_array($breadcrumb) && count($breadcrumb)>0): ?>
        <?php foreach ($breadcrumb as $bread): ?> 
        <li class="<?php echo ($bread['active'] ? 'active' : '') ?>">
            <?php echo html_entity_decode($bread['logo'].' ') ?>
             
            <?php if(!$bread['is_last_bread']): ?>
            <a href="<?php echo $bread['uri'] ?>">
                <?php echo $bread['title'] ?>
            </a>
            <?php  else :  
                echo $bread['title'] ;
            endif; ?>
        </li> 
        <?php endforeach; ?>
        <?php endif; ?>
        
    </ol> 
        
    </li>
    <?php //Initialisation du breadcrumb
    $contentList=  content_tag("li", 
                        ei_icon("ei_project").
                        content_tag("a", $ei_project, array(
                            "id"=>"linkToProject_" . $ei_project->getProjectId() . "_" . $ei_project->getRefId(),
                            "href" => url_for2("projet_show", $urlParameters) )));
                ?>
                 <?php if(is_array($breadcrumb) && count($breadcrumb)>0): ?>
                    <?php foreach ($breadcrumb as $bread): ?>
                    <?php //Concatenation des éléments du breadcrumb 
                    $contentList.=content_tag("li", 
                        html_entity_decode("".$bread["logo"]." ").
                        (!$bread["is_last_bread"]?content_tag("a", (isset($bread["completeTitle"])?
                                    strtr(  $bread["completeTitle"],array("\"" => " ", "'" => " ")):
                                    strtr( $bread["title"],array("\"" => " ", "'" => " "))), array( 
                            'href' => $bread["uri"]  )):$bread["title"]),
                            
                            array("class"=> ($bread["active"] ? "active" : "")));
                    ?> 
                    <?php endforeach; ?>
                    <?php endif; ?> 
    <?php $dataContent=  content_tag("ol", $contentList, array("class" => "breadcrumb")) ?>
    <li id='headerBreadcrumbComplete'> 
            <a href="#" class=" breadCrumbPopover" title="Complete path" data-html="true"
               data-trigger="focus"  data-placement="bottom" data-toggle="popover" 
               data-content=' <?php echo $dataContent ?>'> 
                <i class="fa fa fa-chevron-circle-right "></i> 
            </a>  
    </li>
</ol>
    

    
<?php endif; ?>