<?php

namespace Business;

/**
 * Description of Ecart
 *
 * @author AL.
 * @package Business.Ecart
 */
class Ecart extends \Ecart
{
	
	/** 
	* Project Status
	*/
	const TYPE_0		= "Ecart de shoot";
	const TYPE_1		= "Ecart de livraison";
	const TYPE_2		= "Ecart de GP";
    const SHOOT  = 1;
    const DELIVERY  = 2;
    const GP  = 3;

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
            'Alert' => array(self::HAS_MANY, '\Business\Alert', 'idEcart'),
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

    public static function loadById($idEcart)
    {
        return self::model()->findByPk($idEcart);
    }

    public static function buildNotification($idEcart)
    {
        $criteria = new \CDbCriteria;
        $criteria->select = "t.id,t.type,a.id,a.statut,a.idSubCampaign";
        $criteria->condition = 't.id = '.$idEcart;
        $criteria->join = 'inner join V2_alert a on a.idEcart=t.id';
        return self::model()->findAll($criteria);

    }
    public static function TypeName($type)
    {
        switch ($type) {
            case 0:
                $name = "Ecart de shoot";
                break;
            case 1:
                $name = "Ecart de livraison";
                break;
            case 2:
                $name = "Ecart de GP";
                break;
            default:
                $name = "Nom inconnu";
                break;
        }
        return $name;
    }
    /**
     * @desc cette m�thode retourn si le cron a �t� execut� pour une date donnee
     * @param date d'execution $date
     * @return boolean
     */
    static public function ExecutedEcartDeliveryByDate($date)
    {
        $criteria = new \CDbCriteria;
        $criteria = new \CDbCriteria;
        $criteria->condition = "creationDate LIKE'%".$date."%' and type = ".self::DELIVERY;
        $count = self::model()->count($criteria);
        return $count > 0 ? 1 : 0;
    }
}

?>

