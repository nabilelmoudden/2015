<?php

/**
 * Description of DateUtils
 *
 * @author JulienL
 * @package Helper
 */
class DateHelper
{
    /**
     * Format un string pour correspondre a une date
     * @param	string	date	Date
     * @return	string	Date formaté
     */
	protected static function formatDate( $date )
	{
		$date = str_replace( '/', '-', $date );
		return $date;
	}

    /**
     * Calcule l'age a partir de la date de naissance
     * @param	date	$birthday	Date de naissance
     * @return	integer Age calculé
     */
    public static function getAge( $birthday )
    {
		if( empty($birthday) )
			return false;
		
		$Date1	= new DateTime( self::formatDate($birthday) );
		$Date2	= new DateTime();
		$Diff	= $Date1->diff( $Date2 );

		return $Diff->format( '%y' );
    }

	/**
	 * Retourne le signe astrologique a partir de la date de naissance
	 * @param	date	$birthday	Date de naissance ( Y-m-d OU Y/m/d )
	 * @return	integer	Numero du signe astro
	 */
	public static function getAstroSign( $birthday )
	{
		if( empty($birthday) )
			return false;
		$Date	= new DateTime( self::formatDate($birthday) );
		$mois	= $Date->format( 'm' );
		$jour	= $Date->format( 'd' );

		if( $mois == 1 && $jour >=20 || $mois == 2 && $jour <=18 )
			$signe = "11";
		else if( $mois == 2 && $jour >=19 || $mois == 3 && $jour <=20 )
			$signe = "7";
		else if( $mois == 3 && $jour >=21 || $mois == 4 && $jour <=19 )
			$signe = "2";
		else if( $mois == 4 && $jour >=20 || $mois == 5 && $jour <=20 )
			$signe = "10";
		else if( $mois == 5 && $jour >=21 || $mois == 6 && $jour <=21 )
			$signe = "5";
		else if( $mois == 6 && $jour >=22 || $mois == 7 && $jour <=22 )
			$signe = "3";
		else if( $mois == 7 && $jour >=23 || $mois == 8 && $jour <=22 )
			$signe = "6";
		else if( $mois == 8 && $jour >=23 || $mois == 9 && $jour <=22 )
			$signe = "12";
		else if( $mois == 9 && $jour >=23 || $mois == 10 && $jour <=22 )
			$signe = "1";
		else if( $mois == 10 && $jour >=23 || $mois == 11 && $jour <=21 )
			$signe = "9";
		else if( $mois == 11 && $jour >=22 || $mois == 12 && $jour <=21 )
			$signe = "8";
		else if( $mois == 12 && $jour >=22 || $mois == 1 && $jour <=19 )
			$signe = "4";

		return $signe;
	}

	/**
	 * Retourne le signe astrologique a partir du numero du signe
	 * @param	integer	$numeroSigne	Numero du signe astrologique
	 * @return	string	Signe astro
	 */
	public static function getAstroSignByNumber( $numeroSigne )
	{
		$signe		= Yii::t( 'astrologie', 'sign_'.$numeroSigne );
		$domination	= Yii::t( 'astrologie', 'domination_'.$numeroSigne );

		return ( $signe != 'sign_'.$numeroSigne && $domination != 'domination_'.$numeroSigne ) ? array( $signe, htmlentities($signe), $domination ) : array();
	}

	/**
	 * Retourne la date uniquement ( sans les heures )
	 * @param string $date date
	 * @return string date
	 */
	public static function dateOnly( $date )
	{
		$Date	= new DateTime( self::formatDate($date) );
		return $Date->format( 'Y-m-d' );
	}

	/**
	 * Retourne le nom du jour correspondant au numero passé en argument
	 * @param int $num Numero du jour
	 * @return string Nom du jour
	 */
	public static function getJour( $num )
	{
		return \Yii::t( 'date', 'jour_'.$num );
	}

	/**
	 * Retourne le nom du moi correspondant au numero passé en argument
	 * @param int $num Numero du moi
	 * @return string Nom du moi
	 */
	public static function getMoi( $num )
	{
		return \Yii::t( 'date', 'moi_'.$num );
	}
}

?>