<?php

$liste = array();

// On vérifie qu'il y a au moins un scénario sinon on retourne un tableau vide.
if( isset($scenarios) && $scenarios->getFirst() ){

    // On parcourt les scénarios.
    foreach( $scenarios as $ind => $scenario ){
        // Création d'une sous-liste pour contenir les informations du scénario.
        $sousListe = array();

        foreach($scenario as $key => $value){
            $sousListe[$key] = $value;
        }

        $liste[] = $sousListe;
    }
}
else{
    $liste["erreur"] = "e";
}


echo json_encode($liste);

?>
