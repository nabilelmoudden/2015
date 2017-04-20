<?php

namespace Business;

/**
 * Description of Pacnet
 * paymentprocessortype.param = mercantId, subMercantId, dataIntegrityCode, labelPaymentProcessorPage, submitter
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class Paymentwall extends \Business\PaymentProcessor
{
	const STATUS_OK		= 'OK';
	
	
     

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

	public function internalProcessPayment()
	{
		
		$param = array();
		
		
		$amountStr = number_format($this->amount, 2, '.', '') ;
		$amountStr = str_replace(',', '.', $amountStr);
		
		$amountItem = $amountStr;
		

		
		$this->gmtTimestamp = gmdate('Y-m-d H:i:s'); // conctr�ler que nous sommes en GMT

		$param = array();
		
		$param['md_contact_email'] = urlencode($this->Invoice->User->email);
		$param['md_currency'] = $this->currency;
		$param['md_amount'] = $amountItem;
		$param['md_language'] = $this->Invoice->codeSite;
		$param['md_billing_name'] = urlencode(html_entity_decode($this->Invoice->User->lastName,ENT_COMPAT | ENT_HTML401 , 'UTF-8')) ." " .urlencode(html_entity_decode($this->Invoice->User->firstName,ENT_COMPAT | ENT_HTML401 , 'UTF-8'));
		
		
		if($this->Invoice->codeSite == 'pl'){
			
			Paymentwall_Base::setAppKey('46d904f0c2891db37cf5a41b95971e1f');
			Paymentwall_Base::setSecretKey('494646c9df9d5b5f1a514c9d2887e75d');
			Paymentwall_Config::getInstance()->set(array(
				'api_type' => Paymentwall_Config::API_GOODS,
				'public_key' => '46d904f0c2891db37cf5a41b95971e1f',
				'private_key' => '494646c9df9d5b5f1a514c9d2887e75d'
			));
			
		}else{
			
			Paymentwall_Base::setApiType(Paymentwall_Base::API_CART);
			Paymentwall_Base::setAppKey('280337ba5edb06d3a7d95f3e1a8e1fd3');
			Paymentwall_Base::setSecretKey('32e885b04f585b6d003dded63494123c');
			Paymentwall_Config::getInstance()->set(array(
					'api_type' => Paymentwall_Config::API_GOODS,
					'public_key' => '280337ba5edb06d3a7d95f3e1a8e1fd3',
					'private_key' => '32e885b04f585b6d003dded63494123c'
			));
		}
		
		$civility = $this->Invoice->User->civility==1?'male':'female';
		
		$date = $this->Invoice->User->birthday;
		$birthday = strtotime($date);
		
		if($this->Invoice->codeSite == 'pl'){
			
			$widget = new Paymentwall_Widget(
				$this->Invoice->User->id,
				$this->dataIntegrityCode,
				array(
						new Paymentwall_Product(
								$this->Invoice->RecordInvoice[0]->Product->description,
								number_format($param['md_amount'], 2, '.', '' ),
								$param['md_currency'],
								'Gold Membership',
								Paymentwall_Product::TYPE_FIXED
								)
				),
				array('email' => $param['md_contact_email'], 'customer[firstname]'=>$this->Invoice->User->firstName, 'customer[lastname]'=>$this->Invoice->User->lastName, 'customer[birthday]'=>$birthday,  'customer[sex]'=>$civility, 'lang' => 'pl', 'externID' => $this->Invoice->refInterne,'success_url' => $this->callBackUrlOK)
				);
			
		}else{
		
			$widget = new Paymentwall_Widget(
					$this->Invoice->User->id,
					$this->dataIntegrityCode,
					array(
							new Paymentwall_Product(
									$this->Invoice->RecordInvoice[0]->Product->description,
									number_format($param['md_amount'], 2, '.', '' ),
									$param['md_currency'],
									'Gold Membership',
									Paymentwall_Product::TYPE_FIXED
									)
					),
					array('email' => $param['md_contact_email'], 'customer[firstname]'=>$this->Invoice->User->firstName, 'customer[lastname]'=>$this->Invoice->User->lastName, 'customer[birthday]'=>$birthday,  'customer[sex]'=>$civility, 'lang' => 'tr', 'externID' => $this->Invoice->refInterne,'success_url' => $this->callBackUrlOK)
					);
		}
		
		return $this->callUrlGet( $widget->getUrl(), $param );
	}
        
        function ProductDigest($order) {
		$str = "";
		$str = $order->description .' (Qty: ' .$order->product->qty .')';
		return $str;
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
		
		$this->Invoice->refInterne		= \Yii::app()->request->getParam('externID');
		$this->Invoice->ref1Transaction	= \Yii::app()->request->getParam('ref');
		$this->Invoice->ref2Transaction	= \Yii::app()->request->getParam('ref');
		$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;

		
		return true;
	}
	public function callUrlGet($url, $param) {
		header("location:" .$url);
		die();
	}
	
	
}

?>