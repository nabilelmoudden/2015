<?php

namespace Business;

/**
 * Description of Pacnet
 * paymentprocessortype.param = mercantId, subMercantId, dataIntegrityCode, labelPaymentProcessorPage, submitter
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class G2s extends \Business\PaymentProcessor
{
	const STATUS_OK				= 'OK';
	const STATUS_APPROVED	 	= 'APPROVED';
	
	
     

	static protected $necessaryParam = array(
		\Business\PaymentProcessorType::PARAM_MERCANTID,
		\Business\PaymentProcessorType::PARAM_SUBMERCANTID,
		\Business\PaymentProcessorType::PARAM_DATAINTEGRITY,
		\Business\PaymentProcessorType::PARAM_LANGPP,
		\Business\PaymentProcessorType::PARAM_LABEL,
		\Business\PaymentProcessorType::PARAM_SUBMITTER,
		\Business\PaymentProcessorType::PARAM_PREFIX
	);

	/**
	 * Constructeur
	 * @param int $idInvoice
	 * @param \Business\PaymentProcessorType $Type
	 */
	public function __construct( $idInvoice, $Type )
	{
		parent::__construct( $idInvoice, $Type );

		$this->verifyParam( $Type, self::$necessaryParam );
	}

	public function internalProcessPayment()
	{
		
		$this->gmtTimestamp = gmdate('Y-m-d H:i:s'); // conctr�ler que nous sommes en GMT
		$param = array();
		
		
		$amountStr = number_format($this->amount, 2, '.', '') ;
		$amountStr = str_replace(',', '.', $amountStr);
		
		$amountItem = $amountStr;
		if (($this->Invoice->codeSite=='mx')&& ($this->name != 'DINEROMAIL'))
		   {
		   	\Yii::import( 'ext.ConverterCurrency' );

			$ConverterCurrency = new \ConverterCurrency();
					
					    $amountStr= $ConverterCurrency->currencyConverter('MXN', 'USD', $this->amount);
						
						$amountStr= floor(str_replace(',', '.', $amountStr));
						 
						$amountItem = $amountStr;
						 
						
			}
		#  BEGIN : code dupliqué pour argetntine
		if (($this->Invoice->codeSite=='ar')&& ($this->name != 'DINEROMAIL'))
		   {
		   	\Yii::import( 'ext.ConverterCurrency' );

			$ConverterCurrency = new \ConverterCurrency();
					
					    $amountStr= $ConverterCurrency->currencyConverter($this->currency, 'USD', $this->amount);
						
						$amountStr= floor(str_replace(',', '.', $amountStr));
						 
						$amountItem = $amountStr;
						 
						
			}
		#  END
		
		#  BEGIN : code dupliqué pour CHILI
		if (($this->Invoice->codeSite=='cl')&& ($this->name != 'DINEROMAIL'))
		   {
		   	\Yii::import( 'ext.ConverterCurrency' );

			$ConverterCurrency = new \ConverterCurrency();
					
					    $amountStr= $ConverterCurrency->currencyConverter($this->currency, 'USD', $this->amount);
						
						$amountStr= floor(str_replace(',', '.', $amountStr));
						 
						$amountItem = $amountStr;
						 
						
			}
		#  END

		#  BEGIN : code dupliqué pour IN
		if (($this->isAashaIn())&& ($this->name != 'DINEROMAIL'))
		   {
		   	\Yii::import( 'ext.ConverterCurrency' );

			$ConverterCurrency = new \ConverterCurrency();
					
					    $amountStr= $ConverterCurrency->currencyConverter($this->currency, 'USD', $this->amount);
						
						$amountStr= floor(str_replace(',', '.', $amountStr));
						 
						$amountItem = $amountStr;
						 
						
			}
		#  END
		
		$this->gmtTimestamp = gmdate('Y-m-d H:i:s'); // conctr�ler que nous sommes en GMT

		$param = array();
		
		$param['merchant_id'] 		= $this->mercantId; 
		$param['merchant_site_id'] 	= $this->subMercantId;
		
		if($this->langPP != NULL && $this->langPP !=''){
			$param['merchantLocale'] 	= $this->langPP;
		}
		
        # Ajout de la condition : || $this->Invoice->codeSite=='ar' pour argentine
		$param['currency'] 		    = ($this->Invoice->codeSite=='mx' || $this->Invoice->codeSite=='ar' || $this->Invoice->codeSite=='cl' || $this->isAashaIn() )?'USD':$this->currency;
		$param['total_amount'] 		= $amountStr;

		
		
		
		
		
		
		

		 

		$param['item_name_1'] = urlencode(html_entity_decode($this->Invoice->RecordInvoice[0]->Product->ref));
		

		$param['item_amount_1'] 	= $amountItem;
		
		for( $i=0; $i<count($this->Invoice->RecordInvoice); $i++ )
		{
		  $param['item_quantity_1'] 	= $this->Invoice->RecordInvoice[$i]->qty;
		}
			
		$param['time_stamp'] 		= $this->gmtTimestamp; // current GMT time in the following format: YYYY-MM-DD.HH:MM:SS
		$param['version'] 		    = "3.0.0"; // actuellement version=3.0.0
		$param['checksum'] 	     	= $this->ReturnSignature();// A v�rifier	
		$param['first_name'] 		= urlencode(html_entity_decode($this->Invoice->User->firstName,ENT_COMPAT | ENT_HTML401 , 'UTF-8'));//html_entity_decode($order->user->firstname,ENT_COMPAT | ENT_HTML401 , 'UTF-8');//urlencode($order->user->firstname);//iconv('UTF-8','ASCII//TRANSLIT',$order->user->firstname);////$order->user->firstname;//htmlentities(addslashes($order->user->firstname), ENT_NOQUOTES, "UTF-8");// 
		$param['last_name'] 		= urlencode(html_entity_decode($this->Invoice->User->lastName,ENT_COMPAT | ENT_HTML401 , 'UTF-8'));

		if(strlen($param['first_name']) > 30)
			$param['first_name'] = substr($param['first_name'], 0, 30);

		if(strlen($param['last_name']) > 30)
			$param['last_name'] = substr($param['last_name'], 0, 30); 


		$param['email'] 			= urlencode($this->Invoice->User->email);		
		$param['address1']	 		= urlencode($this->Invoice->User->address);
		$param['city'] 				= urlencode($this->Invoice->User->city);
		$param['country'] 			= urlencode($this->Invoice->User->country);
		$param['zip'] 				= urlencode($this->Invoice->User->zipCode);
		$param['phone1']			= urlencode($this->Invoice->User->phone);
        $param['success_url']       = $this->callBackUrlOK;
        $param['error_url']     	= $this->callBackUrlTreatment;
        $param['back_url']       	= $this->backUrl;
        $param['pending_url']  		= $this->callBackUrlTreatment;
        $param['invoice_id']        = $this->Invoice->refInterne;
		
		$param['url']               = $this->submitter;
		
		
		if ($this->name == 'DINEROMAIL') {
			
			$param['payment_method']    = 'DINEROMAIL';
		
			$param['skip_billing_tab']    = "true";
			
			$param['country'] 		= "MX";
			$param['currency']      = "MXN";
			
			if($param['address1']=='')
			$param['address1']	 	= "Mexico";
			if($param['city']=='')
			$param['city'] 			= "Mexico";
			if($param['zip']=='')
			$param['zip'] 			= "1000";
			if($param['phone1']=='')
			$param['phone1']		= "066666666";

		}
		if ($this->name == 'SOFORT') {
			
			$param['payment_method']    = 'apmgw_Sofort';
		//	$param['total_amount'] 		= $amountStr;
			$param['skip_billing_tab']  = "true";
			$param['country'] 			= 'DE';
			if($param['address1']=='')
			$param['address1']	 	= "GERMANY";
			if($param['city']=='')
			$param['city'] 			= "BERLIN";
			if($param['zip']=='')
			$param['zip'] 			= "1000";
			if($param['phone1']=='')
			$param['phone1']		= "066666666";
			

		}
		if ($this->name == 'PAYPAL')
		{
			$param['payment_method']    = 'apmgw_expresscheckout';
			$param['skip_billing_tab']  = "true";
			if($param['address1']=='')
			$param['address1']	 	= "GERMANY";
			if($param['city']=='')
			$param['city'] 			= "BERLIN";
			if($param['zip']=='')
			$param['zip'] 			= "1000";
			if($param['phone1']=='')
			$param['phone1']		= "066666666";
			
			if( isset($param['first_name']) && ( empty($param['first_name']) || $param['first_name'] == "" ) )
			{
				$param['first_name'] 		=  'First Name';
			}
			
			if( isset($param['last_name']) && ( empty($param['last_name']) || $param['last_name'] == "" ) )
			{
				$param['last_name'] 		=  'Last Name';
			}
			
		}
		
		if ($this->name == 'BOLETO')
		{
			$param['payment_method']    = 'apmgw_BOLETO';
			$param['skip_billing_tab']  = "true";
			$param['country'] 			= 'BR';
			if($param['address1']=='')
				$param['address1']	 	= "BRAZIL";
			if($param['city']=='')
				$param['city'] 			= "BRAZIL";
			if($param['zip']=='')
				$param['zip'] 			= "1000";
			if($param['phone1']=='')
				$param['phone1']		= "066666666";
			if($param['last_name']=='')
				$param['last_name']		= "user";
				
		}

		if ($this->name == 'AMEX')
		{
			$param['payment_method']    = 'cc_card';
				
		}

		
		
		//$urls = new \Business\ConfigPaymentProcessor( G2S_CB_URL, $param, true );
		//echo $urls->getParamURL();exit;
		if($this->Invoice->emailUser=='othmane.halhouli.ki@gmail.com' )
		{
			
			$show = new \Business\ConfigPaymentProcessor( $param['url'] , $param, true );
			echo('<pre>');
			//print_r($this->amount." ".$ConverterCurrency->currencyConverter($this->currency, 'USD', 1000)." ".$this->currency." ".$param['url'].$show->getParamURL());
			print_r($param['url'].$show->getParamURL());
			exit();
		}
		return new \Business\ConfigPaymentProcessor( $param['url'] , $param, true );
	}
        
 	function ProductDigest($order) {
		$str = "";
		$str = $order->description .' (Qty: ' .$order->product->qty .')';
		return $str;
	}

	function isAashaIn() {
		if (strstr($_SERVER['HTTP_HOST'], 'aasha-in.com') !== FALSE && $this->Invoice->codeSite=='in') {
			return true;
		}
		return FALSE;
	}
        
	public function ReturnSignature() {
		\Yii::import( 'ext.ConverterCurrency' );

		$ConverterCurrency = new \ConverterCurrency();
    	// compute digest
		$amountStr = number_format($this->amount, 2, '.', '') ;
		$amountStr = str_replace(',', '.', $amountStr);
		
        //$amountStr = round($this->amount) ;
		$amountItem = $amountStr;
		
		if (($this->Invoice->codeSite=='mx') && ($this->name != 'DINEROMAIL'))
		   {
					
					    $amountStr= $ConverterCurrency->currencyConverter('MXN', 'USD', $this->amount);
						
						$amountStr= floor(str_replace(',', '.', $amountStr));
						 
						$amountItem = $amountStr;
						 
						
			}
			
      #  BEGIN : code dupliqué pour argetntine	
	   if (($this->Invoice->codeSite=='ar') && ($this->name != 'DINEROMAIL'))
		   {
					
					    $amountStr= $ConverterCurrency->currencyConverter($this->currency, 'USD', $this->amount);
						
						$amountStr= floor(str_replace(',', '.', $amountStr));
						 
						$amountItem = $amountStr;
						 
						
			}
	  #  END
	  
	  #  BEGIN : code dupliqué pour argetntine	
	   if (($this->Invoice->codeSite=='cl') && ($this->name != 'DINEROMAIL'))
		   {
					
					    $amountStr= $ConverterCurrency->currencyConverter($this->currency, 'USD', $this->amount);
						
						$amountStr= floor(str_replace(',', '.', $amountStr));
						 
						$amountItem = $amountStr;
						 
						
			}
	  #  END

	  #  BEGIN : code dupliqué pour Aasha IN	
		if (($this->isAashaIn()) && ($this->name != 'DINEROMAIL'))
		{	
			$amountStr= $ConverterCurrency->currencyConverter($this->currency, 'USD', $this->amount);
			$amountStr= floor(str_replace(',', '.', $amountStr));
			$amountItem = $amountStr;
		}
	  #  END
	  
		if ($this->name != 'DINEROMAIL'){
			
			if ($this->Invoice->codeSite=='mx')
			   {

					$strToMD5 = $this->dataIntegrityCode
								.$this->mercantId
								.'USD'
								.$amountStr
								//."toto" //html_entity_decode($order->description)
								.utf8_decode($this->Invoice->RecordInvoice[0]->Product->ref)
								.$amountItem
								.$this->Invoice->RecordInvoice[0]->qty
								.$this->gmtTimestamp;
			   }
			   else if ($this->Invoice->codeSite=='ar')
			   {

					$strToMD5 = $this->dataIntegrityCode
								.$this->mercantId
								.'USD'
								.$amountStr
								//."toto" //html_entity_decode($order->description)
								.utf8_decode($this->Invoice->RecordInvoice[0]->Product->ref)
								.$amountItem
								.$this->Invoice->RecordInvoice[0]->qty
								.$this->gmtTimestamp;
			   }else if ($this->Invoice->codeSite=='cl')
			   {

					$strToMD5 = $this->dataIntegrityCode
								.$this->mercantId
								.'USD'
								.$amountStr
								//."toto" //html_entity_decode($order->description)
								.utf8_decode($this->Invoice->RecordInvoice[0]->Product->ref)
								.$amountItem
								.$this->Invoice->RecordInvoice[0]->qty
								.$this->gmtTimestamp;
			   }
			   else if ($this->isAashaIn())
			   {

					$strToMD5 = $this->dataIntegrityCode
								.$this->mercantId
								.'USD'
								.$amountStr
								.utf8_decode($this->Invoice->RecordInvoice[0]->Product->ref)
								.$amountItem
								.$this->Invoice->RecordInvoice[0]->qty
								.$this->gmtTimestamp;
			   }else
			   {
				   $strToMD5 = $this->dataIntegrityCode
								.$this->mercantId
								.$this->currency
								.$amountStr
								//."toto" //html_entity_decode($order->description)
								.utf8_decode($this->Invoice->RecordInvoice[0]->Product->ref)
								.$amountItem
								.$this->Invoice->RecordInvoice[0]->qty
								.$this->gmtTimestamp;
			   }
		}
		else
		{
			
			
		
				$strToMD5 = $this->dataIntegrityCode
							.$this->mercantId
							.'MXN'
							.$amountStr
							
							.utf8_decode($this->Invoice->RecordInvoice[0]->Product->ref)
							.$amountItem
							.$this->Invoice->RecordInvoice[0]->qty
							.$this->gmtTimestamp;
		
		}

		

		$signature = md5($strToMD5);
                
		return $signature;
	}

	public function internalCheckAnswer()
	{
		return true;
	}

	public function internalTreatResult()
	{
		return true;
	}

	public function internalLoadDataFromAnswer()
	{
		
		$this->Invoice->refInterne		= \Yii::app()->request->getParam('invoice_id');
		$this->Invoice->ref1Transaction	= \Yii::app()->request->getParam('TransactionID');
		$this->Invoice->ref2Transaction	= \Yii::app()->request->getParam('invoice_id');

        switch (\Yii::app()->request->getParam('ppp_status')){
			
			case self::STATUS_OK : 
				$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
				break;
			case self::STATUS_APPROVED :
				$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
				break;
			default:
				$this->status	= \Business\PaymentProcessor::TRANS_STATUS_ERROR;
                                $this->errorMessage	= \Yii::app()->request->getParam('Status');
				break;
		}
		
		return true;
	}
	
	
}

?>