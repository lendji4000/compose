<?php

/**
 * Class TreeView
 */
class TreeView {

    /** @var  ITree $tree */
    private $tree;

    /** @var  array $options */
    private $options;

    /** @var  ITreeStrategy $strategie */
    private $strategie;

    /**
     * Types fournis par POST
     */
    public static $TYPE_NODE = "node";
    public static $TYPE_LEAF = "leaf";

    /**
     * Types mis à disposition.
     */
    public static $TYPE_XML = "/images/icones/crystal_xml.png";
    public static $TYPE_VALUE = "/images/icones/xml_value.png";
    public static $TYPE_TAG = "/images/icones/xml_tag.png";
    public static $TYPE_XSL_VALUE = "/images/icones/xsl_value.png";

    /**
     * @param ITree $racine
     * @param ITreeStrategy $strategie
     * @param array $options
     */
    function __construct(ITree $racine, ITreeStrategy $strategie, array $options){
        $this->tree = $racine;
        $this->options = $options;
        $this->strategie = $strategie;
    }

    /**
     * @return mixed
     */
    public function render($key = null){
        return html_entity_decode($this->strategie->render($this->tree, $this->options, $key), ENT_QUOTES, "UTF-8");
    }

    /**
     *
     */
    public function importAssets(){
        $this->strategie->importAssets();
    }

} 