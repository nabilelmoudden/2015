<?php

/**
 * This is the model class for table "userBehavior".
 *
 * The followings are the available columns in table 'userBehavior'.
 * @property integer $id
 * @property string $lastDateClick
 * @property intger $reflation
 * @property integer $bdcClicks
 *
 * The followings are the available model relations :
 * @property CampaignHistory[] $campaignHistory
 *
 * @package Models.UserBehavior
 */
class UserBehavior extends ActiveRecord {

	public $id;
	public $lastDateClick;
	public $reflation; 
	public $bdcClicks;
	public $idCampaignHistory;
	public static $master_db;
	 
	public function getDbConnection() {
		self::$master_db = Yii::app()->db;
		if (self::$master_db instanceof CDbConnection) {
			self::$master_db->setActive(true);
			return self::$master_db;
		}
		else{
			throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));
		}
	}

	
	public function rawTableName() {
		return 'userBehavior';
	}
	
	/**
	 *
	 * @return array relation rules
	 */
	public function relations() {
		return array (
				'CampaignHistroy' => array (
						self::BELONGS_TO,
						'CampaignHistroy',
						'id' 
				) 
		);
	}
	
	/**
	 *
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array (
				'id' => 'id',
				'lastDateClick' => 'date de dérnière click',
				'reflation' => 'relance ',
				'bdcClicks' => 'click sur le bdc',
				'idCampaignHistory'=>'id de campaifn history'
				
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * 
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */

	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria = new CDbCriteria ();
		$criteria->compare ( 'id', $this->id );
		$criteria->compare ( 'lastDateClick', $this->lastDateClick );
		$criteria->compare ( 'bdcClicks', $this->bdcClicks );
		$criteria->compare ( 'reflation', $this->reflation );
		$criteria->compare ( 'idCampaignHistory', $this->idCampaignHistory );
	
		return new CActiveDataProvider ( $this, array (
				'criteria' => $criteria
		) );
	}	
	
	
	public function getBdcClicks() {
		return $this->bdcClicks;
		
	}
	public function getId() 
	{
		return $this->id;
	}
}
?>