<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name ); 
        ?>
<?php if (isset($ei_node) ):   ?>
<!-- Recherche des images à afficher suivant le type de noeud -->
<?php 
$node_type=$ei_node->getType() ;
if($node_type=="EiScenario") $img='<img alt="" src="/images/boutons/test_suit_img.png">';
if($node_type=="EiFolder" || $node_type=="EiDataSetFolder")  $img='<i class="cus-folder"></i>';
if($node_type=="EiDataSetTemplate")  $img='<i class="cus-page-white-text"></i>';
?>
    <li >
        <span >
            <?php $getNodeChildsForCampaignGraph=$url_tab;
            $getNodeChildsForCampaignGraph['ei_node_id']=$ei_node->getId();
            $getNodeChildsForCampaignGraph['ei_node_type']=$node_type;?>
            <a href="<?php echo url_for2('getNodeChildsForCampaignGraph',$getNodeChildsForCampaignGraph) ?>"
               class="showNodeChilds">
                <img title="Show Child Node"  src="/images/icones/fleche-droite.png" class="iconNodeChildren showNodeChildsImg">
                <?php echo $img ?> &nbsp; &nbsp;<?php echo $ei_node->getName() ?>   
            </a> 
            <?php if($node_type=="EiScenario" ): ?>
            &nbsp; &nbsp; &nbsp; 
            <a href="<?php echo url_for2('chooseTestSuiteForCampaignGraphNode',$getNodeChildsForCampaignGraph)?>" 
               class=" chooseTestSuiteForCampaignGraphNode">
                <i class="fa fa-check"></i>
            </a>
            <?php endif; ?>
            <?php if($node_type=="EiDataSetTemplate" ): ?>
            &nbsp; &nbsp; &nbsp;
            <a href="<?php echo url_for2('chooseDataSetForCampaignGraphNode',$getNodeChildsForCampaignGraph)?>" 
               class="chooseDataSetForCampaignGraphNode">
                <i class="fa fa-check"></i>
            </a>
            <?php endif; ?>
        </span>
        
        <ul class="node_childs">
            
        </ul>
    </li>
<?php endif; ?>
 