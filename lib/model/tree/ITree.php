<?php

/**
 * Interface ITree
 */
interface ITree {

    public static function getFormNameFormat();

    /**
     * @return ITreeViewerItem
     */
    public function getRoot();
}

?>