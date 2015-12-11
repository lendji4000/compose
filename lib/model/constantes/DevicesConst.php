<?php

/**
 * Class DevicesConst
 *
 * Constantes identifiant la liste des navigateurs et leur appellation en base de données..
 */
class DevicesConst {
    const RASPBERRY = "Raspberry";
    const SELENIUM_IDE = "SeleniumIde";
    const IOS = "Ios";
    const ANDROID = "Android";
    const CHROME = "Chrome";
    const FIREFOX = "Firefox";
    const INTERNET_EXPLORER = "InternetExplorer";
    const SAFARI = "Safari";

    /**
     * Tableau regroupant les navigateurs/os acceptés.
     *
     * @var array
     */
    private static $accepted = array(
        self::SELENIUM_IDE,
//        self::ANDROID,
//        self::IOS,
//        self::CHROME,
//        self::FIREFOX,
//        self::INTERNET_EXPLORER,
//        self::SAFARI,
        self::RASPBERRY
    );

    /**
     * Retourne l'intitulé d'un device/browser.
     *
     * @param $constante
     * @return string
     */
    public static function getTitle($constante)
    {
        $key = "";

        switch($constante){
            case self::SELENIUM_IDE:
                $key = "Selenium IDE";
                break;

            case self::IOS:
                $key = "Apple/Ipad";
                break;

            case self::INTERNET_EXPLORER:
                $key = "Internet Explorer";
                break;

            default :
                $key = $constante;
                break;
        }

        return $key;
    }

    /**
     * Méthode permettant de récupérer le chemin vers l'icone d'un device/browser.
     *
     * @param $constante
     * @return mixed
     */
    public static function getImgPath($constante)
    {
        $key = "";

        switch($constante){
            case self::SELENIUM_IDE:
                $key = "app_icone_selenium_24x24_path";
                break;

            case self::IOS:
                $key = "app_icone_apple_16x16_path";
                break;

            case self::ANDROID:
                $key = "app_icone_android_16x16_path";
                break;

            case self::CHROME:
                $key = "app_icone_chrome_24x24_path";
                break;

            case self::FIREFOX:
                $key = "app_icone_firefox_24x24_path";
                break;

            case self::INTERNET_EXPLORER:
                $key = "app_icone_ie_24x24_path";
                break;

            case self::SAFARI:
                $key = "app_icone_safari_24x24_path";
                break;

            case self::RASPBERRY:
                $key = "app_icone_raspberry_24x24_path";
                break;
        }

        return sfConfig::get($key);
    }

    public static function getDevices(){
        return self::$accepted;
    }

    /**
     * Méthode permettant de vérifier qu'un device/browser est accepté/valide.
     *
     * @param $constante
     * @return bool
     */
    public static function isValid($constante)
    {
        return in_array($constante, self::$accepted);
    }
} 