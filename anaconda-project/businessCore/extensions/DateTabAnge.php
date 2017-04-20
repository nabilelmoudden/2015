<?php
/**
 * Description of ArrayHelper
 *
 * @author JulienL
 */
class DateTabAnge
{
	/**
	 * 
	 * 
	 * 
	 */

	 
	 	protected static function formatDate( $date )

	{

		$date = str_replace( '/', '-', $date );

		return $date;

	}
	
/**

	 * Retourne l'ange a partir de la date de naissance

	 * @param	date	$birthday	Date de naissance ( Y-m-d OU Y/m/d )

	 * @return	integer	Numero de l'ange

	 */
	public static function getAstroAnge( $birthday )

	{

		if( empty($birthday) )

			return false;



		$Date	= new DateTime( self::formatDate($birthday) );

		$mois	= $Date->format( 'm' );

		$jour	= $Date->format( 'd' );



		if( $mois == 3 && $jour >=21 || $mois == 4 && $jour <=29 )

			$ange = "1";
			
		else if( $mois == 4 && $jour >=30 || $mois == 5 && $jour <=31 || $mois == 6 && $jour <=8)

			$ange = "2";
			
		else if( $mois == 6 && $jour >=9 || $mois == 7 && $jour <=18 )
			
			$ange = "3";
			
		else if( $mois == 7 && $jour >=19 || $mois == 8 && $jour <=27 )

			$ange = "4";
			
		else if( $mois == 8 && $jour >=28 || $mois == 9 && $jour <=30 || $mois == 10 && $jour <=6)

			$ange = "5";
			
		else if( $mois == 10 && $jour >=7 || $mois == 11 && $jour <=15 )

			$ange = "6";
			
		else if( $mois == 11 && $jour >=16 || $mois == 12 && $jour <=25 )

			$ange = "7";
			
		else if( $mois == 12 && $jour >=26 || $mois == 1 && $jour <=31 || $mois == 2 && $jour <=3)

			$ange = "8";
		
		else if( $mois == 2 && $jour >=4 || $mois == 3 && $jour <=20 )

			$ange = "9";

		return $ange;

	}
	
	public static function AngeName( $birthday )

	{

		if( empty($birthday) )

			return false;



		$Date	= new DateTime( self::formatDate($birthday) );

		$mois	= $Date->format( 'm' );

		$jour	= $Date->format( 'd' );



		if( $mois == 3 && $jour >=21 || $mois == 4 && $jour <=29 )

			$angeName = "Métatron";
			
		else if( $mois == 4 && $jour >=30 || $mois == 5 && $jour <=31 || $mois == 6 && $jour <=8)

			$angeName = "Raziel";
			
		else if( $mois == 6 && $jour >=9 || $mois == 7 && $jour <=18 )
			
			$angeName = "Binael ";
			
		else if( $mois == 7 && $jour >=19 || $mois == 8 && $jour <=27 )

			$angeName = "Hesediel";
			
		else if( $mois == 8 && $jour >=28 || $mois == 9 && $jour <=30 || $mois == 10 && $jour <=6)

			$angeName = "Camael";
			
		else if( $mois == 10 && $jour >=7 || $mois == 11 && $jour <=15 )

			$angeName = "Raphael";
			
		else if( $mois == 11 && $jour >=16 || $mois == 12 && $jour <=25 )

			$angeName = "Haniel";
			
		else if( $mois == 12 && $jour >=26 || $mois == 1 && $jour <=31 || $mois == 2 && $jour <=3)

			$angeName = "Michel";
		
		else if( $mois == 2 && $jour >=4 || $mois == 3 && $jour <=20 )

			$angeName = "Gabriel";

		return $angeName;

	}		public static function AngeNameSE( $birthday )	{		if( empty($birthday) )			return false;		$Date	= new DateTime( self::formatDate($birthday) );		$mois	= $Date->format( 'm' );		$jour	= $Date->format( 'd' );		if( $mois == 3 && $jour >=21 || $mois == 4 && $jour <=29 )			$angeName = "Metatron";					else if( $mois == 4 && $jour >=30 || $mois == 5 && $jour <=31 || $mois == 6 && $jour <=8)			$angeName = "Raziel";					else if( $mois == 6 && $jour >=9 || $mois == 7 && $jour <=18 )						$angeName = "Binael";					else if( $mois == 7 && $jour >=19 || $mois == 8 && $jour <=27 )			$angeName = "Zadkiel";					else if( $mois == 8 && $jour >=28 || $mois == 9 && $jour <=30 || $mois == 10 && $jour <=6)			$angeName = "Camael";					else if( $mois == 10 && $jour >=7 || $mois == 11 && $jour <=15 )			$angeName = "Rafael";					else if( $mois == 11 && $jour >=16 || $mois == 12 && $jour <=25 )			$angeName = "Haniel";					else if( $mois == 12 && $jour >=26 || $mois == 1 && $jour <=31 || $mois == 2 && $jour <=3)			$angeName = "Mikael";				else if( $mois == 2 && $jour >=4 || $mois == 3 && $jour <=20 )			$angeName = "Gabriel";		return $angeName;	}
	
	public static function AngeNameNO( $birthday )	
	{		
		   if( empty($birthday) )			
		   return false;		
		   $Date	= new DateTime( self::formatDate($birthday) );
		   $mois	= $Date->format( 'm' );
		   $jour	= $Date->format( 'd' );
		   if( $mois == 3 && $jour >=21 || $mois == 4 && $jour <=29 )
		   $angeName = "Metatron";
		   else if( $mois == 4 && $jour >=30 || $mois == 5 && $jour <=31 || $mois == 6 && $jour <=8)
		   $angeName = "Raziel";
		   else if( $mois == 6 && $jour >=9 || $mois == 7 && $jour <=18 )
		   $angeName = "Binael";
		   else if( $mois == 7 && $jour >=19 || $mois == 8 && $jour <=27 )
		   $angeName = "Hesediel";
		   else if( $mois == 8 && $jour >=28 || $mois == 9 && $jour <=30 || $mois == 10 && $jour <=6)
		   $angeName = "Kamael";	
		   else if( $mois == 10 && $jour >=7 || $mois == 11 && $jour <=15 )
		   $angeName = "Rafael";
		   else if( $mois == 11 && $jour >=16 || $mois == 12 && $jour <=25 )
		   $angeName = "Haniel";
		   else if( $mois == 12 && $jour >=26 || $mois == 1 && $jour <=31 || $mois == 2 && $jour <=3)
		   $angeName = "Mikael";
		   else if( $mois == 2 && $jour >=4 || $mois == 3 && $jour <=20 )
		   $angeName = "Gabriel";
		   return $angeName;	
  	 }
public static function getAstroAngeByNumber( $numeroAnge )

	{

		$ange	= Yii::t( 'astrologie', 'ange_'.$numeroAnge );

		$domination	= Yii::t( 'astrologie', 'domination_'.$numeroAnge );



		return ( $ange != 'ange_'.$numeroAnge && $domination != 'domination_'.$numeroAnge ) ? array( $ange, htmlentities($ange), $domination ) : array();

	}

}
?>