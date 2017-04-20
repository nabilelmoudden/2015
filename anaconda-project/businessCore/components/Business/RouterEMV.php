<?php
//UPDATE `invoice` as i LEFT JOIN internaute as it ON it.id = i.idInternaute INNER JOIN debugSiteInstit as d ON d.EMAIL = it.Email SET i.site = 'mx'
namespace Business;

/**
 * Description of RouterEMV
 *
 * @author JulienL
 * @package Business.Campaign
 */
class RouterEMV extends \Routeremv
{
	/**
	 * Date format for Data Base
	 */
	const DB_FORMAT_DATE	= 'Y-m-d H:i:s';
	
	/**
	 * Type
	 */
	const URL_PAIEMENT			= 'UrlPaiement';
	const URL_INTENTION_CHEQUE	= 'UrlIntentionCheque';
	const URL_PAYMENT_CHEQUE	= 'UrlPaiementCheque';
	const URL_PAYMENT_DM		= 'UrlPaiementDm';
	const URL_PAYMENT_DINEROMAIL= 'UrlPaiementDineromail';
	const URL_PAYMENT_DINERO	= 'UrlPaiementDinero';
	
	//Multibanco
	const URL_PAYMENT_MULTIBANCO   = 'UrlPaiementMultibanco';
	const URL_INTENTION_MULTIBANCO = 'UrlIntentionMultibanco';
	//BOLETO
	const URL_PAIEMENT_BOLETO	= 'UrlPaiementBoleto';
	const URL_INTENTION_BOLETO	= 'UrlIntentionBoleto';
	
	const URL_ABANDON_PANIER	= 'UrlAbandonPanier';
	//const URL_RELANCE_CHEQUE	= 'UrlRelanceCheque';
	const URL_RELANCE_CHEQUE1	= 'UrlRelanceCheque1';
	const URL_RELANCE_CHEQUE2	= 'UrlRelanceCheque2';
	const URL_RELANCE_CHEQUE3	= 'UrlRelanceCheque3';
	const URL_RELANCE_CHEQUE4	= 'UrlRelanceCheque4';
	const URL_RELANCE_CHEQUE5	= 'UrlRelanceCheque5';
	const URL_RELANCE_CHEQUE6	= 'UrlRelanceCheque6';
	const URL_RELANCE_CHEQUE7	= 'UrlRelanceCheque7';
	const URL_RELANCE_CHEQUE8	= 'UrlRelanceCheque8';
	const URL_RELANCE_CHEQUE9	= 'UrlRelanceCheque9';
	// Dineromail
	const URL_RELANCE_DINEROMAIL1	= 'UrlRelanceDineromail1';
	const URL_RELANCE_DINEROMAIL2	= 'UrlRelanceDineromail2';
	const URL_RELANCE_DINEROMAIL3	= 'UrlRelanceDineromail3';
	const URL_RELANCE_DINEROMAIL4	= 'UrlRelanceDineromail4';
	const URL_RELANCE_DINEROMAIL5	= 'UrlRelanceDineromail5';
	const URL_RELANCE_DINEROMAIL6	= 'UrlRelanceDineromail6';
	const URL_RELANCE_DINEROMAIL7	= 'UrlRelanceDineromail7';
	const URL_RELANCE_DINEROMAIL8	= 'UrlRelanceDineromail8';
	const URL_RELANCE_DINEROMAIL9	= 'UrlRelanceDineromail9';
	
	// Miltibanco 
	const URL_RELANCE_MULTIBANCO1	= 'UrlRelanceMultibanco1';
	const URL_RELANCE_MULTIBANCO2	= 'UrlRelanceMultibanco2';
	const URL_RELANCE_MULTIBANCO3	= 'UrlRelanceMultibanco3';
	const URL_RELANCE_MULTIBANCO4	= 'UrlRelanceMultibanco4';
	const URL_RELANCE_MULTIBANCO5	= 'UrlRelanceMultibanco5';
	const URL_RELANCE_MULTIBANCO6	= 'UrlRelanceMultibanco6';
	const URL_RELANCE_MULTIBANCO7	= 'UrlRelanceMultibanco7';
	const URL_RELANCE_MULTIBANCO8	= 'UrlRelanceMultibanco8';
	const URL_RELANCE_MULTIBANCO9	= 'UrlRelanceMultibanco9';
	
	// Boleto
	const URL_RELANCE_BOLETO1	= 'UrlRelanceBoleto1';
	const URL_RELANCE_BOLETO2	= 'UrlRelanceBoleto2';
	const URL_RELANCE_BOLETO3	= 'UrlRelanceBoleto3';
	const URL_RELANCE_BOLETO4	= 'UrlRelanceBoleto4';
	const URL_RELANCE_BOLETO5	= 'UrlRelanceBoleto5';
	const URL_RELANCE_BOLETO6	= 'UrlRelanceBoleto6';
	const URL_RELANCE_BOLETO7	= 'UrlRelanceBoleto7';
	const URL_RELANCE_BOLETO8	= 'UrlRelanceBoleto8';
	const URL_RELANCE_BOLETO9	= 'UrlRelanceBoleto9';
	
	//STC
	const URL_PAIEMENT_VG			= 'UrlPaiementVG';

	/**
	 * Array of type
	 * @var array
	 */
	static public $tabType = array(
		self::URL_PAIEMENT				=> self::URL_PAIEMENT,
		self::URL_INTENTION_CHEQUE		=> self::URL_INTENTION_CHEQUE,
		self::URL_PAYMENT_CHEQUE		=> self::URL_PAYMENT_CHEQUE,
		self:: URL_PAYMENT_DM			=> self:: URL_PAYMENT_DM,
		self:: URL_PAYMENT_DINEROMAIL	=> self:: URL_PAYMENT_DINEROMAIL,
		self:: URL_PAYMENT_DINERO		=> self:: URL_PAYMENT_DINERO,
		
		self::URL_PAYMENT_MULTIBANCO	=> self::URL_PAYMENT_MULTIBANCO,
		self::URL_INTENTION_MULTIBANCO	=> self::URL_INTENTION_MULTIBANCO,
		
		//BOLETO
		self::URL_PAIEMENT_BOLETO	=> self::URL_PAIEMENT_BOLETO,
		self::URL_INTENTION_BOLETO	=> self::URL_INTENTION_BOLETO,
		
		self::URL_ABANDON_PANIER		=> self::URL_ABANDON_PANIER,
	  //self::URL_RELANCE_CHEQUE		=> self::URL_RELANCE_CHEQUE,
		self::URL_RELANCE_CHEQUE1		=> self::URL_RELANCE_CHEQUE1,
		self::URL_RELANCE_CHEQUE2		=> self::URL_RELANCE_CHEQUE2,
		self::URL_RELANCE_CHEQUE3		=> self::URL_RELANCE_CHEQUE3,
		self::URL_RELANCE_CHEQUE4		=> self::URL_RELANCE_CHEQUE4,
		self::URL_RELANCE_CHEQUE5		=> self::URL_RELANCE_CHEQUE5,
		self::URL_RELANCE_CHEQUE6		=> self::URL_RELANCE_CHEQUE6,
		self::URL_RELANCE_CHEQUE7		=> self::URL_RELANCE_CHEQUE7,
		self::URL_RELANCE_CHEQUE8		=> self::URL_RELANCE_CHEQUE8,
		self::URL_RELANCE_CHEQUE9		=> self::URL_RELANCE_CHEQUE9,
		// Dineromail
		self::URL_RELANCE_DINEROMAIL1		=> self::URL_RELANCE_DINEROMAIL1,
		self::URL_RELANCE_DINEROMAIL2		=> self::URL_RELANCE_DINEROMAIL2,
		self::URL_RELANCE_DINEROMAIL3		=> self::URL_RELANCE_DINEROMAIL3,
		self::URL_RELANCE_DINEROMAIL4		=> self::URL_RELANCE_DINEROMAIL4,
		self::URL_RELANCE_DINEROMAIL5		=> self::URL_RELANCE_DINEROMAIL5,
		self::URL_RELANCE_DINEROMAIL6		=> self::URL_RELANCE_DINEROMAIL6,
		self::URL_RELANCE_DINEROMAIL7		=> self::URL_RELANCE_DINEROMAIL7,
		self::URL_RELANCE_DINEROMAIL8		=> self::URL_RELANCE_DINEROMAIL8,
		self::URL_RELANCE_DINEROMAIL9		=> self::URL_RELANCE_DINEROMAIL9,
		
		// Miltibanco 
		self::URL_RELANCE_MULTIBANCO1	=> self::URL_RELANCE_MULTIBANCO1,
		self::URL_RELANCE_MULTIBANCO2	=> self::URL_RELANCE_MULTIBANCO2,
		self::URL_RELANCE_MULTIBANCO3	=> self::URL_RELANCE_MULTIBANCO3,
		self::URL_RELANCE_MULTIBANCO4	=> self::URL_RELANCE_MULTIBANCO4,
		self::URL_RELANCE_MULTIBANCO5	=> self::URL_RELANCE_MULTIBANCO5,
		self::URL_RELANCE_MULTIBANCO6	=> self::URL_RELANCE_MULTIBANCO6,
		self::URL_RELANCE_MULTIBANCO7	=> self::URL_RELANCE_MULTIBANCO7,
		self::URL_RELANCE_MULTIBANCO8	=> self::URL_RELANCE_MULTIBANCO8,
		self::URL_RELANCE_MULTIBANCO9	=> self::URL_RELANCE_MULTIBANCO9,
		
		// Boleto 
		self::URL_RELANCE_BOLETO1	=> self::URL_RELANCE_BOLETO1,
		self::URL_RELANCE_BOLETO2	=> self::URL_RELANCE_BOLETO2,
		self::URL_RELANCE_BOLETO3	=> self::URL_RELANCE_BOLETO3,
		self::URL_RELANCE_BOLETO4	=> self::URL_RELANCE_BOLETO4,
		self::URL_RELANCE_BOLETO5	=> self::URL_RELANCE_BOLETO5,
		self::URL_RELANCE_BOLETO6	=> self::URL_RELANCE_BOLETO6,
		self::URL_RELANCE_BOLETO7	=> self::URL_RELANCE_BOLETO7,
		self::URL_RELANCE_BOLETO8	=> self::URL_RELANCE_BOLETO8,
		self::URL_RELANCE_BOLETO9	=> self::URL_RELANCE_BOLETO9,
		
		//stc
		self::URL_PAIEMENT_VG		=> self::URL_PAIEMENT_VG,
	);

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Product' => array(self::BELONGS_TO, '\Business\Product', 'idProduct'),
		);
	}

	/**
	 * Envoi la requete vers l'URL courrante
	 * @param	\Business\Invoice	$Invoice	Invoice
	 * @param	bool	$stockInDB	Defini si l'url doit etre stocké en DB ou executer direct
	 * @param	bool	$async	Envoi asynchrone ( default = true )
	 * @return string|false Retour de la requete, false en cas de probleme
	 */
    public function sendRequest( \Business\Invoice $Invoice, $stockInDB = true, $async = true )
	{
	
		$WF = new \WebForm( $this->url, $async );
		$WF->setTokenWithInvoice($Invoice);

		if( $stockInDB ){
			$ReqEmv					= new \Business\RequestRouterEMV();
			$ReqEmv->idProduct		= $this->idProduct;
			$ReqEmv->idUser			= $Invoice->User->id;
			$ReqEmv->email			= $Invoice->User->email;
			$ReqEmv->idInvoice		= $Invoice->id;
			$ReqEmv->type			= $this->type;
			$ReqEmv->compteEMV		= $this->compteEMV;	
			
			$Date  = new \DateTime();
			
			switch ($this->type){
			case self::URL_RELANCE_CHEQUE1 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P10D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_CHEQUE2 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P15D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_CHEQUE3 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P20D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_CHEQUE4 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P25D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_CHEQUE5 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P30D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_CHEQUE6 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P35D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_CHEQUE7 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P40D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_CHEQUE8 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P45D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_CHEQUE9 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P50D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_DINEROMAIL1 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P10D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_DINEROMAIL2 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P15D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_DINEROMAIL3 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P20D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_DINEROMAIL4 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P25D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_DINEROMAIL5 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P30D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_DINEROMAIL6 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P35D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_DINEROMAIL7 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P40D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_DINEROMAIL8 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P45D') )->format( self::DB_FORMAT_DATE );
				break;
			case self::URL_RELANCE_DINEROMAIL9 : 
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P50D') )->format( self::DB_FORMAT_DATE );
				break;
			default :
				//$ReqEmv->creationDate	= date( \Yii::app()->params['dbDateTime'] );
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('PT20M') )->format( self::DB_FORMAT_DATE );
				break;
			}
			/*
			if($this->type == \Business\RouterEMV::URL_RELANCE_CHEQUE1 )
			{		
			
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P10D') )->format( self::DB_FORMAT_DATE );
						
			}else{
			
				$ReqEmv->creationDate	= date( \Yii::app()->params['dbDateTime'] );
				
			}
			*/
			$ReqEmv->executed		= \Business\RequestRouterEMV::PENDING;
			$ReqEmv->response		= \Business\RequestRouterEMV::RES_PENDING;
			$ReqEmv->url			= $WF->constructUrl( true );
			if($this->compteEMV == $Invoice->User->compteEMVactif)
				return $ReqEmv->save();
			elseif($Invoice->User->compteEMVactif == '' && strpos($this->compteEMV, '_FID')  == false )
				return $ReqEmv->save();
			return true;
		}
		else
		{
			if($this->compteEMV == $Invoice->User->compteEMVactif)
				return $WF->execute( true );
			elseif($Invoice->User->compteEMVactif == '' && strpos($this->compteEMV, '_FID')  == false )
				return $WF->execute( true );
			return true;
		}
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\RouterEMV
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	static public function loadByIdProduct($idProduct){
		return self::model()->findAllByAttributes(array('idProduct'=>$idProduct));
	}
	
	static public function loadByTypeAndIdProductAndCompteEMV($idProduct,$type,$compte)
	{
		return self::model()->findAllByAttributes(array('idProduct'=>$idProduct,'type'=>$type,'compteEMV'=>$compte));
	}
	
	static public function GetWebFormAnaconda()
	{
		return self::model()->findAllBySql("SELECT router.id , url FROM `V2_routeremv` router LEFT JOIN `V2_product` product ON router.`idProduct`=product.`id` WHERE  product.`ref` LIKE  'gps%' AND `type` LIKE  'UrlPaiement'");
	}
	
	 public function updateWebForm($id){
	 	$webform = 	$this->findByPk($id);
		$string=$webform->url;
		$newString=explode ( '&EMAIL_FIELD=__m__' , $string);
		$newUrl=$newString[0].'&EMAIL_FIELD=__m__&CLIENTURN_FIELD=0';	
		
		$webform->url=$newUrl;	
		
		$webform->save(); 
		echo '<br>khikhi<br>';
	}
}

?>