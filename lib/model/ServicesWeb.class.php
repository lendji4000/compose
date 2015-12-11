<?php

class ServicesWeb {

// Chargement d'un resultat de web service     
    public static function loadResultOfWebService($url) { 
        if ($url == null)
            return null;
        $cobj = curl_init($url); // créer une nouvelle session cURL
        if ($cobj) {
            curl_setopt($cobj, CURLOPT_RETURNTRANSFER, 1); //définition des options
            $xml = curl_exec($cobj); //execution de la requete curl
            curl_close($cobj); //liberation des ressources 
            $xmlDoc = new DOMDocument("1.0", "utf-8");
            $xmlDoc->loadXML($xml);
            return $xmlDoc->saveXML();
        }
        return null;
    }

    /**
     * Création des fichiers .xsl & .xml pour une fonction ou une tâche donnée.
     * 
     * @param EiEnvironnementEiProfil     $environnement  Environnement sur lequel se base la tâche et/ou la fonction. 
     * @param EiProfil                    $profil         Profil utilisé.
     * @param array                       $commands      Liste des séquences/fonctions à inclure.
     * @param EiTache|EiFunction          $objet          Fonction ou Tâche à exporter.
     */
    public static function creerXSL(sfWebRequest $request, EiProjet $ei_project,EiUser $ei_user, EiProfil $profil = null, $commands = null) {

        //Récupération des paramètres de profil 
        $profileParams = $profil->getParamsWithName($ei_user);
        
        /*************************************/
        /*****     Définition du XSL     *****/
        /*************************************/

        // On définit le document.
        $dom = new DomDocument("1.0", "UTF-8");
        $dom->formatOutput = true;
        // On définit les spécificités xsl.
        //-> Feuille de Style
        $css = $dom->createElement('xsl:stylesheet');
        $css->setAttribute("xmlns:xsl", "http://www.w3.org/1999/XSL/Transform");
        $css->setAttribute("version", "1.0");
        $dom->appendChild($css);

        //-> Fichier de sortie
        $ps = $dom->createElement('xsl:preserve-space');
        $ps->setAttribute("elements", "xsl:text");
        $css->appendChild($ps);

        //-> Fichier de sortie
        $pi = $dom->createElement('xsl:output');
        $pi->setAttribute("method", "xml");
        $css->appendChild($pi);
        //-> Template à matcher.
        $tpl = $dom->createElement('xsl:template');
        $tpl->setAttribute("match", "/");
        $css = $css->appendChild($tpl);

        // On définit le jeu de test.
        $test = $dom->createElement("TestCase");
        $test->setAttribute("seleniumIDEVersion", sfConfig::get("app_xml_seleniumIDEVersion"));
        if ($profil != null)
            $test->setAttribute("baseURL", $profil->getBaseUrl());
        else
            $test->setAttribute("baseURL", "default");

        // Puis on l'ajoute.
        $tpl = $tpl->appendChild($test);

        /************************************************ */
        /*****     Séquences/Fonctions/Commandes     **** */
        /************************************************ */
        if ($commands != null) {
            foreach ($commands as $index => $command) {
                /******************************************** */
                /*****     Chargement des paramètres     **** */
                /******************************************** */


                // en créant la racine selense.
                $o = $dom->createElement("selenese");
                // Puis ses trois noeuds: commande, target, value. 
                $cmd = $o->appendChild($dom->createElement("command")); 
                $texte = $cmd->appendChild($dom->createTextNode($command->getName()));

                // Cible dynamique/statique 
                $cible = $o->appendChild($dom->createElement("target"));

                $pattern = "#(?<!\#)\#\{[\w./]*}#";
                /* On commence par interpréter tous les paramètres de profil sur la cible de la commande */
                //$ch1=self::parseAndExtractProfileParamsValue($command->getCommandTarget(), $profileParams);
                $ch1=self::replaceProfileParamWithValue($command->getCommandTarget(),$profileParams);
                $nbMatched = preg_match_all($pattern, $ch1, $matches);

                if (isset($matches) && $nbMatched > 0) {
                    $toReplace = array();
                    //$ch1 = $command->getCommandTarget();
                    // pour chaque élément qui a matché le pattern
                    foreach ($matches[0] as $m => $param) {
                        //on protège notre expression afin que celle-ci ne soit pas considérer comme un pattern de regex
                        $matches[0][$m] = '#\\' . preg_quote($param) . '#';
                        //puis l'on créer la chaine de remplacement correspondante
                        $dompar = $dom->createElement("xsl:value-of");
                        $dompar->setAttribute("select", "user/fonction-" . $command->getFunctionId() . "_" . $command->getFunctionRef() . "/parameter[@name='" . substr($param, 2, -1)."']/value");

                        //On récupère la sous chaine de $ch à partir de $param
                        $sous_chaine_restante = strstr($ch1, $param);

                        $cible->appendChild($dom->createCDATASection(substr($ch1, 0, stripos($ch1, $sous_chaine_restante)) //On ajoute le texte contenu avant $param
                        )); //stripos recherche la première occurence de $sous_chaine_restante dans $ch
                        $cible->appendChild($dompar); //on ajoute la section <xsl:value-of> au CDATA
                        //On extrait à la sous chaine restante le paramètre variable trouvé
                        $ch1 = substr($sous_chaine_restante, strlen($param));
                    }

                    /* Si c'est le dernier paramètre à interpreter ou si on trouvé aucun paramètre à interpreter
                      alors on ajoute le reste de texte */
                    $cible->appendChild($dom->createCDATASection($ch1));
                } else {
                    $cible->appendChild($dom->createCDATASection($ch1));
                }

                //Interprétation de la valeur de la commande
                $valeur = $o->appendChild( $dom->createElement("value"));
                /* On commence par interpréter tous les paramètres de profil sur la valeur de la commande */
                //$ch2=self::parseAndExtractProfileParamsValue($command->getCommandValue(), $profileParams);
                $ch2=self::replaceProfileParamWithValue($command->getCommandValue(),$profileParams);
                $nbMatched = preg_match_all($pattern, $ch2, $matches);

                if (isset($matches) && $nbMatched > 0) {
                    $toReplace = array();
                    //$ch = $command->getCommandValue();

                    // pour chaque élément qui a matché le pattern
                    foreach ($matches[0] as $m => $param) {
                        //on protège notre expression afin que celle-ci ne soit pas considérer comme un pattern de regex
                        $matches[0][$m] = '#\\' . preg_quote($param) . '#';
                        //puis l'on créer la chaine de remplacement correspondante
                        $dompar = $dom->createElement("xsl:value-of");
                        $dompar->setAttribute("select", "user/fonction-" . $command->getFunctionId() . "_" . $command->getFunctionRef() . "/parameter[@name='" . substr($param, 2, -1)."']/value");
                        //$toReplace[$m]=$dompar;
                        //Méthode 2
                        $sous_chaine_restante = strstr($ch2, $param);
                        $sous_chaine_restante_debut= substr($ch2, 0, stripos($ch2, $sous_chaine_restante));

                        // Si que des caractères vides, on place cela dans une balise texte.
                        if( preg_match("/^([[:space:]]+)$/", $sous_chaine_restante_debut) ){
                            $valeur->appendChild($dom->createElement("xsl:text",
                                "".$sous_chaine_restante_debut.""
                            ));
                        }
                        else{
                            $valeur->appendChild($dom->createCDATASection(
                                $sous_chaine_restante_debut
                            ));
                        }

                        $valeur->appendChild($dompar);
//                                //On extrait à la sous chaine restante le paramètre variable trouvé
                        $ch2 = substr($sous_chaine_restante, strlen($param));
                    }
                    /* Si c'est le dernier paramètre à interpreter ou si on trouvé aucun paramètre à interpreter
                      alors on ajoute le reste de texte */
                    $valeur->appendChild($dom->createCDATASection($ch2));
                } else {
                    $valeur->appendChild($dom->createCDATASection($ch2));
                }


                $tpl->appendChild($o);
            }
        }

        return $dom->saveXML();
    }
    
    
    /* Parsing et analyse d'une chaine pour en extraire les paramètres de profil */
    
    public static function parseAndExtractProfileParamsValue($ch,$profileParams){
        $ch=html_entity_decode($ch);
          if(is_array($profileParams) && count($profileParams) > 0)://Si des paramètres existent pour le profil  
          
          //On analyse la chaine pour interpréter les paramètres variables 
        // regex recherchant tous les paramètres qui sont précédé d'un nombre pair de # 
        //(soit un nombre impair de # avant l'accolade ouvrante). 
        //                        |--1--||------2-----|
        $patternImpair = "#(?<!\#)(\#\#)*(\#\{[\w./]*})#";
        $nbMatchedImpair = preg_match_all($patternImpair, $ch, $matches);
        //$matches[0] contient les résultats complets.
        //$matches[1] contient la série de # précédant le paramètre pour l'expression matchée
        //$matches[2] contient le paramètre à proprement parler (la forme #{})
        
        if(isset($matches) && $nbMatchedImpair > 0 ): 
            $toReplace = array();
            // pour chaque élément qui a matché le pattern
            foreach($matches[0] as $m => $matche){
                //on protège notre expression afin que celle-ci ne soit pas considérer comme un pattern de regex
                $matches[0][$m] = '#'.preg_quote($matche, '#').'#';
                //puis l'on créer la chaine de remplacement correspondante
                $to_matche=substr($matches[2][$m], 2, -1);
                 
                
                /* On vérifie s'il n'ya pas de paramètre de profil à interpréter  */ 
                if(count($profileParams)>0): 
                    foreach ($profileParams as $profileParam) :
                    //On vérifie si un paramètre du profil correspond à la valeur contenue dans #{text}
                    if ($profileParam['name'] == $to_matche ):
                        $toReplace[$m] = $profileParam['value']; //si oui on récupère cette valeur pour remplacer le #{text} dans la description
//                    else:
//                        $toReplace[$m] =$matches[2][$m];
                    endif;
                    endforeach;
                endif;
                
            } 
            //on replace pour chaque $matches par la nouvelles chaine générée puis stockées dans $toReplace
            //dans la chaine représentant la valeur de notre EiParam
            $res = preg_replace($matches[0], $toReplace, $ch);
            
        //si aucune valeur n'a été trouvée, alors on retournera simplement la valeur du paramètre.
        else :
            $res = $ch;
        endif;
        //on transforme ## en #.
        
         return preg_replace("#\#\##", "#", $res); 
        else:  
            return  $ch  ;
        endif;  
    }
    
    /* Remplacer les paramètres de profil par les valeurs */
    
    public static function replaceProfileParamWithValue($ch,$profileParams){
        /* On vérifie s'il n'ya pas de paramètre de profil à interpréter  */ 
                if(count($profileParams)>0):
                    foreach ($profileParams as $profileParam) :
                    if(isset($profileParam['upp_id'])):
                        $val=$profileParam['upp_value'];
                        else:
                            $val=$profileParam['value'];
                    endif;
                     $ch=preg_replace("#(?<!\#)(\#\#)*(\#\{".$profileParam['name']."})#", $val, $ch);
                    endforeach;
                endif;
                return $ch;
    }

}

?>
