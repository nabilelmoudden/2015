<?php

/**
 * Mapping
 *
 * @package EMV\DataTransform
 */
class EMV_DataTransform_Mapping {

    /**
     * Columns
     *
     * @var array
     */
    public $column;

    /**
     * Constructor
     */
    function __construct() {
        $this->column = array();
    }

    /**
     * Adds a column
     *
     * @param EMV_DataTransform_MappingColumn $column
     */
    public function addColumn(EMV_DataTransform_MappingColumn $column) {
        $this->column[] = $column;
    }

}
