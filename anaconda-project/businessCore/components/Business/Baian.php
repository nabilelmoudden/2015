<?php

namespace Business;

/**
 * Description of Baian
 * paymentprocessortype.param = mercantId, subMercantId, dataIntegrityCode, labelPaymentProcessorPage, submitter
 *
 * @author Bouchra IDBOURAISS
 * @package Business.PaymentProcessor
 */
class Baian extends \Business\PaymentProcessor
{
	const STATUS_OK		= 'OK';
	
	
     
	const BAIAN_USERID				= "sandra.calvo";
  	const BAIAN_URL 				= 'https://connect2.baian.com/';

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
		$this->gmtTimestamp = gmdate('Y-m-d H:i:s'); // conctr�ler que nous sommes en GMT

		$param = array();
		$param['orderID'] 		   = $this->mercantId;
		$param['paymentMode'] 	   = Connect2PayClient::_PAYMENT_MODE_SINGLE;
		$param['currency'] 		   = $this->currency;
		$param['amount'] 		   = $amountStr * 100;
		$param['paymentType'] 	   = "CreditCard";
		$param['shippingType']     = Connect2PayClient::_SHIPPING_TYPE_VIRTUAL;
		$param['shopperFirstName'] = urlencode(html_entity_decode($this->Invoice->User->firstName,ENT_COMPAT | ENT_HTML401 , 'UTF-8'));
		$param['shopperLastName']  = urlencode(html_entity_decode($this->Invoice->User->lastName,ENT_COMPAT | ENT_HTML401 , 'UTF-8'));
		$param['shopperEmail'] 	   = html_entity_decode($this->Invoice->User->email);		
		$param['shopperAddress']   = urlencode($this->Invoice->User->address);
		$param['shopperCity'] 	   = urlencode($this->Invoice->User->city);
		$param['shopperState'] 	   = urlencode($this->Invoice->User->country);
		$param['shopperZipcode']   = urlencode($this->Invoice->User->zipCode);
		$param['shopperPhone']	   = urlencode($this->Invoice->User->phone);
        $param['ctrlRedirectURL']  = $this->callBackUrlOK;
        $param['ctrlCallbackURL']  = $this->callBackUrlTreatment;
        $param['ctrlCustomData']   = $this->callBackUrlTreatment;
       	$param['timeOut']		   = 'PT10M';
		$param['url']              = $this->submitter;
		$baian_pwd				   = $this->dataIntegrityCode;
		$c2pClient			   	   = new Connect2PayClient($param['url'] , $param['orderID'] , $baian_pwd, $param);

		// Validate our information
		if ($c2pClient->validate()) {
		  // Create the payment transaction on the payment page

		  if ($c2pClient->prepareTransaction()) {
		    // We can save in session the token info returned by the payment page (could
		    // be used later when the customer will return from the payment page)
		    $_SESSION['merchantToken'] = $c2pClient->getMerchantToken();

		    //Get url redirect for user with email "bouchra.idbouraiss@kindyinfomaroc.com"
		    if($this->Invoice->emailUser == 'bouchra.idbouraiss@kindyinfomaroc.com' )
			{
				echo('<pre>');
				print_r($c2pClient->getCustomerRedirectURL());
				exit();
			}
			////////////////////////////////////////////////////////////////////////

		    // If setup is correct redirect the customer to the payment page.
		    header('Location: ' . $c2pClient->getCustomerRedirectURL());
		    exit;
		  } else {
		    echo "error prepareTransaction: ";
		    echo $c2pClient->getClientErrorMessage() . "\n";
		    exit();
		  }
		} else {
		  echo "error validate: ";
		  echo $c2pClient->getClientErrorMessage() . "\n";
		  exit();
		}
	}
        
    function ProductDigest($order) 
    {
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
		$this->Invoice->refInterne		= \Yii::app()->request->getParam('invoice_id');
		$this->Invoice->ref1Transaction	= \Yii::app()->request->getParam('TransactionID');
		$this->Invoice->ref2Transaction	= \Yii::app()->request->getParam('invoice_id');
		$this->status = \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
        
		

		return true;
	}

	public function callUrlGet($url, $param) {
		header("location:" .$url);
		die();
	}
	
	
}

?>