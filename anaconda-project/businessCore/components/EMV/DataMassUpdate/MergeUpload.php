<?php

/**
 * mergeUpload
 *
 * @package EMV\DataMassUpdate
 */
class EMV_DataMassUpdate_MergeUpload {

    /**
     * File content or attachement ID (not supported)
     *
     * @var string
     */
    public $fileName;

    /**
     * File encoding
     *
     * @var string
     */
    public $fileEncoding;

    /**
     * Column separator
     *
     * @var string
     */
    public $separator;

    /**
     * Ignore first line
     *
     * @var boolean
     */
    public $skipFirstLine;

    /**
     * Date format
     *
     * @var string
     */
    public $dateFormat;

    /**
     * Criteria
     *
     * @var string
     */
    public $criteria;

    /**
     * Mapping
     *
     * @var EMV_DataMassUpdate_UploadMapping
     */
    public $mapping;

    /**
     * Constructor
     *
     * @param string $fileName
     */
    function __construct($fileName = null) {
        $this->fileName = isset($fileName) ? basename($fileName) : 'null';
        $this->fileEncoding = 'UTF-8';
        $this->separator = EMV::SEPARATOR_PIPE;
        $this->skipFirstLine = true;
        $this->dateFormat = null;
        $this->criteria = 'EMAIL';
        $this->mapping = new EMV_DataMassUpdate_UploadMapping();
    }

    /**
     * Creates object from array
     *
     * @param array $data
     * @return null|EMV_DataMassUpdate_MergeUpload
     */
    public static function createFromArray($data) {
        if (!is_array($data) || empty($data)) {
            return null;
        }

        $class = get_class();
        $vars = array_keys(get_class_vars($class));

        $mergeUpload = new EMV_DataMassUpdate_MergeUpload();
        foreach ($vars as $var) {
            if ($var == 'mapping') {
                continue;
            }

            if (isset($data[$var])) {
                $mergeUpload->$var = $data[$var];
            }
        }

        if (isset($mergeUpload->fileName)) {
            $mergeUpload->fileName = basename($mergeUpload->fileName);
        }

        // Mapping
        if (isset($data['mapping']) && is_array($data['mapping'])) {
            $mapping = EMV_DataMassUpdate_UploadMapping::createFromArray($data['mapping']);
        } else {
            $mapping = new EMV_DataMassUpdate_UploadMapping();
        }
        $mergeUpload->mapping = $mapping;

        return $mergeUpload;
    }

}

