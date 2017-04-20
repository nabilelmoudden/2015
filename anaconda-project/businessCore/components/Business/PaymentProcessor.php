<?php

namespace Business;

interface PaymentProcessorInterface
{
	/**
	 * This method will ask for the payment
	 * This method has to be implement by every inherited class
	 * @return \Business\ConfigPaymentProcessor	Config Payment Processor
	 */
	function internalProcessPayment();
	/**
	 * This method will treat the result
	 * This method has to be implement by every inherited class
	 */
	function internalTreatResult();
	/**
	 * Check the validity of the answer
	 */
	function internalCheckAnswer();
	/**
	 * Configure the object with the answer
	 */
	function internalLoadDataFromAnswer();
}

/**
 * Description of PaymentProcessor
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
abstract class PaymentProcessor implements \Business\PaymentProcessorInterface
{
	/**
	 * Transaction status :
	 */
	const TRANS_STATUS_PENDING		= 'TRANS_STATUS_PENDING';
	const TRANS_STATUS_VALIDATED	= 'TRANS_STATUS_VALIDATED';
	const TRANS_STATUS_CANCEL		= 'TRANS_STATUS_CANCEL';
	const TRANS_STATUS_ERROR		= 'TRANS_STATUS_ERROR';
	const TRANS_ERR_BAD_SIGNATURE	= 'TRANS_ERR_BAD_SIGNATURE';

	/**
	 * Label du processeur de paiement ( affiché sur la page du processeur )
	 * @var string	labelPaymentProcessorPage
	 */
	protected $labelPaymentProcessorPage;
	/**
	 * Nom du processeur de paiement
	 * @var string	PaymentProcessor Name
	 */
	protected $name;
	/**
	 * Type de paiement ( 0 => normal, 1 => check )
	 * @var int	Type
	 */
	protected $type;
	/**
	 * Prefix pour la ref Interne
	 * @var int	Type
	 */
	protected $prefix;
	/**
	 * Invoice instance
	 * @var \Business\Invoice
	 */
	protected $Invoice;
	/**
	 * testMode uses to define in wich mod is the payment
	 * @var boolean
	 */
	protected $testMode;
	/**
	 * Defines the mercantID
	 * @var string
	 */
	protected $mercantId;
	/**
	 * Defines the sub mercantID
	 * @var string
	 */
	protected $subMercantId;
	/**
	 * Defines the data integrity code. This code is used to secure the data communication
	 * @var string
	 */
	protected $dataIntegrityCode;
	/**
	 * Defines the language to use in the bank website.
	 * @var string
	 */
	protected $language;
	/**
	 * Defines the currency to use
	 * @var string
	 */
	protected $currency;
	/**
	 * Amount of the invoice
	 * @var float
	 */
	protected $amount;
	/**
	 * Defines the submitter to use
	 * @var string
	 */
	protected $submitter;
	/**
	 * Store the status normalized
	 * This variable contains the values define in the begin of this file and beginning by TRANS_STATUS_...
	 * @var string
	 */
	protected $status;
	/**
	 * Store the error code normalized
	 * @var int
	 */
	protected $errorCode;
	/**
	 * Store the error message from the payment processor
	 * @var string
	 */
	protected $errorMessage;
	/**
	 * Url de callback en cas de payment avec succes
	 * @var string	Url
	 */
	protected $callBackUrlOK;
	/**
	 * Added by Marouane FIKRI 14/03/2016
	 * Url de de retour vers le site inst
	 * @var string	Url
	 */
	protected $backUrl;
	/**
	 * Url de callback en cas d'erreur de payment
	 * @var string	Url
	 */
	protected $callBackUrlNOK;
	/**
	 * Url de callback de traitement
	 * @var string	Url
	 */
	protected $callBackUrlTreatment;

	/**
	 * langue du processeur de paiement
	 * @var string	langPP
	 */
	protected $langPP;
	/**
	 * Constructeur
	 * @param int $idInvoice
	 * @param \Business\PaymentProcessorType $Type
	 */
	public function __construct( $idInvoice, $Type )
	{
		$this->testMode						= false;
		$this->Invoice						= \Business\Invoice::load( $idInvoice );
		$this->name							= $Type->name;
		$this->type							= $Type->type;
		$this->language						= $this->Invoice->codeSite;
		$this->currency						= strtoupper( $this->Invoice->currency );
		$this->amount						= $this->Invoice->getTotalInvoice();
		$this->status						= self::TRANS_STATUS_PENDING;
		$this->errorCode					= 0;

		// Callback :
		
		
		

		$ConfDNS = \Business\Config::loadByKey( 'DNS' );
		$this->backUrl		= $ConfDNS->value;
		
		//Traitement Anaconda Cron Deliver Stand By :: Added by Yacine RAMI
		if(!isset($_SERVER['SERVER_NAME']))
		{
			$this->callBackUrlOK		= ( $this->backUrl.'/index.php/Callback/paymentValidate/'.$this->Invoice->id );
			$this->callBackUrlNOK		= ( $this->backUrl.'/index.php/Callback/paymentFail/'.$this->Invoice->id );
			$this->callBackUrlTreatment	= ( $this->backUrl.'/index.php/Callback/paymentTreatment/'.$this->Invoice->id );
		}
		else 
		{
			$this->callBackUrlOK		= ( \Yii::app()->getBaseUrl(true).'/index.php/Callback/paymentValidate/'.$this->Invoice->id );
			$this->callBackUrlNOK		= ( \Yii::app()->getBaseUrl(true).'/index.php/Callback/paymentFail/'.$this->Invoice->id );
			$this->callBackUrlTreatment	= ( \Yii::app()->getBaseUrl(true).'/index.php/Callback/paymentTreatment/'.$this->Invoice->id );
		}

		// Amount test :
		if( !$Type->isFreePayment() && $this->amount <= 0 )
			throw new \EsoterException( 108, \Yii::t( 'error', '108' ) );
	}

	/**
	 * Engage the payment process
	 * @return \Business\ConfigPaymentProcessor	Config Payment Processor
	 */
	public function engagePayment()
	{
	// ******* Script add by Jalal *********** //
	
		// Si le type est SYNCHRONE ( CB, ... )
		if( in_array( $this->type, \Business\PaymentProcessorType::$typeSync ) )
		{
			$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_ABANDON_PANIER );
		}
		
	// ***** //	
		return $this->internalProcessPayment();
	}

	/**
	 * Method that treat return of the payment
	 * All the information are store in the Get or Post Variable
	 * This method call the InternalTreatResult implement in the inherited class
	 */
public function treatResult(){
		\Yii::import( 'ext.AnacondaBehavior' );
		
		
		
		//recuperer la campaignHistory correspondante s il s agit d'une fid anaconda
		$email=$this->Invoice->emailUser;
		$record=$this->Invoice->RecordInvoice;
		$product=\Business\Product::loadByRef($record[0]->refProduct);
		$subCampaign=\Business\SubCampaign::loadByProduct($product->id);
		$campaign= \Business\Campaign::load( $subCampaign->idCampaign );
		
		if($this->internalCheckAnswer()){
			
			if( ($this->status == \Business\PaymentProcessor::TRANS_STATUS_PENDING || $this->status == \Business\PaymentProcessor::TRANS_STATUS_VALIDATED) && $this->internalTreatResult() )
			{
				if (isset($campaign->isAnaconda) && $campaign->isAnaconda==1)
				{
					$user=\Business\User::loadByEmail($email);
					//mise a jour du indice d'implication
					$user->updateIndiceImplication($this->Invoice->priceStep, $subCampaign->position);
					$campaignHistory=\Business\CampaignHistory::loadByUserAndSubCampaign($user->id, $subCampaign->id);
					echo $campaignHistory->id;
					if(isset($campaignHistory))
					{
						$dateToday = new \DateTime();
						$dateShoot = new \DateTime();
						$dateLivraison = new \DateTime();
						//mise a jour de la campaign history
						
						if($subCampaign->isInter())
						{
							$lastInter=$campaignHistory->getLastPurshasedInter();
							if($lastInter)
							{
								$dateShoot=new \DateTime($lastInter->deliveryDate);
								$dateLivraison=new \DateTime($lastInter->deliveryDate);
								$dateLivraison->add( new \DateInterval('P1D') );
								$s=1;
							}
							else 
							{
								$dateLivraison->add( new \DateInterval('P1D') );
								if(!$campaignHistory->hasPurshasedSixHour($dateShoot))
								{
									$s=2;
								}
								else
								{
									$s=1;
								}
							}
						}
						else 
						{
							if(!$campaignHistory->hasPurshasedSixHour($dateShoot) &&  !$campaignHistory->hasPurshasedInterNextSixHour($dateShoot))
							{
								$s=2;
							}
							else
							{
								$s=1;
							}
						}
						$campaignHistory->deliveryDate=$dateLivraison->format('Y-m-d');
						$campaignHistory->modifiedShootDate=$dateShoot->format('Y-m-d');
						if($campaignHistory->hasPurshasedInterInSixHour($dateLivraison))
						{
							$campaignHistory->behaviorHour=$dateToday->format('H')+3;
							$s=1;
						}
						else
						{
							$campaignHistory->behaviorHour=$dateToday->format('H');
						}
						$campaignHistory->status=$s;
						$campaignHistory->save();
						if($s==2)
						{
							\AnacondaBehavior::execWebFormPayment($campaignHistory);
							
						}
					}
						
				}
				
				// Si le type est SYNCHRONE ( CB, ... )
					
				if( in_array( $this->type, \Business\PaymentProcessorType::$typeSync ) ){
					// mail('sd.mimouni@gmail.com', 'RETOUR DU WEBFORM 3', print_r(\Business\RouterEMV::URL_PAIEMENT, 1)); 
					$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_PAIEMENT );
				}
				else if( in_array( $this->type, \Business\PaymentProcessorType::$typeAsync ) ){
					if($this->type == 4){
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_PAYMENT_MULTIBANCO );
					}
					else if($this->type == 9){
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_INTENTION_BOLETO );
					}
					else{
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_INTENTION_CHEQUE );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE1 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE2 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE3 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE4 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE5 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE6 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE7 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE8 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE9 );
					}
					
					
				}else if(in_array( $this->type, \Business\PaymentProcessorType::$typefree)){
					$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_PAIEMENT_VG );
				}
			}elseif($this->type == 3 && $this->status == \Business\PaymentProcessor::TRANS_STATUS_ERROR){
				$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_PAYMENT_DM );
			}
			if(isset($campaignHistory))
			{
				if($subCampaign->position==1 && $campaign->hasCT())
				{
					//traitement P2
					\AnacondaBehavior::pauseLastFid($campaignHistory);
					\AnacondaBehavior::passNextProduct($campaignHistory);
				}
				else
				{
					\AnacondaBehavior::passInterFidPayed($campaignHistory);
				}
			}
		}
		else
		{
			$this->status 		= self::TRANS_STATUS_ERROR;
			$this->errorMessage = self::TRANS_ERR_BAD_SIGNATURE;
		}
		//mail('othmane.halhouli@kindyinfomaroc.com', 'RETOUR DU WEBFORM 4', print_r(\Business\RouterEMV::URL_PAIEMENT));
		return $this->updateInvoiceStatus();
	}

	/**
	 * Update the invoice status
	 */
	//=============================================== Anaconda Treat Result Stand By ===============================================================//
	/**
	 * Method that treat return of the payment for Anaconda Stand By Deliveries
	 */
	public function treatResultStandBy(){

		if($this->internalCheckAnswer()){
			if( ($this->status == \Business\PaymentProcessor::TRANS_STATUS_PENDING || $this->status == \Business\PaymentProcessor::TRANS_STATUS_VALIDATED) && $this->internalTreatResult() )
			{
				// Si le type est SYNCHRONE ( CB, ... )
				if( in_array( $this->type, \Business\PaymentProcessorType::$typeSync ) ){
					$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_PAIEMENT );
				}
				else if( in_array( $this->type, \Business\PaymentProcessorType::$typeAsync ) ){
					if($this->type == 4){
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_PAYMENT_MULTIBANCO );
					}else{
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_INTENTION_CHEQUE );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE1 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE2 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE3 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE4 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE5 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE6 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE7 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE8 );
						$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_RELANCE_CHEQUE9 );
					}
						
						
				}else if(in_array( $this->type, \Business\PaymentProcessorType::$typefree)){
					$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_PAIEMENT_VG );
				}
			}elseif($this->type == 3 && $this->status == \Business\PaymentProcessor::TRANS_STATUS_ERROR){
				$this->Invoice->sendRequestToEMV( \Business\RouterEMV::URL_PAYMENT_DM );
			}
		}
		else
		{
			$this->status 		= self::TRANS_STATUS_ERROR;
			$this->errorMessage = self::TRANS_ERR_BAD_SIGNATURE;
		}
		return true;
	}
	
	//==============================================================================================================================================//
	
	/**
	 * Update the invoice status
	 */
	
	protected function updateInvoiceStatus()
	{
		$this->Invoice->modificationDate = date( \Yii::app()->params['dbDateTime'] );

		switch( $this->status )
		{
			case self::TRANS_STATUS_PENDING :
				$this->Invoice->invoiceStatus	= \Business\Invoice::INVOICE_IN_PROGRESS;
				break;
			case self::TRANS_STATUS_VALIDATED :
				$this->Invoice->invoiceStatus	= \Business\Invoice::INVOICE_PAYED;
				break;
			case self::TRANS_STATUS_CANCEL :
				$this->Invoice->invoiceStatus	= \Business\Invoice::INVOICE_CANCEL;
				$this->Invoice->errorMessage	= $this->errorMessage;
				break;
			case self::TRANS_STATUS_ERROR :
			case self::TRANS_ERR_BAD_SIGNATURE:
				$this->Invoice->errorMessage	= $this->errorMessage;
				$this->Invoice->invoiceStatus	= \Business\Invoice::INVOICE_ERROR;
				break;
		}
		// mail('sd.mimouni@gmail.com', 'RETOUR DU WEBFORM 4', print_r($this->Invoice, true)); 
		return $this->Invoice->save();
	}
	
	
	

	/**
	 * Verifie que les parametres necessaires a la config du processeur existe
	 * Et les configure si c'est le cas
	 * @param \Business\PaymentProcessorType $Type
	 * @param array $tab
	 */
	protected function verifyParam( $Type, $tab )
	{
		foreach( $tab as $name )
		{
			if( $Type->getParam( $name ) === false )
				throw new \EsoterException( 12, \Yii::t( 'error', '12' ).' : '.$name );
			else if( property_exists( $this, $name ) )
				$this->$name = $Type->getParam ( $name );
			else
				throw new \EsoterException( 12, \Yii::t( 'error', '12' ).' : '.$name.' ?!?' );
		}

		return true;
	}

	// *************************** SETTER / GETTER *************************** //
	public function getLabel()
	{
		return $this->label;
	}
	public function getName()
	{
		return $this->name;
	}
	public function getTestMode()
	{
		return $this->testMode;
	}
	public function setTestMode($testMode)
	{
		$this->testMode = $testMode;
	}
	public function getLanguage()
	{
		return $this->language;
	}
	public function setLanguage($language)
	{
		$this->language = $language;
	}
	public function getType()
	{
		return $this->type;
	}
	public function getCurrency()
	{
		return $this->currency;
	}
	public function setCurrency($currency)
	{
		$this->currency = $currency;
	}
	public function getCallBackUrlOK()
	{
		return $this->callBackUrlOK;
	}
	public function setCallBackUrlOK($callBackUrlOK)
	{
		$this->callBackUrlOK = $callBackUrlOK;
	}

	//added by Marouane FIKRI 15/03/2015
	public function getBackUrl()
	{
		return $this->backUrl;
	}	
	public function setBackUrl()
	{
		$this->$backUrl = $backUrl;
	}
	//fin
	
	public function getCallBackUrlNOK()
	{
		return $this->callBackUrlNOK;
	}
	public function setCallBackUrlNOK($callBackUrlNOK)
	{
		$this->callBackUrlNOK = $callBackUrlNOK;
	}
	public function getCallBackUrlTreatment()
	{
		return $this->callBackUrlTreatment;
	}
	public function setCallBackUrlTreatment($callBackUrlTreatment)
	{
		$this->callBackUrlTreatment = $callBackUrlTreatment;
	}
	/**
	 * @return \Business\Invoice
	 */
	public function getInvoice()
	{
		return $this->Invoice;
	}
	public function getStatus()
	{
		return $this->status;
	}

	// *************************** STATIC *************************** //
	/*
	 * Factory : Retourne l'instance du PaymentProcessor en fonction du processor de paiement desiré
	 * @param	string	$ref	PaymentProcessor
	 * @param	int	$idInvoice	ID de l'invoice
	 * @return	\Business\PaymentProcessor	Instance du processeur de paiement
	 */
	static public function loadByRef( $refPP, $idInvoice )
	{
		if( !($Type = \Business\PaymentProcessorType::loadByRef($refPP)) )
			return false;

		$className = '\\Business\\'.$Type->className;
		if( class_exists($className) )
			return new $className( $idInvoice, $Type );
		else
			throw new \EsoterException( 107, \Yii::t( 'error', '107' ) );

		return false;
	}

	/*
	 * Factory : Retourne l'instance du PaymentProcessor en fonction d'u processor de paiement desiré'une Invoice
	 * @param	int	$idInvoice	ID de l'invoice
	 * @return	\Business\PaymentProcessor	Instance du processeur de paiement
	 */
	static public function loadByInvoice( $idInvoice )
	{
		if( !($Invoice = \Business\Invoice::load($idInvoice)) )
			return false;

		if( !($Type = \Business\PaymentProcessorType::loadByRef($Invoice->paymentProcessor)) )
			return false;

		$className = '\\Business\\'.$Type->className;
		if( class_exists($className) )
			return new $className( $idInvoice, $Type );
		else
			throw new \EsoterException( 107, \Yii::t( 'error', '107' ) );

		return false;
	}
	
	/*
	 * Factory : Retourne la convertion en base 36
	 * @param	int	$idInvoice	ID de l'invoice
	 * @return	chaine de caractére 
	 */
	
	static public function ConvertToBase36($idInvoice) 
	{
		$base = 36;
		$divEntier = $multiple = $idInvoice;
		$reste = 0;
		$tab = array();
		$asciiA = ord("A") -10;
		$nbBase36 = "";
		$compteur = 0; // Juste pour ne pas planter le serveur

		while($multiple > 0 && $compteur < 10) {
			$divEntier = floor($multiple/$base);
			$reste = $multiple -$divEntier *$base;
			$multiple = $divEntier;
			$nbBase36 = ($reste <10 ? $reste : chr($reste +$asciiA)) .$nbBase36;

			$compteur += 1;
		}
		return $nbBase36;
	}
	
}

?>