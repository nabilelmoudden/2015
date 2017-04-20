<?php

namespace Business;

/**
 * Description of RequestRouterEMV
 *
 * @author JulienL
 * @package Business.RequestRouterEMV
 */
class RequestRouterEMV extends \RequestRouterEMV
{
	/**
	 * Constante definissant si l'url a été execué ou est tjrs en attente
	 */
	const PENDING		= 0;
	const EXECUTED		= 1;

	/**
	 * Constante definissant le resultat de l'execution
	 */
	const RES_PENDING	= 0;
	const RES_OK		= 1;
	const RES_NOK		= 2;


	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Product' => array(self::BELONGS_TO, '\Business\Product', 'idProduct'),
			'User' => array(self::BELONGS_TO, '\Business\User', 'idUser'),
			'Invoice' => array(self::BELONGS_TO, '\Business\Invoice', 'idInvoice'),
		);
	}

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return \CActiveDataProvider	CActiveDataProvider
	 */
	public function search( $order = false, $pageSize = 0 )
	{
		$Provider = parent::search();

		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;

		if( $order != false )
			$Provider->criteria->order = $order;

		return $Provider;
	}

	/**
	 * Execute la requete en DB
	 * @param	bool	$async	Envoi asynchrone ( default = true )
	 * @return string|false Retour de la requete, false en cas de probleme
	 */
	public function sendRequest( $async = true )
	{
		$WF		= new \WebForm( $this->url, $async );
		$res	= $WF->execute();

		$this->executed			= self::EXECUTED;
		$this->executionDate	= date( \Yii::app()->params['dbDateTime'] );
		$this->response			= ( $res == \WebForm::RES_OK ) ? self::RES_OK : self::RES_NOK;

		if( $this->save() ){
		
			if($this->type == \Business\RouterEMV::URL_ABANDON_PANIER )
			{
					
				$Abandonedcaddy					= new \Business\AbandonedCaddy();
				$Abandonedcaddy->type		    = 1;
				$Abandonedcaddy->idInvoice		= $this->idInvoice;
				$Abandonedcaddy->idAdmin		= $this->idUser;
				$Abandonedcaddy->creationDate	= $this->creationDate;
				$Abandonedcaddy->alertDate		= date( \Yii::app()->params['dbDateTime'] );
				$Abandonedcaddy->status		    = 1;
				$Abandonedcaddy->journal		= $this->url;
			
		
			    $Abandonedcaddy->save();
				$Invoice = \Business\Invoice::load( $this->idInvoice );
				$Invoice -> idAbandonedCaddy    = $Abandonedcaddy->id;
				$Invoice->save();
			}			
			return $res;
		}else{
			throw new \EsoterException( 302, \Yii::t( 'error', 302 ).' : ID '.$this->id );
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
	 * @return \Business\RequestRouterEMV
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	/**
	 *
	 * @param type $id
	 * @return \Business\RequestRouterEMV
	 */
	static public function PendingDate()
	{
		return true;
		   
	}
	
}

?>