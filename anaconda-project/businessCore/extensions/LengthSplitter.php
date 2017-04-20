<?php

/**
 * Description of DateValidator
 *
 * @author JulienL
 * @package Validators
 */
class LengthSplitter extends CStringValidator
{
	protected function validateAttribute( $Model, $attribute )
    {
		$Field = $Model->metaData->columns[ $attribute ];

		if( strpos( $Field->dbType, 'varchar' ) !== false )
		{
			$this->max	= $Field->precision;

			if( $this->max > 0  && strlen($Model->$attribute) > $this->max )
				$Model->$attribute = substr( $Model->$attribute, 0, $this->max );
		}

		return parent::validateAttribute( $Model, $attribute );
    }
}

?>
