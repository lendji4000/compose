<?php

/**
 * Interface ITreeStrategy
 * @package tree
 */
interface ITreeStrategy
{

    /**
     * Méthode permettant le paramétrage de l'arbre.
     *
     * @param ITree $tree
     * @param ITreeViewerItem $item
     * @return mixed
     */
    public function defaults(ITree $tree, ITreeViewerItem $item);

    /**
     *
     * Méthode permettant d'afficher l'arbre à partir de celui passé en paramètre.
     *
     * @param ITree $tree
     * @param array $options
     * @param $key
     *
     * @return mixed
     */
    public function render(ITree $tree, array $options, $key);

    /**
     * @return mixed
     */
    public function importAssets();
} 