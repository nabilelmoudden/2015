<?php

namespace Business;

/**
 * Description of Pacnet
 * paymentprocessortype.param = mercantId, subMercantId, dataIntegrityCode, labelPaymentProcessorPage, submitter
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class Ogone extends \Business\PaymentProcessor
{
	const STATUS_OK		= '5';
	const STATUS_PENDING	= '51';
	
     

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
		$param				= array();

       		$param["PSPID"]			= OGONE_USERID;                     //$this->mercantId;  // Merchant ID
		$param["EMAIL"]			= $this->Invoice->User->email;// Customer email
		$param["CN"]			= $this->Invoice->User->firstName.' '.$this->Invoice->User->lastName;
		$param["OWNERADDRESS"]		= $this->Invoice->User->address;
		$param["OWNERZIP"]		= $this->Invoice->User->zipCode;
		$param["OWNERTOWN"]		= $this->Invoice->User->city;
		$param["OWNERCTY"]		= $this->Invoice->User->country;
		$param["OWNERTELNO"]		= $this->Invoice->User->phone;
		$param["ORDERID"]		= $this->Invoice->refInterne; //$this->GetExternIdFromOrder($order);	// Order reference nombre de 8 caractères max
		$param["AMOUNT"]		= number_format( $this->amount, 2, '', '' );//(int)$amountStr2;			// Amount without comma or point for the decimal	
		$param["CURRENCY"]		= $this->currency;		// Currency to use
                //TODO ========================
                $param["COM"]			= 'Description A VOIR'; //$this->ProductDigest($order);
		//=============================
                $param["PM"]			= "CreditCard";			// 
		$param["USERID"]		= OGONE_USERID;	// Merchant ID
		$param["LANGUAGE"]		= $this->language;
		$param["ACCEPTURL"]		= $this->callBackUrlOK; //OGONE_SUCCESS_URL;
		$param["CANCELURL"]		= $this->callBackUrlTreatment; //OGONE_CANCEL_URL;
		$param["PMLIST"]		= "MasterCard;VISA";
		$param["OPERATION"]		= "RES";
		$param["HOMEURL"]		= OGONE_HOME_URL;
                $param['md_timestamp']		= gmdate( 'Y-m-d\TH:i:s\Z' );
                $param['md_submitter']		= $this->submitter;
		$param['SHASIGN']		= $this->genSignature( $param );


		return new \Business\ConfigPaymentProcessor( OGONE_CB_URL, $param, true );
	}
        
        function ProductDigest($order) {
		$str = "";
		$str = $order->description .' (Qty: ' .$order->product->qty .')';
		return $str;
	}
        
	public function genSignature( $param )
	{
		/*$first = strtoupper( md5( $param['md_submitter'].','.$param['md_timestamp'].','.$param['AMOUNT'].','.$param['CURRENCY'].','.$param['ORDERID'] ) );
		return strtoupper( md5( $first.','.$this->dataIntegrityCode ) );*/
            
                $toHash	= ($param["ACCEPTURL"] 	== "" ? "" : "ACCEPTURL=".$param["ACCEPTURL"].'amine_1988_KHOUKHI!').
                          ($param["AMOUNT"] 	== "" ? "" : "AMOUNT=".$param["AMOUNT"].'amine_1988_KHOUKHI!').
                          ($param["CANCELURL"] 	== "" ? "" : "CANCELURL=".$param["CANCELURL"].'amine_1988_KHOUKHI!').
                          ($param["CN"] 	== "" ? "" : "CN=".$param["CN"].'amine_1988_KHOUKHI!').
                          ($param["COM"] 	== "" ? "" : "COM=".$param["COM"].'amine_1988_KHOUKHI!').
                          ($param["CURRENCY"] 	== "" ? "" : "CURRENCY=".$param["CURRENCY"].'amine_1988_KHOUKHI!').
                          ($param["EMAIL"] 	== "" ? "" : "EMAIL=".$param["EMAIL"].'amine_1988_KHOUKHI!').
                          ($param["HOMEURL"] 	== "" ? "" : "HOMEURL=".$param["HOMEURL"].'amine_1988_KHOUKHI!').
                          ($param["LANGUAGE"] 	== "" ? "" : "LANGUAGE=".$param["LANGUAGE"].'amine_1988_KHOUKHI!').
                          ($param["OPERATION"] 	== "" ? "" : "OPERATION=".$param["OPERATION"].'amine_1988_KHOUKHI!').
                          ($param["ORDERID"] 	== "" ? "" : "ORDERID=".$param["ORDERID"].'amine_1988_KHOUKHI!').
                          ($param["OWNERADDRESS"] == "" ? "" : "OWNERADDRESS=".$param["OWNERADDRESS"].'amine_1988_KHOUKHI!').
                          ($param["OWNERCTY"] 	== "" ? "" : "OWNERCTY=".$param["OWNERCTY"].'amine_1988_KHOUKHI!').
                          ($param["OWNERTELNO"] == "" ? "" : "OWNERTELNO=".$param["OWNERTELNO"].'amine_1988_KHOUKHI!').
                          ($param["OWNERTOWN"] 	== "" ? "" : "OWNERTOWN=".$param["OWNERTOWN"].'amine_1988_KHOUKHI!').
                          ($param["OWNERZIP"] 	== "" ? "" : "OWNERZIP=".$param["OWNERZIP"].'amine_1988_KHOUKHI!').
                          ($param["PM"] 	== "" ? "" : "PM=".$param["PM"].'amine_1988_KHOUKHI!').
                          ($param["PMLIST"] 	== "" ? "" : "PMLIST=".$param["PMLIST"].'amine_1988_KHOUKHI!').
                          ($param["PSPID"] 	== "" ? "" : "PSPID=".$param["PSPID"].'amine_1988_KHOUKHI!').
                          ($param["USERID"] 	== "" ? "" : "USERID=".$param["USERID"].'amine_1988_KHOUKHI!');

                return strtoupper(sha1($toHash));
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
		$this->Invoice->refInterne	= \Yii::app()->request->getParam('USERID');
		$this->Invoice->ref1Transaction	= \Yii::app()->request->getParam('USERID');
		$this->Invoice->ref2Transaction	= \Yii::app()->request->getParam('USERID');
		$status = \Yii::app()->request->getParam('STATUS');
                switch ($status){
			case self::STATUS_OK : 
				$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
				break;
			case self::STATUS_PENDING :
				$this->status	= \Business\PaymentProcessor::TRANS_STATUS_PENDING;
				break;
			default:
				$this->status	= \Business\PaymentProcessor::TRANS_STATUS_ERROR;
                                $this->errorMessage	= \Yii::app()->request->getParam('STATUS');
				break;
		}

		return true;
	}
}

?>