<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);  ?>
<?php if(isset($graph_node) && $graph_node!=null) : ?>
<?php $graph_node_children=Doctrine_Core::getTable('EiCampaignGraph')
        ->getGraphNodeChildren($graph_node->getRawValue()); ?>
<!-- Affichage d'un graphe ( version temporaire : affichage comme un arbre) -->
<?php if(count($graph_node_children) > 0 ): //echo $graph_node->getId() ?> 
<li>
    <label class="tree-toggle nav-header">
        <?php $campaign_graph_new=$url_tab;
                $campaign_graph_new['campaign_id']=$graph_node->getCampaignId();
                $campaign_graph_new['parent_id']=$graph_node->getId();?>
        <?php echo $graph_node->getEiScenario()->getNomScenario() ?>
        <a class="pull-right" href="<?php echo url_for2('campaign_graph_new',$campaign_graph_new) ?>"> 
            <?php echo ei_icon('ei_add') ?> CHILD
        </a>  
    </label>
    <ul class="nav nav-list tree ">   
        <?php foreach($graph_node_children as $child): ?>
        <?php $graphLine=$url_tab;
                $graphLine['graph_node']=$child; ?>
        <?php include_partial('eicampaigngraph/graphLine',$graphLine) ?>
        <?php endforeach;   ?>
    </ul>
</li>
<?php else: ?> 
<li>
    <label class="tree-toggle nav-header">
        <a href="#"><?php echo $graph_node->getEiScenario()->getNomScenario() ?></a>
        <?php $campaign_graph_new=$url_tab;
                $campaign_graph_new['campaign_id']=$graph_node->getCampaignId();
                $campaign_graph_new['parent_id']=$graph_node->getId();?>
        <a class="pull-right" href="<?php echo url_for2('campaign_graph_new',$campaign_graph_new) ?>"> 
            <?php echo ei_icon('ei_add') ?> CHILD
        </a>  
    </label>
         
    </li>
<?php endif; ?>
<?php endif; ?>

