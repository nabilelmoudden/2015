<?php
namespace Business;

class Macro{
	
	public function getUrlMacro($file_name, $porteur, $fid_name, $fid_product, $fid_type, $fid_asile_inter, $fid_gp, $fid_s, $fid_de, $fid_sd, $fid_dest, $fid_bs){
		$url = '';
		$file_name = strtolower($file_name);
		$fid_name = strtolower($fid_name);
		//Nom de domaine
		// print_r($GLOBALS['porteurUrl']);
		
		// exit;
		$url = $GLOBALS['porteurUrl'][$porteur]['url'].'/';
		//
		//V1 ou V2
		$url .= ($fid_type == 'oui') ? 'voyances/index.php/site/index?' : $GLOBALS['porteurUrl'][$porteur]['folder'].'/index.php?';
		
		//Ref product: on le récupère du formulaire, V1 & V2
		$ct =  ($fid_product == 'product_1') ? "" : "ct"; 
		$name =  explode('.', $file_name);
		//Ref et C
		if ($fid_type == 'oui')  {
			$url .= 'ref='.(($fid_asile_inter =='asile') ? 'asil': $fid_name);
			// $url .= 'ref='.$fid_name;
		} else{


			if ($fid_asile_inter =='asile' && preg_match("@lmas@", $name[0])) {
				$url .= 'c='.$fid_name.$ct."as";
			}
			if (preg_match("@lmr@", $name[0]) || preg_match("@lmldv@", $name[0])) {
				$url .= 'c='.$fid_name.$ct."ldv";
			} elseif(preg_match("@lmap@", $name[0]) || preg_match("@lmarch@", $name[0]) || preg_match("@lmardn@", $name[0]) ) {
				$url .= 'c='.$fid_name.$ct."ap";//a voir si fidap ou fidctap

			}elseif (preg_match('@lml@', $name[0]) && !preg_match('@lmldv@', $name[0]))  {
				$url .= 'c='.$fid_name.$ct."pro";
			}elseif (preg_match('@pai@', $name[0]))  {
				$url .= 'c='.$fid_name.$ct."pro";
			}
			// $url .= 'c='.$fid_name.$ct;
		// }

		}
		// $url .= ($fid_type == 'oui') ?  'ref='.$fid_name : 'c='.$fid_name;
		
		//Bs: dans V1 & V2
			if ($fid_bs == '1') {
				$url .='&bs=1';
			}else{		
				$url .='&bs=[EMV FIELD]EMVADMIN'.$fid_bs.'[EMV /FIELD]';
			}
		
		//TR: V1 & V2
		$typeFile = str_replace(($fid_product == 'product_1') ? $fid_name : $fid_name.'ct','', $file_name);
		$typeFile = explode('.', $file_name);
		$typeFile = $typeFile[0];
		// die($typeFile);
		if($fid_type == 'oui'){
			if(preg_match('@lmap@', $typeFile) || preg_match('@lmarch@', $typeFile)){
				$url .= '&tr=1&ap=1';
			}
			elseif(preg_match('@lmar@', $typeFile)){
				$url .= '&tr=1';
			}
			elseif(preg_match('@lml@', $typeFile) && !preg_match('@lmldv@', $typeFile) && !preg_match('@lml2@', $typeFile) && !preg_match('@lml3@', $typeFile) && !preg_match('@lml4@', $typeFile)){
				$url .= '&tr=111';
			}
			elseif(preg_match('@lml2@', $typeFile) && !preg_match('@lmldv@', $typeFile)){
				$url .= '&tr=112';
			}
			elseif(preg_match('@lml3@', $typeFile) && !preg_match('@lmldv@', $typeFile)){
				$url .= '&tr=113';
			}
			elseif(preg_match('@lml4@', $typeFile) && !preg_match('@lmldv@', $typeFile)){
				$url .= '&tr=114';
			}
			elseif(preg_match('@lmr1@', $typeFile)){
				$url .= '&tr=2';
			}
			elseif(preg_match('@lmr2@', $typeFile)){
				$url .= '&tr=3';
			}
			elseif(preg_match('@lmr3@', $typeFile)){
				$url .= '&tr=4';
			}
			elseif(preg_match('@lmr4@', $typeFile)){
				$url .= '&tr=5';
			}
			elseif(preg_match('@lmr5@', $typeFile)){
				$url .= '&tr=6';
			}
			elseif(preg_match('@lmr6@', $typeFile)){
				$url .= '&tr=7';
			}
			elseif(preg_match('@lmr7@', $typeFile)){
				$url .= '&tr=8';
			}
			elseif(preg_match('@lmr8@', $typeFile)){
				$url .= '&tr=9';
			}
			elseif(preg_match('@lmr10@', $typeFile)){
				$url .= '&tr=11';
			}
			elseif(preg_match('@lmr11@', $typeFile)){
				$url .= '&tr=12';
			}
			elseif(preg_match('@lmr12@', $typeFile)){
				$url .= '&tr=13';
			}
			elseif(preg_match('@lmr13@', $typeFile)){
				$url .= '&tr=14';
			}
			elseif(preg_match('@lmr14@', $typeFile)){
				$url .= '&tr=15';
			}
			elseif(preg_match('@lmr15@', $typeFile)){
				$url .= '&tr=16';
			}
			elseif(preg_match('@lmr16@', $typeFile)){
				$url .= '&tr=17';
			}
			elseif(preg_match('@lmr17@', $typeFile)){
				$url .= '&tr=18';
			}
			elseif(preg_match('@lmr18@', $typeFile)){
				$url .= '&tr=19';
			}
			elseif(preg_match('@lmr19@', $typeFile)){
				$url .= '&tr=20';
			}
			elseif(preg_match('@lmr20@', $typeFile)){
				$url .= '&tr=21';
			}
			elseif(preg_match('@lmldv@', $typeFile)){
				$url .= '&tr=1';
			} else {
				$url .= '&tr=1';
			}

		}else{

			if(preg_match('@lml@', $typeFile) && !preg_match('@lmldv@', $typeFile)){
				$url .= '&tr=1'; //or tr=111
			}elseif(preg_match('@lmr1@', $typeFile)){
				$url .= '&tr=2';
			}
			elseif(preg_match('@lmr2@', $typeFile)){
				$url .= '&tr=3';
			}
			elseif(preg_match('@lmr3@', $typeFile)){
				$url .= '&tr=4';
			}
			elseif(preg_match('@lmr4@', $typeFile)){
				$url .= '&tr=5';
			}
			elseif(preg_match('@lmr5@', $typeFile)){
				$url .= '&tr=6';
			}
			elseif(preg_match('@lmr6@', $typeFile)){
				$url .= '&tr=7';
			}
			elseif(preg_match('@lmr7@', $typeFile)){
				$url .= '&tr=8';
			}
			elseif(preg_match('@lmr8@', $typeFile)){
				$url .= '&tr=9';
			}
			elseif(preg_match('@lmr10@', $typeFile)){
				$url .= '&tr=11';
			}
			elseif(preg_match('@lmr11@', $typeFile)){
				$url .= '&tr=12';
			}
			elseif(preg_match('@lmr12@', $typeFile)){
				$url .= '&tr=13';
			}
			elseif(preg_match('@lmr13@', $typeFile)){
				$url .= '&tr=14';
			}
			elseif(preg_match('@lmr14@', $typeFile)){
				$url .= '&tr=15';
			}
			elseif(preg_match('@lmr15@', $typeFile)){
				$url .= '&tr=16';
			}
			elseif(preg_match('@lmr16@', $typeFile)){
				$url .= '&tr=17';
			}
			elseif(preg_match('@lmr17@', $typeFile)){
				$url .= '&tr=18';
			}
			elseif(preg_match('@lmr18@', $typeFile)){
				$url .= '&tr=19';
			}
			elseif(preg_match('@lmr19@', $typeFile)){
				$url .= '&tr=20';
			}
			elseif(preg_match('@lmr20@', $typeFile)){
				$url .= '&tr=21';
			}
			else {
				$url .= '&tr=1';
			}

		}
		
		//SP : Que pour V2
//		if($fid_type == 'oui') $url .= ($fid_product == 'product_1') ? '&sp=1' : '&sp=2';
		//SP : Que pour V2
//		if($fid_type == 'oui') $url .= ($fid_product == 'product_1') ? '&sp=1' : '&sp=2';

		if($fid_type == 'oui'){
			if($fid_product == 'product_1'){
				if(preg_match('@asm@', $typeFile)) $url .= '&sp=1';
				elseif(preg_match('@as2@', $typeFile)) $url .= '&sp=2';
				elseif(preg_match('@as3@', $typeFile)) $url .= '&sp=3';
				elseif(preg_match('@as4@', $typeFile)) $url .= '&sp=4';
				else $url.='&sp=1';

			}elseif($fid_product == 'product_2'){
				if(preg_match('@asm@', $typeFile)) $url .= '&sp=1';
				elseif(preg_match('@as2@', $typeFile)) $url .= '&sp=2';
				elseif(preg_match('@as3@', $typeFile)) $url .= '&sp=3';
				elseif(preg_match('@as4@', $typeFile)) $url .= '&sp=4';
				else $url.='&sp=2';
			}

		}


		//GP: V1 & V2
		$url .= '&gp=[EMV FIELD]EMVADMIN'.$fid_gp.'[EMV /FIELD]';
		
		//p : V1 & V2
		$url .= '&p=[EMV FIELD]FIRSTNAME[EMV /FIELD]';
		
		//n : V1 & V2
		$url .= '&n=[EMV FIELD]LASTNAME[EMV /FIELD]';
		
		//d : V1 & V2
		$dateofbirth= ($porteur == 'in_laetizia' || $porteur =='en_aasha' || $porteur =='en_aasha_acq' || $porteur =='ie_laetizia'  || $porteur =='nl_laetizia')? '&d=[EMV FIELD]DATEOFBIRTH[EMV /FIELD]':'&d=[EMV FIELD]DATEOFBIRTH,dd/MM/yyyy,fr[EMV /FIELD]';
		$url .= $dateofbirth;
		//m: V1 & V2
		$url .= '&m=[EMV FIELD]EMAIL[EMV /FIELD]';
		
		// x : V1 & V2
		$url .= '&x=[EMV FIELD]TITLE[EMV /FIELD]';
		
		//de : V1 & V2
		// $url .= '&de=[EMV FIELD]EMVADMIN'.$fid_de.'[EMV /FIELD]';
		if ($fid_product =='product_1') {
			$url .= '&de=[EMV FIELD]EMVADMIN'.$fid_de.'[EMV /FIELD]';
		}else{
			if ((preg_match('@lml@', $name[0]) && !preg_match('@lmldv@', $name[0])) || (preg_match('@pai@', $name[0]))) {
				if(isset($fid_dest) && !empty($fid_dest) && ($fid_dest > 0)){
					$url .= '&de=[EMV FIELD]EMVADMIN'.$fid_dest.'[EMV /FIELD]';
				}else{
					$url .= '&de=[EMV FIELD]EMVADMIN'.$fid_de.'[EMV /FIELD]';
				}
			}else{
				$url .= '&de=[EMV FIELD]EMVADMIN'.$fid_de.'[EMV /FIELD]';
			}
		}

		
		//site : pour porteur multi site
		if(($fid_type == 'oui') && ($porteur == 'es_laetizia' || $porteur == 'fr_rinalda'  || $porteur == 'en_alisha' || $porteur == 'en_aasha' || $porteur =='en_aasha_acq' || $porteur =='en_aasha_acq' || $porteur =='pt_rmay' || $porteur =='br_rmay' || $porteur =='es_rmay' || $porteur =='mx_rmay' || $porteur =='cl_rmay' || $porteur =='ar_rmay' || $porteur =='in_laetizia' || $porteur =='ie_laetizia' || $porteur =='pt_laetizia' || $porteur =='br_laetizia' || $porteur =='br_rinalda' || $porteur =='pt_rinalda' || $porteur =='ar_laetizia' || $porteur =='cl_laetizia' || $porteur =='fr_laetizia')){
		 $url .= '&site=[EMV FIELD]SITE[EMV /FIELD]';
		}
		
		//s :  V1 & V2
		$url .= '&s=[EMV FIELD]EMVADMIN'.$fid_s.'[EMV /FIELD]';
		
		//sd : V1 & V2 si le champs est rempli
		if (isset($fid_sd) && $fid_sd !== "") {
			$url .= '&sd=[EMV FIELD]EMVADMIN'.$fid_sd.'[EMV /FIELD]';
		}
		return $url;
	}
	public function islmar($file_name){
		$islmar = '0';
				$name =  explode('.', $file_name);
				if (preg_match('@lmar@', $name[0]) && !preg_match('@lmard@', $name[0]) && !preg_match('@lmarch@', $name[0])) {
					$islmar = '1';
				}
		return $islmar;

	}

	public function getUrlFAQ($porteur, $fid_name, $fid_product, $fid_type, $fid_asile_inter,$file_name){
		//le site n'est pas le meme pour ruckf pour le faq et pour le site 
		$site = $GLOBALS['porteurUrl'][$porteur]['url'];
		
		//la variable des nombres des asiles
		$asNum =NULL;
		//la variable des nombres des asiles
		$ref =NULL;

		//page c pagina pour es_rmay
		$page = ($porteur == "es_rmay" || $porteur == "pt_rmay"  || $porteur == "br_rmay"  || $porteur == "mx_rmay" || $porteur == "cl_rmay" || $porteur == "ar_rmay" || $porteur == "it_rinalda"  || $porteur == "pt_laetizia" ) ? "pagina" : "page";

		//site c ashasite pour en_asha
		$siteTxt = ($porteur == 'en_aasha' || $porteur == 'en_aasha_acq') ? "asha_site" : "site";
		$siteTxt = ($porteur == 'no_ml' || $porteur == 'it_ml'  || $porteur == 'dk_rmay' ) ? 'SiteInst' : $siteTxt;


		$ifsite = ($porteur == 'es_laetizia' || $porteur == 'en_alisha' || $porteur == 'en_aasha' || $porteur =='en_aasha_acq' || $porteur =='en_aasha_acq' || $porteur =='pt_rmay' || $porteur =='br_rmay' || $porteur =='es_rmay' || $porteur =='mx_rmay' || $porteur =='cl_rmay' || $porteur =='ar_rmay' || $porteur =='in_laetizia' || $porteur =='ie_laetizia' || $porteur =='pt_laetizia' || $porteur =='br_laetizia' || $porteur =='br_rinalda' || $porteur =='pt_rinalda') ? "&site=[EMV FIELD]SITE[EMV /FIELD]" : "";
		$faq = ($porteur == 'it_rinalda') ? 'FAQ':'faq';
		
		if($porteur =='dk_rmay'){
			$url  =	$site.'/voyances/index.php/SiteClient/FAQ?';

		}else{

        $url  = ($porteur =='no_ml'|| $porteur =='it_ml'|| $porteur =='se_rmay' || $porteur =='fi_lae') ? ($site.'/voyances/index.php/SiteInst/FAQ?') : ($site.'/'.$GLOBALS['porteurUrl'][$porteur]['folder'].'/'.$siteTxt.'/index.php?'.$page.'='.$faq.'.php&');
			
		}

		//site avec url siteinst
		$url .= 'm=[EMV FIELD]EMAIL[EMV /FIELD]'.$ifsite.'&ref=';
		
		if($porteur == "fr_rinalda") $porteur = 'rin';
		if($porteur == "fr_laetizia") $porteur = 'lae';
		if($porteur == "de_theodor") $porteur = 'de_ruc';
		if($porteur == "nl_rmay") $porteur = 'nl_rmay';
		if($porteur == "de_rmay") $porteur = 'de_rmay';
		if($porteur == "es_laetizia") $porteur = 'es_lae';
		if($porteur == "en_alisha") $porteur = 'en_alisha';
		if($porteur == "nl_laetizia") $porteur = 'nl_lae';
		if($porteur == "fr_laetizia") $porteur = 'fr_lae';
		if($porteur == "ru_laetizia") $porteur = 'ru_lae';
		if($porteur == "gr_laetizia") $porteur = 'gr_lae';
		if($porteur == "se_laetizia") $porteur = 'se_lae';
		if($porteur == "br_laetizia") $porteur = 'br_lae';
		if($porteur == "pt_laetizia") $porteur = 'pt_lae';
		if($porteur == "no_laetizia") $porteur = 'no_lae';
		if($porteur == "it_laetizia") $porteur = 'it_lae';
		if($porteur == "ie_laetizia") $porteur = 'ie_lae';
		if($porteur == "in_laetizia") $porteur = 'in_lae';
		if($porteur == "es_laetizia") $porteur = 'es_lae';
		if($porteur == "dk_laetizia") $porteur = 'dk_lae';
		if($porteur == "de_laetizia") $porteur = 'de_lae';
		if($porteur == "nl_rinalda") $porteur = 'nl_rin';
		if($porteur == "ca_rinalda") $porteur = 'ca_rin';
		if($porteur == "it_rinalda") $porteur = 'it_rin';
		if($porteur == "uk_rinalda") $porteur = 'uk_rin';
		if($porteur == "no_rinalda") $porteur = 'no_rin';
		if($porteur == "dk_rinalda") $porteur = 'dk_rin';
		if($porteur == "se_rinalda") $porteur = 'se_rin';
		if($porteur == "de_rinalda") $porteur = 'de_rin';
		if($porteur == "br_rinalda") $porteur = 'br_rin';
		if($porteur == "pt_rinalda") $porteur = 'pt_rin';
		if($porteur == "fr_rucker_acq") $porteur = 'fr_rucker';
		if($porteur == "fi_laetizia") $porteur = 'fi_lae';
		//Le numero d'asile
		//echo ($fid_asile_inter);
		if($fid_asile_inter =='asile'){
			$fid_name ='asil';
			if(preg_match('@as@', $file_name) && !preg_match('@as2@', $file_name) && !preg_match('@as3@', $file_name) && !preg_match('@as4@', $file_name)) $asNum  =  1;
			elseif(preg_match('@as2@', $file_name)) $asNum  =  2;
			elseif(preg_match('@as3@', $file_name)) $asNum  =  3;
			elseif(preg_match('@as4@', $file_name)) $asNum  =  4;	

			//die('<hr>'.$fid_name);
		}



		
		if ($fid_asile_inter=='asile') $ref = $asNum;
		else $ref = ($fid_product == 'product_1') ?  '1' : '2' ;
		// echo "<hr>";
		// echo 'la reference :'.$ref;
		// echo "<hr>";
		//$url .= ($fid_type == 'oui') ? $fid_name.'_'.(($fid_asile_inter = 'asile') ? $asNum : ($fid_product == 'product_1') ?  '1' : '2' ) : $porteur.'_'.$fid_name.(($fid_product == 'product_1') ?  '' : 'ct' );
		$url.= ($fid_type == 'oui') ? $fid_name.'_'.$ref : $porteur.'_'.$fid_name.(($fid_product == 'product_1') ?  '' : 'ct' );
		return $url;
	}
}

