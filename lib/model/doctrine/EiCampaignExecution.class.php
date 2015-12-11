<?php

/**
 * EiCampaignExecution
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiCampaignExecution extends BaseEiCampaignExecution
{
    /** @var string */
    private $status_name;
    /** @var string */
    private $status_color;
    /** @var int en ms */
    private $duree;
    /** @var int */
    private $nbEtapesCamp;
    /** @var int */
    private $nbEtapesExecution;
    /** @var int */
    private $nbEtapesExecutees;
    /** @var string */
    private $authorUsername;

    public function __toString()
    {
        return $this->getCreatedAt();
    }

    /**
     * @param string $status_name
     */
    public function setStatusName($status_name)
    {
        $this->status_name = $status_name;
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return $this->status_name;
    }

    /**
     * @param string $status_color
     */
    public function setStatusColor($status_color)
    {
        $this->status_color = $status_color;
    }

    /**
     * @return string
     */
    public function getStatusColor()
    {
        return $this->status_color;
    }

    /**
     * @param int $duree
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;
    }

    /**
     * @return int
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param int $nbEtapesCamp
     */
    public function setNbEtapesCamp($nbEtapesCamp)
    {
        $this->nbEtapesCamp = $nbEtapesCamp;
    }

    /**
     * @return int
     */
    public function getNbEtapesCamp()
    {
        return $this->nbEtapesCamp;
    }

    /**
     * @param int $nbEtapesExecution
     */
    public function setNbEtapesExecution($nbEtapesExecution)
    {
        $this->nbEtapesExecution = $nbEtapesExecution;
    }

    /**
     * @return int
     */
    public function getNbEtapesExecution()
    {
        return $this->nbEtapesExecution;
    }

    /**
     * @param int $nbEtapesExecutees
     */
    public function setNbEtapesExecutees($nbEtapesExecutees)
    {
        $this->nbEtapesExecutees = $nbEtapesExecutees;
    }

    /**
     * @return int
     */
    public function getNbEtapesExecutees()
    {
        return $this->nbEtapesExecutees;
    }

    /**
     * @param string $authorUsername
     */
    public function setAuthorUsername($authorUsername)
    {
        $this->authorUsername = $authorUsername;
    }

    /**
     * @return string
     */
    public function getAuthorUsername()
    {
        return $this->authorUsername;
    }
    
    /* Récupération des fonctions d'une execution */
    public function getExecutionFunctions(Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        return $this->getTable()->getExecutionFunctions($this->getId(),$conn);
    }
}