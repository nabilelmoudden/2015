<?php

	class Seuilswitch
{
     function Seuilswi($site_porteur)  {

      $id=NUll;
		        
      switch ($site_porteur) {
        case "de_rmay":
            $id='1495127';
            break;
        case "de_theodor":
            $id='2592651';
             break;
       
        case "in_alisha":
        case "au_alisha":
        case "sg_alisha":
        case "ca_alisha":
        case "uk_alisha":
        case "sf_alisha":
        case "nz_alisha":
            $id='1493293';
             break;
        case "de_rinalda":
            $id='2592652';
             break;
        case "fr_rinalda":
            $id='1114479804';
             break; 
        case "uk_aasha_fid":        
        case "au_aasha_fid":
        case "ca_aasha_fid":     
            $id='1001917465';
        break;    
        case "uk_aasha":     
        case "au_aasha":  
        case "ca_aasha":        
            $id='2817827';
         break;    
       case "in_aasha":
            $id='1120910';
             break;
        case "es_laetizia":
        case "mx_laetizia":
            $id='2206753';
             break;
       case "ar_laetizia":
            $id='1120911';
             break;
         case "cl_laetizia":
            $id='1120912';
             break;
        case "es_rmay":
        case "mx_rmay":
            $id='1254111';
             break;   
         case "fr_rmay":
            $id='2235707';
             break;
         case "fr_laetizia":
            $id='3934023';
             break;   
       
         case "nl_laetizia":
            $id='1120913';
             break;
         case "in_laetizia":
         case "uk_laetizia":
            $id='1120914';
             break; 
          case "nl_rmay":
            $id='717142';
             break;
         case "pt_laetizia":
            $id='2206754';
             break;
        case "br_laetizia":
            $id='1120915';
             break;   
         case "br_rmay":
            $id='1120916';
             break;
         case "pt_rmay":
            $id='2176252';
             break;  
        
         case "fr_rucker_fid":
            $id='2592655';
             break;
         case "fr_rucker":
            $id='2817471';
             break; 
         case "it_laetizia":
            $id='1120917';
             break;   
         case "dk_laetizia":
            $id='1120918';
             break;
         case "se_laetizia":
            $id='1120919';
             break; 
          case "no_laetizia":
            $id='1120920';
             break;
         case "se_rinalda":
            $id='1120921';
             break; 
         case "no_rinalda":
            $id='1120922';
             break;   
         case "dk_rinalda":
            $id='1120923';
             break;
         case "tr_rmay":
            $id='1120924';
             break; 
         case "nl_rinalda":
            $id='1120927';
             break; 
          case "pt_rinalda":
            $id='1120928';
             break;
         case "br_rinalda":
            $id='1120929';
             break; 
         case "de_laetizia":
            $id='1120930';
             break;   
         case "uk_rinalda":
            $id='1120931';
             break;
         case "it_rinalda":
            $id='1120932';
             break; 
         case "ca_rinalda":
            $id='1120933';
             break;
         case "au_rinalda":
            $id='1120934';
             break; 
         case "dk_rmay":
            $id='1120935';
             break;   
         case "no_ml":
            $id='1120936';
             break;
         case "it_ml":
            $id='1120937';
             break; 
        case "se_rmay":
            $id='1120938';
             break;
         case "pl_rmay":
            $id='1120939';
             break; 
         case "tr_laetizia":
            $id='1124642';
             break; 
         case "ie_laetizia":
            $id='1124643';
             break;
         case "fi_laetizia":
            $id='1247351';
             break; 
         case "fr_myriana":
            $id='1247352';
             break;   
        default:
           break;
    }
	return $id;
	 }

}

?>