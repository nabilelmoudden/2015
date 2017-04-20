<?php

namespace Business;

/**
 * Description of Pacnet
 * paymentprocessortype.param = mercantId, subMercantId, dataIntegrityCode, labelPaymentProcessorPage, submitter
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class Payu extends \Business\PaymentProcessor
{
	const STATUS_OK		= 4;
	const STATUS_PENDING	= 7;
	//const STATUS_NOK	= 'declined';

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
		$amount = number_format( $this->amount, 2, '', '' );
		$amountStr = str_replace(',', '.', $amount);
		
		$param								= array();
		$param["buyerEmail"]				= urlencode( $this->Invoice->emailUser );
		$param['currency']					= $this->currency;
		$param["amount"]					= $amountStr;
		$param["lng"]						= $this->language;
		$param['md_billing_name']			= urlencode( $this->Invoice->getUser()->name() );
		$param["signature"]					= $this->genSignature( $param );
		$param["merchantId"]				= $this->mercantId;
		$param["referenceCode"]				= $this->Invoice->refInterne;
		$param["confirmationUrl"]	= $this->callBackUrlTreatment;;
		$param["responseUrl"]		= $this->callBackUrlOK;
		$param['url'] = PAYU_ASSISTANT_URL;
		$param["tax"]			= "0";
		$param["taxReturnBase"]	= "0";
		//$param['md_collect_shipping']			        = 'No';
		//$param['md_collect_email']				= 'No';
		
		//$param['md_result_post']				= 'Yes';
		//$param['md_email_receipt']				= 'Yes';
		//$param['md_always_notify_fulfillment']	                = 'Yes';
		//$param['md_fulfillment_post']		        	= 'Yes';
		//$param['md_timestamp']					= gmdate( 'Y-m-d\TH:i:s\Z' );
		
		//$param['md_submitter']					= $this->submitter;
		
		//$param['md_title']					= $this->labelPaymentProcessorPage;


		return new \Business\ConfigPaymentProcessor( PACNET_CB_URL, $param, true );
	}

	public function genSignature( $param )
	{
		$first = strtoupper( md5( $param["merchantId"].'~'.$param["referenceCode"].'~'.$amount.'~'.$this->currency) );
		return strtoupper( md5($this->dataIntegrityCode.'~'.$first) );
	}

	public function internalCheckAnswer()
	{
		return true;
	}

	public function internalTreatResult()
	{
		$this->Invoice->refInterne		= \Yii::app()->request->getParam('reference_sale');
		$this->Invoice->subPaymentProcessor	= \Yii::app()->request->getParam('payment_method');
	}

	public function internalLoadDataFromAnswer()
	{
		$this->Invoice->refInterne		= \Yii::app()->request->getParam('reference_sale');
		$this->Invoice->ref1Transaction	= \Yii::app()->request->getParam('reference_pol');
		$this->Invoice->ref2Transaction	= \Yii::app()->request->getParam('reference_sale');
		$this->Invoice->subPaymentProcessor	= \Yii::app()->request->getParam('payment_method');

		switch ($this->ReturnValue('state_pol')){
			case self::STATUS_OK : 
				$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
			break;
			case self::STATUS_PENDING :
				$this->status	= \Business\PaymentProcessor::TRANS_STATUS_PENDING;
			break;
			default:
				$this->status	= \Business\PaymentProcessor::TRANS_STATUS_ERROR;
                $this->errorMessage	= \Yii::app()->request->getParam('response_message_pol');
				break;
		}

		return true;
	}
}

?>