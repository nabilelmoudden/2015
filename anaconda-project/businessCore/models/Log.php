<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property integer $idUser
 * @property string $supportRef
 * @property string $supportDate
 * @property integer $actionType
 * @property integer $idProduct
 * @property integer $idAffiliatePlatform
 * @property integer $idAffiliateCampaign
 * @property integer $idAffiliatePlatformSubId
 * @property integer $idPromoSite
 * @property string $actionDate
 * @property string $email
 * @property string ip
 * @property string $userAgent
 * @property string $queryString
 *
 * The followings are the available model relations:
 * @property Product $idProduct0
 * @property User $idUser0
 *
 * @package Models.Log
 */
class Log extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Log the static model class
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
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array( 'idUser, actionType, idProduct, idAffiliatePlatform, idAffiliateCampaign, idAffiliatePlatformSubId, idPromoSite', 'numerical', 'integerOnly'=>true ),
			array( 'supportRef', 'length', 'max' => 20 ),
			array( 'email, userAgent', 'ext.LengthSplitter', 'max' => 100 ),
			array( 'supportDate, actionDate, userAgent', 'safe' ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idUser, supportRef, supportDate, actionType, idProduct, actionDate, email, userAgent, queryString, idAffiliatePlatform, idAffiliateCampaign, idAffiliatePlatformSubId, idPromoSite, ip', 'safe', 'on'=>'search'),
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
			'Product' => array(self::BELONGS_TO, 'Product', 'idProduct'),
			'User' => array(self::BELONGS_TO, 'User', 'idUser'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idUser' => 'Id User',
			'supportRef' => 'Support Ref',
			'supportDate' => 'Support Date',
			'actionType' => 'Action Type',
			'idProduct' => 'Id Product',
			'idAffiliatePlatform' => 'Id Affiliate Platform',
			'idAffiliateCampaign' => 'Id Affiliate Campaign',
			'idAffiliatePlatformSubId' => 'Id Sub Id',
			'idPromoSite' => 'Id Promo Site',
			'actionDate' => 'Action Date',
			'email' => 'Email',
			'ip' => 'IP',
			'userAgent' => 'User Agent',
			'queryString' => 'Query String',
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
		$criteria->compare('idUser',$this->idUser);
		$criteria->compare('supportRef',$this->supportRef,true);
		$criteria->compare('supportDate',$this->supportDate,true);
		$criteria->compare('actionType',$this->actionType);
		$criteria->compare('idProduct',$this->idProduct);
		$criteria->compare('idAffiliatePlatform',$this->idAffiliatePlatform);
		$criteria->compare('idAffiliateCampaign',$this->idAffiliateCampaign);
		$criteria->compare('idAffiliatePlatformSubId',$this->idAffiliatePlatformSubId);
		$criteria->compare('idPromoSite',$this->idPromoSite);
		$criteria->compare('actionDate',$this->actionDate,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('userAgent',$this->userAgent,true);
		$criteria->compare('queryString',$this->controller,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}