<?php

class EiError {
  public static function handleException(sfEvent $event) {
    $moduleName = sfConfig::get('sf_error_500_module', 'connexion');
    $actionName = sfConfig::get('sf_error_500_action', 'error500');
    sfContext::getInstance()->getRequest()->addRequestParameters(array('exception' => $event->getSubject()));
    $event->setReturnValue(true);
    sfContext::getInstance()->getController()->forward($moduleName, $actionName);
  }
}