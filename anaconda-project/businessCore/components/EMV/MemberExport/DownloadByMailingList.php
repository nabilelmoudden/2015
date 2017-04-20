<?php

/**
 * downloadByMailingList
 *
 * @package EMV\MemberExport
 * @see Emailvision Member Export SOAP API createDownloadByMailinglist
 */
class EMV_MemberExport_DownloadByMailingList extends EMV_MemberExport_Download {

    /**
     * Mailing list ID
     *
     * @var int
     */
    public $mailinglistId;

    /**
     * Operation type
     *
     * @var string
     */
    public $operationType;

    /**
     * Fields
     *
     * @var string
     */
    public $fieldSelection;

    /**
     * Dedup
     *
     * @var boolean
     */
    public $dedupFlag;

    /**
     * Dedup criteria
     *
     * @var string
     */
    public $dedupCriteria;

    /**
     * Keep first
     *
     * @var boolean
     */
    public $keepFirst;

    /**
     * File format
     *
     * @var string
     */
    public $fileFormat;

    /**
     * Date format
     *
     * @var string
     */
    public $dateFormat;

    /**
     * Constructor
     *
     * @param int $mailinglistId
     */
    function __construct($mailinglistId = null) {
        $this->mailinglistId = $mailinglistId;
        $this->operationType = EMV::OPT_ACTIVE_MEMBERS;
        $this->fieldSelection = 'LASTNAME,FIRSTNAME,EMAIL';
        $this->dedupFlag = true;
        $this->dedupCriteria = 'EMAIL';
        $this->keepFirst = true;
        $this->fileFormat = EMV::FILE_FORMAT_PIPE;
        $this->dateFormat = null;
    }

    /**
     * Creates object from array
     *
     * @param array $data
     * @return null|EMV_MemberExport_DownloadByMailingList
     */
    public static function createFromArray($data) {
        if (!is_array($data) || empty($data)) {
            return null;
        }

        $class = get_class();
        $vars = array_keys(get_class_vars($class));

        $download = new EMV_MemberExport_DownloadByMailingList();
        foreach ($vars as $var) {
            if (isset($data[$var])) {
                $download->$var = $data[$var];
            }
        }
        return $download;
    }

}
