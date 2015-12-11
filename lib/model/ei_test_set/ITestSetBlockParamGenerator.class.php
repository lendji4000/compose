<?php

/**
 * Interface ITestSetBlockParamGenerator
 */
interface ITestSetBlockParamGenerator {

    /**
     * @param EiTestSet $testSet
     * @param ITestSetBlockParamGenerator $parent
     * @param null $index
     * @param string $path
     * @return mixed
     */
    public function generateTestSetParameters(EiTestSet $testSet = null, $parent = null, $index = null, $path = "");
} 