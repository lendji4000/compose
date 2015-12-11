<?php

/**
 * eiprofil actions.
 *
 * @package    kalifast
 * @subpackage eiprofil
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiprofilActions extends sfActions {
 
   
    /**
     * Fonction de redirection au changement de profils dans le menu latéral gauche.
     * @param sfWebRequest $request
     * @return type 
     */
    public function executeForwardTo(sfWebRequest $request) {
        //récupération et parsing de l'URL référente.
        $referer = $request->getReferer();
        $url = parse_url($referer);
        $path = trim($url['path'], '/');
        
        if (!sfConfig::get('sf_no_script_name') && $pos = strpos($path, '/')) {
            $path = substr($path, $pos + 1);
        }

        //récupération et setting des paramètres du profils
        $params = sfContext::getInstance()->getRouting()->findRoute('/' . $path);
        $params['parameters']['profile_id'] = $request->getParameter('profile_id');
        $params['parameters']['profile_ref'] = $request->getParameter('profile_ref');
        
        $params['parameters']['profile_name'] = Doctrine_Core::getTable('EiProfil')
                ->findOneByProfileRefAndProfileId($request->getParameter('profile_ref'), $request->getParameter('profile_id'))
                ->getName();

        $URLParams =  array(
                    'project_id' => $request->getParameter('project_id'),
                    'project_ref' => $request->getParameter('project_ref'),
                    'profile_id' => $params['parameters']['profile_id'],
                    'profile_ref' => $params['parameters']['profile_ref'],
                    'profile_name'  => $params['parameters']['profile_name'],
                    'ei_scenario_id' => $request->getParameter('ei_scenario_id'),
                    'action' => $params['parameters']['action']
        );

        // Ajout des paramètres obligatoires manquants.
        $paramsOmis = array_diff_key($params['parameters'], $URLParams, array("module" => "", "sf_culture" => ""));

        foreach( $paramsOmis as $ind => $p ){
            $URLParams[$ind] = $p;
        }

        //Avant la redirection , on change les données du profil en session utilisateur 
        $this->getUser()->setAttribute("current_profile_name", $params['parameters']['profile_name']);
        $this->getUser()->setAttribute("current_profile_id", $params['parameters']['profile_id']);
        $this->getUser()->setAttribute("current_profile_ref", $params['parameters']['profile_ref']);

        return $this->redirect($params['name'], $params['parameters']);
    }

    
    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $ei_profile = $form->save();

            $this->redirect('eiprofil/index');
        }
    }

}
