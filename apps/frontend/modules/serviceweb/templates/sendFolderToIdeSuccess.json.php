<?php if (isset($error) ): ?> 
<?php   echo json_encode(array('error' => $error )); ?>
<?php else : ?> 
<?php $data1=array(); $data2=array();   ?>  

<?php if(isset($ei_node)): ?>  
<?php   $data1[]= $ei_node->getRawValue()->asArray(); ?>
<?php endif;  ?>  
    
<?php if ($childs->getFirst()):    
   foreach ($childs as $key => $child):  
       $data2[]=$child->getRawValue()->asArray();
   endforeach ; 
?>

<?php  endif;  ?>
<?php 
//echo json_encode(array('node'=> $data1,'childs' => $data2),JSON_PRETTY_PRINT), "\n";
echo json_encode(array('node'=> $data1,'childs' => $data2)), "\n";
?> 
<?php endif; ?>  
