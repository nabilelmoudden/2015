<?php



namespace Business;



/**
 * Description of Openedlinkmail
 *
 * @author YacineR 
 * @package Business.Openedlinkmail
 */

class Openedlinkmail extends \Openedlinkmail

{

	/**
	 * Retourne l'Openedlinkmail correspondant
	 * @param type $id
	 * @return \Business\Openedlinkmail
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	static public function LoadopenedlinkmailBySubCampaignReflationAndUser( $idSubCampaignReflation, $idUser)
	{
 
		return self::model()->findByAttributes( array( 'idSubCampaignReflation' => $idSubCampaignReflation,'idUser' => $idUser ) );
	
	}
	/***************************************************** Load By Date **********************************************************************/
	/**
	 * @author Yacine RAMI
	 * @desc Retourne une liste d'Openedlinkmail par date d'ouverture.
	 * @param string $dateOpen
	 * @return Openedlinkmail[] liste d'Openedlinkmail
	 */
	static public function LoadByDateAndShoot($dateOpen,$shoot)
	{
		switch($shoot)
		{
			
		case 1 : 
			return self::model()->findAll( array('condition'=>'openedDate LIKE :date AND activityHour IN (0,1,2)','params'=>array(':date'=>"%$dateOpen%")));
			break;
		case 2 : 
			return self::model()->findAll( array('condition'=>'openedDate LIKE :date AND activityHour IN (3,4,5)','params'=>array(':date'=>"%$dateOpen%")));
			break;
		case 3 : 
			return self::model()->findAll( array('condition'=>'openedDate LIKE :date AND activityHour IN (6,7,8)','params'=>array(':date'=>"%$dateOpen%")));
			break;
		case 4 : 
			return self::model()->findAll( array('condition'=>'openedDate LIKE :date AND activityHour IN (9,10,11)','params'=>array(':date'=>"%$dateOpen%")));
			break;
		case 5 : 
			return self::model()->findAll( array('condition'=>'openedDate LIKE :date AND activityHour IN (12,13,14)','params'=>array(':date'=>"%$dateOpen%")));
			break;
		case 6 : 
			return self::model()->findAll( array('condition'=>'openedDate LIKE :date AND activityHour IN (15,16,17)','params'=>array(':date'=>"%$dateOpen%")));
			break;
		case 7 : 
			return self::model()->findAll( array('condition'=>'openedDate LIKE :date AND activityHour IN (18,19,20)','params'=>array(':date'=>"%$dateOpen%")));
			break;
		case 8 : 
			return self::model()->findAll( array('condition'=>'openedDate LIKE :date AND activityHour IN (21,22,23)','params'=>array(':date'=>"%$dateOpen%")));
			break;
		default:
			return false;
		}

	}


	/*************************************************** / Load By Date **********************************************************************/

    /**
     * @author AL.
     * @param $idUser
     * @param $idSubCampRefl
     * @return mixed
     */
    public static function loadOpenedLmBySubCampReflAndUser( $idUser, $idSubCampRefl )
    {
        return self::model()->findAllByAttributes( ['idUser' => $idUser, 'idSubCampaignReflation' => $idSubCampRefl] );
    }

    /**
     * @author MARO FIKRI.
     * @param $idUser
     * @param $idSubCampRefl
     * @return mixed
     */
    public static function OpenByPeriod($idUser,$dateFrom,$dateTo)
    {
        $criteria = new \CDbCriteria;
        $criteria->condition = 'openedDate BETWEEN \''.$dateFrom.'%\' AND \''.$dateTo.'%\' and idUser = '.$idUser;
        return self::model()->findAll($criteria);
    }

    /*************************************************** Reactivation **********************************************************************/
    /**
     * @author Anas HILAMA
     * @desc r�cuperer  pour un client la date  de la derniere ouverture
     * @param
     * @return Date
     */

    static public function LoadOuvertureDateByID($id) {

        $BornSup = date("Y-m-d H:i:s") ;
        $BornInf = date("Y-m-d H:i:s", strtotime("- 1 day")) ;
        $criteria = new \CDbCriteria;
        $criteria->alias = "openedlinkmail" ;
        $criteria->condition = "openedlinkmail.idUser ='".$id."'  AND ( openedlinkmail.openedDate BETWEEN '".$BornInf."' AND'".$BornSup."')";
        $list = self::model()->findAll($criteria);
        return array_map(function($element) { return $element['openedDate'];}, $list);


    }
    
    static public function LoadopenedlinkmailBySubCampaignAndUser( $idSubCampaign, $idUser)
    {
    	$criteria = new \CDbCriteria;
    	$criteria->alias = "Openedlinkmail" ;
		$criteria->join="LEFT JOIN V2_subcampaignreflation Subcampaignreflation ON Openedlinkmail.idSubCampaignReflation = Subcampaignreflation.id";
		$criteria->condition = "Subcampaignreflation.idSubCampaign = ".$idSubCampaign  ;
    	$list = self::model()->findAll($criteria);
    	if(isset($list))
    	{
    		return true;
    	}
    	else {
    		return false;
    	}
    
    }
}



?>