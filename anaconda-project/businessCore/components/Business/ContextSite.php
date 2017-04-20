<?php

namespace Business;

\Yii::import( 'ext.DateHelper' );

/**
 * Description of ContextSite
 *
 * @author JulienL
 * @package Business.Context
 */
class ContextSite extends \Business\Context
{
	// Parametre GET attendu :
	const SITE			= 'site';
	const MAIL			= 'm';
	const FIRSTNAME		= 'p';
	const LASTNAME		= 'n';
	const CIVILITY		= 'x';
	const SEX			= 'sex';
	const ADRESSE		= 'chad';
	const CP			= 'chcp';
	const CITY			= 'chci';
	const COUNTRY		= 'chco';
	const PHONE			= 'pho';
	const BIRTHDAY		= 'd';
	const OPTIN			= 'o';
	const OPT_PARTNER	= 'op';
	const B_HOUR_FULL	= 'h';
	const B_HOUR		= 'bh';
	const B_MINUTE		= 'bm';
	const SIGN			= 'asnb';
	const WISH		    = 's';
	const VOYANCE		= 'v';

	const NUMBER		= 'tr';
	const PRINCING_GRID	= 'gp';
	const BATCHSELLING	= 'bs';
	const CAMPAIGN		= 'ref';
	const SUB_POSITION	= 'sp';
	const ABANDON_PANIER= 'ap';
	const PRODUCT_CHAINEE = 'pdt';

	
	/**
	 * User
	 * @var \Business\User
	 */
	protected $User				= false;
	/**
	 * Product
	 * @var \Business\Product
	 */
	protected $Product			= false;
	/**
	 * Campaign
	 * @var \Business\SubCampaign
	 */
	protected $SubCampaign		= false;
	/**
	 * Site
	 * @var \Business\Site
	 */
	protected $Site				= false;
	/**
	 * PriceEngine
	 * @var \Business\PriceEngine
	 */
	 /**
	 * Numchance
	 * @var \Business\Numchance
	 */
	protected $Numchance		= false;
	protected $PriceEngine		= false;

	// Params propre au contexte :
	protected $voyance			= false;
	protected $wish				= false;
	protected $birthdayHourFull	= false;
	protected $birthdayHour		= false;
	protected $birthdayMinute	= false;
	protected $signNumber		= false;
	protected $age				= false;

	/**
	 * Charge le contexte pour les pages Lettre de vente, pages produits, ...
	 * Transforme les parametres, cherche l'user, la subCampaign, le produit et le priceEngine en fct des parametres
	 * @param	int		$context	Context de chargement ( GET, POST, ... )
	 * @return	bool	True / False
	 */
	public function loadContext( $context = self::TYPE_GET )
	{
	
		$mail		= $this->getContextVar( self::MAIL, $context );
		$position	= $this->getContextVar( self::SUB_POSITION, $context );
		$refCamp	= $this->getContextVar( self::CAMPAIGN, $context );
		$codeSite	= $this->getContextVar( self::SITE, $context );
		
		if($codeSite == '' || $codeSite == NULL){
			$codeSite = \Yii::app()->params['lang'];
		}
		

		$bs		= $this->getContextVar( self::BATCHSELLING, $context );
		$tr		= $this->getContextVar( self::NUMBER, $context );
		$gp		= $this->getContextVar( self::PRINCING_GRID, $context );
		$ap  	= $this->getContextVar( self::ABANDON_PANIER, $context );
		$sp  	= $this->getContextVar( self::SUB_POSITION, $context );
		$pdt  	= $this->getContextVar( self::PRODUCT_CHAINEE, $context );
		
		if(isset($pdt) && $pdt == 'ch')
			$refCamp	= $this->getContextVar( self::CAMPAIGN, $context ).'a';
		
		$porteur = \Yii::app()->params['porteur'];
		
		if($GLOBALS['porteurRedirectV1'][$porteur]){
			
			$porteur    = $GLOBALS['porteurMap'][$porteur];
			$host       = $_SERVER['HTTP_HOST'];
			$Urlparams  = $_SERVER['REQUEST_URI'];
			$chain_ref  = 'ref='.$refCamp;
			$params     = str_replace($chain_ref,'',parse_url($Urlparams)['query']);
			$chain_sp   = 'sp='.$sp;
			$params     = str_replace($chain_sp,'',$params);
			$params     = str_replace('&&','&',str_replace('&&','&',$params));
			
			// Tester si la page AP
			$Camp     = \Business\Campaign::loadByRef( $refCamp );
			$idCamp	  = $Camp->id;
		
			$this->SubCampaign	= \Business\SubCampaign::loadByCampaignAndPosition( $idCamp, $position );
                if( !is_object($this->SubCampaign) )
					return false;

			$this->Product = $this->SubCampaign->Product;
			if( !is_object($this->Product) )
				return false;
				
			
			if(stripos($params, 'ap=1'))
			{		
				if($sp == 1 && strpos($this->Product->description, 'M1') == false  && strpos($this->Product->description, 'M2') == false ){
					$product = $refCamp.'ap';
				}else{
					$product = $refCamp.'ctap';	
				}			
			}else{
			// Tester si la page pro ou ldv	
				if($tr == 111){
					if($sp == 1 && strpos($this->Product->description, 'M1') == false  && strpos($this->Product->description, 'M2') == false ){
						$product = $refCamp.'pro';
					}else{
						$product = $refCamp.'ctpro';	
					}
				}else{		
					if($sp == 1 && strpos($this->Product->description, 'M1') == false  && strpos($this->Product->description, 'M2') == false ){
						$product = $refCamp.'ldv';	
					}else{
						$product = $refCamp.'ctldv';	
					}	
				}
			}	
			$url 	 = $host.'/'.$porteur.'/index.php?c='.$product.'&'.$params;	
			header("Location: http://".$url);
		    \Yii::app()->end();
		}
		
		$this->codeSite	 = $codeSite;
		$this->gp	     = $gp;
		$this->tr	     = $tr;
		$this->ap	     = $ap;
		$this->sp	     = $sp;
		$this->bs	     = $bs;

		if($mail == '')
	    {
			  $this->User	= new \Business\User();
		  	  $this->User->firstName = '';
			  $this->User->lastName  = '';
			  if($GLOBALS['porteurMail'][$porteur]){
			  		$this->User->email  = $GLOBALS['porteurMail'][$porteur];		
			  }
			  if($GLOBALS['porteurCompteEMVactif'][$porteur]){
			  		$this->User->compteEMVactif  = $GLOBALS['porteurCompteEMVactif'][$porteur];		
			  }
			  
	    }else{
		      $this->User = \Business\User::loadByEmail( $mail );
		}
		
		if( !is_object($this->User)){
		   $User_V1	     = \Business\User_V1::loadByEmail( $mail );


		   if(is_object($User_V1)){
		   
			   $this->User	= new \Business\User( 'search' );
			  
			  
			   $this->User->firstName = $User_V1->Firstname;
		       $this->User->lastName  = $User_V1->Lastname;
			   $this->User->email     = $User_V1->Email;
			   $this->User->civility  = $User_V1->Civility;   
			   $this->User->birthday  = $User_V1->Birthday;
			   $this->User->address   = $User_V1->Address;			  
			   $this->User->zipCode   = $User_V1->CP;
			   $this->User->city      = $User_V1->City;
			   $this->User->country   = $User_V1->Country;
			   $this->User->phone     = $User_V1->Phone;
			   $this->User->optin     = $User_V1->Optin;
			   $this->User->compteEMVactif  = $User_V1->CompteEMVactif;
			   
		   }
		}
		
		if( !is_object($this->User)){
		   
		   $this->User	= new \Business\User( 'search' );
		   $this->User->firstName = '';
		   $this->User->lastName  = '';
		   $this->User->email  = $mail;
		   if($GLOBALS['porteurCompteEMVactif'][$porteur]){
				$this->User->compteEMVactif  = $GLOBALS['porteurCompteEMVactif'][$porteur];		
		   }
		   
		}
				//  Traitement de prenom Si l'utilisateur existe à la table V2_User
		elseif($this->User->firstName=="" || $this->getContextVar( self::FIRSTNAME, $context )=="" )
		{
			if($this->User->civility==1 || $this->getContextVar( self::CIVILITY, $context )==1)
			
				$this->User->firstName =\Yii::t('PrenomTrad','Mr');
			 else			 
				$this->User->firstName = \Yii::t('PrenomTrad','Mme');
		}
		/********/
		$Camp     = \Business\Campaign::loadByRef( $refCamp );
		$idCamp	  = $Camp->id;
		
		$this->SubCampaign	= \Business\SubCampaign::loadByCampaignAndPosition( $idCamp, $position );
                if( !is_object($this->User) || !is_object($this->SubCampaign) )
			return false;

		$this->Product = $this->SubCampaign->Product;
		if( !is_object($this->Product) )
			return false;

		$this->Site	= \Business\Site::loadByCode( $codeSite );
		if( !is_object($this->Site) )
			return false;

		$this->PriceEngine	= \Business\PriceEngine::get( $this->Product->priceModel, $this->Product->paramPriceModel, $this->User, $bs, $tr, $gp, $this->Site->id, $this->ap);
		if( !is_object($this->PriceEngine) )
			return false;
			
		// Traiter le format BIRTHDAY pour Aasha/Alisha format retourner par EMV M/D/Y
		$porteur = \Yii::app()->params['porteur'];
		
		if( strpos( $porteur, 'aasha' ) !== false || strpos( $porteur, 'alisha' ) !== false || strpos( $porteur, 'dk_laetizia' ) !== false || strpos( $porteur, 'in_laetizia' ) !== false)
		{
			$tab_date = explode("/",$this->getContextVar( self::BIRTHDAY, $context ));
			
			if(!empty($tab_date[0]) && !empty($tab_date[1]) && !empty($tab_date[2]) ){	
				if(checkdate($tab_date[0], $tab_date[1], $tab_date[2]))
					$_GET['d'] = $tab_date[1].'/'.$tab_date[0].'/'.$tab_date[2];
			}
		}else{
				
				    $_GET['d'] = $this->getContextVar( self::BIRTHDAY, $context );
		}

		// Mise a jours des parametres propre au contexte :
		$this->age				= \DateHelper::getAge( $this->getContextVar( self::BIRTHDAY, $context ) );
		$this->signNumber		= \DateHelper::getAstroSign( $this->getContextVar( self::BIRTHDAY, $context ) );
		$this->voyance			= $this->getContextVar( self::VOYANCE, $context );
		$this->wish				= $this->getContextVar( self::WISH, $context );
		$this->birthdayHourFull	= $this->getContextVar( self::B_HOUR_FULL, $context );
		$this->birthdayHour		= $this->getContextVar( self::B_HOUR, $context );
		$this->birthdayMinute	= $this->getContextVar( self::B_MINUTE, $context );
		$this->signNumber		= $this->getContextVar( self::SIGN, $context );

		if( strpos( $this->birthdayHourFull, ':' ) !== false )
		{
			$hourFull				= explode( ':', $this->birthdayHourFull );
			$this->birthdayHour		= $hourFull[0];
			$this->birthdayMinute	= $hourFull[1];
		}

		// Mise a jours des parametres propre au l'utilisateur :
		return $this->updateUser( $context );
	}

	/**
	 * Permet de mettre a jour l'utilisateur en DB par rapport au Parametre reçu
	 * @param array $Params	Tableau de parametres
	 * @return bool	true / false
	 */
	private function updateUser( $context )
	{
		static $map	= array(
			self::FIRSTNAME		=> 'firstName',
			self::LASTNAME		=> 'lastName',
			self::CIVILITY		=> 'civility',
			self::ADRESSE		=> 'address',
			self::CP			=> 'zipCode',
			self::CITY			=> 'city',
			self::COUNTRY		=> 'country',
			self::PHONE			=> 'phone',
			self::BIRTHDAY		=> 'birthday',
			self::OPTIN			=> 'optin',
			self::OPT_PARTNER	=> 'optinPartner'
		);

		$update = false;
		foreach( $map as $k => $v )
		{
			$valParam = $this->getContextVar( $k, $context );
			if( $valParam && !empty($valParam) && $valParam != $this->User->$v )
			{
				$this->User->$v = $valParam;
				$update			= true;
			}
		}

		return true;
	}

	// ************************************ GETTER ************************************ //
	/**
	 *
	 * @return \Business\Numchance
	 */
	public function getNumchance()
	{
		return $this->Numchance;
	}
	/**
	 *
	 * @return \Business\User
	 */
	public function getUser()
	{
		return $this->User;
	}
	/**
	 *
	 * @return \Business\Product
	 */
	public function getProduct()
	{
		return $this->Product;
	}
	/**
	 *
	 * @return \Business\SubCampaign
	 */
	public function getSubCampaign()
	{
		return $this->SubCampaign;
	}
	/**
	 *
	 * @return \Business\PriceEngince
	 */
	public function getPriceEngine()
	{
		return $this->PriceEngine;
	}
	/**
	 *
	 * @return \Business\Campaign
	 */
	public function getCampaign()
	{
		return ( is_object($this->SubCampaign) ) ? $this->SubCampaign->Campaign : false;
	}
	/**
	 *
	 * @return \Business\SubCampaignReflation
	 */
	public function getSubCampaignReflation()
	{
		if( !is_object($this->SubCampaign) || !is_object($this->PriceEngine) || $this->PriceEngine->getPriceStep() < 0 )
			return false;

		return $this->SubCampaign->getSubCampaignReflationByNumber( $this->PriceEngine->getPriceStep() );
	}

	/**
	 *
	 * @return \Business\Site
	 */
	public function getSite()
	{
		return $this->Site;
	}

	public function getVoyance()
	{
		return $this->voyance;
	}

	public function getWish()
	{
		return $this->wish;
	}

	public function getBirthdayHourFull()
	{
		return $this->birthdayHourFull;
	}

	public function getBirthdayHour()
	{
		return $this->birthdayHour;
	}

	public function getBirthdayMinute()
	{
		return $this->birthdayMinute;
	}

	public function getSignNumber()
	{
		return $this->signNumber;
	}

	public function getAge()
	{
		return $this->age;
	}

	//*************************** STATIC ***************************//
	/**
	 * Retourne les parametres GET utilisé pour ce context en recuperant les infos d'une commande
	 * @param \Business\Invoice $Invoice
	 * @return string Chaine representant les parametres GET.
	 */
	public static function createContextGetFromInvoice( $Invoice )
	{
		if( !is_object($Invoice) )
			return false;
			
		$param	= array(
			self::MAIL			=> $Invoice->User->email,
			self::SITE			=> $Invoice->codeSite,
			self::BATCHSELLING	=> $Invoice->refBatchSelling,
			self::PRINCING_GRID	=> $Invoice->refPricingGrid,
			self::NUMBER		=> $Invoice->priceStep,
			//self::CAMPAIGN		=> $Invoice->RecordInvoice[0]->Product->SubCampaign->Campaign->id,
			self::CAMPAIGN		=> $Invoice->RecordInvoice[0]->Product->SubCampaign->Campaign->ref,
			self::SUB_POSITION	=> $Invoice->RecordInvoice[0]->Product->SubCampaign->position
		);

		$url = NULL;
		foreach( $param as $k => $v )
			$url .= $k.'='.$v.'&';

		return $url;
	}

	//By Ahmed LAM.
	public function GetPricingGrid($email, $refProd, $porteur){

		$porteurs = [
			"fr_rinalda" => false // is STC in V2 ?
		];

		$this->gprice = 1;

		$bool = 0;
		$sql_test_VG	= 	"SELECT SUM(p.isCT) AS GP,

					  (SELECT refPricingGrid
					   FROM invoice AS inv1
					   INNER JOIN internaute AS i1 ON i1.ID = inv1.IDInternaute
					   WHERE inv1.refProduct LIKE '%$refProd%'
					     AND i1.email = '$email'
					   ORDER BY inv1.ID ASC LIMIT 1) AS GP_prod,

					  (SELECT NULLIF(0,COUNT(*))
					   FROM invoice AS inv
					   INNER JOIN internaute AS i ON inv.IDInternaute = i.ID
					   WHERE i.email = '$email'
					     AND inv.RefProduct LIKE '%voygratuite') AS VG
					FROM invoice AS inv
					INNER JOIN internaute AS i ON i.ID = inv.IDInternaute
					INNER JOIN product AS p ON p.ref = inv.refproduct
					WHERE inv.unitprice >0
					  AND i.email = '$email'";
		$tab = \Yii::app()->db->createCommand($sql_test_VG)->queryRow();

		if($tab) $bool = (is_null($tab['VG']) OR $tab['VG'] == 0) ? 1 : 0;

		if(array_key_exists($porteur, $porteurs) && !$bool) {
			$sql	= 	"SELECT SUM(p.isCT) AS GP,

					  (SELECT refPricingGrid
					   FROM invoice AS inv1
					   INNER JOIN internaute AS i1 ON i1.ID = inv1.IDInternaute
					   WHERE inv1.refProduct LIKE '%$refProd%'
					     AND i1.email = '$email'
					   ORDER BY inv1.ID ASC LIMIT 1) AS GP_prod,

					  (SELECT NULLIF(0,COUNT(*))
					   FROM invoice AS inv
					   INNER JOIN internaute AS i ON inv.IDInternaute = i.ID
					   WHERE i.email = '$email'
					     AND inv.RefProduct LIKE '%voygratuite') AS VG
					FROM invoice AS inv
					INNER JOIN internaute AS i ON i.ID = inv.IDInternaute
					INNER JOIN product AS p ON p.ref = inv.refproduct
					WHERE inv.unitprice >0
					  AND i.email = '$email'";
		} else {
		    $sql = "SELECT sum(prod.isCT) AS GP, max(inv.refPricingGrid) AS GP_prod , NULLIF(0,COUNT(*)) AS VG
					   FROM V2_invoice AS inv
					   INNER JOIN V2_recordinvoice AS rec ON rec.idInvoice = inv.id
					   INNER JOIN V2_product AS prod ON prod.ref = rec.refProduct
					   INNER JOIN V2_user AS USER ON inv.emailUser = USER.email
					   WHERE rec.refProduct LIKE '%$refProd%'
					     AND inv.emailUser = '$email'
					     AND rec.priceATI > 0
					   ORDER BY inv.ID ASC";
		}

		$tab = \Yii::app()->db->createCommand($sql)->queryRow();

		if($tab){
			$array = ['GP_Prod' => $tab['GP_prod'], 'GP' => $tab['GP'], 'VG' => $tab['VG']];
			$this->gprice = $tab['GP_prod']>0?$tab['GP_prod']:(is_null($tab['GP'])?(is_null($tab['VG'])?1:2):($tab['GP']>0?4:3));
		}

		return $this->gprice;
	}
}
?>