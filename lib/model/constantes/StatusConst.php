<?php

/**
 * Class StatusConst
 */
class StatusConst
{
    const STATUS_OK_DB = "ok";
    const STATUS_KO_DB = "ko";
    const STATUS_NA_DB = "NA";
    const STATUS_ABORTED_DB = "ab";
    const STATUS_BLANK_DB = "blank";
    const STATUS_PROCESSING_DB = "processing";

    const STATUS_CAMP_OK_DB = "Ok";
    const STATUS_CAMP_KO_DB = "Ko";
    const STATUS_CAMP_PROCESSING_DB = "Processing";
    const STATUS_CAMP_BLANK_DB = "Blank";
    const STATUS_CAMP_ABORTED_DB = "Aborted";

    const STATUS_OK = "success";
    const STATUS_KO = "failed";
    const STATUS_NA = "aborted";
    const STATUS_PROCESSING = "processing";

    const STATUS_TEST_OK_DB = "Success";
    const STATUS_TEST_KO_DB = "Failed";
    const STATUS_TEST_NA_DB = "Aborted";
    const STATUS_TEST_PROCESSING_DB = "Processing";

    /**
     * @param $status
     * @return string
     */
    public static function getDbStatusFromExecutionStatus($status){
        $return = "";

        switch($status){
            case self::STATUS_TEST_OK_DB:
                $return = self::STATUS_OK_DB;
                break;
            case self::STATUS_TEST_KO_DB:
                $return = self::STATUS_KO_DB;
                break;
            case self::STATUS_TEST_NA_DB:
                $return = self::STATUS_ABORTED_DB;
                break;
            case self::STATUS_TEST_PROCESSING_DB:
                $return = self::STATUS_PROCESSING_DB;
                break;
        }

        return $return;
    }
} 