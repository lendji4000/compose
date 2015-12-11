<?php

/**
 * eifonction actions.
 *
 * @package    kalifast
 * @subpackage presentation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class presentationActions extends sfActions {

    public function executeA_propos(sfWebRequest $request) {
        
    }
    public function executeContact(sfWebRequest $request) {

    }
    public function executePartenaire(sfWebRequest $request) {

    }
    public function executeDon(sfWebRequest $request) {

    }
    public function executeAide(sfWebRequest $request) {
        //Récupération du module
        if($request->getParameter('eiprojet')) $this->eimodule='eiprojet';
        if($request->getParameter('eiscenario')) $this->eimodule='eiscenario';
        if($request->getParameter('eifonction')) $this->eimodule='eifonction';
        if($request->getParameter('eiversion')) $this->eimodule='eiversion';
        if($request->getParameter('eiprofil')) $this->eimodule='eiprofil';
        if($request->getParameter('eilog')) $this->eimodule='eilog';
        
        
    }

}
