<?php

/**
 * This is the model class for table "campaignhistory".
 *
 * The followings are the available columns in table 'campaignhistory'.
 * @property integer $id
 * @property string $modifiedShootDate
 * @property string $initialShootDate
 * @property integer $groupPrice
 * @property integer $status
 * @property string $deliveryDate
 * @property integer $behaviorHour
 *	
 * The followings are the available model relations : 
 * @property User[] $User
 * @property Subcampaign[] $Subcampaign
 *
 * @package Models.Campaignhistory
 */
class CampaignHistory extends ActiveRecord {
	public $MaxGp;
	
	public function rawTableName() {
		return 'campaignHistory';
	}
	
	/**
	 *
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array (
				'SubCampaign' => array (
						self::BELONGS_TO,
						'\Business\SubCampaign',
						'idSubCampaign' 
				),
				'User' => array (
						self::BELONGS_TO,
						'\Business\User',
						'idUser' 
						
				) ,
				'UserBehavior' => array (
						self::HAS_MANY,
						'\Business\UserBehavior',
						'idCampaignHistory'
				),
		);
	}
	/**
	 *
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array (
				'id' => 'ID',
				'modifiedShootDate' => 'Date de Shoot Modifiée',
				'initialShootDate' => 'Initiale Date De Shoot ',
				'groupPrice' => 'Group de Prix',
				'status' => 'Status',
				'deliveryDate' => 'Date De livraison',
				'behaviorHour' => 'Heure de shoot' 
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
		$criteria->compare ( 'initialShootDate', $this->initialShootDate );
		$criteria->compare ( 'groupPrice', $this->groupPrice );
		$criteria->compare ( 'modifiedShootDate', $this->modifiedShootDate );
		$criteria->compare ( 'behaviorHour', $this->behaviorHour );
		$criteria->compare ( 'deliveryDate', $this->deliveryDate );
		
		$criteria->compare ( 'status', $this->status );
		$criteria->compare ( 'idUser', $this->idUser );
		$criteria->compare ( 'idSubCampaign', $this->idSubCampaign );
		
		return new CActiveDataProvider ( $this, array (
				'criteria' => $criteria 
		) );
	}
}

?>