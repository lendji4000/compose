<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfDoctrineGuardPlugin');
//    $this->enablePlugins('sfJqueryReloadedPlugin');
    //$this->setWebDir($this->getRootDir().'/www');
    $this->enablePlugins('sfGuardExtraPlugin');
    //$this->dispatcher->connect('application.throw_exception', array('EiError', 'handleException'));
  }
  
    protected function loadProjectConfig()
	{
		static $load = false;

		if (!$load && $this instanceof sfApplicationConfiguration)
		{
			require $this->getConfigCache()->checkConfig('config/project.yml');
            require $this->getConfigCache()->checkConfig('config/kalifast.yml');
			$load = true;
		}
	}
}
