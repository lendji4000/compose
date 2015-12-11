<?php

class MyFunction {

    /**
     * Remove element(s) from array by value.
     *
     * @param $array
     * @param $element
     * @return array
     */
    public static function array_delete($array, $element) {
        return array_diff($array, array($element));
    }

    public static function getGuard() {
        if (sfContext::getInstance()->getUser()->isAuthenticated())
            return sfContext::getInstance()->getUser()->getGuardUser();
        return null;
    }

    //Récupération de la vue  root d'un projet
    public static function getRootViewOfProject($ref_projet, $id_projet) {
        return Doctrine_Core::getTable('EiView')->findOneByProjectIdAndProjectRefAndIsRoot($id_projet, $ref_projet, 1);
    }
            
    public static function getResultConnection($login_user, $pwd_user) {
        if ($login_user == null || $pwd_user == null)
            return null;

        $result_connect = MyFunction::connexionDistante($login_user, $pwd_user);

        if (is_array($result_connect))
            return $result_connect;
        return null;
    }

    /**
     * Check if a string has utf7 characters in it
     *
     * By bmorel at ssi dot fr
     *
     * @param  string $string
     * @return boolean $bool
     */
    public static function seemsUtf8($string)
    {
        for ($i = 0; $i < strlen($string); $i++) {
            if (ord($string[$i]) < 0x80) continue; # 0bbbbbbb
            elseif ((ord($string[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
            elseif ((ord($string[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
            elseif ((ord($string[$i]) & 0xF8) == 0xF0) $n=3; # 11110bbb
            elseif ((ord($string[$i]) & 0xFC) == 0xF8) $n=4; # 111110bb
            elseif ((ord($string[$i]) & 0xFE) == 0xFC) $n=5; # 1111110b
            else return false; # Does not match any model
            for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
                if ((++$i == strlen($string)) || ((ord($string[$i]) & 0xC0) != 0x80))
                    return false;
            }
        }
        return true;
    }

    /**
     * Remove any illegal characters, accents, etc.
     *
     * @param  string $string  String to unaccent
     * @return string $string  Unaccented string
     */
    public static function unaccent($string)
    {
        if (!preg_match('/[\x80-\xff]/', $string)) {
            return $string;
        }

        if (self::seemsUtf8($string)) {
            $chars = array(
                // Decompositions for Latin-1 Supplement
                chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
                chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
                chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
                chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
                chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
                chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
                chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
                chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
                chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
                chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
                chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
                chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
                chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
                chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
                chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
                chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
                chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
                chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
                chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
                chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
                chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
                chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
                chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
                chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
                chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
                chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
                chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
                chr(195).chr(191) => 'y',
                // Decompositions for Latin Extended-A
                chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
                chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
                chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
                chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
                chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
                chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
                chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
                chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
                chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
                chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
                chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
                chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
                chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
                chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
                chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
                chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
                chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
                chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
                chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
                chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
                chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
                chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
                chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
                chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
                chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
                chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
                chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
                chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
                chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
                chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
                chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
                chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
                chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
                chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
                chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
                chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
                chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
                chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
                chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
                chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
                chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
                chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
                chr(197).chr(148) => 'R', chr(197).chr(149) => 'r',
                chr(197).chr(150) => 'R', chr(197).chr(151) => 'r',
                chr(197).chr(152) => 'R', chr(197).chr(153) => 'r',
                chr(197).chr(154) => 'S', chr(197).chr(155) => 's',
                chr(197).chr(156) => 'S', chr(197).chr(157) => 's',
                chr(197).chr(158) => 'S', chr(197).chr(159) => 's',
                chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
                chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
                chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
                chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
                chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
                chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
                chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
                chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
                chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
                chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
                chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
                chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
                chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
                chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
                chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
                chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
                // Euro Sign
                chr(226).chr(130).chr(172) => 'E',
                // GBP (Pound) Sign
                chr(194).chr(163) => '',
                'Ä' => 'Ae', 'ä' => 'ae', 'Ü' => 'Ue', 'ü' => 'ue',
                'Ö' => 'Oe', 'ö' => 'oe', 'ß' => 'ss',
                // Norwegian characters
                'Å'=>'Aa','Æ'=>'Ae','Ø'=>'O','æ'=>'a','ø'=>'o','å'=>'aa'
            );

            $string = strtr($string, $chars);
        } else {
            // Assume ISO-8859-1 if not UTF-8
            $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
                .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
                .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
                .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
                .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
                .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
                .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
                .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
                .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
                .chr(252).chr(253).chr(255);

            $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

            $string = strtr($string, $chars['in'], $chars['out']);
            $doubleChars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
            $doubleChars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
            $string = str_replace($doubleChars['in'], $doubleChars['out'], $string);
        }

        return $string;
    }

    /**
     * Cleans up the text and adds separator
     *
     * @param string $text
     * @param string $separator
     * @return string
     */
    private static function postProcessText($text, $separator)
    {
        if (function_exists('mb_strtolower')) {
            $text = mb_strtolower($text);
        } else {
            $text = strtolower($text);
        }

        // Remove all none word characters
        $text = preg_replace('/\W/', ' ', $text);

        // More stripping. Replace spaces with dashes
        $text = strtolower(preg_replace('/[^A-Za-z0-9\/]+/', $separator,
            preg_replace('/([a-z\d])([A-Z])/', '\1_\2',
                preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2',
                    preg_replace('/::/', '/', $text)))));

        return trim($text, $separator);
    }
    public static function sluggifyStr($text){
        //Replace all non letters or digits by '-'
        $text=preg_replace("/\W+/", "-",$text);
        //Trim and lowercase
        $text= strtolower(trim($text,"-"));
        return $text;
    }
    /**
     * @param $text
     * @param string $separator
     * @return string
     */
    public static function sluggifyForXML($text, $separator = '-'){
        $text = self::unaccent($text);
        return self::postProcessText($text, $separator);
    }
    
    public static function connexionDistante($login_user, $pwd_user) {
        
        if ($pwd_user == null)
            return null;
        
        //Appel du webservice 
        $xml = ServicesWeb::loadResultOfWebService(MyFunction::getPrefixPath() .
                "en/serviceweb/connexionDistante?login=" . $login_user . "&pwd=" . $pwd_user .
                "&system_uri=". sfConfig::get('project_system_uri'). "&sf_format=xml"); 
        
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $dom->save('login.xml'); 
        if(!$dom->documentElement) return null;
        
        if ($dom->documentElement->nodeName == 'error')
            return $dom->documentElement->nodeValue;

        if ($dom->documentElement && $dom->documentElement->getElementsByTagName("guard_user")) {
            $u = $dom->documentElement->getElementsByTagName("guard_user")->item(0); 
            if ($u->getElementsByTagName("username") && $u->getAttribute("id")) {
                $id = $u->getAttribute("id");
                $username = $u->getElementsByTagName("username")->item(0)->nodeValue;
                $first_name = $u->getElementsByTagName("first_name")->item(0)->nodeValue;
                $last_name = $u->getElementsByTagName("last_name")->item(0)->nodeValue;
                $email_address = $u->getElementsByTagName("email_address")->item(0)->nodeValue;
                $company = $u->getElementsByTagName("company")->item(0)->nodeValue;
                $password = $u->getElementsByTagName("password")->item(0)->nodeValue;
                $is_active = $u->getElementsByTagName("is_active")->item(0)->nodeValue;

                $guard_tab = array('id' => $id, 'username' => $username, 'first_name' => $first_name, 'company' => $company,
                    'last_name' => $last_name, 'email_address' => $email_address, 'password' => $password, 'is_active' =>$is_active);
            }
            $user = $dom->documentElement->getElementsByTagName("ei_user")->item(0);
            if ($user->getElementsByTagName("ref_id") && $user->getElementsByTagName("user_id")) { 
                $user_id = $user->getAttribute("user_id");
                $ref_id = $user->getAttribute("ref_id"); 
                $guard_id = $user->getElementsByTagName("guard_id")->item(0)->nodeValue;
                $matricule = $user->getElementsByTagName("matricule")->item(0)->nodeValue;
                $ei_user_tab = array('user_id' => $user_id, 'ref_id' => $ref_id, 'guard_id' => $guard_id, 'matricule' => $matricule);
            }
            return array('guard_tab' => $guard_tab, 'ei_user_tab' => $ei_user_tab);
        }
        else
            throw new Exception('Impossible de se connecter à ' . MyFunction::getPrefixPath());

        return null;
    }

    public static function getPrefixPath() {
        return sfConfig::get('project_prefix_path');
    }

    public static function rechargerProjet($p, $isFullLoad, $updateDate = null, Doctrine_Connection $conn = null) {
        $res = array('project_id', 'project_ref');
        if ($updateDate == null)
            $updateDate = date('Y-m-d H:i:s');

        $ref_id = $p->getAttribute("ref_id"); 
        $res['project_ref'] = $ref_id;
        $project_id = $p->getAttribute("project_id"); 
        $res['project_id'] = $project_id; 
        $name = $p->getElementsByTagName("name")->item(0)->nodeValue;
        $description = $p->getElementsByTagName("description")->item(0)->nodeValue;
        $state = $p->getElementsByTagName("state")->item(0)->nodeValue;
        $default_notice_lang = $p->getElementsByTagName("default_notice_lang")->item(0)->nodeValue;
        $user_ref = $p->getElementsByTagName("user_ref")->item(0)->nodeValue;
        $user_id = $p->getElementsByTagName("user_id")->item(0)->nodeValue;
        /* Récupération du systeme*/
        $syst=$p->getElementsByTagName("system_id");
        if(!$syst->length>0)    $system_id=null;
        else    $system_id=$syst->item(0)->nodeValue; 
        
        $version = $p->getElementsByTagName("version")->item(0)->nodeValue;  
        //recherche du projet en base
        if ($project_id != null && $ref_id != null) {
            $q = Doctrine_Core::getTable('EiProjet')->findOneByProjectIdAndRefId($project_id, $ref_id);
        }

        if (($q && $q != null) || ($q && $q != null && $isFullLoad == true)) {//si l'element existe , on fait une mise à jour
            $q->name = $name;
            $q->description = $description;
            $q->state = $state;
            $q->system_id = $system_id;
            $q->default_notice_lang = $default_notice_lang;
            if ($isFullLoad == true) {//changement de la date de mise à jour du projet et de la version si mise à jour complète d'un projet
                $q->updated_at = $updateDate;
                $q->version_courante = $version; 
            }
            $q->obsolete = false;
            $q->checked_at = $updateDate;
            $q->version_kalifast = $version;
            $q->save($conn);
        } else {//le projet n'existe pas encore et dans ce cas on le crée
            $ei_project = new EiProjet();
            $ei_project->project_id = $project_id;
            $ei_project->ref_id = $ref_id;
            $ei_project->name = $name;
            $ei_project->description = $description;
            $ei_project->state = $state;
            $ei_project->default_notice_lang = $default_notice_lang;
            $ei_project->user_id = $user_id;
            $ei_project->user_ref = $user_ref; 
            $ei_project->system_id = $system_id;
            $ei_project->version_courante = 0;
            $ei_project->version_kalifast = $version;
            $ei_project->checked_at = $updateDate;
            $ei_project->obsolete = false;
            $ei_project->save($conn);
            $ei_project->createRootFolderIfNew($conn);
            /* Initialisation des statuts par défaut du projet (statuts de livraison, */         
            //$this->initProjectObjStates();
        }

        return $res;
    }
 
    //Parsing d'un tableau a a une dimension récupéré via ajax
    public static function parseSimpleStringToTab($str) {
        if (is_array($str)) :
            return null;
        else :
            return explode(",", $str);
        endif;
    }
    
    public static function parseStringToTab($str) {
        $i = 0;
        //parsing d'une tableau à deux dimensions passé sous forme d'une chaine en paramètre jquery
        if (is_array($str)) :
            return null;
        else :

            $new_tab = explode("|", $str);
            foreach ($new_tab as $cle => $valeur) {

                $tab2 = explode(",", $new_tab[$cle]);
                $tab3[$i] = $tab2;
                $i++;
            }
            return $tab3;
        endif;
    }

    public static function deleteDownloadFiles($download_dir) {
        if ($download_dir == null)
            return null;
        $handle = opendir($download_dir);
        while (false !== ($fichier = readdir($handle))) {
            if (($fichier != ".") && ($fichier != "..")) {
                unlink($download_dir . $fichier);
            }
        }
    }

    public static function transformNameToId($string = "") {
        $string = strtolower($string);
        $new_string = str_replace(" ", "_", $string);

        return self::removeAccents($new_string);
    }

    public static function removeAccents($str, $charset = 'utf-8') {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return $str;
    }

    //Interprétation d'une description d'image de notice contenant des chaines de la forme #{text}
    public static function parseDescImg($description,$id_fonction, $profileParams) {
        $tab_params=  Doctrine_Core::getTable('EiParam')->getParamByFunctionId($id_fonction);
        
        $pattern = "#(?<!\#)\#\{[\w./]*}#"; //chaine regex de matching
        if (!(count($tab_params)>0) && !(count($profileParams)>0))
            return $description;
        
        $nbMatched = preg_match_all($pattern, $description, $matches); //On recherche les expressions respectant le regex
        if (isset($matches) && $nbMatched > 0) { // Si on a retrouvé des expressions 
            $toReplace = array();
            // pour chaque élément qui a matché le pattern
            foreach ($matches[0] as $m => $param) {
                //on protège notre expression afin que celle-ci ne soit pas considérer comme un pattern de regex
                $matches[0][$m] = '#\\' . preg_quote($param) . '#';
                //puis l'on créer la chaine de remplacement correspondante

                $toReplace[$m] = $param;
                if(count($tab_params)>0){
                    foreach ($tab_params as $p) {
                    //On vérifie si un paramètre de la fonction correspond à la valeur contenue dans #{text}
                    if ($p['EiFunctionHasParam']['name'] == substr($param, 2, -1))
                        $toReplace[$m] = $p['valeur']; //si oui on récupère cette valeur pour remplacer le #{text} dans la description
                }
                } 
                
                //Interprétation des paramètres définis dans le profil en question
                if(count($profileParams)>0){
                    foreach ($profileParams as $pp) {
                    //On vérifie si un paramètre de la fonction correspond à la valeur contenue dans #{text}
                    if ($pp->getName() == substr($param, 2, -1))
                        $toReplace[$m] = $pp->getValue(); //si oui on récupère cette valeur pour remplacer le #{text} dans la description
                 
                    }
                }
                
            }
            //On retourne la description avec toutes les valeurs remplacées dans les cas le necessitant
            return preg_replace($matches[0], $toReplace, $description);
        }

        return $description;
    }
    
    public static function parseAndExtractParamsValue($ch,$params,$profileParams){
//        $ch=html_entity_decode(str_replace('&nbsp;', ' ', $ch));
        $ch=html_entity_decode($ch);
          if(is_array($params))://Si des paramètres existent pour la fonction
              //var_dump($params);
          //On analyse la description pour interpréter les paramètres variables 
        // regex recherchant tous les paramètres qui sont précédé d'un nombre pair de # 
        //(soit un nombre impair de # avant l'accolade ouvrante). 
        //                        |--1--||------2-----|
        $patternImpair = "#(?<!\#)(\#\#)*(\#\{[\w./]*})#";
        //$patternImpair = "#(\$(\$\$)*\{[\w'-./]+\})#";
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
                
                /* On vérifie si un paramètre de la fonction a le même nom que la chaine */
                if(count($params)>0):
                foreach ($params as $param):
                    if($param['name']==$to_matche):   
                        $toReplace[$m] = $matches[1][$m]. $param['valeur']; 
                    endif;
                endforeach;
                endif;
                
                /* On vérifie s'il n'ya pas de paramètre de profil à interpréter  */ 
                if(count($profileParams)>0): 
                    foreach ($profileParams as $profileParam) :
                    if(isset($profileParam['upp_id'])): 
                        $val=$profileParam['upp_value'];
                        else:
                            $val=$profileParam['value']; 
                    endif;
                    //On vérifie si un paramètre de la fonction correspond à la valeur contenue dans #{text}
                    if ($profileParam['name'] == $to_matche ):
                        $toReplace[$m] = $val; //si oui on récupère cette valeur pour remplacer le #{text} dans la description
            
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

    public static function parseAndExtractOutParamsValue($ch,$params){
        $ch=html_entity_decode($ch);  
        
          if(is_array($params) && count($params)>0)://Si des paramètres existent pour la fonction  
             // var_dump($params);
          //On analyse la description pour interpréter les paramètres variables 
        // regex recherchant tous les paramètres qui sont précédé d'un nombre pair de # 
        //(soit un nombre impair de $ avant l'accolade ouvrante). 
        //                        |--1--||------2-----| 
        $patternImpair = "~(\\$\\$)*(\\$\\{[\\w\'-./]+\\})~";
        $nbMatchedImpair = preg_match_all($patternImpair, $ch, $matches);
        
        //$matches[0] contient les résultats complets.
        //$matches[1] contient la série de $ précédant le paramètre pour l'expression matchée
        //$matches[2] contient le paramètre à proprement parler (la forme ${})
            
        if(isset($matches) && $nbMatchedImpair > 0 ):  //var_dump($matches);
            $toReplace = array();
            //var_dump($matches[0]);
            // pour chaque élément qui a matché le pattern
            foreach($matches[0] as $m => $matche){
                //on protège notre expression afin que celle-ci ne soit pas considérer comme un pattern de regex
                $matches[0][$m] = '#'.preg_quote($matche, '#').'#';
                //puis l'on créer la chaine de remplacement correspondante
                $to_matche=substr($matches[2][$m], 2, -1);
                
                /* On vérifie si un paramètre de la fonction a le même nom que la chaine */
                if(count($params)>0):
                foreach ($params as $param):  
                    if($param['param_name']==$to_matche):   
                        $toReplace[$m] = $matches[1][$m]. $param['param_valeur']; 
                    endif;
                endforeach;
                endif;

                if( !isset($toReplace[$m]) ){
                    $toReplace[$m] = "";
                }
            } 
            //echo $ch;
            //var_dump($matches[0]);
            //on replace pour chaque $matches par la nouvelles chaine générée puis stockées dans $toReplace
            //dans la chaine représentant la valeur de notre EiParam
            $res = preg_replace($matches[0], $toReplace, $ch);
            
        //si aucune valeur n'a été trouvée, alors on retournera simplement la valeur du paramètre.
        else :
            $res = $ch;  
        endif;
        //on transforme $$ en $.
        
        return preg_replace("#\\$\\$#", "$", $res);  
        else:  
            return  $ch  ;
        endif;  
    }
    
     /*
     * Traitement de l'envoi en post du fichier json pour création d'une fonction
     */
    public static function loadResultOfWebServiceByPost($url,$params){
        if ($url == null)
            return null;
        $cobj = curl_init($url); // créer une nouvelle session cURL
        if ($cobj) { 
            curl_setopt_array($cobj, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Codular Sample cURL Request',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS =>  $params
            ));
            
            
            $xml = curl_exec($cobj); //execution de la requete curl 
            curl_close($cobj); //liberation des ressources 
            $xmlDoc = new DOMDocument("1.0", "utf-8");
            $xmlDoc->loadXML($xml);
            return $xmlDoc->saveXML();
        }
        return null;  
    }
    public static function loadResultOfWebServiceByPostJson($url,$params){
        if ($url == null)
            return null;
        $cobj = curl_init($url); // créer une nouvelle session cURL
        if ($cobj) { 
            curl_setopt_array($cobj, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Codular Sample cURL Request',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS =>  $params
            ));
            
            
            $xml = curl_exec($cobj); //execution de la requete curl 
            curl_close($cobj); //liberation des ressources 
            return $xml;
        }
        return null;  
    }
    
    
//Chemin vers un noeud de l'arbre suivant son type
    public static function getImgTreeNode($node) {
        $img_node = "";  
            
        switch (($node['type'])) {
            case 'View': 
                $img_node=ei_icon('ei_folder',null,'img_node',"Function folder node" ,"function_folder_node") ;
                break;
            case 'ShortCut': 
                $img_node='<i class="fa fa-folder img_node" title="Shortcut"></i>';
                break;
            case "Function": 
                $img_node=$img_node=ei_icon('ei_function',null,'img_node',"Function node") ;;
                break;

            default:
                break;
        }
        return $img_node;
    }

    //Chemin vers un noeud de l'arbre suivant son type
    public static function getPathToTreeNode($node,$url_tab) {
        $img_node="";
        $path_to_node="";
        if($node!=null ){
            switch (($node->getType())) {
                case 'EiScenario':
                    $img_node = ei_icon('ei_scenario',null,'ei-scenario')  ;  
                    $path_to_node = '@projet_new_eiversion?ei_scenario_id=' . $node->getObjId() .'&profile_name='.$url_tab['profile_name']. 
                            '&profile_id='.$url_tab['profile_id'].'&profile_ref='.$url_tab['profile_ref']. 
                            '&project_id=' . $url_tab['project_id'] . '&project_ref=' . $url_tab['project_ref'].
                            '&action=editVersionWithoutId';
                    break;
                case 'EiFolder':
                    $img_node = ei_icon('ei_folder',null,'ei-folder')  ;
                    $path_to_node = '@path_folder?folder_id='.$node->getObjId().'&node_id='.$node->getId().
                        '&project_id='.$url_tab['project_id'].'&project_ref='.$url_tab['project_ref'].
                            '&profile_id='.$url_tab['profile_id'].'&profile_ref='.$url_tab['profile_ref'].
                            '&profile_name='.$url_tab['profile_name'].
                            '&action=edit'; 
                    break;
                
                default:
                    break;
            }
        }
        
        return array("img_node" =>$img_node, "path_to_node" =>$path_to_node);
    }
    public static function   troncatedText($text , $size = 10) {
            /**
     * Retourne un texta raccourci.
     * @param int $size la taille totale de la chaine à retourner.
     * @return string
     * @throws InvalidArgumentException
     */  
        
        if($size <= 0)
            throw new InvalidArgumentException('Invalid size value for troncate. '. $size . ' is not a valid value.');
            
        if (strlen($text) > $size):
            return substr($text, 0, $size-3) . '...';
        else:
            return $text;
        endif;
         
    }

    public static function xml_join($root, $append) {

        if ($append) {
            if (strlen(trim((string) $append))==0) {
                $xml = $root->addChild($append->getName());
                foreach($append->children() as $child) {
                    self::xml_join($xml, $child);
                }
            } else {
                $xml = $root->addChild($append->getName(), (string) $append);
            }
            foreach($append->attributes() as $n => $v) {
                $xml->addAttribute($n, $v);
            }
        }
    }

    /**
     * @param $documentXsd
     * @return DOMElement
     */
    public static function createSchemaXSD(&$documentXsd)
    {
        // Création du document.
        $documentXsd = new DOMDocument("1.0", "UTF-8");

        // On crée la balise indiquant qu'il s'agit d'un schéma XSD.
        $schema = $documentXsd->createElement("xs:schema");
        $schema->setAttribute('xmlns:xs', "http://www.w3.org/2001/XMLSchema");
        // Puis, on l'ajoute au document.
        $documentXsd->appendChild($schema);

        return $schema;
    }

    /**
     * Génère un élément XSD à partir d'un document et d'attributs renseignés.
     *
     * @param DOMDocument $xsd
     * @param array $attributes
     * @return DOMElement
     */
    public static function generateXSDElement(DOMDocument $xsd, array $attributes = array())
    {
        // Création de l'élément.
        $element = $xsd->createElement("xs:element");

        // Ajout des attributes.
        foreach( $attributes as $attr => $valeur ){
            $element->setAttribute($attr, $valeur);
        }

        $type = $xsd->createElement("xs:complexType");

        $sequence = $xsd->createElement("xs:sequence");

        $sequence->setAttribute('minOccurs', 0);
        $sequence->setAttribute('maxOccurs', 1);

        $type->appendChild($sequence);

        $element->appendChild($type);


        return $element;
    }

    /**
     * @param $string
     * @return string
     */
    public static function xml_entities($string) {
        return strtr(
            $string,
            array(
                "<" => "&lt;",
                ">" => "&gt;",
                '"' => "&quot;",
                "'" => "&apos;",
                "&" => "&amp;",
            )
        );
    }
}

?>
