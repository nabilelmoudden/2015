<?php

namespace Business;

/**
 * Description of Alert
 *
 * @author AL.
 * @package Business.Alert
 */
class CommentAlert extends \CommentAlert
{

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
				//'AlertUser' => array(self::HAS_MANY, '\Business\AlertUser', 'idAlert'),
				// 'Comment' => array(self::HAS_MANY, '\Business\Comment', 'idAlert' ),
				'Alert' => array(self::BELONGS_TO, '\Business\Alert', 'idAlert'),
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
	//load Comment by the given $id
	public static function loadById($id)
	{
		return self::model()->findByPk($id);
	}
	
	//Charger tous les alerts selon le type d'ecart dans une duree determinee.
	public static function GetListAlertByFilter($startDate,$endDate,$type)
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = "date(t.creationDate) between '".$startDate."' AND '".$endDate."' and e.type = ".$type;
		//$criteria->condition = 'date(creationDate) between \''.$startDate.'\' AND \''.$endDate.'\' and e.type = '.$type.'\'';
		$criteria->join = "inner join V2_ecart e on e.id=t.idEcart";
		$criteria->order="t.creationDate DESC";
		//$criteria->limit ='1';
		return self::model()->findAll($criteria);
	}
	
	public static function createComment($id_alert,$content)
	{
		$c = new self();
		$c->description=$content;
		$c->creationDate=date("Y-m-d H:i:s");
		$c->idAlert=$id_alert;
		if($c->save())
			return $c->id;
			else
				return false;
	
	}
	
	//load Comments by idAlert
	public static function  getCommentsByidAlert($idAlert)
	{
		return self::model()->findAllByAttributes(array('idAlert'=>$idAlert));
	}

}
