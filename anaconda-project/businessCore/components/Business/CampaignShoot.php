<?php

namespace Business;

/**
 * Description of Campaign
 *
 * @author JulienL
 * @package Business.Campaign
 */
class CampaignShoot extends \CampaignShoot //implements Interface_Camp
{
	public function init()
	{
		parent::init();

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
				'SubCampaign' => array(self::HAS_MANY, '\Business\Subcampaign', 'idCampaign'),
				'Invoice' => array(self::HAS_MANY, '\Business\Invoice', array( 'ref' => 'campaign' ) ),
				'Campaign' => array(self::BELONGS_TO, '\Business\Campaign', 'id_campaign'),
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


	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\Campaign
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	
	static public function getVersion( )
	{	$Version='V2';
		return $Version;
	}
	
	
	static public function loadByCamp( $id = NULL)
	{
	
		return self::model()->findByAttributes( array( 'id_campaign' => $id ) );
	
	}
	
	public function controle(){
		if($this->groupe_prix == ""){
			return false;
		}
		if($this->selection == ""){
			return false;
		}
		if($this->date_prem_shoot == ""){
			return false;
		}
		if($this->comptage == ""){
			return false;
		}
		if($this->jours_shoot == ""){
			return false;
		}
		return true;
	}
}

?>
