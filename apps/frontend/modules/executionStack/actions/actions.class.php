<?php

/**
 * executionStack actions.
 *
 * @package    kalifastRobot
 * @subpackage executionStack
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class executionStackActions extends sfActions
{
    /**
     * @param sfWebRequest $request
     */
    public function executeGetExecutions(sfWebRequest $request)
    {
        $this->setLayout(false);
    }
}
