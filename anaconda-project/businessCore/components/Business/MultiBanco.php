<?php 

namespace Business;
/**
 * Description of Multibanco
 * paymentprocessortype.param = mercantId, subMercantId, dataIntegrityCode, labelPaymentProcessorPage, submitter
 *
 * @author JulienL
 * @package Business.Multibanco
 */
class Multibanco extends \Business\PaymentProcessor{
	const STATUS_OK		= 'approved';
	const STATUS_ABS	= 'absent';
	const STATUS_NOK	= 'declined';
	public $param = array();
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
	public function __construct( $idInvoice, $Type ){
		parent::__construct( $idInvoice, $Type );
		$this->verifyParam( $Type, self::$necessaryParam );
		// DEFINE('MB_DEFAULT_CIN', '920');   			//User ID
		// DEFINE('MB_DEFAULT_USER', 'SHIP210113');  			//Website ID
		// DEFINE('MB_DEFAULT_ENTITY', '10611');	
		// DEFINE('MB_DEFAULT_AUTHORISATION_CODE', '80c42cd3d6961dc6ace8841c881b74b5');
		// DEFINE('MB_DEFAULT_LANGUAGE', 'PT');		//Language
		DEFINE('MB_DEFAULT_REF_TYPE', 'auto');				//Preoffer   
		DEFINE('MB_DEFAULT_COUNTRY', 'PT');   
		// DEFINE('MB_DEFAULT_NAME', 'MB_1');
	}

	public function internalProcessPayment(){
		$param = array();
		$amountStr 								= number_format($this->amount, 2, '.', '');
		$amountStr 								= str_replace(',', '.', $amountStr);
		$this->gmtTimestamp 					= gmdate('Y-m-d H:i:s'); // conctrôler que nous sommes en GMT
		$param['ep_cin'] 						= $this->submitter;
		$param['ep_user'] 						= $this->subMercantId;
		$param['t_value'] 						= $amountStr;
		$param['t_key'] 						= $this->Invoice->refInterne;
		$param['ep_entity'] 					= $this->mercantId;
        $param['ep_ref_type']  					= MB_DEFAULT_REF_TYPE;
		$param['ep_country']  					= MB_DEFAULT_COUNTRY;
		$param['ep_language']  					= $this->language;
		$param['s_code']  						= $this->dataIntegrityCode;
		$param['success_url']  					= $this->callBackUrlOK;
		$param['error_url']  					= $this->callBackUrlOK;
		$param['pending_url']  					= $this->callBackUrlOK;
		
		$param['url'] 							= "https://www.easypay.pt/_s/api_easypay_01BG.php";
		
		
		
		define('MB_ASSISTANT_URL', 'https://www.easypay.pt/_s/api_easypay_01BG.php');
		$xs 									= file_get_contents($param['url']."?ep_cin=".$param['ep_cin']."&ep_user=".$param['ep_user']."&ep_entity=".$param['ep_entity']
													."&ep_ref_type=".$param['ep_ref_type']."&ep_country=".$param['ep_country']."&ep_language=".$param['ep_language']
													."&t_value=".$param['t_value']."&t_key=".$param['t_key']."&s_code=".$param['s_code']);
		$resp 									= new \SimpleXMLElement($xs);
		
		$this->Invoice->ref1Transaction			= $resp->ep_reference;
		$this->Invoice->save();
		$this->chrono 							= $resp->ep_reference;
		
		$oldurl 								= $_SERVER['REQUEST_URI'];
		$posinter 								= strpos($oldurl, "?");
		$pathurl 								= substr($oldurl,0,$posinter-5);
		
		$this->param							= $param;
		
		if($this->Invoice->emailUser=='othmane.halhouli.ki@gmail.com' )
		{
				
			$show = new \Business\ConfigPaymentProcessor( $param['url'] , $param, true );
			echo('<pre>');
			
			print_r($param['url'].$show->getParamURL());
			exit();
		}
		
		
		return new \Business\ConfigPaymentProcessor( $param['success_url'], $param, true );
	}

	public function genSignature($param){
		$first = strtoupper( md5( $param['md_submitter'].','.$param['md_timestamp'].','.$param['md_amount'].','.$param['md_currency'].','.$param['md_reference'] ) );
		return strtoupper( md5( $first.','.$this->dataIntegrityCode ) );
	}

	public function internalCheckAnswer(){
		return true;
	}

	public function internalTreatResult(){
		return true;
	}

	public function internalLoadDataFromAnswer(){
		
		
		$this->Invoice->ref1Transaction	= \Yii::app()->request->getParam('externid');
		$this->Invoice->ref2Transaction	= \Yii::app()->request->getParam('externid');
		
		if( \Yii::app()->request->getParam('status') == self::STATUS_OK ){
			$this->status		= \Business\PaymentProcessor::TRANS_STATUS_VALIDATED;
		} else {
			$this->status		= \Business\PaymentProcessor::TRANS_STATUS_ERROR;
			$this->errorMessage	= \Yii::app()->request->getParam('md_approval_status');
		}
		return true;
	}
}