<?php

class Chronometre
{
    /** @var sfLogger */
    private $logger;

    /** @var int */
    private $timerStart;

    /** @var int */
    private $timerEnd;

    /** @var string */
    private $timerTitle;

    /**
     * Constructeur par défaut.
     */
    public function __construct()
    {
        $this->logger = sfContext::getInstance()->getLogger();
    }

    /**
     * @param $titre
     * @param bool $byVal
     * @return mixed
     */
    public function lancerChrono($titre, $byVal = false){
        $titre = strtoupper($titre);

        $this->logger->debug("-----------------------------------------------------------------------------------");
        $this->logger->debug("-----   DEBUT EXECUTION : ".$titre);
        $this->logger->debug("-----------------------------------------------------------------------------------");

        if( !$byVal ){
            $this->timerTitle = $titre;
            $this->timerStart = microtime(true);
            $this->timerEnd = microtime(true);
        }
        else{
            return microtime(true);
        }
    }

    /**
     * @param bool $byVal
     * @return mixed
     */
    public function arreterChrono($byVal = false){
        if( !$byVal ){
            $this->timerEnd = microtime(true);
        }
        else{
            return microtime(true);
        }
    }

    /**
     * @param bool $byVal
     */
    public function arreterEtAfficherChrono($titre = "", $start = 0)
    {
        $end = $this->arreterChrono(strlen($titre) > 0);

        if( strlen($titre) == 0 ){
            $this->arreterChrono();

            $titre = $this->timerTitle;
            $start = $this->timerStart;
        }

        $this->afficherChrono($titre, $start, $end);
    }

    /**
     * @param string $titre
     * @param int $start
     * @param int $end
     */
    public function afficherChrono($titre = "", $start = 0, $end = 0)
    {
        $titre = strlen($titre) == 0 ? $this->timerTitle:$titre;
        $start = $start == 0 ? $this->timerStart:$start;
        $end = $end == 0 ? $this->timerEnd:$end;

        $diff = number_format($end - $start, 3);

        $this->logger->debug("-----------------------------------------------------------------------------------");
        $this->logger->debug("-----   FIN EXECUTION : ".strtoupper($titre));
        $this->logger->debug("-----------------------------------------------------------------------------------");
        $this->logger->debug("-----   DATE DEBUT : ".date("d/m/Y H:i:s", $start));
        $this->logger->debug("-----   DATE FIN : ".date("d/m/Y H:i:s", $end));
        $this->logger->debug("-----------------------------------------------------------------------------------");
        $this->logger->debug("-----   DUREE D'EXECUTION : ".$diff." secondes.");
        $this->logger->debug("-----------------------------------------------------------------------------------");
    }

    /**
     * @param $message
     */
    public function debug($message)
    {
        $this->logger->debug($message);
    }

} 