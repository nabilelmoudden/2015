<?php

namespace Business;

/**
 * Description of FreePayment
 * paymentprocessortype.param = NULL
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class FreePayment extends \Business\PaymentProcessor
{
	static protected $necessaryParam = array( 'prefix' );

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

	public function internalCheckAnswer()
	{
		return true;
	}

	public function internalLoadDataFromAnswer()
	{
		$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
		return true;
	}

	public function internalProcessPayment()
	{
		$param			= array();
		$param['oi']	= $this->Invoice->id;

		
		return new \Business\ConfigPaymentProcessor( $this->callBackUrlTreatment, $param );
	}

	public function internalTreatResult()
	{
		return true;
	}
}

?>
