<?php

/**
 * uploadFileMerge
 *
 * @package EMV\DataMassUpdate
 * @see Emailvision Data Mass Update SOAP API uploadFileMerge
 */
class EMV_DataMassUpdate_UploadFileMerge extends EMV_DataMassUpdate_Upload {

    /**
     * File content or attachement ID (not supported)
     *
     * @var string
     */
    public $file;

    /**
     * mergeUpload object
     *
     * @var EMV_DataMassUpdate_MergeUpload
     */
    public $mergeUpload;

    /**
     * Constructor
     *
     * @param string $fileName
     * @param boolean $loadFile If true will load the file contents into
     * the file field
     */
    function __construct($fileName = null, $loadFile = false) {
        if ($loadFile === true) {
            /*
             * Emailvision API docs state that the content must be base64 encoded
             * but using base64 will cause the lines to be ignored
             */
            $this->file = file_get_contents($fileName);
        }
        $this->mergeUpload = new EMV_DataMassUpdate_MergeUpload($fileName);
    }

    /**
     * Mapping setter
     *
     * @param EMV_DataMassUpdate_UploadMapping $mapping
     */
    public function setMapping(EMV_DataMassUpdate_UploadMapping $mapping) {
        $this->mergeUpload->mapping = $mapping;
    }

    /**
     * Mapping getter
     *
     * @return EMV_DataMassUpdate_UploadMapping
     */
    public function getMapping() {
        return $this->mergeUpload->mapping;
    }

    /**
     * Creates object from array
     *
     * @param array $data
     * @param boolean $loadFile If true will load the file contents into
     * the file field
     * @return null|EMV_DataMassUpdate_UploadFileMerge
     */
    public static function createFromArray($data, $loadFile = false) {
        if (!is_array($data) || empty($data)) {
            return null;
        }

        if (!isset($data['fileName']) || !is_file($data['fileName'])) {
            return null;
        }

        $fileMerge = new EMV_DataMassUpdate_UploadFileMerge($data['fileName'], $loadFile);

        if (!empty($data) && is_array($data)) {
            $mergeUpload = EMV_DataMassUpdate_MergeUpload::createFromArray($data);
            $fileMerge->mergeUpload = $mergeUpload;
        }

        return $fileMerge;
    }

}
