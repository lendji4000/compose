<?php
$i=0;
if(is_array($tab)) :
    echo 'tableau';
else :
//$pieces = explode(" ", $pizza);
//echo $pieces[0]; // pièce1
//echo $pieces[1]; // pièce2

//print_r($t) ;echo '<br/>';
//}
//print_r($tab);
$new_tab= explode("|", $tab);
foreach($new_tab as $cle => $valeur){
    if(($cle==0)||($cle % 2)==0 ){
        $tab2=explode(",", $new_tab[$cle]);
        $tab3['id_fonction']=$tab2[0];
        $tab3['fonction_position']=$tab2[1];
        $tab3['count']=$tab2[2];
        $final_tab[]=$tab3;
        
    }
    
    
}
print_r($final_tab);
foreach ($final_tab as $t){
    Doctrine_Query::create()
        ->update('EiFonction f')
        ->set('f.position', '?' ,$t['count'] )
            ->where('f.id = ?', $t['id_fonction'])
            ->execute();
    
}

 endif;
?>
