<?php if(isset($ei_project) && $ei_project!=null) : //traitement du resultat de connexion ?>
    
    <projet>
        <nom_projet><?php echo $ei_project->getNomProjet() ?></nom_projet>
        <id_projet><?php echo $ei_project->getIdProjet()?></id_projet>
        <ref_projet><?php echo $ei_project->getIdRef() ?></ref_projet>
        <version><?php echo $ei_project->getVersion() ?></version>
    </projet>
    <?php else: ?>
    <error>
        le projet n'a pas été retrouvé , l'utilisateur n'a pas les droits nécessaires pour y acceder ou est inexistant dans la base
    </error>
    
    <?php endif; ?>
