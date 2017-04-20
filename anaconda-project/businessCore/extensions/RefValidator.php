<?php

/**
 * Description of RefValidator
 *
 * @author JulienL
 */
class RefValidator extends CStringValidator
{
	protected function validateAttribute( $Model, $attribute )
    {
		$Model->$attribute	= self::transform( $Model->$attribute );
		return parent::validateAttribute( $Model, $attribute );
    }
	
	static public function transform( $ref )
	{
		$ref	= str_replace( ' ', '_', $ref );
		$ref	= preg_replace( '#[^a-zA-Z0-9_]#', "", $ref );
		return $ref;
	}
}

?>