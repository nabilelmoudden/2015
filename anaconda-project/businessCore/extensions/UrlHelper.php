<?php

/**
 * Description of UrlHelper
 *
 * @author JulienL
 */
class UrlHelper
{
	public static function parseUrl( $url )
	{
		if( !($parsed = parse_url($url)) )
			return false;

		if( !isset($parsed['query']) || empty($parsed['query']) )
			return false;

		$tmp				= explode( '&', $parsed['query'] );
		$parsed['query']	= array();
		foreach( $tmp as $a )
		{
			$tmpArg							= explode( '=', $a );
			$parsed['query'][ $tmpArg[0] ]	= $tmpArg[1];
		}

		return $parsed;
	}
}

?>