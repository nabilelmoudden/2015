<?php

/**
 * Description of WebForm
 *
 * @author JulienL
 */
class WebForm
{
	/**
	 * Date format for EMV
	 */
	const EMV_FORMAT_DATE	= 'm/d/Y';

	/**
	 * Constante decrivant le resultat de l'execution d'un webform
	 */
	const RES_OK	= 'true';
	const RES_NOK	= 'false';

	/**
	 * Tableau contenant les infos du webform
	 * @var array
	 */
	private $webForm	= false;

	/**
	 * Token array
	 * @var array
	 */
	private $token		= array();

	/**
	 * Envoi de la requete en asynchrone
	 * @var bool
	 */
	private $async		= false;

	/**
	 * Constructeur
	 * @param string $urlWebForm
	 */
	public function __construct( $urlWebForm, $async = false )
	{
		\Yii::import( 'ext.CurlHelper' );

		$this->webForm	= $urlWebForm;
		$this->async	= $async;

		$Date			= new DateTime();
		$this->setToken( 'date', $Date->format( self::EMV_FORMAT_DATE ) );
		$this->setToken( 'h', $Date->format( 'H' ) );
		for( $i=1; $i<=7; $i++ )
		{
			$Date->add( new DateInterval('P1D') );
			$this->setToken( 'date+'.$i, $Date->format( self::EMV_FORMAT_DATE ) );
		}
	}

	/**
	 * Construit l'url du webform en remplacant les tokens
	 * @param bool $checkReplace
	 * @return string
	 * @throws \EsoterException
	 */
	public function constructUrl( $checkReplace ){
		$webForm = str_replace( array_keys($this->token), $this->token, $this->webForm );
		if( $checkReplace ){
			$matches	= array();
			$nbMatches	= preg_match_all( '/__([a-zA-Z0-9_+]+)__/im', $webForm, $matches, PREG_SET_ORDER );

			if( $nbMatches > 0){
				$error = array();
				foreach( $matches as $err )
					$error[] = $err[0];
				throw new \EsoterException( 300, \Yii::t( 'error', 300 ).' : '.  implode( ', ', $error ) );
			}
		}

		return $webForm;
	}

	/**
	 * Ajoute une valeur a un token
	 * Si key est un tableau, ajoute toutes les clefs/valeurs du tableau
	 * @param stirng|array $key
	 * @param mixed $val
	 * @return boolean
	 */
	public function setToken( $key, $val = false )
	{
		if( is_array($key) )
		{
			foreach( $key as $k => $v )
				$this->setToken( $k, $v );
		}
		else
			$this->token[ '__'.$key.'__' ] = $val;

		return true;
	}

	/**
	 * Ajoute les tokens d'un user
	 * @param \Business\User $User
	 */
	public function setTokenWithUser( \Business\User $User ){
		$this->setToken( 'x', $User->civility );
		$this->setToken( 'p', urlencode($User->firstName) );
		$this->setToken( 'n', urlencode($User->lastName) );
		$this->setToken( 'm', $User->email );
		$this->setToken( 'o', $User->optin );
		$this->setToken( 'b', $User->getBirthday( self::EMV_FORMAT_DATE ) );
		$this->setToken( 'ph',$User->phone );
		
		return true;
	}
	
	/**
	 * Ajoute les tokens d'un user
	 * @param \Business\User $User
	 */
	public function setTokenWithUser_V1( \Business\User_V1 $User , $ref = ''){
		$this->setToken( 'x', $User->Civility );
		$this->setToken( 'p', $User->Firstname );
		$this->setToken( 'n', $User->Lastname );
		$this->setToken( 'm', $User->Email );
		$this->setToken( 'b', $User->getBirthday( self::EMV_FORMAT_DATE ) );
		$this->setToken( 'c', $ref);
		return true;
	}

	/**
	 * Ajoute les tokens d'une commande
	 * @param \Business\Invoice $Invoice
	 */
	public function setTokenWithInvoice( \Business\Invoice $Invoice ){
		
		$this->setToken( 'gp', $Invoice->refPricingGrid );
		$this->setToken( 'pc', $Invoice->getTotalInvoice() );
		$this->setToken( 'cc', $Invoice->chrono );
		$this->setToken( 'site', $Invoice->codeSite );
		$this->setToken( 'ref', $Invoice->campaign );
		$this->setToken( 'rf', $Invoice->ref1Transaction );
		
		/****************  traitement anaconda******************/
		$email=$Invoice->emailUser;
		$record=$Invoice->RecordInvoice;
		$product=\Business\Product::loadByRef($record[0]->refProduct);
		$subCampaign=\Business\SubCampaign::loadByProduct($product->id);
		$campaign= \Business\Campaign::load( $subCampaign->idCampaign );
		if (isset($campaign->isAnaconda) && $campaign->isAnaconda==1)
		{
			$user=\Business\User::loadByEmail($email);
			$campaignHistory=\Business\CampaignHistory::loadByUserAndSubCampaign($user->id, $subCampaign->id);
			
			if(isset($campaignHistory))
			{
				$Date = new DateTime();
				$dateToday = $Date->format('Y-m-d');
				$hour = $Date->format( 'H' );
				//verifier s il, s agit d un achat stand by
				if(!is_null($campaignHistory) && $campaignHistory->status=1)
				{
					$arrayStatus=\Business\CampaignHistory::getAllProductsRef($user->id, $campaignHistory->deliveryDate, $hour);
					$deliveryDate=new \DateTime($campaignHistory->deliveryDate);
					$this->token[ '__date__' ] =$deliveryDate->format(self::EMV_FORMAT_DATE);
					for( $i=1; $i<=7; $i++ )
					{
						$deliveryDate->add( new DateInterval('P1D') );
						$this->token[ '__date+'.$i.'__' ] =$deliveryDate->format(self::EMV_FORMAT_DATE);
					}
					$this->token[ '__h__' ] =$campaignHistory->behaviorHour;
				}
				
				else 
				{
					$arrayStatus=\Business\CampaignHistory::getAllProductsRef($user->id, $dateToday, $hour);
				}
				
				$productsRef=$Invoice->getProductsRef();
				$status=$record[0]->refProduct;
				
				$this->setToken( 's', $status );
				
				
				if(!empty($arrayStatus))
				{
					foreach($arrayStatus as $s){
						if($s!=$productsRef[0]){
							$status.=','.$s;
						}
					}
				}
				
				$this->setToken( 'l', $status );
				
			}
			else
			{
				$status=$record[0]->refProduct;
				$this->setToken( 's', $status );
				$this->setToken( 'l', $status );
			}
		}
		
		/****************  /traitement anaconda******************/
		
		
		$RecordInvoiceAnnexe = $Invoice->RecordInvoice[0]->RecordInvoiceAnnexe;
		
		if(is_object($RecordInvoiceAnnexe)){
			
		if(isset($RecordInvoiceAnnexe->productExt->CTdate))
		{	
			$CtDates = $RecordInvoiceAnnexe->productExt->CTdate;
			$intEMVADMIN = 18;	
				foreach( $CtDates as $ctdate ){
	
					$this->setToken( 'EMVADMIN'.$intEMVADMIN, date( self::EMV_FORMAT_DATE, strtotime($ctdate)));
					$intEMVADMIN = $intEMVADMIN + 1;
				}
			}
		}
		
		return $this->setTokenWithUser( $Invoice->User );
	}
	
	/**
	 * Ajoute les tokens d'une commande
	 * @param \Business\Invoice $Invoice
	 */
	public function setTokenWithInvoice_V1( \Business\Invoice_V1 $Invoice){
		$this->setToken( 'gp', $Invoice->RefPricingGrid);
		$this->setToken( 'pc', $Invoice->getTotalInvoice() );
		$this->setToken( 'cc', $Invoice->Chrono );
		$this->setToken( 'site', $Invoice->Site );
		$product = \Business\Product_V1::loadByRef($Invoice->RefProduct);
		$this->setToken( 'ref', $product->WebSiteProductCode );
		
		
		return $this->setTokenWithUser_V1( $Invoice->User , $product->WebSiteProductCode);
	}

	/**
	 * Execute le webform et retourne le resultat
	 * Si $checkReplace = true, verifie que tous les tokens ai été remplacé
	 * @param bool $checkReplace
	 * @param bool $async Envoie asynchrone ( default = false )
	 * @return bool
	 */
	public function execute( $checkReplace = false ){
		$WF		= $this->constructUrl( $checkReplace );
		if( $this->async ){
			return \CurlHelper::sendRequestAsync( $WF );
		}else{
			$Curl	= new \CurlHelper();
			$Curl->setTimeout(CURL_TIMEOUT);
			return $Curl->sendRequest( $WF );
		}
		return true;
	}

	// *********************** STATIC *********************** //
	static public function getTokenHelp(){
		$html	= NULL;
		$html	.= '__date__ : Date execution ( '.self::EMV_FORMAT_DATE.' )<br />';
		$html	.= '__date+1__ : Date execution + 1 ( '.self::EMV_FORMAT_DATE.' )<br />';
		$html	.= '__date+2__ : Date execution + 2 ( '.self::EMV_FORMAT_DATE.' )<br />';

		$html	.= '__x__ : Civility<br />';
		$html	.= '__p__ : Firstname<br />';
		$html	.= '__n__ : Lastname<br />';
		$html	.= '__m__ : Email<br />';
		$html	.= '__o__ : Optin<br />';
		$html	.= '__b__ : Date of birthday ( '.self::EMV_FORMAT_DATE.' )<br />';
		$html	.= '__ph__ : Phone number<br />';

		$html	.= '__gp__ : Pricing group<br />';
		$html	.= '__pc__ : Total invoice<br />';
		$html	.= '__cc__ : Chrono<br />';
		$html	.= '__rf__ : Reference transaction<br />';
		$html	.= '__site__ : Code site';

		return $html;
	}
}

?>