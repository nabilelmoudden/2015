<?php
//UPDATE `invoice` as i LEFT JOIN internaute as it ON it.id = i.idInternaute INNER JOIN debugSiteInstit as d ON d.EMAIL = it.Email SET i.site = 'mx'
namespace Business;

/**
 * Description of RouterEMV
 *
 * @author JulienL
 * @package Business.Campaign
 */
class Router_V1 extends \Router_V1
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
	const URL_ABANDON_PANIER	= 'UrlAbandonPanier';

	/**
	 * Array of type
	 * @var array
	 */
	static public $tabType = array(
		self::URL_PAIEMENT				=> self::URL_PAIEMENT,
		self::URL_INTENTION_CHEQUE		=> self::URL_INTENTION_CHEQUE,
		self::URL_PAYMENT_CHEQUE		=> self::URL_PAYMENT_CHEQUE,
		self::URL_ABANDON_PANIER		=> self::URL_ABANDON_PANIER
	);

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
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
			
			if($this->type == \Business\RouterEMV::URL_RELANCE_CHEQUE1 )
			{		
			
				$ReqEmv->creationDate	= $Date->add( new \DateInterval('P10D') )->format( self::DB_FORMAT_DATE );
						
			}else{
			
				$ReqEmv->creationDate	= date( \Yii::app()->params['dbDateTime'] );
				
			}
			
			$ReqEmv->executed		= \Business\RequestRouterEMV::PENDING;
			$ReqEmv->response		= \Business\RequestRouterEMV::RES_PENDING;
			$ReqEmv->url			= $WF->constructUrl( true );

			return $ReqEmv->save();
		}
		else
		{
			return $WF->execute( true );
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
	
	/**
	 * @param int $id
	 */
	 
	static public function loadByRef($RefProduct, $type){
		return parent::loadByRef($RefProduct, $type);
	}
	
}

?>