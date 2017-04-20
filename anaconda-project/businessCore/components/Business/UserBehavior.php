<?php

/**
 * Created by PhpStorm.
 * User: sbalkaid
 * Date: 17/10/2016
 * Time: 15:12
 */
namespace Business;

class UserBehavior extends \UserBehavior {

	public function setBdcClic($id) {
		$userBehavior=$this->findByPk($id);
		$userBehavior->bdcClicks=1;
		$userBehavior->save(); // enregistrement dans la base
		
	}
	public function create_user_behavior($userBehavior) {
		try {
			
			$userBehavior->save ();
		} catch ( Exception $e ) {
			var_dump ( $e );
		}
	}
	public function searchByIdCampaingHistoryReflation($idCampaignHistory, $reflation) {
		$userBehavior = $this->search ('id');
		$userBehavior->criteria->compare ( 'reflation', $reflation );
		$userBehavior->criteria->compare (  'idCampaignHistory', $idCampaignHistory );
		return $userBehavior; 
		 
	}
	static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	
public function getNbrBdcClickByIdUserPeriod($iduser,$period) {
		return self::model ()->findAll ( array(
				'condition' => 'bdcClicks = 1   and lastDateClick between 
								cast(ADDDATE(CURRENT_DATE,INTERVAL -:d DAY) as datetime)
 								and cast(NOW() as datetime) and idCampaignHistory in (select id from V2_campaignHistory where idUser = :user and
								initialShootDate >= (select distinct(cast(dateGpChange as date)) from V2_user where id = :user ) )',
				'params' => array (
						':user' => $iduser,
						':d' => $period 
				) 
		) );
	}
	
	
	static public function searchByIdCampaingHistory($idCampignHistory){
		$listeUserBehavior=self::model()->findAllByAttributes( array( 'idCampaignHistory' => $idCampignHistory ) );
		if(isset($listeUserBehavior))
			return end($listeUserBehavior) ;
		
			
		
	}
	
	static public function load( $id )
	{ 
		return self::model()->findByPk( $id );
	}
	

    /**
     *this function retunr all the BDC clicks from a $user before a given date(last date click)
     * @param $date type date
     * @param $idUser the user
     * @return mixed all the click mades by the user idUser
     */
    public static function sumBDCClicksBeforeDate($date,$idUser)
    {
        $criteria = new \CDbCriteria;
        $date = new \DateTime($date);
        $date = $date->format('Y-m-d H:i:s');
        $criteria->join = 'inner join V2_campaignHistory ch on t.idCampaignHistory=ch.id';
        $criteria->condition = 'bdcClicks=1 and  t.lastDateClick <= \''.$date.'\''.' and ch.idUser = '.$idUser;
	//**********************************  Reactivation ******************************************************//
	/**
	 * @author Anas HILAMA
	 * @desc r�cuperer  pour un client la date  du dernier  BdcClick
	 * @param
	 * @return Date
	 */

	static public function LoadBdcClickDateByID($id) {

		$BornSup = date("Y-m-d H:i:s") ;
		$BornInf = date("Y-m-d H:i:s", strtotime("- 1 day")) ;
		$criteria = new \CDbCriteria;
		$criteria->alias = "UserBehavior" ;
		$criteria->condition = "UserBehavior.id ='".$id."'  AND ( UserBehavior.	lastDateClick BETWEEN '".$BornInf."' AND'".$BornSup."')";
		$list = self::model()->findAll($criteria);
		return array_map(function($element) { return $element['lastDateClick'];}, $list);


	}

	//**********************************  FIN Reactivation ******************************************************//



        return self::model()->findAll($criteria);
    }
    /**
     *this function retunr all the BDC clicks from a $user between two dates(date
     * @param $date type date
     * @param $idUser the user
     * @return mixed all the click mades by the user idUser
     */
    public static function bdcClicksByPeriod($idUser,$dateFrom,$dateTo)
    {
        $criteria = new \CDbCriteria;
        $datef = new \DateTime($dateFrom);
        $datet = new \DateTime($dateTo);
        $datef = $datef->format('Y-m-d H:i:s');
        $datet = $datet->format('Y-m-d H:i:s');
        $criteria->join = 'inner join V2_campaignHistory ch on t.idCampaignHistory=ch.id';
        $criteria->condition = 'bdcClicks=1 and  t.lastDateClick between \''.$datef.'\''.' and  \''.$datet.'\''.' and ch.idUser = '.$idUser;


        return self::model()->findAll($criteria);
    }

}
?>