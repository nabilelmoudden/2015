<?php

/**
 * Description of Formatter
 *
 * @author JulienL
 * @package Helper
 */
class PriceHelper extends CFormatter
{
	public function __construct()
	{
		$this->numberFormat	= array(
			'decimals' => 2,
			'decimalSeparator' => '.',
			'thousandSeparator' => ' '
		);
	}

	static public function getDevise( $devise = false )
	{
		if( $devise === false )
			$devise = \Yii::app()->params['currency'];

		switch( $devise )
		{
			default :
			case 'EUR' :
				return array( 'EUR', '&euro', '€' );
			case 'CHF' :
				return array( 'CHF', 'CHF', 'CHF' );
			case 'BRL' :
				return array( 'BRL', 'BRL', 'BRL' );
		}
	}

	public function formatPrice( $price, $devise = false )
	{
		$price	= $this->formatNumber( $price );
		$devise	= self::getDevise( $devise );

		return $price.' '.$devise[2]; 
	}
}

?>