<?php

namespace Business;

/**
 * Description of PacnetCheck
 * paymentprocessortype.param = mercantId, subMercantId
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class PacnetCheck extends \Business\PaymentProcessor
{
	static protected $necessaryParam = array(
		\Business\PaymentProcessorType::PARAM_MERCANTID,
		\Business\PaymentProcessorType::PARAM_SUBMERCANTID,
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
		$param			= array();
		
		$param['id']	= $this->Invoice->id;

		
		return new \Business\ConfigPaymentProcessor( $this->callBackUrlTreatment, $param );
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
    
		$this->Invoice->chrono 	= \Business\PaymentProcessor::ConvertToBase36( $this->Invoice->id ). '-' .\Business\PaymentProcessor::ConvertToBase36( $this->Invoice->User->id );
		
		$this->status			= \Business\PaymentProcessor::TRANS_STATUS_PENDING;
		return true;
	}

}

?>
