<?php

/**
 * eifonction actions.
 *
 * @package    kalifast
 * @subpackage eifonction
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class connexionActions extends sfActions {

    public function executeLogin(sfWebRequest $request) {
        $this->setLayout('layout_auth');
    }
    public function executeError500(sfWebRequest $request){
         $this->setLayout('layout_error');
    } 

    public function executeSignin(sfWebRequest $request) {
        $login = $request->getParameter('login');
        $pwd = $request->getParameter('pwd');
        if ($login != null && $pwd != null) {

            try {
                $result_connect = MyFunction::connexionDistante($login, $pwd);
                //Si le fichier est vide
                if ($result_connect == null)
                    throw new Exception('Empty File . Contact administrator');
            } catch (Exception $e) {
                $this->getUser()->setFlash('error_connexion', $e->getMessage());
                $this->redirect('@homepage');
            }

            if (is_array($result_connect)) {
                $guard_tab = $result_connect['guard_tab'];

                //On vérifie que l'utilisateur n'est pas désactivé coté script
                if ($guard_tab['is_active'] != 1) {
                    $this->getUser()->setFlash('error_connexion', sprintf('Inactive user . Contact administrator to no more about'));
                    $this->redirect('@homepage');
                }

                $ei_user_tab = $result_connect['ei_user_tab'];
                $guard_user = Doctrine_Core::getTable("sfGuardUser")->findOneBy('username', $guard_tab['username']);

                /*
                 * Si l'utilisateur se connecte pour la premiere fois 
                 * ou s'il n'est pas enregistré , on le crée 
                 */
                if (!$guard_user || $guard_user == null)  
                    $guard_user = Doctrine_Core::getTable('sfGuardUser')->createUser($guard_tab, $ei_user_tab);
                
                $this->getUser()->setAttribute('user_id', $guard_user->getId(), 'sfGuardSecurityUser');
                $this->getUser()->setAuthenticated(true);
                
                $this->getUser()->setFlash('valid_connexion', sprintf('connexion accepted'));

                //$this->redirect('@recharger_projet');
                $signinUrl =  $request->getReferer();
                return $this->redirect('' != $signinUrl ? $signinUrl : '@homepage');
            } else {
                if ($result_connect == null)
                    $this->getUser()->setFlash('error_connexion', sprintf('Connexion error , unexpected raison ... Contact administrator'));
                else
                    $this->getUser()->setFlash('error_connexion', sprintf($result_connect));
                $this->redirect('@homepage');
            }
        }
        else
            $this->getUser()->setFlash('error_connexion', sprintf('Empty fields.'));

        return $this->redirect('@homepage');
    }

}

