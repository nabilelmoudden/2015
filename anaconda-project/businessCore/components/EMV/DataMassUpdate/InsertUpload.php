<?php

/**
 * insertUpload
 *
 * @package EMV\DataMassUpdate
 */
class EMV_DataMassUpdate_InsertUpload {

    /**
     * File name
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
     * Use first line as mapping
     *
     * @var boolean
     */
    public $autoMapping;

    /**
     * Dedup
     *
     * @var EMV_DataMassUpdate_InsertDedup
     */
    public $dedup;

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
        $this->autoMapping = false;
        $this->dedup = null;
        $this->mapping = new EMV_DataMassUpdate_UploadMapping();
    }

    /**
     * Creates object from array
     *
     * @param array $data
     * @return null|EMV_DataMassUpdate_InsertUpload
     */
    public static function createFromArray($data) {
        if (!is_array($data) || empty($data)) {
            return null;
        }

        $class = get_class();
        $vars = array_keys(get_class_vars($class));

        $insertUpload = new EMV_DataMassUpdate_InsertUpload();
        foreach ($vars as $var) {
            if (in_array($var, array('dedup', 'mapping'))) {
                continue;
            }

            if (isset($data[$var])) {
                $insertUpload->$var = $data[$var];
            }
        }

        if (isset($insertUpload->fileName)) {
            $insertUpload->fileName = basename($insertUpload->fileName);
        }

        // Dedup
        if (isset($data['dedup']) && is_array($data['dedup'])) {
            $dedup = EMV_DataMassUpdate_InsertDedup::createFromArray($data['dedup']);
            $insertUpload->dedup = $dedup;
        }

        // Mapping
        if (isset($data['mapping']) && is_array($data['mapping'])) {
            $mapping = EMV_DataMassUpdate_UploadMapping::createFromArray($data['mapping']);
        } else {
            $mapping = new EMV_DataMassUpdate_UploadMapping();
        }
        $insertUpload->mapping = $mapping;

        return $insertUpload;
    }

}
