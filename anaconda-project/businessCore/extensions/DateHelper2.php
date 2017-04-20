<?php

class DateHelper2{

    protected static $dateluneArr = [
        'FR' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/20/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'IT' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/20/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'NO' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/20/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'DE' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/20/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'PT' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/20/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'DK' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/20/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'BR' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/19/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '10/02/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'NL' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/19/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'ES' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/20/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'TR' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/22/2016', '06/20/2016', '07/20/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '11/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'SE' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/20/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'EN' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/19/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017'],
        'IE' => ['02/22/2016', '03/23/2016', '04/22/2016', '05/21/2016', '06/20/2016', '07/19/2016', '08/18/2016', '09/16/2016', '10/16/2016', '11/14/2016', '12/14/2016', '01/12/2017', '02/11/2017', '12/03/2017', '11/04/2017', '10/05/2017', '09/06/2017', '07/09/2017', '08/07/2017', '09/06/2017', '10/05/2017', '11/04/2017', '12/03/2017']

    ];

    /**
     * @author  Tarek Hatem <[<tarek.hatim@kindyinfomaroc.com>]>
     * @param [string] $[codePays] [<represente le code iso du pays>]
     * @param [integer] $[numDate] [<le nombre de date a recuperer>]
     * @return [array] [<tableau qui contient les dates de lune>]
     */

        public static function getDatesLune($numDate,$sd,$codePays)
        {
           
            $result = [];
            $error = '';
            $sessionSD = Yii::app()->session;
            //if the param $sd is true we get the $date_shoot query string else we set it to false
            
            
            
            
            $date_shoot = ($sd === true) ? \Yii::app()->request->getQuery( 'sd', false ) : false;
            
            if(isset($sessionSD['SD']))
            {
            	$date_shoot=$sessionSD['SD'];
            		
            
            }

            // s'assurer que le code pays existe dans le tableau des dates
            if(!array_key_exists($codePays, self::$dateluneArr))
            {

                $error = "Veuillez fournir un code iso correcte comme \"FR, BR\".";
                return $error;
            }

            
            //s'assurer que le nombre de date est superieur a 0 et inferieur  a 5
            if(!($numDate > 0 && $numDate < 5))
            {

                $error = "Le nombre doit etre un entier de 1 a 4.";
                return $error;
            }

            
            // on stock le tableau approprié selon la valeur d'entré "codeIso"
            $matchedCodePays = self::$dateluneArr[$codePays];

            if ($date_shoot){

               $sdObj = DateTime::createFromFormat('m/d/Y', $date_shoot); 

            }else{

                $nowDate = date('m/d/Y');
                //on converti la date à un objet date
               $sdObj = DateTime::createFromFormat('m/d/Y', $nowDate);
            }
            
            
            
            $arrLength = count($matchedCodePays); // question d'optimisation  :)
            $indice = -1;
            $result;
            // parcourir le tableau pour récuperer l'index ou la condition evalue TRUE
            for ($i=0; $i < $arrLength; $i++) { 
                
                $d  = DateTime::createFromFormat('m/d/Y', $matchedCodePays[$i]);

                if($d > $sdObj && $i < ($arrLength -3)){
                    $indice = $i;

                    break;
                }
            }
            if( $indice != -1 ){
                switch ($numDate) {

                        case 1:
                            $result = $matchedCodePays[$indice];
                            break;
                        case 2 :
                        
                            $result = $matchedCodePays[$indice + 1];
                            break;
                           
                        case 3 : 
                            
                            $result = $matchedCodePays[$indice + 2];
                            break;
                        case 4 : 
                           
                            $result = $matchedCodePays[$indice + 3];
                            break;
                        default:
                            break;
                    }

            }else{
                $error = "les dates de lune qui reste dans le tableau sont insuffisantes, veuillez le remplir.";
                return $error;
            }
           
            return $result;

            

        }

        
	//public static function completDate($dt=null,$format=null,$sep=null,$lang=null)
    public static function completDate($dt=null,$lang=null,$format=null,$sep=null){
		
    		$sessionSD = Yii::app()->session;
    	if(isset($sessionSD['SD']))
    		{
    			$date_shoot=$sessionSD['SD'];
    				
    		
    		}  
		elseif(isset($_REQUEST["sdEmv"]))
			$date_shoot = $_REQUEST["sdEmv"];
		
		elseif(isset($_REQUEST["sd"]))
			$date_shoot = $_REQUEST["sd"];
		
		
		
	
		else $date_shoot = date("m/d/Y");
		if($lang==null || $lang=="")
		{
			$lang = "fr";
			if(isset($sessionSD['SD']))
			{
				$date_shoot=DateTime::createFromFormat('Y-m-d',$sessionSD['SD'])->format('d/m/Y');
				
			}
		}
		
        if($dt==null || $dt=="") 
			$dt ="+0";
			
		$date = new DateTime($date_shoot);
        
		if($format == "mnt")
			$date->modify("$dt month");
		else
			$date->modify("$dt day");
		
        $ndate = $date->format('l-d-m-F-Y');
        
       
		 
		
    
        switch(strtolower($lang))
        {
            case "fr" :
                $days   = array("Monday" => 'Lundi', "Tuesday" => 'Mardi', "Wednesday" => 'Mercredi', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'Janvier', "February" => 'F&#233;vrier', "March" => 'Mars', "April" => 'Avril', "May" => 'Mai', "June" => 'Juin', "July" => 'Juillet', "August" => 'Ao&#251;t', "September" => 'Septembre', "October" => 'Octobre', "November" => 'Novembre', "December" => 'D&#233;cembre');
            break;
            case "en" :
                $days   = array("Monday" => 'Monday', "Tuesday" => 'Tuesday', "Wednesday" => 'Wednesday', "Thursday" => 'Thursday', "Friday" => 'Friday', "Saturday" => 'Saturday', "Sunday" => 'Sunday');
                $months = array("January" => 'January', "February" => 'February', "March" => 'March', "April" => 'April', "May" => 'May', "June" => 'June', "July" => 'July', "August" => 'August', "September" => 'September', "October" => 'October', "November" => 'November', "December" => 'December');
            break;
            case "es" :
                $days   = array("Monday" => 'Lunes', "Tuesday" => 'Martes', "Wednesday" => 'Mi&#233;rcoles', "Thursday" => 'Jueves', "Friday" => 'Viernes', "Saturday" => 'S&#225;bado', "Sunday" => 'Domingo');
                $months = array("January" => 'Enero', "February" => 'Febrero', "March" => 'Marzo', "April" => 'Abril', "May" => 'Mayo', "June" => 'Junio', "July" => 'Julio', "August" => 'Agosto', "September" => 'Septiembre', "October" => 'Octubre', "November" => 'Noviembre', "December" => 'Diciembre');
            break;
            case "al" :
                $days   = array("Monday" => 'Montag', "Tuesday" => 'Dienstag', "Wednesday" => 'Mittwoch', "Thursday" => 'Donnerstag', "Friday" => 'Freitag', "Saturday" => 'Samstag', "Sunday" => 'Sonntag');
                $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
            break;
			case "de" :
                $days   = array("Monday" => 'Montag', "Tuesday" => 'Dienstag', "Wednesday" => 'Mittwoch', "Thursday" => 'Donnerstag', "Friday" => 'Freitag', "Saturday" => 'Samstag', "Sunday" => 'Sonntag');
                $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
            break;
            case "pt" :
                $days   = array("Monday" => 'segunda-feira', "Tuesday" => 'ter&#231;a-feira', "Wednesday" => 'quarta-feira', "Thursday" => 'quinta-feira', "Friday" => 'sexta-feira', "Saturday" => 's&#225;bado', "Sunday" => 'domingo');
                $months = array("January" => 'janeiro', "February" => 'fevereiro', "March" => 'mar&#231;o', "April" => 'abril', "May" => 'maio', "June" => 'junho', "July" => 'julho', "August" => 'agosto', "September" => 'setembro', "October" => 'outubro', "November" => 'novembro', "December" => 'dezembro');
            break;
			case "nl" :
                $days   = array("Monday" => 'maandag', "Tuesday" => 'dinsdag', "Wednesday" => 'woensdag', "Thursday" => 'donderdag', "Friday" => 'vrijdag', "Saturday" => 'zaterdag', "Sunday" => 'zondag');
                $months = array("January" => 'januari', "February" => 'februari', "March" => 'maart', "April" => 'april', "May" => 'mei', "June" => 'juni', "July" => 'juli', "August" => 'augustus', "September" => 'september', "October" => 'oktober', "November" => 'november', "December" => 'december');
            break;
			case "it" :
                $days   = array("Monday" => 'Lunedí', "Tuesday" => 'Martedi', "Wednesday" => 'Mercoledí', "Thursday" => 'Giovedí', "Friday" => 'Venerdí', "Saturday" => 'Sabato', "Sunday" => 'Domenica');
                $months = array("January" => 'Gennaio', "February" => 'Febbraio', "March" => 'Marzo', "April" => 'Aprile', "May" => 'Maggio', "June" => 'Giugno', "July" => 'Luglio', "August" => 'Agosto', "September" => 'Settembre', "October" => 'Ottobre', "November" => 'Novembre', "December" => 'Dicembre');
            break;
			case "dk" :
                $days   = array("Monday" => 'Mandag', "Tuesday" => 'Tirsdag', "Wednesday" => 'Onsdag', "Thursday" => 'Torsdag', "Friday" => 'Fredag', "Saturday" => 'Lørdag', "Sunday" => 'Søndag');
                $months = array("January" => 'januar', "February" => 'februar', "March" => 'marts', "April" => 'april', "May" => 'Maj', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'December');
            break;
            case "se" :
                $days   = array("Monday" => 'M&aring;ndag', "Tuesday" => 'Tisdag', "Wednesday" => 'Onsdag', "Thursday" => 'Torsdag', "Friday" => 'Fredag', "Saturday" => 'L&ouml;rdag', "Sunday" => 'S&ouml;ndag');
                $months = array("January" => 'Januari', "February" => 'Februari', "March" => 'Mars', "April" => 'April', "May" => 'May', "June" => 'Juni', "July" => 'Juli', "August" => 'august', "September" => 'september', "October" => 'Oktober', "November" => 'November', "December" => 'December');
            break;
            case "no" :
                $days   = array("Monday" => 'mandag', "Tuesday" => 'Tisdag', "Wednesday" => 'Onsdag', "Thursday" => 'Torsdag', "Friday" => 'Fredag', "Saturday" => 'L&oslash;rdag', "Sunday" => 'S&oslash;ndag');
                $months = array("January" => 'Januar', "February" => 'februar', "March" => 'Mars', "April" => 'April', "May" => 'Maj', "June" => 'Juni', "July" => 'Juli', "August" => 'Augusti', "September" => 'September', "October" => 'oktober', "November" => 'November', "December" => 'Desember');
            break;
			case "tr" :
               $days   = array("Monday" => 'Pazartesi', "Tuesday" => 'Sal&#305;', "Wednesday" => '&Ccedil;ar&#351;amba', "Thursday" => 'Per&#351;embe', "Friday" => 'Cuma', "Saturday" => 'Cumartesi', "Sunday" => 'Pazar');
               $months = array("January" => 'Ocak', "February" => '&#350;ubat', "March" => 'Mart', "April" => 'Nisan', "May" => 'May&#305;s', "June" => 'Haziran', "July" => 'Temmuz', "August" => 'A&#287;ustos', "September" => 'Eylül', "October" => 'Ekim', "November" => 'Kas&#305;m', "December" => 'Aral&#305;k');
            break;
			
		    case "gr" :
               $days   = array("Monday" => 'Δευτέρα', "Tuesday" => 'Τρίτη', "Wednesday" => 'Τετάρτη', "Thursday" => 'Πέμπτη', "Friday" => 'Παρασκευή', "Saturday" => 'Σάββατο', "Sunday" => 'Κυριακή');
               $months = array("January" => 'Ιανουάριος', "February" => 'Φεβρουάριος', "March" => 'Μάρτιος', "April" => 'Απρίλιος', "May" => 'Μάιος', "June" => 'Ιούνιος', "July" => 'Ιούλιος', "August" => 'Αύγουστος', "September" => 'Σεπτέμβριος', "October" => 'Οκτώβριος', "November" => 'Νοέμβριος', "December" => 'Δεκέμβριος');
            break;
			
			 case "fin" :
                $days   = array("Monday" => 'Maanantai', "Tuesday" => 'Tiistai', "Wednesday" => 'Keskiviikko', "Thursday" => 'Torstai', "Friday" => 'Perjantai', "Saturday" => 'Lauantai', "Sunday" => 'Sunnuntai');
               $months = array("January" => 'Tammikuu', "February" => 'Helmikuu', "March" => 'Maaliskuu', "April" => 'Huhtikuu', "May" => 'Toukokuu', "June" => 'Kes&#228;kuu', "July" => 'Hein&#228;kuu', "August" => 'Elokuu', "September" => 'Syyskuu', "October" => 'Lokakuu', "November" => 'Marraskuu', "December" => 'Joulukuu');
            break;
			
			 case "pl" :
                $days   = array("Monday" => 'Poniedziałek', "Tuesday" => 'Wtorek', "Wednesday" => 'Środa', "Thursday" => 'Czwartek', "Friday" => 'Piątek', "Saturday" => 'Sobota', "Sunday" => 'Niedziela');
                $months = array("January" => 'Styczeń', "February" => 'Luty', "March" => 'Marzec', "April" => 'Kwiecień', "May" => 'Maj', "June" => 'Czerwiec', "July" => 'Lipiec', "August" => 'Sierpień', "September" => 'Wrzesień', "October" => 'Październik', "November" => 'Listopad', "December" => 'Grudzień');
            break;
			
			case "hi" :
                $days   = array("Monday" => 'सोमवार', "Tuesday" => 'मंगलवार', "Wednesday" => 'बुधवार', "Thursday" => 'गुरूवार', "Friday" => 'शुक्रवार', "Saturday" => 'शनिवार', "Sunday" => ' रविवार ');
                $months = array("January" => 'जनवरी', "February" => 'फ़रवरी', "March" => 'मार्च', "April" => 'अप्रैल', "May" => 'मई', "June" => 'जून', "July" => 'जुलाई', "August" => 'अगस्', "September" => 'सितंबर', "October" => 'अक्टूबर', "November" => 'नवंबर', "December" => 'दिसंबर');
            break;	
			
            default :
                $days   = array("Monday" => 'Lundi', "Tuesday" => 'Mardi', "Wednesday" => 'Mercredi', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'Janvier', "February" => 'F&#233;vrier', "March" => 'Mars', "April" => 'Avril', "May" => 'Mai', "June" => 'Juin', "July" => 'Juillet', "August" => 'Ao&#251;t', "September" => 'Septembre', "October" => 'Octobre', "November" => 'Novembre', "December" => 'D&#233;cembre');
            break;
        }
		
        /*
        $days   = array("Monday" => 'Montag', "Tuesday" => 'Dienstag', "Wednesday" => 'Mittwoch', "Thursday" => 'Donnerstag', "Friday" => 'Freitag', "Saturday" => 'Samstag', "Sunday" => 'Sonntag');
        $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
        */

        $ndate = explode("-",$ndate);
        
		
		
        if($sep==null || $sep=="") $sep = " ";
        
        $rdate = "";
        
		/* la partie de code qui ajoute den et ten specialement pour les porteurs turques*/
		       /* switch($ndate[1])
		{
			case 3:
			case 4:
			case 5:
			case 13:
			case 14:
			case 15: 
				//$res = $days[$ndate[0]].$sep.$ndate[1].'\'ten'.$sep.$months[$ndate[3]].$sep.$ndate[4];
				$rdate = $days[$ndate[0]].$sep.$ndate[1].'<sup>ten</sup>'.$sep.$months[$ndate[3]];
			break;
			default: 
			//$res = $days[$ndate[0]].$sep.$ndate[1].'\'den'.$sep.$months[$ndate[3]].$sep.$ndate[4];
			$rdate = $days[$ndate[0]].$sep.$ndate[1].'<sup>den</sup>'.$sep.$months[$ndate[3]];
			break;
		}*/
		/**/
		
        if($format!=null && $format!="")
        {
            $format = explode("/",$format);
            if(in_array("jj",$format)) { if($rdate!="") $rdate.=$sep; $rdate.= $days[$ndate[0]]; }
            if(in_array("dd",$format)) { if($rdate!="") $rdate.=$sep; $rdate.= $ndate[1]; }
            if(in_array("mm",$format)) { if($rdate!="") $rdate.=$sep; $rdate.= $ndate[2]; }
			if(in_array("mnt",$format)) { if($rdate!="") $rdate.=$sep; $rdate.= $months[$ndate[3]]; }
            if(in_array("mt",$format)) { if($rdate!="") $rdate.=$sep; $rdate.= $months[$ndate[3]]; }
            if(in_array("yy",$format)) { if($rdate!="") $rdate.=$sep; $rdate.= $ndate[4]; }
        }
        else $rdate = $days[$ndate[0]].$sep.$ndate[1].$sep.$months[$ndate[3]].$sep.$ndate[4];
        
		
		
        return trim($rdate);        
    }
	
	public static function completDate_short($dt=null){
		$sessionSD = Yii::app()->session;
	if(isset($sessionSD['SD']))
            {
            	$date_shoot=$sessionSD['SD'];
            		
            
            } 
		
		elseif (isset ( $_REQUEST ["sdEmv"] ))
			$date_shoot = $_REQUEST ["sdEmv"];
		
		else if (isset ( $_REQUEST ["sd"] ))
			$date_shoot = $_REQUEST ["sd"];
		
			
		else
			$date_shoot = date ( "m/d/Y" );
		
		if ($dt == null || $dt == "")
			$dt = "+0";
		
		$date = new DateTime ( $date_shoot );
		$date->modify ( "$dt day" );
		$ndate = $date->format ( 'm/d/Y' );
		
		return trim ( $ndate );
	}
	
	
	/*/////////////////////////* Fid FMAC //////////////////////////////////////////////*/	
		public static function completDateFMAC($dt=null,$lang=null,$format=null,$sep=null)
    {
    	$sessionSD = Yii::app()->session;
		 if(isset($sessionSD['SD']))
            {
            	$date_shoot=$sessionSD['SD'];
            		
            
            } 
        elseif(isset($_REQUEST["sdEmv"])) $date_shoot = $_REQUEST["sdEmv"];
        else if(isset($_REQUEST["sd"])) $date_shoot = $_REQUEST["sd"];
        
        	
        else $date_shoot = new DateTime('2016-04-20');
        if($dt==null || $dt=="") $dt ="+0";
        $date = $date_shoot;
        $date->modify("$dt day");
        $ndate = $date->format('l-d-m-F-Y');
        
        //return $dt; exit();
        if($lang==null || $lang=="") $lang = "fr";
         
        switch(strtolower($lang))
        {
            case "fr" :
                $days   = array("Monday" => 'Lundi', "Tuesday" => 'Mardi', "Wednesday" => 'Mercredi', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'Janvier', "February" => 'F&#233;vrier', "March" => 'Mars', "April" => 'Avril', "May" => 'Mai', "June" => 'Juin', "July" => 'Juillet', "August" => 'Ao&#251;t', "September" => 'Septembre', "October" => 'Octobre', "November" => 'Novembre', "December" => 'D&#233;cembre');
            break;
            case "en" :
                $days   = array("Monday" => 'Monday', "Tuesday" => 'Tuesday', "Wednesday" => 'Wednesday', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'January', "February" => 'February', "March" => 'March', "April" => 'April', "May" => 'May', "June" => 'June', "July" => 'July', "August" => 'August', "September" => 'September', "October" => 'October', "November" => 'November', "December" => 'December');
            break;
            case "es" :
                $days   = array("Monday" => 'Lunes', "Tuesday" => 'Martes', "Wednesday" => 'Mi&#233;rcoles', "Thursday" => 'Jueves', "Friday" => 'Viernes', "Saturday" => 'S&#225;bado', "Sunday" => 'Domingo');
                $months = array("January" => 'Enero', "February" => 'Febrero', "March" => 'Marzo', "April" => 'Abril', "May" => 'Mayo', "June" => 'Junio', "July" => 'Julio', "August" => 'Agosto', "September" => 'Septiembre', "October" => 'Octubre', "November" => 'Noviembre', "December" => 'Diciembre');
            break;
            case "al" :
                $days   = array("Monday" => 'Montag', "Tuesday" => 'Dienstag', "Wednesday" => 'Mittwoch', "Thursday" => 'Donnerstag', "Friday" => 'Freitag', "Saturday" => 'Samstag', "Sunday" => 'Sonntag');
                $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
            break;
            case "pt" :
                $days   = array("Monday" => 'segunda-feira', "Tuesday" => 'ter&#231;a-feira', "Wednesday" => 'quarta-feira', "Thursday" => 'quinta-feira', "Friday" => 'sexta-feira', "Saturday" => 's&#225;bado', "Sunday" => 'domingo');
                $months = array("January" => 'janeiro', "February" => 'fevereiro', "March" => 'mar&#231;o', "April" => 'abril', "May" => 'maio', "June" => 'junho', "July" => 'julho', "August" => 'agosto', "September" => 'setembro', "October" => 'outubro', "November" => 'novembro', "December" => 'dezembro');
            break;
            case "nl" :
            	$days   = array("Monday" => 'maandag', "Tuesday" => 'dinsdag', "Wednesday" => 'woensdag', "Thursday" => 'donderdag', "Friday" => 'vrijdag', "Saturday" => 'zaterdag', "Sunday" => 'zondag');
            	$months = array("January" => 'januari', "February" => 'februari', "March" => 'maart', "April" => 'april', "May" => 'mei', "June" => 'juni', "July" => 'juli', "August" => 'augustus', "September" => 'september', "October" => 'oktober', "November" => 'november', "December" => 'december');
            break;
			case "de" :
                $days   = array("Monday" => 'Montag', "Tuesday" => 'Dienstag', "Wednesday" => 'Mittwoch', "Thursday" => 'Donnerstag', "Friday" => 'Freitag', "Saturday" => 'Samstag', "Sunday" => 'Sonntag');
                $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
            break;
            default :
                $days   = array("Monday" => 'Lundi', "Tuesday" => 'Mardi', "Wednesday" => 'Mercredi', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'Janvier', "February" => 'F&#233;vrier', "March" => 'Mars', "April" => 'Avril', "May" => 'Mai', "June" => 'Juin', "July" => 'Juillet', "August" => 'Ao&#251;t', "September" => 'Septembre', "October" => 'Octobre', "November" => 'Novembre', "December" => 'D&#233;cembre');
            break;
        }

        $ndate = explode("-",$ndate);
        
        if($sep==null || $sep=="") $sep = " ";
        
        $res = "";
        
        if($format!=null && $format!="")
        {
            $format = explode("/",$format);
            if(in_array("jj",$format)) { if($res!="") $res.=$sep; $res.= $days[$ndate[0]]; }
            if(in_array("dd",$format)) { if($res!="") $res.=$sep; $res.= $ndate[1]; }
            if(in_array("mm",$format)) { if($res!="") $res.=$sep; $res.= $ndate[2]; }
            if(in_array("mt",$format)) { if($res!="") $res.=$sep; $res.= $months[$ndate[3]]; }
            if(in_array("yy",$format)) { if($res!="") $res.=$sep; $res.= $ndate[4]; }
        }
        else $res = $days[$ndate[0]].$sep.$ndate[1].$sep.$months[$ndate[3]].$sep.$ndate[4];
        
        return trim($res);        
    }
	
	/*/////////////////////////* DE+X //////////////////////////////////////////////*/

public static function completDate_DE($dt=null,$lang=null,$format=null,$sep=null)
    {
    	$sessionSD = Yii::app ()->session;
    	
    	 if (isset ( $sessionSD ['SD'] )) {
    		$date_shoot = $sessionSD ['SD'];
    	}
    	 
		elseif (isset ( $_REQUEST ["deEmv"] ))
			$date_shoot = $_REQUEST ["deEmv"];
		else if (isset ( $_REQUEST ["de"] ))
			$date_shoot = $_REQUEST ["de"];
		
        else $date_shoot = date("m/d/Y");
        if($dt==null || $dt=="") $dt ="+0";
        $date = new DateTime($date_shoot);
        $date->modify("$dt day");
        $ndate = $date->format('l-d-m-F-Y');
        
        if($lang==null || $lang=="") $lang = "fr";
        
        switch(strtolower($lang))
        {
            case "fr" :
                $days   = array("Monday" => 'Lundi', "Tuesday" => 'Mardi', "Wednesday" => 'Mercredi', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'Janvier', "February" => 'F&#233;vrier', "March" => 'Mars', "April" => 'Avril', "May" => 'Mai', "June" => 'Juin', "July" => 'Juillet', "August" => 'Ao&#251;t', "September" => 'Septembre', "October" => 'Octobre', "November" => 'Novembre', "December" => 'D&#233;cembre');
            break;
            case "en" :
                $days   = array("Monday" => 'Monday', "Tuesday" => 'Tuesday', "Wednesday" => 'Wednesday', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'January', "February" => 'February', "March" => 'March', "April" => 'April', "May" => 'May', "June" => 'June', "July" => 'July', "August" => 'August', "September" => 'September', "October" => 'October', "November" => 'November', "December" => 'December');
            break;
            case "es" :
                $days   = array("Monday" => 'Lunes', "Tuesday" => 'Martes', "Wednesday" => 'Mi&#233;rcoles', "Thursday" => 'Jueves', "Friday" => 'Viernes', "Saturday" => 'S&#225;bado', "Sunday" => 'Domingo');
                $months = array("January" => 'Enero', "February" => 'Febrero', "March" => 'Marzo', "April" => 'Abril', "May" => 'Mayo', "June" => 'Junio', "July" => 'Julio', "August" => 'Agosto', "September" => 'Septiembre', "October" => 'Octubre', "November" => 'Noviembre', "December" => 'Diciembre');
            break;
            case "al" :
                $days   = array("Monday" => 'Montag', "Tuesday" => 'Dienstag', "Wednesday" => 'Mittwoch', "Thursday" => 'Donnerstag', "Friday" => 'Freitag', "Saturday" => 'Samstag', "Sunday" => 'Sonntag');
                $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
            break;
			case "de" :
                $days   = array("Monday" => 'Montag', "Tuesday" => 'Dienstag', "Wednesday" => 'Mittwoch', "Thursday" => 'Donnerstag', "Friday" => 'Freitag', "Saturday" => 'Samstag', "Sunday" => 'Sonntag');
                $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
            break;
            case "pt" :
                $days   = array("Monday" => 'segunda-feira', "Tuesday" => 'ter&#231;a-feira', "Wednesday" => 'quarta-feira', "Thursday" => 'quinta-feira', "Friday" => 'sexta-feira', "Saturday" => 's&#225;bado', "Sunday" => 'domingo');
                $months = array("January" => 'janeiro', "February" => 'fevereiro', "March" => 'mar&#231;o', "April" => 'abril', "May" => 'maio', "June" => 'junho', "July" => 'julho', "August" => 'agosto', "September" => 'setembro', "October" => 'outubro', "November" => 'novembro', "December" => 'dezembro');
            break;
			case "nl" :
                $days   = array("Monday" => 'maandag', "Tuesday" => 'dinsdag', "Wednesday" => 'woensdag', "Thursday" => 'donderdag', "Friday" => 'vrijdag', "Saturday" => 'zaterdag', "Sunday" => 'zondag');
                $months = array("January" => 'januari', "February" => 'februari', "March" => 'maart', "April" => 'april', "May" => 'mei', "June" => 'juni', "July" => 'juli', "August" => 'augustus', "September" => 'september', "October" => 'oktober', "November" => 'november', "December" => 'december');
            break;
             case "se" :
                $days   = array("Monday" => 'M&aring;ndag', "Tuesday" => 'Tisdag', "Wednesday" => 'Onsdag', "Thursday" => 'Torsdag', "Friday" => 'Fredag', "Saturday" => 'L&ouml;rdag', "Sunday" => 'S&ouml;ndag');
                $months = array("January" => 'Januari', "February" => 'Februari', "March" => 'Mars', "April" => 'April', "May" => 'Maj', "June" => 'Juni', "July" => 'Juli', "August" => 'Augusti', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'December');
            break;
			case "tr" :
                $days   = array("Monday" => 'M&aring;ndag', "Tuesday" => 'Tisdag', "Wednesday" => 'Onsdag', "Thursday" => 'Torsdag', "Friday" => 'Cuma', "Saturday" => 'Cumartesi', "Sunday" => 'Pazar');
                $months = array("January" => 'Ocak', "February" => '&#350;ubat', "March" => 'Mart', "April" => 'Nisan', "May" => 'May&#305;s', "June" => 'Haziran', "July" => 'Temmuz', "August" => 'A&#287;ustos', "September" => 'Eyl&uuml;l', "October" => 'Ekim', "November" => 'Kas&#305;m', "December" => 'Aral&#305;k');
            break;
			case "dk" :
                $days   = array("Monday" => 'Mandag', "Tuesday" => 'Tirsdag', "Wednesday" => 'Onsdag', "Thursday" => 'Torsdag', "Friday" => 'Fredag', "Saturday" => 'Lørdag', "Sunday" => 'Søndag');
                $months = array("January" => 'januar', "February" => 'februar', "March" => 'marts', "April" => 'april', "May" => 'Maj', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'December');
            break;
			case "fin" :
			 $days   = array("Monday" => 'Maanantai', "Tuesday" => 'Tiistai', "Wednesday" => 'Keskiviikko', "Thursday" => 'Torstai', "Friday" => 'Perjantai', "Saturday" => 'Lauantai', "Sunday" => 'Sunnuntai');
               $months = array("January" => 'Tammikuu', "February" => 'Helmikuu', "March" => 'Maaliskuu', "April" => 'Huhtikuu', "May" => 'Toukokuu', "June" => 'Kes&#228;kuu', "July" => 'Hein&#228;kuu', "August" => 'Elokuu', "September" => 'Syyskuu', "October" => 'Lokakuu', "November" => 'Marraskuu', "December" => 'Joulukuu');
            break;
			
            default :
                $days   = array("Monday" => 'Lundi', "Tuesday" => 'Mardi', "Wednesday" => 'Mercredi', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'Janvier', "February" => 'F&#233;vrier', "March" => 'Mars', "April" => 'Avril', "May" => 'Mai', "June" => 'Juin', "July" => 'Juillet', "August" => 'Ao&#251;t', "September" => 'Septembre', "October" => 'Octobre', "November" => 'Novembre', "December" => 'D&#233;cembre');
            break;
        }

        $ndate = explode("-",$ndate);
        
        if($sep==null || $sep=="") $sep = " ";
        
        $res = "";
        
        if($format!=null && $format!="")
        {
            $format = explode("/",$format);
            if(in_array("jj",$format)) { if($res!="") $res.=$sep; $res.= $days[$ndate[0]]; }
            if(in_array("dd",$format)) { if($res!="") $res.=$sep; $res.= $ndate[1]; }
            if(in_array("mm",$format)) { if($res!="") $res.=$sep; $res.= $ndate[2]; }
            if(in_array("mt",$format)) { if($res!="") $res.=$sep; $res.= $months[$ndate[3]]; }
            if(in_array("yy",$format)) { if($res!="") $res.=$sep; $res.= $ndate[4]; }
        }
        else $res = $days[$ndate[0]].$sep.$ndate[1].$sep.$months[$ndate[3]].$sep.$ndate[4];
        
        return trim($res);        
    }
	
	////////////////////// Le premier Jour apres [S+X] ////////////////
	/*
		Arguments:
		$NBweek: Nombre de semaines à ajouter
		$Numjour: Jour de la semaine à afficher tel que: Lundi=1, Mardi= 2, etc...
	*/
	
public static function DayOfWeek($NBweek,$Numjour)
	{	
		$sessionSD = Yii::app()->session;
		$duree = $NBweek*7;	
		$array = array();
		
			
			 if(isset($sessionSD['SD']))
			{
				$date=$sessionSD['SD'];
			}
			elseif(isset($_GET['sd']))
			{
				$date = $_GET['sd'];
			}	
				
		$sd_time 		= strtotime($date); 
		$nbreWeek="+".$NBweek." week";
		

		$date=strtotime("$nbreWeek", $sd_time);
		$date=date("m/d/Y",$date);
	
		for ($i = 0; $i <= $duree; $i++)
			{	

					$dateDepartTimestamp = strtotime($date);
				
					$dateFin = date('d-m-Y', strtotime('+'.$i.' days' , $dateDepartTimestamp ));	
					$datetemp = explode("-",$dateFin);	
					$datetemp = $datetemp[2].'/'.$datetemp[1].'/'.$datetemp[0];		
	
					
	
				if (date('w', strtotime($datetemp)) == $Numjour)
				{
						
					
					$res = date('l d F Y', strtotime($datetemp));
					$tab = explode(" ",$res); 
					$find = array($tab[0],$tab[2]);
					$replace = array(\Yii::t('DateTraduction',$tab[0]),\Yii::t('DateTraduction',$tab[2]));
					$translate= str_replace($find,$replace,$res);
					array_push($array, $translate);
					break;
				}
			}

		return $array[sizeof($array)-1];
	}
	
	////////////////////Date du premier Jour après le [J+X] //////////////
	/*
		Arguments:
			$NBjour: Nombre de jours à ajouter
			$Numjour: Jour de la semaine à afficher tel que: Lundi=1, Mardi= 2, etc...
		*/
public static function DayAfterDate($NBjour,$Numjour)
	{	
		$sessionSD = Yii::app()->session;
		$array = array();
		
		if(isset($sessionSD['SD']))
		{
			$date=$sessionSD['SD'];
				
				
		}
			elseif(isset($_GET['sd']))
			{
				$date = $_GET['sd'];
			}	
	
		$sd_time 		= strtotime($date); 
		$NBjour="+".$NBjour." day";
		$date=strtotime("$NBjour", $sd_time);
		$date=date("m/d/Y",$date);
				
			
			for ($i = 0; $i <= 14; $i++)
			{	

					$dateDepartTimestamp = strtotime($date);
					$dateFin = date('d-m-Y', strtotime('+'.$i.' days' , $dateDepartTimestamp ));	
					$datetemp = explode("-",$dateFin);	
					$datetemp = $datetemp[2].'/'.$datetemp[1].'/'.$datetemp[0];		
	
				if (date('w', strtotime($datetemp)) == $Numjour)
				{	
	
					$res = date('l d F Y', strtotime($datetemp));
					$tab = explode(" ",$res); 
					$find = array($tab[0],$tab[2]);
					$replace = array(\Yii::t('DateTraduction',$tab[0]),\Yii::t('DateTraduction',$tab[2]));
					$translate= str_replace($find,$replace,$res);
					array_push($array, $translate);
				}
			}

		return $array[sizeof($array)-2];
	}


      /**
    * Developpeur: Mounir MOUIH
    * get [M+X]
    * @param $dt nombre de mois à ajouter
    * @param $lang la langue supporté 
    * @param $type on utilise la date sd ou de ? sd par default
    * @return string
    */
    public static function getMonth($dt = null, $lang = null, $type = "sd"){
        
        /**
        *  Sd by default
        */
		
		
    	$sessionSD = Yii::app ()->session;
		$requestEmv = "sdEmv";
		$request_direct = "sd";
		
		if ($type == "de") :
			$requestEmv = "deEmv";
			$request_direct = "de";
		
        endif;
        if (isset ( $sessionSD ['SD'] )) :
        	
        $date_shoot = $sessionSD ['SD'];
		elseif (isset ( $_REQUEST [$requestEmv] )) :
			$date_shoot = $_REQUEST [$requestEmv];
		 elseif (isset ( $_REQUEST [$request_direct] )) :
			$date_shoot = $_REQUEST [$request_direct];
		
		 else :
			$date_shoot = date ( "m/d/Y" );
		endif; 
        
        		
        		
       
        if($dt == null || $dt == "")  :
            $dt ="+0";
        endif; 

        $dt  = (int)$dt; 
        $array_date  = explode('/', $date_shoot);
        $current_month  = (int)$array_date[0];
        $month = $current_month + $dt%12; 

        /* this isn't unecessary*/
        if($month >  12) : 
            $month -= 12; 
        elseif($month < 1) :
            $month += 12; 
        endif; 

        /* Year and day doesn't matter here */
        $date = $month.'/01/'.$array_date[2]; 

        $month = date('F', strtotime($date)); 
            

        if($lang == null || $lang=="") : 
            $lang = "fr";
        endif; 
            
         switch(strtolower($lang))
            {
                case "fr" :
                    $months = array("January" => 'Janvier', "February" => 'F&#233;vrier', "March" => 'Mars', "April" => 'Avril', "May" => 'Mai', "June" => 'Juin', "July" => 'Juillet', "August" => 'Ao&#251;t', "September" => 'Septembre', "October" => 'Octobre', "November" => 'Novembre', "December" => 'D&#233;cembre');
                break;
                case "en" :
                    $months = array("January" => 'January', "February" => 'February', "March" => 'March', "April" => 'April', "May" => 'May', "June" => 'June', "July" => 'July', "August" => 'August', "September" => 'September', "October" => 'October', "November" => 'November', "December" => 'December');
                break;
                case "es" :
                    $months = array("January" => 'Enero', "February" => 'Febrero', "March" => 'Marzo', "April" => 'Abril', "May" => 'Mayo', "June" => 'Junio', "July" => 'Julio', "August" => 'Agosto', "September" => 'Septiembre', "October" => 'Octubre', "November" => 'Noviembre', "December" => 'Diciembre');
                break;
                case "al" :
                    $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
                break;
				case "de" :
                    $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
                break;
                case "pt" :
                    $months = array("January" => 'janeiro', "February" => 'fevereiro', "March" => 'mar&#231;o', "April" => 'abril', "May" => 'maio', "June" => 'junho', "July" => 'julho', "August" => 'agosto', "September" => 'setembro', "October" => 'outubro', "November" => 'novembro', "December" => 'dezembro');
                break;
                case "nl" :
                    $months = array("January" => 'januari', "February" => 'februari', "March" => 'maart', "April" => 'april', "May" => 'mei', "June" => 'juni', "July" => 'juli', "August" => 'augustus', "September" => 'september', "October" => 'oktober', "November" => 'november', "December" => 'december');
                break;
                case "it" :
                    $months = array("January" => 'Gennaio', "February" => 'Febbraio', "March" => 'Marzo', "April" => 'Aprile', "May" => 'Maggio', "June" => 'Giugno', "July" => 'Luglio', "August" => 'Agosto', "September" => 'Settembre', "October" => 'Ottobre', "November" => 'Novembre', "December" => 'Dicembre');
                break;
                case "dk" :
                    $months = array("January" => 'januar', "February" => 'februar', "March" => 'marts', "April" => 'april', "May" => 'Maj', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'December');
                break;
                case "se" :
                    $months = array("January" => 'Januari', "February" => 'Februari', "March" => 'Mars', "April" => 'April', "May" => 'May', "June" => 'Juni', "July" => 'Juli', "August" => 'august', "September" => 'september', "October" => 'Oktober', "November" => 'November', "December" => 'December');
                break;
                case "no" :
                    $months = array("January" => 'Januar', "February" => 'februar', "March" => 'Mars', "April" => 'April', "May" => 'Maj', "June" => 'Juni', "July" => 'Juli', "August" => 'Augusti', "September" => 'September', "October" => 'oktober', "November" => 'November', "December" => 'Desember');
                break;
				case "tr" :
                    $months = array("January" => 'Ocak', "February" => '&#350;ubat', "March" => 'Mart', "April" => 'Nisan', "May" => 'May&#305;s', "June" => 'Haziran', "July" => 'Temmuz', "August" => 'A&#287;ustos', "September" => 'Eyl&uuml;l', "October" => 'Ekim', "November" => 'Kas&#305;m', "December" => 'Aral&#305;k');
                break;
                default :
                    $months = array("January" => 'Janvier', "February" => 'F&#233;vrier', "March" => 'Mars', "April" => 'Avril', "May" => 'Mai', "June" => 'Juin', "July" => 'Juillet', "August" => 'Ao&#251;t', "September" => 'Septembre', "October" => 'Octobre', "November" => 'Novembre', "December" => 'D&#233;cembre');
                break;
            }

        $result= $months[$month];

        return trim($result); 
    }
	
	
	///////////////////
	/* * Developpeur: Mounir wafae cheglal
    * get M+X et J+x selon de et sd
    * @param $dt nombre de jour à ajouter
	* @param $m nombre de mois à ajouter
    * @param $lang la forme anglaise ou française
    * @param $type_date on utilise la date sd ou de 
	* day, mounths, year : le bloc qu'on veut afficher
    * @return string
	*/
		public static function completDateV2($dt=null,$m=null,$type_date=null,$lang=null,$day=null,$months=null,$year=null){
			$sessionSD = Yii::app ()->session;
		$request = Yii::app ()->request;
		if (isset ( $sessionSD ['SD'] )) {
			$date_sd = $sessionSD ['SD'];
		}
		elseif ($type_date == "sd") {
			$date_sd = $request->getParam ( 'sd', date ( "m/d/Y" ) );
			$date_sd = $request->getParam ( 'sdEmv', $date_sd );
		 
		} else {
			$date_shoot = $request->getParam ( 'de', date ( "m/d/Y" ) );
			$date_shoot = $request->getParam ( 'deEmv', $date_shoot );
		}
	
	   
        if($dt==null || $dt=="") $dt ="+0";
		if($m==null || $m=="") $m ="+0";
		if(isset($date_sd) && $date_sd!=null){
			
		$date = new DateTime($date_sd);
        $date->modify("$dt day");
		$date->modify("$m month");
		}
		else{
        $date = new DateTime($date_shoot);
        $date->modify("$dt day");
		$date->modify("$m month");}
							
		 if($lang==null || $lang=="") $lang = "fr";
		 if($lang=='en') $ndate = $date->format('l, F d Y');			 
		 else $ndate = $date->format('l d F Y');
		 
        $ndate = explode(" ",$ndate);      	       
        $rdate = array();    
              
           if($lang=='en'){  
		   if($day){ $rdate = $ndate[0];}
		   else if($months){ $rdate = $ndate[1];}
		   else if($year){$rdate = $ndate[3];}
		   else if($day && $months)$rdate = $ndate[0]." ".$ndate[1]." ".$ndate[2];
		   else $rdate = $ndate[0]." ".$ndate[1]." ".$ndate[2]." ".$ndate[3];
		   }
		   else{
		   if($day){ $rdate = \Yii::t('DateTraduction',$ndate[0]);}   
		   else if($months){$rdate = \Yii::t('DateTraduction',$ndate[2]);}
		   else if($year){ $rdate = \Yii::t('DateTraduction',$ndate[3]);}
		   else if($day && $months){ $rdate = \Yii::t('DateTraduction',$ndate[0])." ".\Yii::t('DateTraduction',$ndate[1])." ".\Yii::t('DateTraduction',$ndate[2]);}
		   else {$rdate = \Yii::t('DateTraduction',$ndate[0])." ".\Yii::t('DateTraduction',$ndate[1])." ".\Yii::t('DateTraduction',$ndate[2])." ".\Yii::t('DateTraduction',$ndate[3]);}		    
		   }	
			                 		    	                 		
	     return ($rdate);        
    }

	
			public static function completDateopact($dt=null,$lang=null,$format=null,$sep=null)
    {
    	$sessionSD = Yii::app ()->session;
    if (isset ( $sessionSD ['SD'] )) {
			$date_shoot = $sessionSD ['SD'];
		}
		elseif (isset ( $_REQUEST ["sdEmv"] ))
			$date_shoot = $_REQUEST ["sdEmv"];
		else if (isset ( $_REQUEST ["sd"] ))
			$date_shoot = $_REQUEST ["sd"];
		
		 else
			$date_shoot = date ( "m/d/Y" );
		if ($dt == null || $dt == "")
			$dt = "+0";
		$date = new DateTime ( $date_shoot );
		$date->modify ( "$dt day" );
		$ndate = $date->format ( 'l-d-m-F-Y' );
        
        //return $dt; exit();
        if($lang==null || $lang=="") $lang = "fr";
         
        switch(strtolower($lang))
        {
            case "fr" :
                $days   = array("Monday" => 'Lundi', "Tuesday" => 'Mardi', "Wednesday" => 'Mercredi', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'Janvier', "February" => 'F&#233;vrier', "March" => 'Mars', "April" => 'Avril', "May" => 'Mai', "June" => 'Juin', "July" => 'Juillet', "August" => 'Ao&#251;t', "September" => 'Septembre', "October" => 'Octobre', "November" => 'Novembre', "December" => 'D&#233;cembre');
            break;
            case "en" :
                $days   = array("Monday" => 'Monday', "Tuesday" => 'Tuesday', "Wednesday" => 'Wednesday', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'January', "February" => 'February', "March" => 'March', "April" => 'April', "May" => 'May', "June" => 'June', "July" => 'July', "August" => 'August', "September" => 'September', "October" => 'October', "November" => 'November', "December" => 'December');
            break;
            case "es" :
                $days   = array("Monday" => 'Lunes', "Tuesday" => 'Martes', "Wednesday" => 'Mi&#233;rcoles', "Thursday" => 'Jueves', "Friday" => 'Viernes', "Saturday" => 'S&#225;bado', "Sunday" => 'Domingo');
                $months = array("January" => 'Enero', "February" => 'Febrero', "March" => 'Marzo', "April" => 'Abril', "May" => 'Mayo', "June" => 'Junio', "July" => 'Julio', "August" => 'Agosto', "September" => 'Septiembre', "October" => 'Octubre', "November" => 'Noviembre', "December" => 'Diciembre');
            break;
            case "al" :
                $days   = array("Monday" => 'Montag', "Tuesday" => 'Dienstag', "Wednesday" => 'Mittwoch', "Thursday" => 'Donnerstag', "Friday" => 'Freitag', "Saturday" => 'Samstag', "Sunday" => 'Sonntag');
                $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
            break;
			case "de" :
                $days   = array("Monday" => 'Montag', "Tuesday" => 'Dienstag', "Wednesday" => 'Mittwoch', "Thursday" => 'Donnerstag', "Friday" => 'Freitag', "Saturday" => 'Samstag', "Sunday" => 'Sonntag');
                $months = array("January" => 'Januar', "February" => 'Februar', "March" => 'M&#228;rz', "April" => 'April', "May" => 'Mai', "June" => 'Juni', "July" => 'Juli', "August" => 'August', "September" => 'September', "October" => 'Oktober', "November" => 'November', "December" => 'Dezember');
            break;
            case "pt" :
                $days   = array("Monday" => 'segunda-feira', "Tuesday" => 'ter&#231;a-feira', "Wednesday" => 'quarta-feira', "Thursday" => 'quinta-feira', "Friday" => 'sexta-feira', "Saturday" => 's&#225;bado', "Sunday" => 'domingo');
                $months = array("January" => 'janeiro', "February" => 'fevereiro', "March" => 'mar&#231;o', "April" => 'abril', "May" => 'maio', "June" => 'junho', "July" => 'julho', "August" => 'agosto', "September" => 'setembro', "October" => 'outubro', "November" => 'novembro', "December" => 'dezembro');
            break;
            case "nl" :
            	$days   = array("Monday" => 'maandag', "Tuesday" => 'dinsdag', "Wednesday" => 'woensdag', "Thursday" => 'donderdag', "Friday" => 'vrijdag', "Saturday" => 'zaterdag', "Sunday" => 'zondag');
            	$months = array("January" => 'januari', "February" => 'februari', "March" => 'maart', "April" => 'april', "May" => 'mei', "June" => 'juni', "July" => 'juli', "August" => 'augustus', "September" => 'september', "October" => 'oktober', "November" => 'november', "December" => 'december');
            break;
			case "tr" :
                $days   = array("Monday" => 'Pazartesi', "Tuesday" => 'Salı', "Wednesday" => 'Çarşamba', "Thursday" => 'Perşembe', "Friday" => 'Cuma', "Saturday" => 'Cumartesi', "Sunday" => 'Pazar');
                $months = array("January" => 'Ocak', "February" => 'Şubat', "March" => 'Mart', "April" => 'Nisan', "May" => 'Mayıs', "June" => 'Haziran', "July" => 'Temmuz', "August" => 'Ağustos', "September" => 'Eylül', "October" => 'Ekim', "November" => 'Kasım', "December" => 'Aralık');
            break;
            default :
                $days   = array("Monday" => 'Lundi', "Tuesday" => 'Mardi', "Wednesday" => 'Mercredi', "Thursday" => 'Jeudi', "Friday" => 'Vendredi', "Saturday" => 'Samedi', "Sunday" => 'Dimanche');
                $months = array("January" => 'Janvier', "February" => 'F&#233;vrier', "March" => 'Mars', "April" => 'Avril', "May" => 'Mai', "June" => 'Juin', "July" => 'Juillet', "August" => 'Ao&#251;t', "September" => 'Septembre', "October" => 'Octobre', "November" => 'Novembre', "December" => 'D&#233;cembre');
            break;
        }

        $ndate = explode("-",$ndate);
        
        if($sep==null || $sep=="") $sep = " ";
        
        $res = "";
		
		/* la partie de code qui ajoute den et ten specialement pour les porteurs turques*/
		        switch($ndate[1])
		{
			case 3:
			case 4:
			case 5:
			case 13:
			case 14:
			case 15: 
				//$res = $days[$ndate[0]].$sep.$ndate[1].'\'ten'.$sep.$months[$ndate[3]].$sep.$ndate[4];
				$res = $days[$ndate[0]].$sep.$ndate[1].'<sup>ten</sup>'.$sep.$months[$ndate[3]];
			break;
			default: 
			//$res = $days[$ndate[0]].$sep.$ndate[1].'\'den'.$sep.$months[$ndate[3]].$sep.$ndate[4];
			$res = $days[$ndate[0]].$sep.$ndate[1].'<sup>den</sup>'.$sep.$months[$ndate[3]];
			break;
		}
		/**/
        
        if($format!=null && $format!="")
        {
            $format = explode("/",$format);
            if(in_array("jj",$format)) { if($res!="") $res.=$sep; $res.= $days[$ndate[0]]; }
            if(in_array("dd",$format)) { if($res!="") $res.=$sep; $res.= $ndate[1]; }
            if(in_array("mm",$format)) { if($res!="") $res.=$sep; $res.= $ndate[2]; }
            if(in_array("mt",$format)) { if($res!="") $res.=$sep; $res.= $months[$ndate[3]]; }
            if(in_array("yy",$format)) { if($res!="") $res.=$sep; $res.= $ndate[4]; }
        }
       // else $res = $days[$ndate[0]].$sep.$ndate[1].$sep.$months[$ndate[3]].$sep.$ndate[4];
        
        return trim($res);        
    }
}



?>