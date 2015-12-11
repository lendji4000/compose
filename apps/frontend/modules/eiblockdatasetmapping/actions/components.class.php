<?php

/**
 * Class EiBlockDataSetMappingComponents
 */
class EiBlockDataSetMappingComponents extends sfComponents{

    public function executeBlockMapping(sfWebRequest $request)
    {
        // On récupère le block parent.
        /** @var EiBlock $block */
        $block = Doctrine_Core::getTable("EiVersionStructure")->findBlock($this->ei_version_structure_id);
        // Et on charge les paramètres de ce block que l'on retourne au composant.
        $this->blockParams = $block->getParams();
    }

    public function executeDataSetSynchronization(sfWebRequest $request)
    {
        // On récupère le block parent.
        /** @var EiBlock $block */
        $block = Doctrine_Core::getTable("EiVersionStructure")->findBlock($this->ei_version_structure_id);
        // Et on charge les paramètres de ce block que l'on retourne au composant.
        $this->blockParams = $block->getParams();
    }

} 