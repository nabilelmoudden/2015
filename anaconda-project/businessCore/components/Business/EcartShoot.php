<?php

namespace Business;

/**
 * Description of Alert
 *
 * @author AL.
 * @package Business.Alert
 */
class EcartShoot extends \EcartShoot
{

    public $id;
    public $activityHour;
    public $shootType;
    public $expected;
    public $shooted;
    public $unjoin;
    public $quarantaine;
    public $positionCampaign;
    public $standByIn;
    public $standByOut;
    public $reactivated;
    public $idEcart;
    public $idSubCampaignReflation;


	public function init()
	{
		parent::init();

		//$this->onBeforeDelete	= array( $this, 'deleteAlert' );
		//$this->onBeforeSave		= array( $this, 'createAlert' );
	}

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
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
            //'AlertUser' => array(self::HAS_MANY, 'AlertUser', 'idAlert'),
            //'Comment' => array(self::HAS_MANY, 'Comment', 'idAlert' ),
            'Ecart' => array(self::BELONGS_TO, 'Ecart', 'idEcart'),
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
		else
			$Provider->criteria->order ='id DESC';

		return $Provider;
	}
    //load alert by the given $idAlert
    public static function loadById($id)
    {
        return self::model()->findByPk($id);
    }

    public static function  getEcartShootByidEcart($idEcart)
    {
        return self::model()->findAllByAttributes( array( 'idEcart' => $idEcart ) );
    }
    
    /**
     * 
     * @author Saad HDIDOU
     * @param date $dateMin
     * @param date $dateMax
     * @param int $idSubCampaignReflation
     * @param int $vague
     * @desc recuperer les ecarts de shoot par date, par SubCampaignReflation et par intervalle de shoot
     */
    public static function loadByDateAndSubcampaignreflationAndTimeSlot($dateMin, $dateMax, $idSubCampaignReflation, $vague)
    {
    	$criteria = new \CDbCriteria;
    	$criteria->alias = 'EcartShoot';
    	$criteria->join='LEFT JOIN V2_ecart Ecart ON EcartShoot.idEcart = Ecart.id';
    	$criteria->condition = 'Ecart.creationDate BETWEEN \''.$dateMin->format('Y-m-d').' 00:00:00%\' AND \''.$dateMax->format('Y-m-d').' 23:59:59%\' AND EcartShoot.idSubCampaignReflation = '.$idSubCampaignReflation.' AND EcartShoot.activityHour = '.$vague;
    	return self::model()->findAll($criteria);
    }


}

?>