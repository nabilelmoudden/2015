<?php

namespace Business;

/**
 * Description of Pacnet
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class Cashtronic extends \Business\PaymentProcessor
{
	const STATUS_OK		= 'ACCEPTED';
	const STATUS_NOK	= 'REFUSED';

	static protected $necessaryParam = array( 'mercantId', 'subMercantId', 'dataIntegrityCode', 'labelPaymentProcessorPage', 'submitter', 'prefix' );

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
		$User					= $this->Invoice->getUser();
		$param					= array();
		$param["C_NAME"] 		= urlencode( $User->lastName );
		$param["C_FIRSTNAME"] 	= urlencode( $User->firstName );
		$param["C_EMAIL"]		= urlencode( $User->email );
		$param["C_STREET"]		= urlencode( $User->address );
		$param["C_CITY"]		= urlencode( $User->city );
		$param["C_ZIP"]			= urlencode( $User->zipCode );
		$param["C_STATE"]		= urlencode( $User->state );
		$param["C_PHONE"]		= urlencode( $User->phone );
		$param["C_TAXID"]		= str_replace( array(' ', '-', ',', '.'), '', '123' ); // ???????????????????????
		$param["C_IPADDR"]		= $_SERVER['REMOTE_ADDR'];
		$param["M_ORDERREF"]	= $this->Invoice->refInterne;
		$param["M_AMOUNT"]		= number_format( $this->Invoice->getTotalInvoice(), 2, '', '' );
		$param["M_CURRENCY"]	= $this->Invoice->currency;
		$param["M_DESCR"]		= urlencode( implode( ', ', $this->Invoice->getDescription() ) );
		$param["M_NAME"] 		= $this->labelPaymentProcessorPage;
		$param["M_ID"]			= $this->mercantId;
		$param["M_EMAIL"]		= $this->submitter;
		$param["M_CBACKURL"]	= $this->callBackUrlOK;
		$param["M_CRBACKURL"]	= $this->callBackUrlNOK;
		$param["M_MBACKURL"]	= $this->callBackUrlTreatment;
		$param["M_SIGN"]		= $this->genSignature( $param );

		return new \Business\ConfigPaymentProcessor( CASHTRONIC_CB_URL, $param, true );
	}

	public function genSignature( $param )
	{
		$msg = $param["M_AMOUNT"].$param["M_ORDERREF"].$param["M_ID"].$param["M_CURRENCY"].$this->dataIntegrityCode;
		return strtoupper( sha1( $msg ) );
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
		$this->Invoice->refInterne		= \Yii::app()->request->getParam('R_ORDERREF');
		$this->Invoice->ref1Transaction	= NULL;
		$this->Invoice->ref2Transaction	= \Yii::app()->request->getParam('R_ORDERREF');

		if( \Yii::app()->request->getParam('R_AUTHRESULT') == self::STATUS_OK )
		{
			$this->status		= \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
		}
		else
		{
			$this->status		= \Business\PaymentProcessor::TRANS_STATUS_ERROR;
			$this->errorMessage	= \Yii::app()->request->getParam('R_ERRCODE');
		}

		return true;
	}
}

?>