<?php

/**
 * uploadFileInsert
 *
 * @package EMV\DataMassUpdate
 * @see Emailvision Data Mass Update SOAP API uploadFileInsert
 */
class EMV_DataMassUpdate_UploadFileInsert extends EMV_DataMassUpdate_Upload {

    /**
     * File content or attachement ID (not supported)
     *
     * @var string
     */
    public $file;

    /**
     * insertUpload object
     *
     * @var EMV_DataMassUpdate_InsertUpload
     */
    public $insertUpload;

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
        $this->insertUpload = new EMV_DataMassUpdate_InsertUpload($fileName);
    }

    /**
     * Mapping setter
     *
     * @param EMV_DataMassUpdate_UploadMapping $mapping
     */
    public function setMapping(EMV_DataMassUpdate_UploadMapping $mapping) {
        $this->insertUpload->mapping = $mapping;
    }

    /**
     * Mapping getter
     *
     * @return EMV_DataMassUpdate_UploadMapping
     */
    public function getMapping() {
        return $this->insertUpload->mapping;
    }

    /**
     * Dedup setter
     *
     * @param EMV_DataMassUpdate_InsertDedup $dedup
     */
    public function setDedup(EMV_DataMassUpdate_InsertDedup $dedup) {
        $this->insertUpload->dedup = $dedup;
    }

    /**
     * Dedup getter
     *
     * @return EMV_DataMassUpdate_InsertDedup
     */
    public function getDedup() {
        return $this->insertUpload->dedup;
    }

    /**
     * Creates object from array
     *
     * @param array $data
     * @param boolean $loadFile If true will load the file contents into
     * the file field
     * @return null|EMV_DataMassUpdate_UploadFileInsert
     */
    public static function createFromArray($data, $loadFile = false) {
        if (!is_array($data) || empty($data)) {
            return null;
        }

        if (!isset($data['fileName']) || !is_file($data['fileName'])) {
            return null;
        }

        $fileInsert = new EMV_DataMassUpdate_UploadFileInsert($data['fileName'], $loadFile);

        if (!empty($data) && is_array($data)) {
            $insertUpload = EMV_DataMassUpdate_InsertUpload::createFromArray($data);
            $fileInsert->insertUpload = $insertUpload;
        }

        return $fileInsert;
    }

}
