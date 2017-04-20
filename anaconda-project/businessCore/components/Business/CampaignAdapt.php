<?php
namespace Business;
/**
 * This is the model class for table "campaign". 
 *
 * The followings are the available columns in table 'campaign':
 * @property integer $id
 * @property string $label
 * @property integer $type
 * @property string $ref
 *
 * The followings are the available model relations:
 * @property Subcampaign[] $SubCampaign
 *
 * @package Models.Campaign
 */
class CampaignAdapt extends \CampaignAdapt
{
	/**
	* Project Status
	*/
	const PROJECT_IN_PROGRESS		= 0;
	const PROJECT_CREATE    		= 1;
	const PROJECT_CONTROL			= 2;
	const PROJECT_DEVELOP			= 3;
	const PROJECT_CONTROL_FINAL		= 4;
	const PROJECT_VALID_FINAL		= 5;

	
	
	    
		
	
	
	public $adminMails = array(
	    'fr_laetizia'   => array('sofia.faid.ki@gmail.com','loubna.faiq.ki@gmail.com', 'soumaya.radoine.ki@gmail.com'),		
	    'fr_rinalda'	=> array('sofia.faid.ki@gmail.com','safae.benaissa.ki@gmail.com', 'soumaya.radoine.ki@gmail.com'),		
		'fr_rmay'		=> array('ikram.dhoum.ki@gmail.com','widad.kettab.ki@gmail.com', 'saad.echcharraq.ki@gmail.com'),
		'fr_rucker'		=> array('ikram.dhoum.ki@gmail.com','widad.kettab.ki@gmail.com', 'saad.echcharraq.ki@gmail.com'),
		'fr_althea'		=> array('ikram.dhoum.ki@gmail.com','khadija.elboukhari.ki@gmail.com', 'soumaya.radoine.ki@gmail.com'),
		
		'fr_evamaria'	=> array('ouakani.mohammed@gmail.com','i.chaanoun.ki@gmail.com', 'saad.echcharraq.ki@gmail.com'), //KO Manque compte Qualt
		'fr_myriana'	=> array('saloua.khalis.ki@gmail.com','mhadachi.ki@gmail.com', 'saad.echcharraq.ki@gmail.com'), //KO Manque compte Qualt
		
		'ca_rinalda'    => array('sofia.faid.ki@gmail.com','i.chaanoun.ki@gmail.com', 'oualid.ibahimi.ki@gmail.com'),
		'au_en_rinalda' => array('sofia.faid.ki@gmail.com','i.chaanoun.ki@gmail.com', 'oualid.ibahimi.ki@gmail.com'),		
		'br_rucker'		=> array('ouakani.mohammed@gmail.com','khadija.elboukhari.ki@gmail.com', 'ikbal.moulnakhla.ki@gmail.com'), //KO Manque compte Mrk et Qualt
		'br_laetizia'	=> array('ouakani.mohammed.ki@gmail.com','khadija.elboukhari.ki@gmail.com', 'naoufal.amadir.ki@gmail.com'),
		'br_rmay'		=> array('ouakani.mohammed.ki@gmail.com','rabab.rahib.ki@gmail.com', 'ali.baddane.ki@gmail.com'),		
		'de_rmay'		=> array('ouakani.mohammed.ki@gmail.com','khadija.elboukhari.ki@gmail.com', 'younes.ahyoud.ki@gmail.com'),
		
		'de_theodor'	=> array('ouakani.mohammed.ki@gmail.com','khadija.elboukhari.ki@gmail.com', 'younes.ahyoud.ki@gmail.com'),		
        'de_rinalda'	=> array('ouakani.mohammed.ki@gmail.com','widad.kettab.ki@gmail.com','imane.dehmani.kindy@gmail.com','jalal.bensaad.ki@gmail.com','samia.daouiji.ki@gmail.com','ikbal.moulnakhla.ki@gmail.com'),
		'de_laetizia'	=> array('ouakani.mohammed.ki@gmail.com','widad.kettab.ki@gmail.com', 'younes.ahyoud.ki@gmail.com'),
		'de_althea'		=> array('ouakani.mohammed.ki@gmail.com','khadija.elboukhari.ki@gmail.com', 'ikbal.moulnakhla.ki@gmail.com'), //KO Manque compte Mrk
		
		'en_alisha'		=> array('saloua.khalis.ki@gmail.com','mhadachi.ki@gmail.com', 'rajae.hajli.ki@gmail.com'),
		'en_aasha'		=> array('ikram.dhoum.ki@gmail.com','loubna.faiq.ki@gmail.com', 'ayoub.elarar.ki@gmail.com'),
		'in_laetizia'	=> array('saloua.khalis.ki@gmail.com','oumayma.ibenbrahim.ki@gmail.com', 'selmi.younes.ki@gmail.com'), //KO Manque compte Mrk et Qualt
		
		'es_laetizia'	=> array('ikram.dhoum.ki@gmail.com','oumayma.ibenbrahim.ki@gmail.com', 'aymane.rochd.ki@gmail.com'),	
		'es_rmay'		=> array('saloua.khalis.ki@gmail.com','anas.betari.ki@gmail.com', 'aymane.rochd.ki@gmail.com'),
		
		'nl_laetizia'	=> array('sofia.faid.ki@gmail.com','houda.sail1@gmail.com', 'anass.chehal.ki@gmail.com'),
		'nl_rmay'  		=> array('ikram.dhoum.ki@gmail.com','i.chaanoun.ki@gmail.com', 'anass.chehal.ki@gmail.com'),
		'nl_rinalda'	=> array('sofia.faid.ki@gmail.com','houda.sail1@gmail.com', 'anass.chehal.ki@gmail.com'),
		
		'pt_laetizia'	=> array('ouakani.mohammed.ki@gmail.com','nawfal.serrar.ki@gmail.com', 'essmami.elhoussine.ki@gmail.com'),
		'pt_rmay'		=> array('ikram.dhoum.ki@gmail.com','maloul.oussama@gmail.com','ali.baddane.ki@gmail.com'), // KO Manque IT
		'pt_rinalda'	=> array('sofia.faid.ki@gmail.com','nawfal.serrar.ki@gmail.com', 'essmami.elhoussine.ki@gmail.com'),
		
		'pl_rmay'		=> array('ouakani.mohammed.ki@gmail.com','khadija.elboukhari.ki@gmail.com', 'selmi.younes.ki@gmail.com'),		
		'gr_laetizia'	=> array('ouakani.mohammed.ki@gmail.com','khadija.elboukhari.ki@gmail.com', 'ikbal.moulnakhla.ki@gmail.com'), //KO Manque compte Mrk
		
		'it_laetizia'	=> array('saloua.khalis.ki@gmail.com','anas.betari.ki@gmail.com', 'meryem.elachqar.ki@gmail.com'),
		'it_rinalda'	=> array('saloua.khalis.ki@gmail.com','anas.betari.ki@gmail.com', 'meryem.elachqar.ki@gmail.com'),
		'it_ml'		    => array('saloua.khalis.ki@gmail.com','anas.betari.ki@gmail.com', 'selmi.younes.ki@gmail.com'),
		
		'dk_laetizia'	=> array('saloua.khalis.ki@gmail.com','mhadachi.ki@gmail.com', 'imad.fartas.ki@gmail.com'),
		'dk_rinalda'    => array('saloua.khalis.ki@gmail.com','mhadachi.ki@gmail.com', 'imad.fartas.ki@gmail.com'),
		'dk_rmay'		=> array('ikram.dhoum.ki@gmail.com','oumayma.ibenbrahim.ki@gmail.com', 'selmi.younes.ki@gmail.com'),
		
		'se_rmay'       => array('ikram.dhoum.ki@gmail.com','widad.kettab.ki@gmail.com', 'ayoub.zirari.ki@gmail.com'),
		'se_laetizia'   => array('sofia.faid.ki@gmail.com','i.chaanoun.ki@gmail.com', 'ayoub.zirari.ki@gmail.com'),
		'se_rinalda'    => array('sofia.faid.ki@gmail.com','i.chaanoun.ki@gmail.com', 'ayoub.zirari.ki@gmail.com'),
		'se_althea'     => array('sofia.faid.ki@gmail.com','i.chaanoun.ki@gmail.com', 'selmi.younes.ki@gmail.com'), //KO Manque compte Mrk
		
		'no_laetizia'   => array('ouakani.mohammed.ki@gmail.com','jalal.bensaad.ki@gmail.com', 'ali.baddane.ki@gmail.com'),
		'no_rinalda'    => array('ouakani.mohammed.ki@gmail.com','jalal.bensaad.ki@gmail.com', 'ali.baddane.ki@gmail.com'),
		'no_ml'         => array('saloua.khalis.ki@gmail.com','salaheddine.bouidir.ki@gmail.com', 'achraf.haddouch.ki@gmail.com'),
		
		
		'tr_laetizia'	=> array('ouakani.mohammed.ki@gmail.com','salaheddine.bouidir.ki@gmail.com','salaheddine.bouidir.ki@gmail.com'), //KO Manque compte Mrk
		'tr_rmay'		=> array('ouakani.mohammed.ki@gmail.com','salaheddine.bouidir.ki@gmail.com','hasnae.taleb.ki@gmail.com'),
		
		'uk_rinalda'    => array('sofia.faid.ki@gmail.com','mhadachi.ki@gmail.com', 'ikbal.moulnakhla.ki@gmail.com'), //KO Manque compte Mrk
		'ie_ml'         => array('sofia.faid.ki@gmail.com','i.chaanoun.ki@gmail.com', 'ikbal.moulnakhla.ki@gmail.com'), //KO Manque compte Mrk
		
		'fi_laetizia'   => array('saloua.khalis.ki@gmail.com','widad.kettab.ki@gmail.com', 'khawla.haloui.ki@gmail.com'), //KO Manque compte Mrk
		'ru_laetizia'   => array('sofia.faid.ki@gmail.com','anas.betari.ki@gmail.com', 'selmi.younes.ki@gmail.com'), //KO Manque compte Mrk
	);
	
	static public function loadByCampaignAndSite( $idCDCAdapt, $site )
	{
		return self::model()->findByAttributes( array( 'idCDCAdapt' => $idCDCAdapt, 'site' => $site ) );
	}
	
	static public function loadByCDC( $idCDCAdapt )
	{
		return self::model()->findAllByAttributes( array( 'idCDCAdapt' => $idCDCAdapt ) );
	}

	
	public function search( $camp = 0 )
	{
		$Provider = parent::search();

		

		return $Provider;
	}
	
	public function SuiviPlanification($id = false){

		if($id){
			$campaign	= \Business\CampaignAdapt::load($id);
			$cmps       = $campaign->search();
		} else {
			$campaign	= new \Business\CampaignAdapt();
			$cmps       = $campaign->search();
		}

		if(!empty($cmps->data)){

				$tab = '';
				
				foreach($cmps->data as $cmp)
				{
					
					$date_creation_cdc_prev = $cmp->date_creation_cdc_prev;

					$date_prev	 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_creation_cdc_prev );
					$date_livrer = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_creation_cdc );

					$date_prev1	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_cdc_prev );
					$date_livrer1 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_cdc );

					$date_prev2	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_dev_it_prev );
					$date_livrer2 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_dev_it );

					$date_prev3	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_project_prev );
					$date_livrer3 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_project );

					$date_prev4	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_valid_project_prev );
					$date_livrer4 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_valid_project );

					$prev_color = $prev_color_step1 = $prev_color_step2 = $prev_color_step3 = $prev_color_step4 = '&Hd9d9d9';

					$msg_step0  = '&nbsp;&nbsp;Prévu le&nbsp; <b>'.$date_prev->format("d/m/Y H:i").'</b></b>';
					$msg_step1  = '&nbsp;&nbsp;Prévu le&nbsp; <b>'.$date_prev1->format("d/m/Y H:i").'</b></b>';
					$msg_step2  = '&nbsp;&nbsp;Prévu le&nbsp; <b>'.$date_prev2->format("d/m/Y H:i").'</b></b>';
					$msg_step3  = '&nbsp;&nbsp;Prévu le&nbsp; <b>'.$date_prev3->format("d/m/Y H:i").'</b></b>';
					$msg_step4  = '&nbsp;&nbsp;Prévu le&nbsp; <b>'.$date_prev4->format("d/m/Y H:i").'</b></b>';

					if ($cmp->projectStatus == self::PROJECT_CREATE || $cmp->projectStatus == self::PROJECT_CONTROL || $cmp->projectStatus == self::PROJECT_DEVELOP || $cmp->projectStatus == self::PROJECT_CONTROL_FINAL|| $cmp->projectStatus == self::PROJECT_VALID_FINAL)
					{
							if($date_prev->format("d/m/y")== $date_livrer->format("d/m/y")){
							   $prev_color = '#92d050';
							   $msg_step0  = '&nbsp;&nbsp;Reçu le <b>'.$date_livrer->format("d/m/Y").'<b>&nbsp;&nbsp;';
							}else{
								if($date_prev->format("Y-m-d") > $date_livrer->format("Y-m-d"))
							         $prev_color = '#ffff00';
								else
									 $prev_color = '#ff0000';

								$msg_step0  = '&nbsp;&nbsp;Reçu le <b>'.$date_livrer->format("d/m/Y H:i").'</b> au lieu de <b>'.$date_prev->format("d/m/Y H:i").'</b>&nbsp;&nbsp;';
							}
							$prev_color_step1 = $prev_color_step2 = $prev_color_step3 = $prev_color_step4 = '#d9d9d9';

							if($date_prev1->format("d/m/y") == $date_livrer1->format("d/m/y")){

							   $prev_color_step1 = '#92d050';
							   $msg_step1  = '&nbsp;&nbsp;Reçu le <b>'.$date_livrer1->format("d/m/Y").'<b>&nbsp;&nbsp;';

							}else{
								if( $cmp->date_control_cdc != '0000-00-00 00:00:00')
								{
									if($date_prev1->format("Y-m-d") > $date_livrer1->format("Y-m-d"))
										 $prev_color_step1 = '#ffff00';
									else
										 $prev_color_step1 = '#ff0000';

									$msg_step1  = '&nbsp;&nbsp;Reçu le <b>'.$date_livrer1->format("d/m/Y H:i").'</b> au lieu de <b>'.$date_prev1->format("d/m/Y H:i").'</b>&nbsp;&nbsp;';
								}
							}

							if($date_prev2->format("d/m/y") == $date_livrer2->format("d/m/y")){

							   $prev_color_step2 = '#92d050';
							   $msg_step2  = '&nbsp;&nbsp;Reçu le <b>'.$date_livrer2->format("d/m/Y").'<b>&nbsp;&nbsp;';

							}else{
								if( $cmp->date_dev_it != '0000-00-00 00:00:00')
								{
									if($date_prev2->format("Y-m-d") > $date_livrer2->format("Y-m-d"))
										 $prev_color_step2 = '#ffff00';
									else
										 $prev_color_step2 = '#ff0000';

									$msg_step2  = '&nbsp;&nbsp;Reçu le <b>'.$date_livrer2->format("d/m/Y H:i").'</b> au lieu de <b>'.$date_prev2->format("d/m/Y H:i").'</b>&nbsp;&nbsp;';
								}
							}

							if($date_prev3->format("d/m/y") == $date_livrer3->format("d/m/y")){

							   $prev_color_step3 = '#92d050';
							   $msg_step3  = '&nbsp;&nbsp;Reçu le <b>'.$date_livrer3->format("d/m/Y").'<b>&nbsp;&nbsp;';

							}else{
								if( $cmp->date_control_project != '0000-00-00 00:00:00')
								{
									if($date_prev3->format("Y-m-d") > $date_livrer3->format("Y-m-d"))
										 $prev_color_step3 = '#ffff00';
									else
										 $prev_color_step3 = '#ff0000';

									$msg_step3  = '&nbsp;&nbsp;Reçu le <b>'.$date_livrer3->format("d/m/Y H:i").'</b> au lieu de <b>'.$date_prev3->format("d/m/Y H:i").'</b>&nbsp;&nbsp;';
								}
							}


							if($date_prev4->format("d/m/y") == $date_livrer4->format("d/m/y")){

							   $prev_color_step4 = '#92d050';
							   $msg_step4  = '&nbsp;&nbsp;Reçu le <b>'.$date_livrer4->format("d/m/Y").'<b>&nbsp;&nbsp;';

							}else{
								if( $cmp->date_valid_project != '0000-00-00 00:00:00')
								{
									if($date_prev4->format("Y-m-d") > $date_livrer4->format("Y-m-d"))
										 $prev_color_step4 = '#ffff00';
									else
										 $prev_color_step4 = '#ff0000';

									$msg_step4  = '&nbsp;&nbsp;Reçu le <b>'.$date_livrer4->format("d/m/Y H:i").'</b> au lieu de <b>'.$date_prev4->format("d/m/Y H:i").'</b>&nbsp;&nbsp;';
								}
							}
					}




					
					if($date_creation_cdc_prev>'2016-07-15'){
						$lang_pourteur	= strtoupper(substr(\Yii::app()->session['porteur'], 0, 2));
						$camps	= \Business\CDCAdapt::load($cmp->idCDCAdapt);

						$tab .='<tr><td valign="middle" style="font-weight:bold;">'.$cmp->site.'</td>
									<td>&nbsp;'.$cmp->num.'&nbsp;</td>
									<td>&nbsp;'.$camps->label.'&nbsp;</td>
									<td>&nbsp;'.$cmp->date_shoot.'&nbsp;</td>
									<td style="background-color:'.$prev_color.'">&nbsp;'.$msg_step0.'&nbsp;</td>
									<td style="background-color:'.$prev_color_step1.'">&nbsp;'.$msg_step1.'&nbsp;</td>
									<td style="background-color:'.$prev_color_step2.'">&nbsp;'.$msg_step2.'&nbsp;</td>
									<td style="background-color:'.$prev_color_step3.'">&nbsp;'.$msg_step3.'&nbsp;</td>
									<td style="background-color:'.$prev_color_step4.'">&nbsp;'.$msg_step4.'&nbsp;</td>
									<td style="background-color:#ffffcc;color:red;">&nbsp;&nbsp;'.$cmp->commentaire_palanification.'&nbsp;&nbsp;</td></tr>';

					} else {
						//
					}
				}

		}

		$ExcelTab = "<!DOCTYPE html>
							<html lang='fr'>
							   <head>
							      <meta charset='UTF-8'>
							   </head>
							   <body>
							      <div style='padding-left: 20px; vertical-align: middle;'>
								       <table width='100%'  valign='middle' align='center'>
									    	<tr valign='middle'>
												<td></td>
												<td><br><br><h2><b><u>".ucfirst( strtolower( \Yii::app()->name ) )." :</u></b></h2></b>
													<table border='2' valign='middle'>
														<thead>
															<tr><th>Pays</th><th>N°Fid</th><th>Label</th><th>Date Shoot</th><th>Date réception CDC</th><th>Validation du CDC</th><th>Lancement IT</th><th>Livraison Qualité</th><th>Livraison Marketing</th><th>Commentaire</th></tr>
															".$tab."
														</thead>
													</table>
												</td>
											<tr>
										</table><br><br><br>
										<table>
											<tr valign='middle'></tr>
												<tr valign='middle'><td>&nbsp;</td><td style='height:20px; background-color:#92d050;'></td><td style='height:10px;'>&nbsp;</td><td colspan='2' >Réception dans les délais </td></tr>
												<tr valign='middle'><td>&nbsp;</td><td style='height:20px; background-color:#ffc000;'></td><td style='height:10px;'>&nbsp;</td><td colspan='2' >Réception avec risque potentiel</td></tr>
												<tr valign='middle'><td>&nbsp;</td><td style='height:20px; background-color:#ff0000;'></td><td style='height:10px;'>&nbsp;</td><td colspan='2' >Réception en retard</td></tr>
												<tr valign='middle'><td>&nbsp;</td><td style='height:20px; background-color:#d9d9d9;'></td><td style='height:10px;'>&nbsp;</td><td colspan='2' >Réception prévue</td></tr>
												<tr valign='middle'><td>&nbsp;</td><td style='height:20px; background-color:#ffff00;'></td><td style='height:10px;'>&nbsp;</td><td colspan='2' >Réception avancée</td></tr>
										</table>
									</div>
								</body>
							</html>
						";

		return $ExcelTab;
	}

	public function SuiviPlanification_v2_old($id = false){

		if($id){
			$campaign	= \Business\CampaignAdapt::load($id);
			$cmps       = $campaign->search();
		}else{
			$campaign	= new \Business\CampaignAdapt();
			$cmps       = $campaign->search();
		}
		$tab = array();
		if(!empty($cmps->data)){
				
				foreach($cmps->data as $cmp)
				{
				$date_creation_cdc_prev = $cmp->date_creation_cdc_prev;

					$date_prev	 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_creation_cdc_prev );
					$date_livrer = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_creation_cdc );

					$date_prev1	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_cdc_prev );
					$date_livrer1 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_cdc );

					$date_prev2	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_dev_it_prev );
					$date_livrer2 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_dev_it );

					$date_prev3	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_project_prev );
					$date_livrer3 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_project );

					$date_prev4	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_valid_project_prev );
					$date_livrer4 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_valid_project );


					$prev_color = $prev_color_step1 = $prev_color_step2 = $prev_color_step3 = $prev_color_step4 = 'd9d9d9';

					$msg_step0  = 'Prévu le '.$date_prev->format("d/m/Y H:i");
					$msg_step1  = 'Prévu le '.$date_prev1->format("d/m/Y H:i");
					$msg_step2  = 'Prévu le '.$date_prev2->format("d/m/Y H:i");
					$msg_step3  = 'Prévu le '.$date_prev3->format("d/m/Y H:i");
					$msg_step4  = 'Prévu le '.$date_prev4->format("d/m/Y H:i");

					if ($cmp->projectStatus == self::PROJECT_CREATE || $cmp->projectStatus == self::PROJECT_CONTROL || $cmp->projectStatus == self::PROJECT_DEVELOP || $cmp->projectStatus == self::PROJECT_CONTROL_FINAL|| $cmp->projectStatus == self::PROJECT_VALID_FINAL)
					{
							if($date_prev->format("d/m/y")== $date_livrer->format("d/m/y")){
							   $prev_color = '50d092';
							   $msg_step0  = 'Reçu le '.$date_livrer->format("d/m/Y").'';
							}else{
								if($date_prev->format("Y-m-d") > $date_livrer->format("Y-m-d"))
							         $prev_color = 'ffff00';
								else
									 $prev_color = 'ff0000';

								$msg_step0  = 'Reçu le '.$date_livrer->format("d/m/Y H:i").' au lieu de '.$date_prev->format("d/m/Y H:i");
							}
							$prev_color_step1 = $prev_color_step2 = $prev_color_step3 = $prev_color_step4 = 'd9d9d9';

							if($date_prev1->format("d/m/y") == $date_livrer1->format("d/m/y")){

							   $prev_color_step1 = '50d092';
							   $msg_step1  = 'Reçu le '.$date_livrer1->format("d/m/Y");

							}else{
								if( $cmp->date_control_cdc != '0000-00-00 00:00:00')
								{
									if($date_prev1->format("Y-m-d") > $date_livrer1->format("Y-m-d"))
										 $prev_color_step1 = 'ffff00';
									else
										 $prev_color_step1 = 'ff0000';

									$msg_step1  = 'Reçu le '.$date_livrer1->format("d/m/Y H:i").' au lieu de '.$date_prev1->format("d/m/Y H:i");
								}
							}

							if($date_prev2->format("d/m/y") == $date_livrer2->format("d/m/y")){

							   $prev_color_step2 = '50d092';
							   $msg_step2  = 'Reçu le '.$date_livrer2->format("d/m/Y");

							}else{
								if( $cmp->date_dev_it != '0000-00-00 00:00:00')
								{
									if($date_prev2->format("Y-m-d") > $date_livrer2->format("Y-m-d"))
										 $prev_color_step2 = 'ffff00';
									else
										 $prev_color_step2 = 'ff0000';

									$msg_step2  = 'Reçu le '.$date_livrer2->format("d/m/Y H:i").' au lieu de '.$date_prev2->format("d/m/Y H:i");
								}
							}

							if($date_prev3->format("d/m/y") == $date_livrer3->format("d/m/y")){

							   $prev_color_step3 = '50d092';
							   $msg_step3  = 'Reçu le '.$date_livrer3->format("d/m/Y").'';

							}else{
								if( $cmp->date_control_project != '0000-00-00 00:00:00')
								{
									if($date_prev3->format("Y-m-d") > $date_livrer3->format("Y-m-d"))
										 $prev_color_step3 = 'ffff00';
									else
										 $prev_color_step3 = 'ff0000';

									$msg_step3  = 'Reçu le '.$date_livrer3->format("d/m/Y H:i").' au lieu de '.$date_prev3->format("d/m/Y H:i");
								}
							}


							if($date_prev4->format("d/m/y") == $date_livrer4->format("d/m/y")){

							   $prev_color_step4 = '50d092';
							   $msg_step4  = 'Reçu le '.$date_livrer4->format("d/m/Y").'';

							}else{
								if( $cmp->date_valid_project != '0000-00-00 00:00:00')
								{
									if($date_prev4->format("Y-m-d") > $date_livrer4->format("Y-m-d"))
										 $prev_color_step4 = 'ffff00';
									else
										 $prev_color_step4 = 'ff0000';

									$msg_step4  = 'Reçu le '.$date_livrer4->format("d/m/Y H:i").' au lieu de '.$date_prev4->format("d/m/Y H:i");
								}
							}
					}
					if(date($date_creation_cdc_prev)>'2016-07-15'){
						$lang_pourteur	= strtoupper(substr(\Yii::app()->session['porteur'], 0, 2));
						$tab[] = array(
											$cmp->site.'|__#__|',
											$cmp->num.'|__#__|',
											$cmp->num.'|__#__|',
											'----'.'|__#__|',
											$cmp->date_shoot.'|__#__|',
											$msg_step0.'|__#__|'.$prev_color,
											$msg_step1.'|__#__|'.$prev_color_step1,
											$msg_step2.'|__#__|'.$prev_color_step2,
											$msg_step3.'|__#__|'.$prev_color_step3,
											$msg_step4.'|__#__|'.$prev_color_step4,
											$cmp->commentaire_palanification.'|__#__|'
									);
					}
				 }

		}
		$ExcelTab = $tab;

		return $ExcelTab;

	}

	public function SuiviPlanification_v2($id = false){

		if($id){
			$campaign	= \Business\CampaignAdapt::load($id);
			$cmps       = $campaign->search();
		}else{
			$campaign	= new \Business\CampaignAdapt();
			$cmps       = $campaign->search();
		}
		$tab = array();
		if(!empty($cmps->data)){
				
				foreach($cmps->data as $cmp)
				{
				$date_creation_cdc_prev = $cmp->date_creation_cdc_prev;

					$date_prev	 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_creation_cdc_prev );
					$date_livrer = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_creation_cdc );

					$date_prev1	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_cdc_prev );
					$date_livrer1 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_cdc );

					$date_prev2	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_dev_it_prev );
					$date_livrer2 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_dev_it );

					$date_prev3	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_project_prev );
					$date_livrer3 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_control_project );

					$date_prev4	  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_valid_project_prev );
					$date_livrer4 = \DateTime::createFromFormat( 'Y-m-d H:i:s', $cmp->date_valid_project );


					$prev_color = $prev_color_step1 = $prev_color_step2 = $prev_color_step3 = $prev_color_step4 = 'd9d9d9';
					$prev_color00 = $prev_color_step01 = $prev_color_step02 = $prev_color_step03 = $prev_color_step04 = 'd9d9d9';

					
					$msg_step0  = $date_prev->format("d/m/Y");
					$msg_step00 = "-";
					$msg_step1  = $date_prev1->format("d/m/Y");
					$msg_step01 = "-";
					$msg_step2  = $date_prev2->format("d/m/Y");
					$msg_step02 = "-";
					$msg_step3  = $date_prev3->format("d/m/Y");
					$msg_step03 = "-";
					$msg_step4  = $date_prev4->format("d/m/Y");
					$msg_step04 = "-";

					if ($cmp->projectStatus == self::PROJECT_CREATE || $cmp->projectStatus == self::PROJECT_CONTROL || $cmp->projectStatus == self::PROJECT_DEVELOP || $cmp->projectStatus == self::PROJECT_CONTROL_FINAL|| $cmp->projectStatus == self::PROJECT_VALID_FINAL)
					{
							if($date_prev->format("d/m/y")== $date_livrer->format("d/m/y")){
							   $prev_color00 = '50d092';
							   
							   $msg_step0  = $date_prev->format("d/m/Y");
							   $msg_step00  = $date_livrer->format("d/m/Y").'';
							}else{
								if($date_prev->format("Y-m-d") > $date_livrer->format("Y-m-d"))
							         $prev_color00 = 'ffff00';
								else
									 $prev_color00 = 'ff0000';
									 
								
								$msg_step0  = $date_prev->format("d/m/Y");
								$msg_step00  = $date_livrer->format("d/m/Y");
							}
							$prev_color_step1 = $prev_color_step2 = $prev_color_step3 = $prev_color_step4 = 'd9d9d9';

							if($date_prev1->format("d/m/y") == $date_livrer1->format("d/m/y")){
								$prev_color_step01 = '50d092';

							   	
							   	$msg_step1  = $date_prev1->format("d/m/Y");
								$msg_step01  = $date_livrer1->format("d/m/Y");
							}else{
								if( $cmp->date_control_cdc != '0000-00-00 00:00:00')
								{
									if($date_prev1->format("Y-m-d") > $date_livrer1->format("Y-m-d"))
										 $prev_color_step01 = 'ffff00';
									else
										 $prev_color_step01 = 'ff0000';
								
									
									$msg_step1  = $date_prev1->format("d/m/Y");
									$msg_step01  = $date_livrer1->format("d/m/Y");
								}
							}
							
							if($date_prev2->format("d/m/y") == $date_livrer2->format("d/m/y")){
								
								$prev_color_step02 = '50d092';

								
								$msg_step2  = $date_prev2->format("d/m/Y");
								$msg_step02  = $date_livrer2->format("d/m/Y");
							   
							}else{
								if( $cmp->date_dev_it != '0000-00-00 00:00:00')
								{
									if($date_prev2->format("Y-m-d") > $date_livrer2->format("Y-m-d"))
										 $prev_color_step02 = 'ffff00';
									else
										 $prev_color_step02 = 'ff0000';
								
									
									$msg_step2  = $date_prev2->format("d/m/Y");
									$msg_step02  = $date_livrer2->format("d/m/Y");
								}
							}
							
							if($date_prev3->format("d/m/y") == $date_livrer3->format("d/m/y")){
								
							   	$prev_color_step03 = '50d092';

							   	
							   	$msg_step3  = $date_prev3->format("d/m/Y");
								$msg_step03  = $date_livrer3->format("d/m/Y");
							   
							}else{
								if( $cmp->date_control_project != '0000-00-00 00:00:00')
								{
									if($date_prev3->format("Y-m-d") > $date_livrer3->format("Y-m-d"))
										 $prev_color_step03 = 'ffff00';
									else
										 $prev_color_step03 = 'ff0000';
								
									
									$msg_step3  = $date_prev3->format("d/m/Y");
									$msg_step03  = $date_livrer3->format("d/m/Y");
								}
							} 
							
							
							if($date_prev4->format("d/m/y") == $date_livrer4->format("d/m/y")){
								
							   	$prev_color_step04 = '50d092';
							   
							   	
							   	$msg_step4  = $date_prev4->format("d/m/Y");
								$msg_step04  = $date_livrer4->format("d/m/Y");
							   
							}else{
								if( $cmp->date_valid_project != '0000-00-00 00:00:00')
								{
									if($date_prev4->format("Y-m-d") > $date_livrer4->format("Y-m-d"))
										 $prev_color_step04 = 'ffff00';
									else
										 $prev_color_step04 = 'ff0000';
								
									
									$msg_step4  = $date_prev4->format("d/m/Y");
									$msg_step04  = $date_livrer4->format("d/m/Y");
								}
							}
					}
					if(date($date_creation_cdc_prev)>'2015-07-15'){
						$lang_pourteur	= strtoupper(substr(\Yii::app()->session['porteur'], 0, 2));
						$tab[] = array(
											$cmp->site.'|__#__|',
											$cmp->num.'|__#__|',
											$cmp->num.'|__#__|',
											'----'.'|__#__|',
											$cmp->date_shoot.'|__#__|',
											$msg_step0.'|__#__|'.$prev_color,
											$msg_step00.'|__#__|'.$prev_color00,
											$msg_step1.'|__#__|'.$prev_color_step1,
											$msg_step01.'|__#__|'.$prev_color_step01,
											$msg_step2.'|__#__|'.$prev_color_step2,
											$msg_step02.'|__#__|'.$prev_color_step02,
											$msg_step3.'|__#__|'.$prev_color_step3,
											$msg_step03.'|__#__|'.$prev_color_step03,
											$msg_step4.'|__#__|'.$prev_color_step4,
											$msg_step04.'|__#__|'.$prev_color_step04,
											$cmp->commentaire_palanification.'|__#__|'
									);
					}
				}

		}
		$ExcelTab = $tab;

		return $ExcelTab;

	}
	
}
