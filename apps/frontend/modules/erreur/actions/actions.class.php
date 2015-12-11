<?php

/**
 * erreur actions.
 *
 * @package    kalifastRobot
 * @subpackage erreur
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class erreurActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  public function executeError400(sfWebRequest $request){

    }
    public function executeError460(sfWebRequest $request){

    }
    public function executeError404(sfWebRequest $request){
        $this->msg=$request->getParameter('msg');
        $this->back_link=$request->getParameter('back_link');
    }
    public function executeError500(sfWebRequest $request){

    }
    public function executeError505(sfWebRequest $request){

    }
    public function executeError600(sfWebRequest $request){

    }
    public function executeError604(sfWebRequest $request){

    }
    public function executeError700(sfWebRequest $request){

    }
    public function executeError704(sfWebRequest $request){

    }
}
