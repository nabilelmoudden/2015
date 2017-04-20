<?php

namespace Business;

/**
 * Description of CampaignReflation
 *
 * @author JulienL
 * @package Business.Campaign
 *
 * The followings are the available model relations:
 * @property \Business\Pricinggrid[] $PricingGrid
 * @property \Business\Product $Product
 * @property \Business\Campaign $Campaign
 * @property \Business\Subcampaignreflation[] $SubCampaignReflation
 */
class SubCampaign extends \Subcampaign
{
	
	public $prodRef, $prodDesc;

	public function init()
	{
		parent::init();

		$this->onBeforeDelete	= array( $this, 'deleteSubCampaign' );
		$this->onAfterSave		= array( $this, 'createSubCampaign' );
	}

	/**
	 * Supprime le dossier associé a cet sous campagne
	 * @return boolean
	 */
	public function deleteSubCampaign()
	{
		\Yii::import( 'ext.FileHelper' );

		$refC	= $this->Campaign->ref;
		$refP	= $this->Product->ref;
		$dir	= \Yii::app()->controller->portDir( true ).'/'.$refC.'/'.$refP;

		if( !is_dir($dir) )
			return true;

		if( !\FileHelper::cleanDir($dir) )
			return false;

		return rmdir($dir);
	}

	/**
	 * Créé le dossier contenant les vues associé au produit
	 * @return boolean
	 */
	public function createSubCampaign()
	{
		umask(0);

		$refC	= $this->Campaign->ref;
		$refP	= $this->Product->ref;
		$dir	= \Yii::app()->controller->portDir( true ).'/'.$refC.'/'.$refP;

		if( !is_dir($dir) )
		{
			if( !mkdir($dir, 0777, true) )
				return false;

			if( !mkdir($dir.'/css', 0777, true) )
				return false;

			if( !mkdir($dir.'/images', 0777, true) )
				return false;
		}

		return true;
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
			'PricingGrid' => array(self::HAS_MANY, '\Business\PricingGrid', 'idSubCampaign'),
			'AnacondaSegment' => array(self::HAS_MANY, 'AnacondaSegment', 'idSubCampaign'),
			'Product' => array(self::BELONGS_TO, '\Business\Product', 'idProduct'),
			'Campaign' => array(self::BELONGS_TO, '\Business\Campaign', 'idCampaign'),
			'SubCampaignReflation' => array(self::HAS_MANY, '\Business\SubCampaignReflation', 'idSubCampaign'),
			'CampaingHistory' => array(self::HAS_MANY, 'CampaingHistory', 'idSubCampaign'),
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

		$Provider->criteria->with = array( 'Product' );
		$Provider->criteria->compare( 'Product.ref', $this->prodRef, true );
		$Provider->criteria->compare( 'Product.description', $this->prodDesc, true );

		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;

		if( $order != false )
			$Provider->criteria->order = $order;

		return $Provider;
	}

	/**
	 * Retourne la relance attachà a cet sous campagne en fonction de son numero
	 * @param int $number
	 * @return \Business\SubCampaignReflation
	 */
	public function getSubCampaignReflationByNumber( $number )
	{
		$res = $this->SubCampaignReflation( array( 'condition' => 'number="'.$number.'"' ) );
		return ( count($res) == 1 ) ? $res[0] : $res;
	}

	/**
	 * Retourne le nombre d'offset price step distinct des SubCampaignReflation
	 * @return int
	 */
	public function countDistinctOffsetPriceStep()
	{
		if( !is_array($this->SubCampaignReflation) || count($this->SubCampaignReflation) <= 0 )
			return false;

		$tab = array();
		for( $i=0; $i<count($this->SubCampaignReflation); $i++ )
			$tab[ $this->SubCampaignReflation[$i]->offsetPriceStep ] = 1;
		return count($tab);
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 * Retourne la SubCampaign correspondante
	 * @param type $id
	 * @return \Business\SubCampaign
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 * Retourne la SubCampaign correspondante
	 * @param int $idCampaign
	 * @param int $position
	 * @return \Business\SubCampaign
	 */
	static public function loadByCampaignAndPosition( $idCampaign, $position )
	{
		//echo $idCampaign.' and '.$position;exit;
		return self::model()->findByAttributes( array( 'idCampaign' => $idCampaign, 'position' => $position ) );
	}
	
	/**
	 * Retourne la position d'un nouveau produit 
	 * @param int $idCampaign
	 * @return position(int)
	 */
	static public function GetLastPositionByCampaign( $idCampaign )
	{
		$SubCampain  = self::model()->findByAttributes( array( 'idCampaign' => $idCampaign ),array( 'order' => 'position desc') );
		$NewPosition = (is_object($SubCampain))?$SubCampain->position+1:1;
			
		return $NewPosition;
	}
	
	
// ==================================================================
// Generation CDC ====== MOUJJAB ABDELILAH
	
	/**
	 * Retourne la SubCampaign correspondante
	 * @param int $idCampaign
	 * @param int $idproduit
	 * @return \Business\SubCampaign
	 */
	static public function loadByCampaignAndProduct( $idCampaign, $idprod )
	{
		return self::model()->findByAttributes( array( 'idCampaign' => $idCampaign, 'idProduct' => $idprod ) );
	}
	
	
	/**
	 * Retourne la SubCampaign correspondante
	 * @param int $idCampaign
	 * @return \Business\SubCampaign
	 */
	static public function loadByCampaign( $idCampaign )
	{
		return self::model()->findAllByAttributes( array( 'idCampaign' => $idCampaign ) );
	}
	
// Fin Generation CDC ====== MOUJJAB ABDELILAH
// ==================================================================
	/**
	 * Retourne la position correspondante
	 * @param int $id
	 * @return position(int)
	 */
	static public function getPositionBySubCampaign( $id )
	{
		$SubCampain  = self::model()->findByAttributes( array( 'id' => $id ) );
		$Position = $SubCampain->position;
		
		return $Position;
	}
	
	/**
	 * Retourne la SubCampaign correspondante
	 * @param int $idproduit
	 * @return \Business\SubCampaign
	 */
	static public function loadByProduct( $idprod )
	{
		return self::model()->findByAttributes( array( 'idProduct' => $idprod ) );
	}
	
	/**
	 * Retourne la premiere SubCampaign
	 * @param
	 * @return \Business\SubCampaign
	 */
	static public function loadfirst()
	{
		$subCampaigns = self::model()->findAll();
		return current($subCampaigns);
	}
	
	/**
	 * Verifier si la ampaign est de type INTER
	 * @param
	 * @return Boolean
	 */
	public function isInter()
	{
		if ($this->position == 1 && $this->Campaign->hasCT())
		{
			$subCampaign=self::loadByCampaignAndPosition( $this->Campaign->id, 2 );
			if($subCampaign->Product->asile_type == 'inter')
				return true;
			else
				return false;
		}
		else 
			return false;
		
	
	}

    /**
     * @author AL.
     * @param $idSubCampaign
     * @return mixed
     */
    public static function loadByIdSubCamp( $idSubCampaign )
    {
        return self::model()->findByAttributes( array( 'id' => $idSubCampaign ) );
    }
	
	/**
	 * Verifier si la ampaign est de type ASILE
	 * @param
	 * @return Boolean
	 */
	public function isAsile()
	{
		if ($this->position == 1 && $this->Campaign->hasCT())
		{
			$subCampaign=self::loadByCampaignAndPosition( $this->Campaign->id, 2 );
			return $subCampaign->Product->asile_type == 'asile'?1:0;
		}
		else
		{
			return false;
		}
	
	
	}	
}

?>
