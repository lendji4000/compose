<?php

/**
 * eifunctionnotice actions.
 *
 * @package    kalifastRobot
 * @subpackage eifunctionnotice
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eifunctionnoticeActions extends sfActionsKalifast {
    /* Cette fonction permet de rechercher la fonction (EiFonction) avec les paramètres renseignés.  */

    public function checkEiFonction(sfWebRequest $request, EiProjet $ei_project) {
        $this->ei_fonction_id = $request->getParameter('ei_fonction_id');

        if ($this->ei_fonction_id != null) {
            //Recherche de la fonction en base
            $this->ei_fonction = Doctrine_Core::getTable('EiFonction')
                    ->findOneByIdAndProjectIdAndProjectRef(
                    $this->ei_fonction_id, $ei_project->getProjectId(), $ei_project->getRefId());
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->ei_fonction == null)
                $this->ei_fonction = null;
        }
        else {
            $this->ei_fonction_id = null;
        }
    }

    /* Recherche d'une EiVersion */

    public function checkFunctionNoticeVersion(sfWebRequest $request) {
        if (($this->ei_version_id = $request->getParameter('ei_version_id')) != null) {
            //Recherche du projet en base
            $this->ei_version = Doctrine_Core::getTable('EiVersion')->findOneById($this->ei_version_id);
            //Si la version n'existe pas , alors on retourne un erreur 404
            if ($this->ei_version == null)
                $this->forward404('Version not found with these parameters ...');
        }
        else {
            $this->forward404('Missing version parameters  ...');
        }
    }

    /* Recherche d'une notice fonction */

    public function findEiFunctionNotice(sfWebRequest $request) {
        if (($this->ei_version_id = $request->getParameter('ei_version_id')) != null &&
                ($this->ei_fonction_id = $request->getParameter('ei_fonction_id')) != null &&
                ($this->lang = $request->getParameter('lang')) != null) {
            //Recherche de la notice en base 
            $this->ei_function_notice = Doctrine_Core::getTable('EiFunctionNotice')
                    ->findOneByLangAndEiVersionIdAndEiFonctionId(
                    $this->lang, $this->ei_version_id, $this->ei_fonction_id);
        } else {
            $this->forward404('Missing Notice parameters  ...');
        }
    } 
    
    /* Cette fonction permet de rechercher la langue d'une version de notice avec les paramètres renseignés.  */

    public function checkNoticeVersionLang(sfWebRequest $request) {
        if (($this->notice_ref = $request->getParameter('notice_ref')) != null &&
                ($this->notice_id = $request->getParameter('notice_id')) != null &&
                ($this->version_notice_id = $request->getParameter('version_notice_id')) != null &&
                ($this->lang = $request->getParameter('lang')) != null) {
            //Recherche de la notice en base
            $this->ei_version_notice = Doctrine_Core::getTable('EiVersionNotice')
                    ->findOneByNoticeIdAndNoticeRefAndVersionNoticeIdAndLang(
                    $this->notice_id, $this->notice_ref, $this->version_notice_id, $this->lang);

            //Si la version de notice  n'existe pas , alors on retourne un erreur 404
            if ($this->ei_version_notice == null)
                $this->forward404('notice version  not found with these parameters ...');
        }
        else {
            $this->forward404('Missing notice version  parameters  ...');
        }
    }

     /* Edition de la notice par défaut */
    public function executeEditDefaultNotice(sfWebRequest $request){
        $this->checkProject($request);  
        $this->checkProfile($request, $this->ei_project); // Recherche du profil en base 
        $this->checkEiFonction($request, $this->ei_project); //Recherche de la EiFonction en base
        $this->checkFunctionNoticeVersion($request); //Recherche de la EiVersion en base 
        $this->checkNoticeVersionLang($request);
        $partialParams=$this->urlParameters;
        $partialParams['ei_version_notice']=$this->ei_version_notice;
        $partialParams['ei_version_id']=$this->ei_version->getId();
        $partialParams['ei_fonction_id']=$this->ei_fonction->getId(); 
        /* Construction du formulaire de la version de notice par défaut */
        $partialParams['form']=new EiVersionNoticeForm($this->ei_version_notice);
        $this->getAndParseFunctionAndProjectParametersToArray($this->ei_profile, $this->ei_fonction); //Récupération des paramètres de fonction et de projet sous forme de tableau 
        return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eifunctionnotice/editDefaultNotice', $partialParams),
                        'inParameters' => $this->inTabParameters,
                        'outParameters' => $this->outTabParameters,    
                        'success' => true)));
        return sfView::NONE;
    }
    /* Mise à jour de la notice par défaut */
    public function executeUpdateDefaultNotice(sfWebRequest $request){
        $this->checkProject($request);  
        $this->checkProfile($request, $this->ei_project); // Recherche du profil en base 
        $this->checkEiFonction($request, $this->ei_project); //Recherche de la EiFonction en base
        $this->checkFunctionNoticeVersion($request); //Recherche de la EiVersion en base 
        $this->checkNoticeVersionLang($request); 
        $this->form=new EiVersionNoticeForm($this->ei_version_notice);
        $this->processDefaultNoticeForm($request, $this->form);
        if($this->success):  
            return $this->renderText(json_encode(array(
                        'html' => "Well done . Notice has been updated successfully ...",
                        'alert_class' => "alert-success",
                        'alert_message' => "Well done . Notice has been updated successfully ...",
                        'updateMode' =>true,
                        'success' => true)));
            else:
                $partialParams=$this->urlParameters;
                $partialParams['ei_version_notice']=$this->ei_version_notice;
                $partialParams['ei_version_id']=$this->ei_version->getId();
                $partialParams['ei_fonction_id']=$this->ei_fonction->getId(); 
                /* Construction du formulaire de la version de notice par défaut */
                $partialParams['form']=$this->form;
                return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eifunctionnotice/editDefaultNotice', $partialParams),  
                        'success' => false)));
        endif;
        return sfView::NONE;
    }
    /* Recherche de la version de notice associé à un profil ( sur script.kalifast ) */

    public function findDefaultEiFunctionNotice(EiProfil $ei_profile, EiFonction $ei_fonction, $lang) {
        $this->ei_version_notices = Doctrine_Core::getTable('EiVersionNotice')->findNoticeVersionForProfile($ei_profile, $ei_fonction, $lang);
        $this->ei_version_notice = null;
        if (count($this->ei_version_notices) > 0)
            $this->ei_version_notice = $this->ei_version_notices->getFirst();

        if ($this->ei_version_notice == null)//Aucune notice n'est trouvé sur la plate forme centrale , il s'agit d'une erreur système
            $this->forward404('Make sure there is a notice version associate to this Environment in central system .Contact administrator if the problem persist');
    }

    /* Recherche des langues d'une version de notice provenant de la plate forme centrale (script.kalifast)  */

    public function findDefaultEiFunctionNoticeLangs(EiVersionNotice $vn) {
        $this->functionNoticeLangs = Doctrine_Core::getTable('EiVersionNotice')->findByVersionNoticeIdAndNoticeIdAndNoticeRef(
                $vn->getVersionNoticeId(), $vn->getNoticeId(), $vn->getNoticeRef());
    }

    public function executeIndex(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project); // Recherche du profil en base 
        $this->ei_function_notices = Doctrine_Core::getTable('EiFunctionNotice')
                ->createQuery('a')
                ->execute();
    }

    /* Visuel de la notice (oracle d'une fonction ) */

    public function executeShow(sfWebRequest $request) {       
        $this->checkProject($request);  
        $this->checkProfile($request, $this->ei_project); // Recherche du profil en base 
        $this->checkEiFonction($request, $this->ei_project); //Recherche de la EiFonction en base
        $this->checkFunctionNoticeVersion($request); //Recherche de la EiVersion en base
        $this->lang = $request->getParameter('lang');

        //On recherche la version de notice associé au profil dans la plate forme centrale (script)
        $this->findDefaultEiFunctionNotice($this->ei_profile, $this->ei_fonction, $this->lang);

        //On recherche les différentes langues de la version de notice
        $this->findDefaultEiFunctionNoticeLangs($this->ei_version_notice);

        //On commence par regarder si la notice a été redéfinie sur l'environnement client (Compose)
        //Recherche de la notice de la fonction dans le système client
        $this->findEiFunctionNotice($request); //Recherche de la EiFonction en base
        //
    //Si la notice n'existe pas , on récupère celle définit sur la plate forme centrale
        if ($this->ei_function_notice == null) {

            //Retour de la notice par défaut définie sur la centrale (script.kalifast)
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eifunctionnotice/showDefaultNotice', array('ei_version_notice' => $this->ei_version_notice,
                            'ei_version_id' => $this->ei_version->getId(),
                            'ei_fonction_id' => $this->ei_fonction->getId(),
                            'project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name,
                            'functionNoticeLangs' => $this->functionNoticeLangs)),
                        'success' => true)));
        } else {
            //Retour de la notice surchargée par le client  (sur compose.kalifast)
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eifunctionnotice/show', array('ei_function_notice' => $this->ei_function_notice,
                            'ei_version_id' => $this->ei_version->getId(),
                            'ei_fonction_id' => $this->ei_fonction->getId(),
                            'project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name,
                            'functionNoticeLangs' => $this->functionNoticeLangs)),
                        'success' => true)));
        }

        return sfView::NONE;
    }

    //Parsing des paramètres de fonction et de projet sous forme de tableau
    public function parseFunctionAndProjectParamsToArray($InFunctionsParameters, $OutFunctionsParameters, $projectParams) {
        $inTabParameters = array();
        $outTabParameters = array();
        //Parse inParameters to array 
        if (count($InFunctionsParameters)):
            foreach ($InFunctionsParameters as $i => $inParameter):
                $inTabParameters[$i] = $inParameter->getName();
            endforeach;
        endif;
        //Parse out parameters to array 
        if (count($OutFunctionsParameters)):
            foreach ($OutFunctionsParameters as $i => $outParameter):
                $outTabParameters[$i] = $outParameter->getName();
            endforeach;
        endif;
        //Parse global parameters ( project parameters) to array
        if (count($projectParams)):
            foreach ($projectParams as $i => $inProjectParameter): 
                if($inProjectParameter->getParamType()== "IN"): //Si param de type "IN"
                    $inTabParameters[] = $inProjectParameter->getName();
                endif;
                if($inProjectParameter->getParamType()== "OUT"): //Si param de type "OUT"
                    $outTabParameters[] = $inProjectParameter->getName();
                endif; 
            endforeach;
        endif;

        $this->inTabParameters = $inTabParameters;
        $this->outTabParameters = $outTabParameters;
    }

    /* Récupération des paramètres de fonction et de projet pour le formulaire d'oracle (notice)
     * d'une fonction chez le client (compose)
     */

    public function getAndParseFunctionAndProjectParametersToArray(EiProfil $ei_profile, EiFonction $ei_fonction) {
        $this->kal_fonction = $ei_fonction->getKalFonction(); //Récupération de la fonction de la plate forme centrale (script)
        $this->InFunctionsParameters = $this->kal_fonction->getInKalParams(); //Récupération  des paramètres d'entrée de la fonction
        $this->OutFunctionsParameters = $this->kal_fonction->getOutKalParams(); //Récupération  des paramètres de sortie de la fonction
        $this->ei_project = $ei_profile->getProject();
        $this->projectParams = $this->ei_project->getGlobalParams(); //Paramètres du projet
        /* Transformation des resultats en tableaux pour le json */
        $this->parseFunctionAndProjectParamsToArray(
                $this->InFunctionsParameters, $this->OutFunctionsParameters, $this->projectParams);
    }

    //Surcharge de la notice d'une fonction de la plate forme centrale (script)
    public function executeNew(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project); // Recherche du profil en base 
        $this->checkEiFonction($request, $this->ei_project); //Recherche de la EiFonction en base
        $this->checkFunctionNoticeVersion($request); //Recherche de la EiVersion en base 
        $this->checkNoticeVersionLang($request); //Recherche de la version de notice
        $this->getAndParseFunctionAndProjectParametersToArray($this->ei_profile, $this->ei_fonction); //Récupération des paramètres de fonction et de projet sous forme de tableau
        //On recherche les différentes langues de la version de notice
        $this->findDefaultEiFunctionNoticeLangs($this->ei_version_notice);

        $ei_function_notice = new EiFunctionNotice();
        $ei_function_notice->setDescription($this->ei_version_notice->getDescription());
        $ei_function_notice->setExpected($this->ei_version_notice->getExpected());
        $ei_function_notice->setResult($this->ei_version_notice->getResult());
        $ei_function_notice->setEiFonctionId($this->ei_fonction->getId());
        $ei_function_notice->setEiVersionId($this->ei_version->getId());
        $ei_function_notice->setLang($this->ei_version_notice->getLang());
        /* On recopie les propriétes de le notice de la plate forme centrale */
        $this->form = new EiFunctionNoticeForm($ei_function_notice);
        // Retour du partiel résultat 
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eifunctionnotice/new', array('form' => $this->form,
                        'url_form' => $this->generateUrl('createFunctionNotice', array('ei_version_id' => $this->ei_version->getId(),
                            'ei_fonction_id' => $this->ei_fonction->getId(),
                            'lang' => $this->ei_version_notice->getLang(),
                            'project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name)),
                        'functionNoticeLangs' => $this->functionNoticeLangs,
                        'ei_version_id' => $this->ei_version->getId(),
                        'ei_fonction_id' => $this->ei_fonction->getId(),
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name
                    )),
                    'inParameters' => $this->inTabParameters,
                    'outParameters' => $this->outTabParameters,
                    'creationMode' => true,
                    'success' => true)));

        return sfView::NONE;
    }

    public function executeCreate(sfWebRequest $request) {
        $this->lang = $request->getParameter('lang');
        if ($this->lang == null)
            $this->forward404('Missing Notice Language...');
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project); // Recherche du profil en base 
        $this->checkEiFonction($request, $this->ei_project); //Recherche de la EiFonction en base
        $this->checkFunctionNoticeVersion($request); //Recherche de la EiVersion en base 
        $ei_function_notice = new EiFunctionNotice();
        $ei_function_notice->setEiFonctionId($this->ei_fonction->getId());
        $ei_function_notice->setEiVersionId($this->ei_version->getId());
        $ei_function_notice->setLang($this->lang);
        $this->form = new EiFunctionNoticeForm($ei_function_notice);

        $this->processForm($request, $this->form);

        if ($this->success): //Si la validation s'est bien passée , on revoit la route de mise à jour
            return $this->renderText(json_encode(array(
                        'url_form' => $this->generateUrl('updateFunctionNotice', array('ei_version_id' => $this->ei_function_notice->getEiVersionId(),
                            'ei_fonction_id' => $this->ei_function_notice->getEiFonctionId(),
                            'lang' => $this->ei_function_notice->getLang(),
                            'project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name)),
                        'modal_footer' => $this->getPartial('eifunctionnotice/modal_footer_show', array(
                            'ei_function_notice' => $this->ei_function_notice,
                            'ei_profile' => $this->ei_profile
                        )),
                        'alert_message' => 'Overwriting of notice has been done successfully...',
                        'alert_class' => 'alert alert-success',
                        'creationMode' => true,
                        'success' => true)));
        else: //Echec , on renvoit le formulaire complet avec les erreurs
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eifunctionnotice/form', array('form' => $this->form,
                            'url_form' => $this->generateUrl('createFunctionNotice', array('ei_version_id' => $this->ei_version->getId(),
                                'ei_fonction_id' => $this->ei_fonction->getId(),
                                'lang' => $this->lang,
                                'project_id' => $this->project_id,
                                'project_ref' => $this->project_ref,
                                'profile_id' => $this->profile_id,
                                'profile_ref' => $this->profile_ref,
                                'profile_name' => $this->profile_name))
                        )),
                        'success' => false)));
        endif;
        return sfView::NONE;
    }

    public function executeEdit(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project); // Recherche du profil en base 
        $this->checkEiFonction($request, $this->ei_project); //Recherche de la EiFonction en base
        $this->checkFunctionNoticeVersion($request); //Recherche de la EiVersion en base 
        $this->findEiFunctionNotice($request); //Recherche de la EiFonction en base 
        $this->getAndParseFunctionAndProjectParametersToArray($this->ei_profile, $this->ei_fonction); //Récupération des paramètres de fonction et de projet sous forme de tableau
        //On recherche la version de notice associé au profil dans la plate forme centrale (script)
        $this->findDefaultEiFunctionNotice($this->ei_profile, $this->ei_fonction, $this->ei_function_notice->getLang());

        //On recherche les différentes langues de la version de notice
        $this->findDefaultEiFunctionNoticeLangs($this->ei_version_notice);

        $this->form = new EiFunctionNoticeForm($this->ei_function_notice);
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eifunctionnotice/edit', array('form' => $this->form,
                        'url_form' => $this->generateUrl('updateFunctionNotice', array('ei_version_id' => $this->ei_function_notice->getEiVersionId(),
                            'ei_fonction_id' => $this->ei_function_notice->getEiFonctionId(),
                            'lang' => $this->ei_function_notice->getLang(),
                            'project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name)),
                        'ei_version_id' => $this->ei_function_notice->getEiVersionId(),
                        'ei_fonction_id' => $this->ei_function_notice->getEiFonctionId(),
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'profile_name' => $this->profile_name,
                        'functionNoticeLangs' => $this->functionNoticeLangs
                    )),
                    'inParameters' => $this->inTabParameters,
                    'outParameters' => $this->outTabParameters,
                    'updateMode' => true,
                    'success' => true)));
        return sfView::NONE;
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project); // Recherche du profil en base 
        $this->checkEiFonction($request, $this->ei_project); //Recherche de la EiFonction en base
        $this->findEiFunctionNotice($request); //Recherche de la EiFonction en base 
        $this->form = new EiFunctionNoticeForm($this->ei_function_notice);

        $this->processForm($request, $this->form);

        if ($this->success): //Si la validation s'est bien passée , on revoit la route de mise à jour
            return $this->renderText(json_encode(array(
                        'url_form' => $this->generateUrl('updateFunctionNotice', array('ei_version_id' => $this->ei_function_notice->getEiVersionId(),
                            'ei_fonction_id' => $this->ei_function_notice->getEiFonctionId(),
                            'lang' => $this->ei_function_notice->getLang(),
                            'project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'profile_id' => $this->profile_id,
                            'profile_ref' => $this->profile_ref,
                            'profile_name' => $this->profile_name)),
                        'updateMode' => true,
                        'alert_message' => 'Update of notice has been done successfully...',
                        'alert_class' => 'alert alert-success',
                        'success' => $this->success)));
        else: //Echec , on renvoit le formulaire complet avec les erreurs
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eifunctionnotice/form', array('form' => $this->form,
                            'url_form' => $this->generateUrl('updateFunctionNotice', array('ei_version_id' => $this->ei_function_notice->getEiVersionId(),
                                'ei_fonction_id' => $this->ei_function_notice->getEiFonctionId(),
                                'lang' => $this->ei_version_notice->getLang(),
                                'project_id' => $this->project_id,
                                'project_ref' => $this->project_ref,
                                'profile_id' => $this->profile_id,
                                'profile_ref' => $this->profile_ref,
                                'profile_name' => $this->profile_name))
                        )),
                        'success' => $this->success)));
        endif;

        return sfView::NONE;
    }

    public function executeDelete(sfWebRequest $request) {
        //$request->checkCSRFProtection();
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project); // Recherche du profil en base 
        $this->checkEiFonction($request, $this->ei_project); //Recherche de la EiFonction en base
        $this->checkFunctionNoticeVersion($request); //Recherche de la version 
        $this->findEiFunctionNotice($request); //Recherche de la notice en base
        //On recherche la version de notice associé au profil dans la plate forme centrale (script)
        $this->findDefaultEiFunctionNotice($this->ei_profile, $this->ei_fonction, $this->ei_function_notice->getLang());

        //On recherche les différentes langues de la version de notice
        $this->findDefaultEiFunctionNoticeLangs($this->ei_version_notice);


        $this->ei_function_notice->delete(); //Suppression de la surcharge
        $this->findDefaultEiFunctionNotice($this->ei_profile, $this->ei_fonction, $this->lang);
        //Si tout s'est bien déroulé
        //Retour de la notice par défaut définie sur la centrale (script.kalifast)
        return $this->renderText(json_encode(array(
                    'html' => $this->getPartial('eifunctionnotice/showDefaultNotice', array('ei_version_notice' => $this->ei_version_notice,
                        'ei_fonction_id' => $this->ei_fonction->getId(),
                        'ei_version_id' => $this->ei_version->getId(),
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'profile_name' => $this->profile_name,
                        'functionNoticeLangs' => $this->functionNoticeLangs)),
                    'alert_message' => 'Default notice has been well restore...',
                    'alert_class' => 'alert alert-success',
                    'success' => true)));
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $this->ei_function_notice = $form->save();
            $this->success = true;
        } else {
            $this->success = false;
        }
    }
    protected function processDefaultNoticeForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $this->success=$this->ei_version_notice->updateCentralNotice(array(
                "description" => $form->getValue("description"),
                "expected" => $form->getValue("expected"),
                "result" => $form->getValue("result"),
            ))  ;
            $this->success = true;
        } else {
            $this->success = false;
        }
    }

}
