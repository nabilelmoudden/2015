<?php

/**
 * This is the model class for table "trackingcode".
 *
 * The followings are the available columns in table 'trackingcode':
 * @property integer $id
 * @property string $TCLeadPromo
 * @property string $TCLandingPagePromo
 * @property string $TCPrePurchase
 * @property string $TCPurchase
 * @property string $TCLead
 * @property string $TCLandingPage
 * @property string $TCDualOptin
 *
 * @package Models.AffiliatePlatform
 */
class Trackingcode extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Trackingcode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function rawTableName()
	{
		return 'trackingcode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TCLeadPromo, TCLandingPagePromo, TCPrePurchase, TCPurchase, TCLead, TCLandingPage, TCDualOptin', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, TCLeadPromo, TCLandingPagePromo, TCPrePurchase, TCPurchase, TCLead, TCLandingPage, TCDualOptin', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'ChoiceTrackingCode' => array(self::HAS_MANY, 'ChoiceTrackingCode', 'idTrackingCode'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'TCLeadPromo' => 'Tclead Promo',
			'TCLandingPagePromo' => 'Tclanding Page Promo',
			'TCPrePurchase' => 'Tcpre Purchase',
			'TCPurchase' => 'Tcpurchase',
			'TCLead' => 'Tclead',
			'TCLandingPage' => 'Tclanding Page',
			'TCDualOptin' => 'Dual Optin'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('TCLeadPromo',$this->TCLeadPromo,true);
		$criteria->compare('TCLandingPagePromo',$this->TCLandingPagePromo,true);
		$criteria->compare('TCPrePurchase',$this->TCPrePurchase,true);
		$criteria->compare('TCPurchase',$this->TCPurchase,true);
		$criteria->compare('TCLead',$this->TCLead,true);
		$criteria->compare('TCLandingPage',$this->TCLandingPage,true);
		$criteria->compare('TCDualOptin',$this->TCDualOptin,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}