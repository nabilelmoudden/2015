<?php

namespace Business;

/**
 * Description of Pacnet
 * paymentprocessortype.param = mercantId, subMercantId, dataIntegrityCode, labelPaymentProcessorPage, submitter
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class Pacnet extends \Business\PaymentProcessor
{
	const STATUS_OK		= 'approved';
	const STATUS_ABS	= 'absent';
	const STATUS_NOK	= 'declined';

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
		$param									= array();
		$param['md_contact_email']				= urlencode( $this->Invoice->emailUser );
		$param['md_currency']					= $this->currency;
		$param['md_amount']						= number_format( $this->amount, 2, '', '' );
		switch ($this->Invoice->codeSite){
			case 'nl':
			$param['md_language']					='en';
			break;
			case 'no':
			$param['md_language']					='en';
			break;
			case 'de':
			$param['md_language']					='de';
			break;
			case 'ca':
			$param['md_language']					='fr';
			break;
			case 'mx':
			$param['md_language']					='es';
			break;
			default:
			$param['md_language']					=$this->language;
			break;
			}
		
		$param['md_collect_shipping']			= 'No';
		$param['md_collect_email']				= 'No';
		$param['md_billing_name']				= urlencode( $this->Invoice->getUser()->name() );
		$param['md_result_post']				= 'Yes';
		$param['md_email_receipt']				= 'Yes';
		$param['md_always_notify_fulfillment']	= 'No';
		$param['md_fulfillment_post']		    = 'Yes';
		$param['md_timestamp']					= gmdate( 'Y-m-d\TH:i:s\Z' );
		$param['md_reference']					= $this->Invoice->refInterne;
		$param['md_submitter']					= $this->submitter;
		$param['md_routing']					= $this->mercantId;
		
		$param['md_signature']					= $this->genSignature( $param );
		$param['md_result_url']					= $this->callBackUrlOK;
		$param['md_retry_fulfillment_notification']='yes';
		$param['md_fulfillment_url']			        = $this->callBackUrlTreatment;
		/****** Code Ajouter par Othmane pour voir les param envoyer *********/ 
		if($this->Invoice->emailUser=='othmane.halhouli.ki@gmail.com')
		{
		$show = new \Business\ConfigPaymentProcessor( PACNET_CB_URL, $param, true );
		var_dump($show->getParamURL());exit();
		}
		/****** FIN Code Ajouter par Othmane pour voir les param envoyer *********/
		return new \Business\ConfigPaymentProcessor( PACNET_CB_URL, $param, true );
	}

	public function genSignature( $param )
	{
		$first = strtoupper( md5( $param['md_submitter'].','.$param['md_timestamp'].','.$param['md_amount'].','.$param['md_currency'].','.$param['md_reference'] ) );
		return strtoupper( md5( $first.','.$this->dataIntegrityCode ) );
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
		$this->Invoice->refInterne		= \Yii::app()->request->getParam('md_reference');
		$this->Invoice->ref1Transaction	= \Yii::app()->request->getParam('md_tracking_number');
		$this->Invoice->ref2Transaction	= \Yii::app()->request->getParam('md_reference2');

		if( \Yii::app()->request->getParam('md_approval_status') == self::STATUS_OK )
		{
			$this->status		= \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
		}
		else
		{
			$this->status		= \Business\PaymentProcessor::TRANS_STATUS_ERROR;
			$this->errorMessage	= \Yii::app()->request->getParam('md_approval_status');
		}

		return true;
	}
}

?>