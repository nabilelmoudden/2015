<?php
/**
 * Description of CallbackController
 *
 * @author JulienL
 * @package Controllers
 */
class CallbackController extends Controller
{
	/**
	 *
	 * @var \Business\PaymentProcessor
	 */
	private $PaymentProcessor	= false;

	public function init()
	{
		parent::init();

		// Defini la langue :
		\Yii::app()->setLanguage( \Yii::app()->params['lang'] );
		// Defini le dossier contenant les traductions : :
		\Yii::app()->messages->basePath = $this->portViewDir(true).'messages';

		// Recupere les parametres GET indispensable :
		if( \Yii::app()->request->getQuery('id') == NULL || \Yii::app()->request->getQuery('id') <= 0 )
		{throw new \EsoterException( 200, \Yii::t( 'error', '200' ) );}

		// Charge le processeur de paiement :
		$this->PaymentProcessor	= \Business\PaymentProcessor::loadByInvoice( \Yii::app()->request->getQuery('id') );
		if( !is_object($this->PaymentProcessor) )
		{throw new \EsoterException( 200, \Yii::t( 'error', '200' ) );}

		// Traite les donnés renvoyés par le PP :
		if( !$this->PaymentProcessor->internalLoadDataFromAnswer() || !$this->PaymentProcessor->internalCheckAnswer() )
		{throw new \EsoterException( 201, \Yii::t( 'error', '201' ) );}
	}

	public function actionPaymentValidate(){
		
		$p = \Yii::app()->request->getParam('p');
		
		if( $this->PaymentProcessor->getStatus() === \Business\PaymentProcessor::TRANS_STATUS_PENDING || $this->PaymentProcessor->getStatus() === \Business\PaymentProcessor::TRANS_STATUS_VALIDATED ){
			if( in_array( $this->PaymentProcessor->getType(), \Business\PaymentProcessorType::$typeAsync ) )
			{$url = \Yii::app()->baseUrl.'/index.php/site/ThankYouCheck/?idInvoice='.$this->PaymentProcessor->getInvoice()->id.'&'.\Business\ContextSite::createContextGetFromInvoice( $this->PaymentProcessor->getInvoice() ).'&p='.$p;}
			else{
				if( in_array( $this->PaymentProcessor->getType(), \Business\PaymentProcessorType::$typefree ) )
				{
					if(isset($_GET['emv8']))
					{
						if(Yii::app()->request->getParam( 'emv8' ) !== NULL ){
							$emv8 = Yii::app()->request->getParam( 'emv8' );
						}
					}
					else
					{
						$emv8 = 1;
					}
					$url = \Yii::app()->baseUrl.'/index.php/site/ThankYouVG/?idInvoice='.$this->PaymentProcessor->getInvoice()->id.'&'.\Business\ContextSite::createContextGetFromInvoice( $this->PaymentProcessor->getInvoice() ).'&p='.$p.'&emv8='.$emv8;
				}
				else
				{
					if(isset($_GET['emv8']))
					{
						if(Yii::app()->request->getParam( 'emv8' ) !== NULL ){
							$emv8 = Yii::app()->request->getParam( 'emv8' );
						}
					}
					else
					{
						$emv8 = 1;
					}
					$url = \Yii::app()->baseUrl.'/index.php/site/ThankYou/?idInvoice='.$this->PaymentProcessor->getInvoice()->id.'&'.\Business\ContextSite::createContextGetFromInvoice( $this->PaymentProcessor->getInvoice() ).'&p='.$p.'&emv8='.$emv8;
				}				
			}
			\Yii::app()->request->redirect( $url );
		}
		else{
			if($this->PaymentProcessor->getType() == 4){
				$url = \Yii::app()->baseUrl.'/index.php/site/ThankYouMB/?idInvoice='.$this->PaymentProcessor->getInvoice()->id.'&'.\Business\ContextSite::createContextGetFromInvoice( $this->PaymentProcessor->getInvoice() ).'&p='.$p;
				\Yii::app()->request->redirect($url);
			}
			else
			{$this->actionPaymentFail();}
		}
	}

	public function actionPaymentFail()
	{
		$p = \Yii::app()->request->getParam('p');
		
		if( $this->PaymentProcessor->getStatus() !== \Business\PaymentProcessor::TRANS_STATUS_PENDING )
		{
			if($this->PaymentProcessor->getType() == 3 && $this->PaymentProcessor->getStatus() === \Business\PaymentProcessor::TRANS_STATUS_ERROR )
			{		
				$url = \Yii::app()->baseUrl.'/index.php/site/ThankYouDm/?idInvoice='.$this->PaymentProcessor->getInvoice()->id.'&'.\Business\ContextSite::createContextGetFromInvoice( $this->PaymentProcessor->getInvoice() ).'&p='.$p;
		    }else
			{
				if(isset($_GET['emv8']))
				{
					if(Yii::app()->request->getParam( 'emv8' ) !== NULL ){
						$emv8 = Yii::app()->request->getParam( 'emv8' );
					}
				}
				else
				{
					$emv8 = 1;
				}
				$url = \Yii::app()->baseUrl.'/index.php/site/Failed/?idInvoice='.$this->PaymentProcessor->getInvoice()->id.'&'.\Business\ContextSite::createContextGetFromInvoice( $this->PaymentProcessor->getInvoice() ).'&p='.$p.'&emv8='.$emv8;
			}	
			\Yii::app()->request->redirect( $url );
		}
		else
		{$this->actionPaymentValidate();}
	}

	public function actionPaymentTreatment()
	{
		
		// Traite les resultats envoyé par le processeur de paiement
		if( !$this->PaymentProcessor->treatResult() )
		{throw new \EsoterException( 201, \Yii::t( 'error', '201' ) );}

		// Ajout d'un log :
		if( $this->PaymentProcessor->getInvoice()->invoiceStatus === \Business\Invoice::INVOICE_PAYED )
		{
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_TRANS_OK ) ) );
			
		}
		else
		{
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_TRANS_NOK ) ) );
			$this->actionPaymentFail();
		}
	}
}

?>
