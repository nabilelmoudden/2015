<?php

namespace Business;

/**
 * Description of AffiliateCampaign
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class AffiliateCampaign extends \AffiliateCampaign
{
	public $template;

	public function init()
	{
		parent::init();

		$this->onAfterFind		= array( $this, 'loadTemplate' );
		$this->onAfterSave		= array( $this, 'saveTemplate' );
		$this->onAfterDelete	= array( $this, 'deleteTemplate' );
	}

	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'ChoiceTrackingCode' => array(self::HAS_MANY, '\Business\ChoiceTrackingCode', 'idAffiliateCampaign'),
			'RouterPS' => array(self::HAS_MANY, '\Business\RouterPS', 'idAffiliateCampaign'),
		);
	}

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
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
	 * Met a jour / Créé les TrackingCodes lié a cet campagne
	 * @param array $newTrackingCode Tableau contenant les trackingcodes
	 * @param int $idAffiliatePlatform	Optionnelle
	 * @param int $idAffiliatePlatformSubID Optionnelle
	 * @return boolean
	 */
	public function updateTrackingCode( $newTrackingCode, $idAffiliatePlatform = NULL, $idAffiliatePlatformSubID = NULL )
	{
		\Yii::import( 'ext.ArrayHelper' );

		if( ( $TC = $this->getTrackingCode( $idAffiliatePlatform, $idAffiliatePlatformSubID ) ) )
		{
			if( \ArrayHelper::isEmpty($newTrackingCode) )
				return $TC->delete();

			$TC->attributes = $newTrackingCode;
			return $TC->save();
		}
		else
		{
			if( \ArrayHelper::isEmpty($newTrackingCode) )
				return true;

			$CTC							= new \Business\ChoiceTrackingCode();
			$CTC->idAffiliateCampaign		= $this->id;
			$CTC->idAffiliatePlatform		= $idAffiliatePlatform;
			$CTC->idAffiliatePlatformSubId	= $idAffiliatePlatformSubID;
			if( !$CTC->addTrackingCode( $newTrackingCode ) )
				return false;

			return $CTC->save();
		}
	}

	/**
	 * Met a jour le PromoSite lié a cet campagne
	 * @param int $idPromoSite ID du PromoSite
	 * @param int $idAffiliatePlatform	Optionnelle
	 * @param int $idAffiliatePlatformSubID Optionnelle
	 * @return boolean
	 */
	public function updatePromoSite( $idPromoSite, $idAffiliatePlatform = NULL, $idAffiliatePlatformSubID = NULL )
	{
		if( $idAffiliatePlatform == NULL && $idAffiliatePlatformSubID == NULL )
			$Router = $this->RouterPS( array( 'condition' => 'idAffiliatePlatform IS NULL AND idAffiliatePlatformSubId IS NULL' ) );
		else if( $idAffiliatePlatform > 0 && $idAffiliatePlatformSubID == NULL )
			$Router = $this->RouterPS( array( 'condition' => 'idAffiliatePlatform = "'.$idAffiliatePlatform.'" AND idAffiliatePlatformSubId IS NULL' ) );
		else
			$Router = $this->RouterPS( array( 'condition' => 'idAffiliatePlatform = "'.$idAffiliatePlatform.'" AND idAffiliatePlatformSubId ="'.$idAffiliatePlatformSubID.'"' ) );
		if( is_array($Router) && count($Router) > 0 )
		{
			$Router	= $Router[0];

			// Si $idPromoSite == 0 alors SitePromo par defaut pour le porteur, donc on supprime l'entré dans la DB
			if( $idPromoSite <= 0 )
				return $Router->delete();
		}
		else
		{
			// Si $idPromoSite == 0 alors SitePromo par defaut pour le porteur, donc on ne créé aucune entré ds la DB
			if( $idPromoSite <= 0 )
				return true;

			$Router								= new \Business\RouterPS();
			$Router->idAffiliateCampaign		= $this->id;
			$Router->idAffiliatePlatform		= $idAffiliatePlatform;
			$Router->idAffiliatePlatformSubId	= $idAffiliatePlatformSubID;
		}

		$Router->idPromoSite = $idPromoSite;
		return $Router->save();
	}

	/**
	 * Retourne les TrackingCodes lié a la campagne courante
	 * @param int $idAffiliatePlatform	Optionnelle
	 * @param int $idAffiliatePlatformSubID Optionnelle
	 * @return \Business\TrackingCode
	 */
	public function getTrackingCode( $idAffiliatePlatform = NULL, $idAffiliatePlatformSubID = NULL )
	{
		if( $this->id <= 0 ) return false;

		$CTC = \Business\ChoiceTrackingCode::loadByAP( $idAffiliatePlatform, $this->id, $idAffiliatePlatformSubID );
		return ( is_object($CTC) ) ? $CTC->TrackingCode : false;
	}

	/**
	 * Retourne le PromoSite lié a la campagne courante
	 * @param int $idAffiliatePlatform	Optionnelle
	 * @param int $idAffiliatePlatformSubID Optionnelle
	 * @return \Business\PromoSite
	 */
	public function getPromoSite( $idAffiliatePlatform = NULL, $idAffiliatePlatformSubID = NULL )
	{
		if( $this->id <= 0 ) return false;

		$Router = \Business\RouterPS::loadByAP( $idAffiliatePlatform, $this->id, $idAffiliatePlatformSubID );
		return ( is_array($Router) && count($Router) > 0 ) ? $Router[0]->PromoSite : false;
	}

	/**
	 * Lance la generation des templates pour les plateformes passé en argument
	 * @param array $tabAF Tableau contenant les ID des plateformes pour lesquel generer la campagne
	 * @return boolean
	 */
	public function generateCampaignForAffiliatePlatform( $tabAF )
	{
		\Yii::import( 'ext.FileHelper' );
		\Yii::import( 'ext.CSVHelper' );

		$root		= TMP_DIR.'/campaignTemplate/'.\Yii::app()->params['porteur'].'/'.$this->id;
		$ConfDNS	= \Business\Config::loadByKey( 'DNS' );
		$porteur	= \Yii::app()->params['porteur'];
		$Csv		= new \CSVHelper( $root.'/info.csv', ';', 'w' );

		if( !\FileHelper::rm( $root, array( 'html' ), array( 'template.html' ) ) )
			return false;

		if( !is_object($ConfDNS) )
		{
			\Yii::app()->user->setFlash( "warning", \Yii::t( 'AP', 'noDNS' ) );
			return false;
		}

		$Csv->fputcsv( array(
			'Affiliate Campaign',
			'Affiliate Platform',
			'Sub ID',
			'Link'
		) );

		foreach( $tabAF as $idAF )
		{
			$tempVar	= array(
				'{$CAMPAGNE}',
				'{$PLATFORM}',
				'{$LINK}'
			);

			$tempVal	= array(
				$this->id,
				$idAF,
				str_replace( '&', '&amp;', $ConfDNS->value.'/'.SCRIPT_ROUTER_PS.'?ap='.$idAF.'&ac='.$this->id.'&si=__0__'.( IS_DEV_VERSION ? '&dev=true' : NULL ) )
			);

			$AF		= \Business\AffiliatePlatform::load( $idAF );
			$data	= str_replace( $tempVar, $tempVal, $this->template );
			$name	= str_replace( array( '/', '\\' ), '', $porteur.'_'.$this->label.' - '.$AF->label.'.html' );

			$Csv->fputcsv( array(
				$this->label,
				$AF->label,
				'',
				str_replace( '&amp;', '&', $tempVal[2] )
			) );

			if( !\FileHelper::saveTemplate( $root.'/'.$name, $data ) )
				return false;

			// Generation des campagnes pour le subID :
			foreach( $AF->AffiliatePlatformSubId  as $SubID )
			{
				if( $SubID->subID == DEFAULT_SUBID )
					continue;

				$tempValSI	= array(
					$this->id,
					$idAF,
					str_replace( '&', '&amp;', $ConfDNS->value.'/'.SCRIPT_ROUTER_PS.'?ap='.$idAF.'&ac='.$this->id.'&si='.$SubID->subID.( IS_DEV_VERSION ? '&dev=true' : NULL )  )
				);

				$data	= str_replace( $tempVar, $tempValSI, $this->template );
				$name	= str_replace( array( '/', '\\' ), '', $porteur.'_'.$this->label.' - '.$AF->label.'_Sub ID '.$SubID->label.'.html' );

				$Csv->fputcsv( array(
					$this->label,
					$AF->label,
					$SubID->subID,
					str_replace( '&amp;', '&', $tempValSI[2] )
				) );

				if( !\FileHelper::saveTemplate( $root.'/'.$name, $data ) )
					return false;
			}
		}

		return true;
	}

	/**
	 * Test si la campagne a été generé pour la plateforme
	 * @param \Business\AffiliatePlatform $AF Affiliate platform
	 * @return boolean
	 */
	public function isGenerated( $AF )
	{
		// Pour l'appel dans un CButtonColumn d'un CGridView
		if( !is_object($AF) && func_num_args() == 3 )
			$AF = func_get_arg( 1 );

		$porteur	= \Yii::app()->params['porteur'];
		$root		= TMP_DIR.'/campaignTemplate/'.\Yii::app()->params['porteur'].'/'.$this->id;
		return is_file($root.'/'.$porteur.'_'.$this->label.' - '.$AF->label.'.html') ? $porteur.'_'.$this->label.' - '.$AF->label.'.html' : false;
	}

	/**
	 * Retourne un tableau contenant tous les fichiers generé pour les differentes plateformes
	 * @return array	Tableau de fichiers
	 */
	public function getGenerated( $withCsvInfo = true )
	{
		$root = TMP_DIR.'/campaignTemplate/'.\Yii::app()->params['porteur'].'/'.$this->id;

		if( !is_dir($root) || $this->id <= 0 )
			return false;

		$Dir = dir( $root );
		$tab = array();
		while( ($read = $Dir->read()) != false )
		{
			$tmpExt	= explode( '.', $read );
			$ext	= end( $tmpExt );

			if( $read[0] == '.' || $read == 'template.html' || ( $ext != 'html' && $read != 'info.csv' ) )
				continue;

			$tab[] = $root.'/'.$read;
		}

		return $tab;
	}

	// *********************** GESTION DES VARIABLES SPECIAL ( template ) *********************** //
	/**
	 * Surcharge des rules pour tenir compte de la variable Template qui n'est pas en DB
	 * @return type
	 */
	public function rules()
	{
		return \CMap::mergeArray(
				array(
					array( 'template', 'length', 'max'=>100000000)
				),
				parent::rules() );
	}

	/**
	 * Events appelé a chaque chargement d'une Campagne
	 * Charge le contenu du template en variable de classe
	 */
	protected function loadTemplate()
	{
		\Yii::import( 'ext.FileHelper' );

		if( empty(\Yii::app()->params['porteur']) )
			return false;

		$root	= TMP_DIR.'/campaignTemplate/'.\Yii::app()->params['porteur'].'/'.$this->id;

		// Suppression des Header/Footer HTML
		$data	= \FileHelper::getTemplate( $root.'/template.html' );

		$this->template = $data;
	}

	/**
	 * Events appelé apres chaque enregistrement d'une Campagne
	 * Sauvegarde le contenu du template dans un fichier
	 */
	protected function saveTemplate()
	{
		\Yii::import( 'ext.FileHelper' );

		if( empty(\Yii::app()->params['porteur']) )
			return false;

		$root	= TMP_DIR.'/campaignTemplate/'.\Yii::app()->params['porteur'].'/'.$this->id;

		if( !is_dir($root) )
			mkdir( $root, 0777, true );

		// Ajout des Header/Footer HTML
		$data = $this->template;

		return \FileHelper::saveTemplate( $root.'/template.html', $data );
	}

	/**
	 * Events appelé pour attribuer les valeurs d'un parametre a une variable de la classe qui n'est pas en DB
	 * @param string $name
	 * @param mixed $value
	 */
	protected function unsafeAttribute( $name, $value )
	{
		$this->$name	= $value;
	}

	/**
	 * Events appelé a la suppression d'une campagne
	 * Supprime aussi le fichier de template associé a la campagne
	 */
	protected function deleteTemplate()
	{
		// Supprimer le fichier de template ?
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\AffiliateCampaign
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>