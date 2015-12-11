
<?php  if ($xmlfile!=null) : ?>
    <?php   //xmlns="http://www.w3.org/1999/xhtml"
    echo '<!--Créer par lenine DJOUATSA sous la direction EISGE-->
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html   xml:lang="en" lang="en">
        <head>
          <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
          <title>Test Suite</title>
        </head>
        <body><table align="center" width="50%" id="suiteTable" cellpadding="1" cellspacing="1" border="1" class="selenium"><tbody>
        ';
        $version=Doctrine_Core::getTable('EiVersion')->findOneBy('id', $id_version);
        $scenario=Doctrine_Core::getTable('EiScenario')->findOneBy('id', $ei_scenario_id);
        
        if(($version==null) || ($scenario==null)) { echo '<erreur1>Aucun scénario spécifié</erreur1>';}
        else{

            echo '<tr><td>'.$scenario->nom_scenario.'</td></tr>';
            echo '<tr><td>'.$version->libelle.'</td></tr>';
            // récupération des fonctions du scénario
        $q=Doctrine_Core::getTable('EiFonction')->getFonctionsByCriteria(Doctrine_Core::getTable('EiFonction')->getFonctions()
                , null ,$version->id , $scenario->id , null,null ,null,null);
        $fonctions=$q->execute();
        
        if($fonctions==null) {echo '<erreur2>Aucune fonction trouvé </erreur2>'; }
        
        else{
            
    // Ajout des fonctions au tableau
            $doc="";
        foreach ($fonctions as $fonction) {
            $doc=$doc.'<tr><td>'; //frontend_dev.php/eifonction/generateXML.xml?id_fonction=42
            $doc=$doc.'<a href="http://"'.$sf_request->getHost().url_for("@ei_fonction_xml?sf_format=xml&id_fonction=".$fonction->id).'">'.$fonction->getKalFonction()->nom_fonction;
            $doc=$doc.'</a></td></tr>';
        }
        echo $doc;
        }
        }
        
        
    echo '</tbody></table></body> </html>';
//echo $xmlfile;
    ?>
<?php else :  ?>
<b> Fichier vide!!</b>
<?php endif; ?>
