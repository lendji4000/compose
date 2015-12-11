<?php
if( isset($projets) && $projets->getFirst() )
{
    $return = $projets->toArray()->getRawValue();
}
else
{
    $return = array(array("erreur" => "e"));
}

echo json_encode($return);
?>