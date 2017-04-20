<?php

/**
 * Description of ArrayHelper
 *
 * @author JulienL
 */
class ArrayHelper
{
	/**
	 * Test si un tableau possede des valeur
	 * @param type $trackingCode
	 * @return boolean
	 */
	static public function isEmpty( $array )
	{
		if( !is_array($array) )
			return true;

		foreach( $array as $val )
			if( trim($val) != '' )
				return false;
		return true;
	}
}

?>