<?php

namespace Business;

/**
 * Description of Pacnet
 * paymentprocessortype.param = mercantId, subMercantId, dataIntegrityCode, labelPaymentProcessorPage, submitter
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class Commercegate extends \Business\PaymentProcessor
{
	const TRANS_STATUS_VALIDATED	= 'SALE';
	
	

	static protected $necessaryParam = array(
		\Business\PaymentProcessorType::PARAM_MERCANTID,
		\Business\PaymentProcessorType::PARAM_SUBMERCANTID,
		\Business\PaymentProcessorType::PARAM_DATAINTEGRITY,
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

	/**
	 * check if the browser is mobile
	 */
	function isMobile() {
		return preg_match(
				"/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", 
				$_SERVER["HTTP_USER_AGENT"]
			);
	}

	public function internalProcessPayment()
	{
		
		$amount = number_format( $this->amount, 2, '.', '' );
		$amountStr = str_replace('.', ',', $amount);
		
		$this->gmtTimestamp = gmdate('Y-m-d H:i:s'); // conctrôler que nous sommes en GMT
		
		// We get de token from commercegate by givin amount, title, currency
		$url = COMMERCEGATE_TOKEN_URL 
				."?title=" .utf8_decode($this->Invoice->RecordInvoice[0]->Product->description)
				."&amount=" .$amountStr
				."&currency=" .$this->currency
				."&cid=" .$this->mercantId					// ID Client
				."&wid=" .$this->subMercantId;					// ID site web;
		
		\Yii::import( 'ext.CurlHelper' );

		$Curl = new \CurlHelper();
		$Curl->setTimeout( CURL_TIMEOUT );
		$Curl->sendRequest( urldecode($url) );
				
		$token = $Curl->sendRequest( urldecode($url) );
		
		
		$param	= array();
		$param["cid"]		= $this->mercantId; // ID Client
		$param["wid"]		= $this->subMercantId; // ID site web
		$param["username"]	= urlencode( $this->Invoice->emailUser );
		$param["email"]		= urlencode( $this->Invoice->emailUser );
		$param["lang"]		= ($this->Invoice->codeSite=='in')?'en':($this->Invoice->codeSite=='au')?'en':($this->Invoice->codeSite=='ca')?'en':$this->language;
		if($this->Invoice->codeSite == 'nz')
			$param["lang"] = 'en';
		if($this->Invoice->codeSite == 'sf')
			$param["lang"] = 'en';
		if($this->Invoice->codeSite == 'sg')
			$param["lang"] = 'en';
		$param["op1"]		= $this->Invoice->refInterne;
		
		$param["token"]		= trim($token);

		if($this->isMobile()){
			$param["theme"]		= 'minimal'; 
		}
		
		if($this->Invoice->emailUser=='othmane.halhouli.ki@gmail.com')
		{
		$show = new \Business\ConfigPaymentProcessor( COMMERCEGATE_CB_URL, $param, true );
		var_dump(COMMERCEGATE_CB_URL.$show->getParamURL());exit();
		}
		
		
		
		
		
		
		

		return new \Business\ConfigPaymentProcessor( COMMERCEGATE_CB_URL, $param, true );		
	}

	public function genSignature( $param )
	{
		$first = strtoupper( md5( $param["cid"].'~'.$param["op1"].'~'.$amount.'~'.$this->currency) );
		return strtoupper( md5($this->dataIntegrityCode.'~'.$first) );
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
		$this->Invoice->ref1Transaction	= \Yii::app()->request->getParam('transactionid'). "-" .\Yii::app()->request->getParam('transactionreferenceid');
		$this->Invoice->ref2Transaction	= \Yii::app()->request->getParam('invoice_id');
		
		
		switch (\Yii::app()->request->getParam('transactiontype')){
			case 'SALE' : 
				$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
				break;
			case 'UPSELL' :
			/* Ajouter par jalal le 21-04-2015 */
				$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
				break;	
			case 'REFUND' :
				$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
				break;
			case 'CANCELMEMBERSHIP' :
				$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
				break;
			/* FIN Ajouter par jalal le 21-04-2015 */
			default :
				$this->status = \Business\PaymentProcessor::TRANS_STATUS_ERROR;
				break;
		}
		
		return true;
		
	}
}
?>