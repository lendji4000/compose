<?php if(isset($ei_view) && $ei_tree): ?>
 

<h3 id="nodeDetailsModalLabel">
    <ul class="nav nav-tabs">
        <li role="viewName" class="active">
            <a  href="#" title="<?php echo $ei_tree ?>">
                <?php  echo ei_icon('ei_folder',null,'img_node',"Function folder node" ,"function_folder_node")." ".MyFunction::troncatedText($ei_tree, 40)?>          
            </a>
        </li> 
    </ul>
</h3>
<?php endif;?>