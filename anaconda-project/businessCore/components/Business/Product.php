<?php

namespace Business;

/**
 * Description of Product
 *
 * @author JulienL
 * @package Business.Campaign
 *
 *
 * The followings are the available model relations:
 * @property \Business\Evtemv[] $EvtEMV
 * @property \Business\Log[] $Log
 * @property \Business\Routeremv[] $RouterEMV
 * @property \Business\Subcampaign $SubCampaign
 * @property \Business\Paymentprocessorset $PaymentProcessorSet
 */
class Product extends \Product
{
	public function init()
	{
		parent::init();

		$this->onAfterFind		= array( $this, 'loadAdditionnalValues' );
		$this->onBeforeSave		= array( $this, 'saveAdditionnalValues' );
		$this->onAfterSave		= array( $this, 'loadAdditionnalValues' );

		$this->bdcFields		= new \StdClass();
		$this->paramPriceModel	= new \StdClass();
	}

	/**
	 * Decode les valeurs additionnels apres recuperation en DB
	 * @return boolean
	 */
	protected function loadAdditionnalValues()
	{
		$this->bdcFields		= !empty($this->bdcFields) ? json_decode( $this->bdcFields ) : new \StdClass();
		$this->paramPriceModel	= !empty($this->paramPriceModel) ? json_decode( $this->paramPriceModel ) : new \StdClass();
		return true;
	}

	/**
	 * Encode les valeurs additionnels avant sauvegarde en DB
	 * @return boolean
	 */
	protected function saveAdditionnalValues()
	{
		$this->bdcFields		= !empty($this->bdcFields) ? json_encode( $this->bdcFields ) : NULL;
		$this->paramPriceModel	= !empty($this->paramPriceModel) ? json_encode( $this->paramPriceModel ) : NULL;
		return true;
	}

	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Log' => array(self::HAS_MANY, '\Business\Log', 'idProduct'),
			'RouterEMV' => array(self::HAS_MANY, '\Business\RouterEMV', 'idProduct'),
			'SubCampaign' => array(self::HAS_ONE, '\Business\SubCampaign', 'idProduct'),
			'PaymentProcessorSet' => array(self::BELONGS_TO, '\Business\PaymentProcessorSet', 'idPaymentProcessorSet'),
			'RecordInvoice' => array(self::HAS_MANY, '\Business\Recordinvoice', array( 'ref' => 'refProduct' ) ),
		);
	}

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function search( $order = false, $pageSize = 0 )
	{
		$Provider = parent::search();

		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;

		if( $order != false )
			$Provider->criteria->order = $order;

		return $Provider;
	}

	
	/**
	 * Verifie qu'un type de PaymentProcessor est disponible pour le produit
	 * @param string $refPP
	 * @return boolean	true / false
	 */
	public function isPaymentProcessorAvailable( $refPP )
	{
		/*traitement*/
	}

	/**
	 * Retourne le PaymentProcessorSet disponible pour le site passé en argument
	 * @param int $idSite
	 * @return array[\Business\PaymentProcessorSet]
	 */
	public function getPaymentProcessorTypeForSite( $idSite )
	{
		$Res = $this->PaymentProcessorSet->PaymentProcessorType( array( 'condition' => '`PaymentProcessorType`.idSite='.$idSite ) );
		if( is_array($Res) && count($Res) > 0 )
			return $Res;
		else
			return false;
	}

	/**
	 * Test si un champs du BDC est disponible pour ce produit
	 * @param string $type
	 * @param string $name
	 * @return boolean
	 */
	public function isBdcFields( $type, $name )
	{
		if( !isset($this->bdcFields->$type) )
			return false;

		return in_array( $name, $this->bdcFields->$type );
	}

	/**
	 * Retourne un parametre du priceModel
	 * @param string $name
	 * @return mixed
	 */
	public function getParamPriceModel( $name )
	{
		return isset($this->paramPriceModel->$name) ? $this->paramPriceModel->$name : false;
	}

	///load by subcampaign
    static public function loadBySubCamp( $idSub )
    {
        if( !($sub = \Business\SubCampaign::model()->findByPk( $idSub )) )
            return false;

        return self::model()->findByPk( $sub->idProduct );
    }
	
	/**
	 *
	 * @param id product
	 * @return les chaamps des dates CT de produit 08/02/2016
	 */
	   public function CTdateGAR($Product)
	 {
	 
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=0;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.'</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.'</option>';
	 		}
	 		$hours .= '</select>';
			$inputt = "iptDatePicker";
	 		$input = "iptDatePicker".\Yii::app()->params['lang'];
	 		$input2 = "iptDatePicker".\Yii::app()->params['lang']."2";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				}
	 				 
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input2."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 	 	 					 
	 		}
	 	 		$outputCTdate .= "</table></center>";
		}else{
	 	 					
	 		$outputCTdate = '';
	 	 	
	 		}
	 	 			return $outputCTdate;
	 }
	 
	 public function CTdateGSR($Product)
	 {
		 
		if($Product->ctdate && $Product->ctdate >0)
		{
			$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
			for($i=0;$i<=23;$i++){
				
				$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
			}
			$hours .= '</select><select name="select5" style="width: 80px;">';
			for($i=0;$i<=30;$i=$i+30){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' min</option>';
			}
			$hours .= '</select>';
			
			$input = "iptDatePicker".\Yii::app()->params['lang']."2";	
			$input2 = "iptDatePicker".\Yii::app()->params['lang'];	
			$outputCTdate ="<center><table width='480' border='0' cellspacing='0' cellpadding='0'>";
				$j=1;		
				for($i=1;$i<=$Product->ctdate;$i++)
				{
					
				if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 	 		{
	 	 		$outputCTdate .= "<tr><td width='350' valign='top'><p style='margin-left:0px;'></br><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 	 		}
				
				
					if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
					$outputCTdate .= "<tr><td width='350' valign='top'><p style='font-size:14px !important;'>".$pos." ".\Yii::t('BDC', 'session')." : <input class='".$input."' type='text' id='".$input2.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > ".$hours."</p></td></tr>";
					
				}
				$outputCTdate .= "</table></center>";
		}else{
			
			$outputCTdate = '';
			
		}
		return $outputCTdate;
	 }	 
	 
	 	 public function CTdateAHM($Product)
	 {
		 
		if($Product->ctdate && $Product->ctdate >0)
		{
			$inputt = "iptDatePicker";
			$input = "iptDatePicker".\Yii::app()->params['lang'];	
			$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
						
				for($i=1;$i<=$Product->ctdate;$i++)
				{
					if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
					$outputCTdate .= "<tr><td width='350' valign='top'><p>".$pos." ".\Yii::t('BDC', 'session')." : <input class='".$input."' type='text' id='".$inputt.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > </p></td></tr>";
					
				}
				$outputCTdate .= "</table></center>";
		}else{
			
			$outputCTdate = '';
			
		}
		return $outputCTdate;
	 }
	 
	 	 public function CTdateSHM($Product)
	 {
		 
		if($Product->ctdate && $Product->ctdate >0)
		{
			$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 55px;">';
			for($i=0;$i<=23;$i++){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
			}
			$hours .= '</select><select name="select5" style="width: 75px;">';
			for($i=5;$i<=50;$i=$i+5){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' min</option>';
			}
			$hours .= '</select>';
			$inputt = "iptDatePicker";
			$input = "iptDatePicker".\Yii::app()->params['lang'];	
			$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
						
				for($i=1;$i<=$Product->ctdate;$i++)
				{
					if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
					$outputCTdate .= "<tr><td width='350' valign='top'><p>".$pos." ".\Yii::t('BDC', 'session')." : <input class='".$input."' type='text' id='".$inputt.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > ".$hours."</p></td></tr>";
					
				}
				$outputCTdate .= "</table></center>";
		}else{
			
			$outputCTdate = '';
			
		}
		return $outputCTdate;
	 }

	/**
	 *
	 * @param id product
	 * @return les chaamps des dates CT de produit
	 */
	 
	 public function CTdateMYR($Product)
	 {
		 
		if($Product->ctdate && $Product->ctdate >0)
		{
			$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 55px;">';
			for($i=0;$i<=23;$i++){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
			}
			$hours .= '</select><select name="select5" style="width: 75px;">';
			for($i=0;$i<=59;$i=$i+1){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' min</option>';
			}
			$hours .= '</select>';
			$inputt = "iptDatePicker";
			$input = "iptDatePicker7".\Yii::app()->params['lang'];	
			$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
						
				for($i=1;$i<=$Product->ctdate;$i++)
				{
					if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
					$outputCTdate .= "<tr><td width='350' valign='top'><p> <input class='".$input."' type='text' id='".$inputt.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > ".$hours."</p></td></tr>";
					
				}
				$outputCTdate .= "</table></center>";
		}else{
			
			$outputCTdate = '';
			
		}
		return $outputCTdate;
	 }
	 
	 
	 public function CTdate($Product)
	 {
		 
		if($Product->ctdate && $Product->ctdate >0)
		{
			$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 55px;">';
			for($i=9;$i<=23;$i++){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
			}
			$hours .= '</select><select name="select5" style="width: 75px;">';
			for($i=5;$i<=50;$i=$i+5){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' min</option>';
			}
			$hours .= '</select>';
			$input = "iptDatePicker";	
			$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
						
				for($i=1;$i<=$Product->ctdate;$i++)
				{
					if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
					$outputCTdate .= "<tr><td width='350' valign='top'><p>".$pos." ".\Yii::t('BDC', 'session')." : <input class='".$input."' type='text' id='".$input.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > ".$hours."</p></td></tr>";
					
				}
				$outputCTdate .= "</table></center>";
		}else{
			
			$outputCTdate = '';
			
		}
		return $outputCTdate;
	 }
	 
	 
	 public function CTdate2($Product)
	 {
	 
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=9;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' h</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
	 		$input = "iptDatePicker";
	 		$input2 = "iptDatePicker2";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				}
	 				 
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input2."' lang='".\Yii::app()->params['lang']."' type='text' id='".$input.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$input.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 	 	 					 
	 		}
	 	 		$outputCTdate .= "</table></center>";
		}else{
	 	 					
	 		$outputCTdate = '';
	 	 	
	 		}
	 	 			return $outputCTdate;
	 }	
	 
	 
	 
	 /** Dates CT de GV ML PT */
	 
	  public function CTdateMLPT($Product)
	 {
	 		
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=18;$i<=22;$i++){
	 
	 			$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=0;$i<=30;$i=$i+30){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
	 		$input = "iptDatePickerMLPT";
	 		$input2 = "iptDatePicker";
	 		$outputCTdate ="<center><table width='480' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 				
	 			if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 			{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p style='margin-left:0px;'></br><u>".\Yii::t('BDC', 'semana')." ".$j."</u> :</p></td></tr>";
	 				$j++;
	 			}
	 
	 
	 			if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 			$outputCTdate .= "<tr><td width='350' valign='top'><p style='font-size:14px !important;'>".$pos." ".\Yii::t('BDC', 'session1')." : <input class='".$input."' type='text' id='".$input2.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > ".$hours."</p></td></tr>";
	 				
	 		}
	 		$outputCTdate .= "</table></center>";
	 	}else{
	 			
	 		$outputCTdate = '';
	 			
	 	}
	 	return $outputCTdate;
	 }
	 
	 
	 /** Dates CT  ML BR */
	 
	 	 public function CTdateMLBR($Product)
	 {
	 		
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', ' às ').'<select name="select4" style="width: 60px;">';
	 		for($i=16;$i<=23;$i++){
	 
	 			$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
			$hours .= '<option>00 min</option>';
			$hours .= '<option>30 min</option>';
	 		$hours .= '</select>';
			
	 		$input = "iptDatePickerMLBR";
	 		$input2 = "iptDatePicker";
	 		$outputCTdate ="<center><table width='480' border='0' cellspacing='0' cellpadding='0'>";
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 				$outputCTdate .= "<tr><td width='400' valign='top'><p style='font-size:14px !important;'> <input class='".$input."' type='text' id='".$input2.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:100px; height:28px;color:#000;text-align:center' readonly='readonly' > ".$hours."</p></td></tr>";
	 				
	 		}
	 		$outputCTdate .= "</table></center>";
	 	}else{
	 			
	 		$outputCTdate = '';
	 			
	 	}
	 	return $outputCTdate;
	 }
	 
	 
	 
	 
	 /**
* Dates CT de la  grande voyance RIN PT
*/
	 
 public function CTdatePT($Product) 
	 {
	
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=9;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' om</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' u</option>';
	 		}
	 		$hours .= '</select>';
	 		$inputt = "iptDatePicker";
	 		$input4 = "iptDatePickerpt";
	 		$input5 = "iptDatePickerpt2";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semana')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				} 
	 					
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input5."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input4."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 
	 	}
	 	 		$outputCTdate .= "</table></center>";
	 }else{
	 	
	 $outputCTdate = '';
	 	
	 }
	 return $outputCTdate;
	 }
	 
	 
	 

/**
* Dates CT de la  grande voyance ML ES
*/		
	 
	 public function CTdate3($Product)
	 {
		 
		if($Product->ctdate && $Product->ctdate >0)
		{
			$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
			for($i=18;$i<=22;$i++){
				
				$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
			}
			$hours .= '</select><select name="select5" style="width: 80px;">';
			for($i=0;$i<=30;$i=$i+30){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' min</option>';
			}
			$hours .= '</select>';
			$input = "iptDatePicker3";	
			$input2 = "iptDatePicker";	
			$outputCTdate ="<center><table width='480' border='0' cellspacing='0' cellpadding='0'>";
				$j=1;		
				for($i=1;$i<=$Product->ctdate;$i++)
				{
					
				if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 	 		{
	 	 		$outputCTdate .= "<tr><td width='350' valign='top'><p style='margin-left:0px;'></br><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 	 		}
				
				
					if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
					$outputCTdate .= "<tr><td width='350' valign='top'><p style='font-size:14px !important;'>".$pos." ".\Yii::t('BDC', 'session')." : <input class='".$input."' type='text' id='".$input2.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > ".$hours."</p></td></tr>";
					
				}
				$outputCTdate .= "</table></center>";
		}else{
			
			$outputCTdate = '';
			
		}
		return $outputCTdate;
	 }	 
	
	
	public function CTdateEN3($Product)
	 {
		 
		if($Product->ctdate && $Product->ctdate >0)
		{
			$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
			for($i=18;$i<=22;$i++){
				
				$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
			}
			$hours .= '</select><select name="select5" style="width: 80px;">';
			for($i=0;$i<=30;$i=$i+30){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' min</option>';
			}
			$hours .= '</select>';
			$input = "iptDatePicker3EN";	
			$input2 = "iptDatePicker";	
			$outputCTdate ="<center><table width='480' border='0' cellspacing='0' cellpadding='0'>";
				$j=1;		
				for($i=1;$i<=$Product->ctdate;$i++)
				{
					
				if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 	 		{
	 	 		$outputCTdate .= "<tr><td width='350' valign='top'><p style='margin-left:0px;'></br><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 	 		}
				
				
					if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
					$outputCTdate .= "<tr><td width='350' valign='top'><p style='font-size:14px !important;'>".$pos." ".\Yii::t('BDC', 'session')." : <input class='".$input."' type='text' id='".$input2.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > ".$hours."</p></td></tr>";
					
				}
				$outputCTdate .= "</table></center>";
		}else{
			
			$outputCTdate = '';
			
		}
		return $outputCTdate;
	 }	 
	  /**
	  * Dates CT de la  grande voyance ML tr
	  */
	 
	 public function CTdatetr3($Product)
	 {
		 
		if($Product->ctdate && $Product->ctdate >0)
		{
			$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
			for($i=18;$i<=22;$i++){
				
				$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
			}
			$hours .= '</select><select name="select5" style="width: 80px;">';
			for($i=0;$i<=30;$i=$i+30){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' min</option>';
			}
			$hours .= '</select>';
			$input = "iptDatePickertr3";	
			$input2 = "iptDatePicker";	
			$outputCTdate ="<center><table width='480' border='0' cellspacing='0' cellpadding='0'>";
				$j=1;		
				for($i=1;$i<=$Product->ctdate;$i++)
				{
					
				if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 	 		{
	 	 		$outputCTdate .= "<tr><td width='350' valign='top'><p style='margin-left:0px;'></br><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 	 		}
				
				
					if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
					$outputCTdate .= "<tr><td width='350' valign='top'><p style='font-size:14px !important;'>".$pos." ".\Yii::t('BDC', 'session')." : <input class='".$input."' type='text' id='".$input2.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > ".$hours."</p></td></tr>";
					
				}
				$outputCTdate .= "</table></center>";
		}else{
			
			$outputCTdate = '';
			
		}
		return $outputCTdate;
	 }	 
	
	 
	 /**
	  * Dates CT de la  grande voyance ML Fr TH Fr
	  */
	 
	 public function CTdateFr($Product)
	 {
	 		
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=18;$i<=22;$i++){
	 
	 			$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=0;$i<=30;$i=$i+30){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
	 		$input = "iptDatePickerFr";
	 		$input2 = "iptDatePicker";
	 		$outputCTdate ="<center><table width='480' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 				
	 			if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 			{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p style='margin-left:0px;'></br><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 				$j++;
	 			}
	 
	 
	 			if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 			$outputCTdate .= "<tr><td width='350' valign='top'><p style='font-size:14px !important;'>".$pos." ".\Yii::t('BDC', 'session')." : <input class='".$input."' type='text' id='".$input2.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > ".$hours."</p></td></tr>";
	 				
	 		}
	 		$outputCTdate .= "</table></center>";
	 	}else{
	 			
	 		$outputCTdate = '';
	 			
	 	}
	 	return $outputCTdate;
	 }
	 
	 
	 
	 /**
	  * Dates CT de la  grande voyance NL
	  */	 
	 public function CTdateNl($Product) 
	 {
	
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 82px;">';
	 		for($i=18;$i<=22;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' u</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=0;$i<=30;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
	 		$inputt = "iptDatePicker";
	 		$input4 = "iptDatePicker4";
	 		$input5 = "iptDatePicker5";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				} 
	 					
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input5."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input4."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 
	 	}
	 	 		$outputCTdate .= "</table></center>";
	 }else{
	 	
	 $outputCTdate = '';
	 	
	 }
	 return $outputCTdate;
	 }
	 
	  /**
	  * Dates CT de la  grande voyance TR
	  */	 
	 public function CTdateTR($Product) 
	 {
	
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=9;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' om</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' u</option>';
	 		}
	 		$hours .= '</select>';
	 		$inputt = "iptDatePicker";
	 		$input4 = "iptDatePickertr";
	 		$input5 = "iptDatePickertr2";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				} 
	 					
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input5."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input4."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 
	 	}
	 	 		$outputCTdate .= "</table></center>";
	 }else{
	 	
	 $outputCTdate = '';
	 	
	 }
	 return $outputCTdate;
	 }
	  /**
	  * Dates CT de la  grande voyance ES
	  */	 
		 
	 public function CTdateES($Product) 
	 {
	
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=9;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' h</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
	 		$inputt = "iptDatePicker";
	 		$input4 = "iptDatePickeres";
	 		$input5 = "iptDatePickeres2";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				} 
	 					
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input5."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input4."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 
	 	}
	 	 		$outputCTdate .= "</table></center>";
	 }else{
	 	
	 $outputCTdate = '';
	 	
	 }
	 return $outputCTdate;
	 }
	 /**
	  * Dates CT de la  grande voyance IT
	  */	 
	 public function CTdateIT($Product) 
	 {
	
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=9;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' h</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
	 		$inputt = "iptDatePicker";
	 		$input4 = "iptDatePicker6";
	 		$input5 = "iptDatePicker6";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				} 
	 					
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input5."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input4."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 
	 	}
	 	 		$outputCTdate .= "</table></center>";
	 }else{
	 	
	 $outputCTdate = '';
	 	
	 }
	 return $outputCTdate;
	 }

	 /* ------------------------------------------------------ */ 
	
	 /**
	  * Dates CT ML DE new 
	  */	 
	 
	 public function CTdateDE3($Product) 
	 {
	 	if($Product->ctdate && $Product->ctdate >0)
		{
			$hours = 'UM <select name="select4" style="width: 60px;">';
			for($i=0;$i<=23;$i++){
				
				$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
			}
			$hours .= '</select><select name="select5" style="width: 80px;">';
			for($i=0;$i<=55;$i=$i+5){
				if($i<10){$i="0".$i;}
				$hours .= '<option>'.$i.' min</option>';
			}
			$hours .= '</select>';
			$input = "iptDatePickerde4";	
			$input2 = "iptDatePicker";	
			$outputCTdate ="<center><table width='480' border='0' cellspacing='0' cellpadding='0'>";
				$j=1;		
				for($i=1;$i<=$Product->ctdate;$i++)
				{
					
				
	 	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
					$outputCTdate .= "<tr><td width='350' valign='top'><p style='font-size:14px !important;'> > <u>Termin</u>  : <input class='".$input."' type='text' id='".$input2.$i."' lang='".\Yii::app()->params['lang']."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' > <br/>".$hours."</p></td></tr>";
					
				}
				$outputCTdate .= "</table></center>";
		}else{
			
			$outputCTdate = '';
			
		}
		return $outputCTdate;
	
	 }

	 /* ----------------*/
	 /**
	  * Dates CT de la  grande voyance DE 4 input
	  */ 
	 public function CTdateDE4($Product) 
	 {
	
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=18;$i<=22;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' u</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
	 		$inputt = "iptDatePicker";
	 		$input4 = "iptDatePickerde";
	 		$input5 = "iptDatePickerde2";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				} 
	 					
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input5."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input4."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 
	 	}
	 	 		$outputCTdate .= "</table></center>";
	 }else{
	 	
	 $outputCTdate = '';
	 	
	 }
	 return $outputCTdate;
	 }


	 /**
	  * Dates CT de la  grande voyance DE old
	  */	 
	 public function CTdateDE($Product) 
	 {
	
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=9;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' u</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
	 		$inputt = "iptDatePicker";
	 		$input4 = "iptDatePicker8";
	 		$input5 = "iptDatePicker9";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				} 
	 					
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input5."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input4."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 
	 	}
	 	 		$outputCTdate .= "</table></center>";
	 }else{
	 	
	 $outputCTdate = '';
	 	
	 }
	 return $outputCTdate;
	 }
	 
	 
	 public function CTdateDEBis($Product) 
	 {
	
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = '<strong>'.\Yii::t('BDC', 'um').'</strong><select name="select4" style="width: 60px;">';
	 		for($i=9;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.'</option>';
	 		}
	 		$hours .= '</select><strong>Uhr</strong><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.'</option>';
	 		}
	 		$hours .= '</select>';
	 		$inputt = "iptDatePickerdeBIS";
	 		$input4 = "iptDatePickerdeBIS";
	 		$input5 = "iptDatePickerdeBIS";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				
	 					
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><strong> ".\Yii::t('BDC', 'sessionAm').":</strong><input class='".$input5."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><strong> ".\Yii::t('BDC', 'sessionAm').":</strong><input class='".$input4."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 
	 	}
	 	 		$outputCTdate .= "</table></center>";
	 }else{
	 	
	 $outputCTdate = '';
	 	
	 }
	 return $outputCTdate;
	 }
	 
	 public function CTdateNO($Product)
	 {
	 
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=9;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' u</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
	 		$inputt = "iptDatePicker";
	 		$input4 = "iptDatePickerNo";
	 		$input5 = "iptDatePickerNo2";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				}
	 					
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input5."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 						$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input4."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 
	 	}
	 	 		$outputCTdate .= "</table></center>";
	 }else{
	 
	 $outputCTdate = '';
	 	  
	 					}
	 					return $outputCTdate;
	 }
	  public function CTdateDK($Product)
	 {
	 
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=9;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.'</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.'</option>';
	 		}
	 		$hours .= '</select>';
			$inputt = "iptDatePicker";
	 		$input = "iptDatePickerdk";
	 		$input2 = "iptDatePickerdk2";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				}
	 				 
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input2."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 	 	 					 
	 		}
	 	 		$outputCTdate .= "</table></center>";
		}else{
	 	 					
	 		$outputCTdate = '';
	 	 	
	 		}
	 	 			return $outputCTdate;
	 }	
	 
	 
	 	 /** Dates CT de GV LZ RIN fr */
	 
	  public function CTdateFr2($Product)
	 {
	 		
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=18;$i<=22;$i++){
	 
	 			$hours .= '<option>'.$i.' '.\Yii::t('BDC', 'h').'</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=0;$i<=30;$i=$i+30){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
	 		$input = "iptDatePickerFr2";
	 		$input2 = "iptDatePicker";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 				
	 			if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 			{
	 			
					if($i%2)
							{	 			
	 							$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 								$j++;
	 						}
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input."' lang='".\Yii::app()->params['lang']."' type='text' id='".$input2.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$input2.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 	 	 				 
	 			}
	 
	 
	 			
	 		}
	 		$outputCTdate .= "</table></center>";
	 	}else{
	 			
	 		$outputCTdate = '';
	 			
	 	}
	 	return $outputCTdate;
	 }
	 
	 
	 
	 
	 
	 
	public function CTdateSE($Product)
	 {
	 
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 80px;">';
	 		for($i=9;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' tim</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=5;$i<=50;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
			$inputt = "iptDatePicker";
	 		$input = "iptDatePickerse";
	 		$input2 = "iptDatePickerse2";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = $i."".\Yii::t('BDC', 'first'); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'semaine')." ".$j."</u> :</p></td></tr>";
	 			$j++;
	 				}
	 				 
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input2."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', 'session')." : </br><input class='".$input."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 	 	 					 
	 		}
	 	 		$outputCTdate .= "</table></center>";
		}else{
	 	 					
	 		$outputCTdate = '';
	 	 	
	 		}
	 	 			return $outputCTdate;
	 }	
	 public function CTdatePCN($Product)
	 {
	 
	 	if($Product->ctdate && $Product->ctdate >0)
	 	{
	 		$hours = \Yii::t('BDC', 'a').'<select name="select4" style="width: 60px;">';
	 		for($i=8;$i<=23;$i++){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' h</option>';
	 		}
	 		$hours .= '</select><select name="select5" style="width: 80px;">';
	 		for($i=0;$i<=55;$i=$i+5){
	 			if($i<10){$i="0".$i;}
	 			$hours .= '<option>'.$i.' min</option>';
	 		}
	 		$hours .= '</select>';
			$inputt = "iptDatePicker";
	 		$input = "iptDatePicker_pcn";
	 		$input2 = "iptDatePicker2_pcn";
	 		$outputCTdate ="<center><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	 		$j=1;
	 		for($i=1;$i<=$Product->ctdate;$i++)
	 		{
	 	 	
	 	 	
	 
	 		if($i==1) $pos = "".\Yii::t('BDC', ''); else $pos = $i."".\Yii::t('BDC', 'second');
	 				if($i%2)
	 				{
	 				$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'><u>".\Yii::t('BDC', 'Date of appointment')."</u> :</p></td></tr>";
	 			$j++;
	 				}
	 				 
	 				if($i<3)
	 					$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', '')." </br><input class='".$input2."' lang='".\Yii::app()->params['lang']."' type='text' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 					else
	 	 			$outputCTdate .= "<tr><td width='350' valign='top'><p class='left'>".$pos." ".\Yii::t('BDC', '')." </br><input class='".$input."'  type='text' lang='".\Yii::app()->params['lang']."' id='".$inputt.$i."' name='seance_".$i."' style='font-size:14px; width:80px; height:28px;color:#000;text-align:center' readonly='readonly' /> ".$hours."</p></td></tr>";
	 	 	 					 
	 		}
	 	 		$outputCTdate .= "</table></center>";
		}else{
	 	 					
	 		$outputCTdate = '';
	 	 	
	 		}
	 	 			return $outputCTdate;
	 }	
	 
	/**
	 * Retourne un parametre du PaymentProcessorSet
	 * @param string $name
	 * @return mixed
	 */

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\Product
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 *
	 * @param type $ref
	 * @return \Business\Product
	 */
	static public function loadByRef( $ref )
	{
		return self::model()->findByAttributes( array( 'ref' => $ref ) );
	}
	
	 /**
	 *
	 * @param array $criteria
	 * @return \Business\Product
	 */
	static public function loadByCriteria( $criteria, $targets )
	{
		$target = [];
		foreach ($targets as $item) {
			$target[] = "LOWER($item) REGEXP '$criteria'";
		}
		$condition = implode(' or ', $target);
		$query  = "Select * from V2_product where $condition ";
		// echo "<pre>"; print_r($query); echo "</pre>";
		return self::model()->findAllBySql( $query );
	} 
// ==================================================================
// Generation CDC ====== MOUJJAB ABDELILAH
	
	/****** Fonction de controle d'un produit */	
	public function controle(){
	
		$bdcf = strval(json_encode($this->bdcFields));
	
		$controle = array();
		$i = 0;
		if(floatval($this->theoPricePros) == 0){
			$controle[$i] = "Le prix théorique Prospect vaut 0.";
			$i ++;
		}
		if(floatval($this->theoPriceVg) == 0){
			$controle[$i] = "Le prix théorique VG vaut 0.";
			$i ++;
		}
		if(floatval($this->theoPriceVp) == 0){
			$controle[$i] = "Le prix théorique VP vaut 0.";
			$i ++;
		}
		if(floatval($this->theoPriceCt) == 0){
			$controle[$i] = "Le prix théorique CT vaut 0.";
			$i ++;
		}
		if($this->description_marketing == '' || $this->description_marketing == null){
			$controle[$i] = "La description Marketing est vide.";
			$i ++;
		}
		if($this->description == '' || $this->description == null){
			$controle[$i] = "La description du produit est vide.";
			$i ++;
		}
		if($bdcf == '' || $bdcf == "{}"){
			$controle[$i] = "Aucun champ BDC n'a été sélectionné.";
			$i ++;
		}
		return $controle;
	}
	
// Fin Generation CDC ====== MOUJJAB ABDELILAH
// ==================================================================	

	
}

?>
