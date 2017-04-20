<?php
/**
 * Description of DateValidator
 *
 * @author JulienL
 * @package Validators
 */
class DateValidator extends CDateValidator
{
	protected function validateAttribute( $Model, $attribute )
    {
		$Field = $Model->metaData->columns[ $attribute ];

		switch( $Field->dbType )
		{
			case 'datetime' :
				if( empty($Model->$attribute) )
					$Model->$attribute	= date( Yii::app()->params['dbDateTime'] );
				$this->format		= 'yyyy-MM-dd hh:mm:ss';
				break;

			case 'date' :
				if( empty($Model->$attribute) )
					$Model->$attribute	= date( Yii::app()->params['dbDate'] );
				$this->format		= 'yyyy-MM-dd';
				break;
		}

		return parent::validateAttribute( $Model, $attribute );
    }
}

?>
