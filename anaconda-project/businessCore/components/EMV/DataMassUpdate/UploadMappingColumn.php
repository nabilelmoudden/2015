<?php

/**
 * Column
 *
 * @package EMV\DataMassUpdate
 */
class EMV_DataMassUpdate_UploadMappingColumn extends EMV_DataTransform_MappingColumn {

    /**
     * Column number
     *
     * @var int
     */
    public $colNum;

    /**
     * Field name
     *
     * @var string
     */
    public $fieldName;

    /**
     * Date format
     *
     * @var string
     */
    public $dateFormat;

    /**
     * Default value
     *
     * @var string
     */
    public $defaultValue;

    /**
     * Replace
     *
     * @var boolean
     */
    public $toReplace;

    /**
     * Constructor
     *
     * @param int $colNum
     * @param string $fieldName
     * @param boolean $toReplace
     * @param string $dateFormat
     * @param string $defaultValue
     */
    function __construct($colNum = null, $fieldName = null, $toReplace = false, $dateFormat = null, $defaultValue = null) {
        $this->colNum = $colNum;
        $this->fieldName = $fieldName;
        $this->dateFormat = $dateFormat;
        $this->defaultValue = $defaultValue;
        $this->toReplace = $toReplace;
    }

    /**
     * Creates object from array
     *
     * @param array $data
     * @return null|\EMV_DataMassUpdate_UploadMappingColumn
     */
    public static function createFromArray($data) {
        if (!is_array($data) || empty($data)) {
            return null;
        }

        $class = get_class();
        $vars = array_keys(get_class_vars($class));

        $column = new EMV_DataMassUpdate_UploadMappingColumn();
        foreach ($vars as $var) {
            if (isset($data[$var])) {
                $column->$var = $data[$var];
            }
        }
        return $column;
    }

}
