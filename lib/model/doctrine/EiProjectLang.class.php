<?php

/**
 * EiProjectLang
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiProjectLang extends BaseEiProjectLang
{
    public function __toString() {

        return sprintf('%s', sfCultureInfo::getInstance()->getLanguage($this->getLang()));
    }
}
