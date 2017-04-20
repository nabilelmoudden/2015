<?php

/**
 * Mapping
 *
 * @package EMV\DataMassUpdate
 */
class EMV_DataMassUpdate_UploadMapping extends EMV_DataTransform_Mapping {

    /**
     * Creates a new column and adds it to the mapping
     *
     * @param int $colNum
     * @param string $fieldName
     * @param boolean $toReplace
     * @param string $dateFormat
     * @param string $defaultValue
     */
    public function insertColumn($colNum = null, $fieldName = null, $toReplace = false, $dateFormat = null, $defaultValue = null) {
        $column = new EMV_DataMassUpdate_UploadMappingColumn($colNum, $fieldName, $toReplace, $dateFormat, $defaultValue);
        $this->addColumn($column);
    }

    /**
     * Creates object from array
     *
     * @param array $data
     * @return null|EMV_DataMassUpdate_UploadMapping
     */
    public static function createFromArray($data) {
        if (!is_array($data) || empty($data)) {
            return null;
        }

        $mapping = new EMV_DataMassUpdate_UploadMapping();
        foreach ($data as $column) {
            if (is_array($column)) {
                $mapping->addColumn(EMV_DataMassUpdate_UploadMappingColumn::createFromArray($column));
            }
        }
        return $mapping;
    }

}
