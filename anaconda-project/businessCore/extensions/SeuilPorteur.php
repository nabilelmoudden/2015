<?php

	class SeuilPorteur
{
     function SeuilPorteur($key)  {

      $porteur=NUll;
		        
      switch ($key) {
        case "es_laetizia":
        case "no_laetizia":
        case "de_laetizia":
        case "dk_laetizia":
        case "ar_laetizia":
        case "cl_laetizia":
        case "mx_laetizia":
        case "se_laetizia":
        case "it_laetizia":
        case "uk_laetizia":
        case "br_laetizia":
        case "fr_laetizia":
        case "pt_laetizia":
        case "tr_laetizia":
        case "nl_laetizia":
        case "ie_laetizia":          
        case "fi_laetizia":
        
            $porteur='LAETIZIA';
            break;
       case "fr_rucker":
       case "de_theodor":
            $porteur='THEODOR';
            break;
       case "no_rinalda":
       case "se_rinalda":
       case "fr_rinalda":
       case "uk_rinalda":
       case "nl_rinalda":
       case "dk_rinalda":
       case "it_rinalda":
       case "de_rinalda":
       case "au_rinalda":
       case "br_rinalda":
       case "ca_rinalda":
       case "pt_rinalda":
            $porteur='RINALDA';
            break;
       case "uk_alisha":
       case "in_alisha":
       case "au_alisha":
       case "sg_alisha":
       case "ca_alisha":
       case "sf_alisha":
       case "nz_alisha":
     
            $porteur='ALISHA';
            break;
      case "it_ml":
      case "se_rmay":
      case "fr_rmay":
      case "es_rmay":
      case "mx_rmay":
      case "tr_rmay":
      case "pl_rmay":
      case "dk_rmay":
      case "nl_rmay":
      case "br_rmay":
      case "pt_rmay":
      case "no_ml":
      case "de_rmay":
            $porteur='MONALUISA';
            break;
    case "uk_aasha":
    case "ca_aasha":
    case "in_aasha":
    case "au_aasha":
    case "sg_aasha":
            $porteur='AASHA';
            break;
    case "fr_myriana":
            $porteur='MYRIANA';
            break;
    case "fr_ivana":
            $porteur='EVANA';
            break;
    default:
    $porteur='none';
           break;
    }
	return $porteur;
	 }

}

?>