<?php

/**
 * This is the model class for table "internaute".
 *
 * The followings are the available columns in table 'internaute':
 * @property string $ID
 * @property string $Civility
 * @property string $Firstname
 * @property string $Lastname
 * @property string $Birthday
 * @property string $Email
 * @property string $Address
 * @property string $CP
 * @property string $City
 * @property string $Country
 * @property string $Phone
 * @property integer $IDAffiliatePlatform
 * @property string $CreationDate
 * @property string $UpdateTS
 * @property string $promo
 * @property integer $score
 * @property integer $tmpNbAchat
 * @property string $site
 * @property string $Source
 * @property integer $OptinPartner
 * @property string $Optin
 * @property integer $visibleDesinscrire
 * @property string $CompteEMVactif
 * @property string $Comment
 * @property string $DateValidationCheck
 */
class Internaute extends CActiveRecord
{
	var  $ID;
 	var  $Civility;
 	var  $Firstname;
 	var  $Lastname;
 	var  $Birthday;
 	var  $Email; 
 	var  $Address; 
 	var  $CP; 
 	var  $City; 
 	var  $Country; 
 	var  $Phone; 
 	var  $IDAffiliatePlatform; 
 	var  $CreationDate; 
 	var  $UpdateTS; 
 	var  $promo; 
 	var  $score; 
 	var  $tmpNbAchat; 
 	var  $site; 
 	var  $Source; 
 	var  $OptinPartner; 
 	var  $Optin; 
 	var  $visibleDesinscrire; 
 	var  $CompteEMVactif; 
 	var  $Comment; 
 	var  $DateValidationCheck; 

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Internaute the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'internaute';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Address, CP, City, Country, Phone, CreationDate, UpdateTS, Comment', 'required'),
			array('IDAffiliatePlatform, score, tmpNbAchat, OptinPartner, visibleDesinscrire', 'numerical', 'integerOnly'=>true),
			array('Civility', 'length', 'max'=>5),
			array('Firstname, Lastname, Source', 'length', 'max'=>50),
			array('Email', 'length', 'max'=>100),
			array('Address, City, Country', 'length', 'max'=>64),
			array('CP', 'length', 'max'=>12),
			array('Phone', 'length', 'max'=>32),
			array('promo, site', 'length', 'max'=>20),
			array('Optin', 'length', 'max'=>4),
			array('CompteEMVactif', 'length', 'max'=>26),
			array('Comment', 'length', 'max'=>254),
			array('Birthday, DateValidationCheck', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, Civility, Firstname, Lastname, Birthday, Email, Address, CP, City, Country, Phone, IDAffiliatePlatform, CreationDate, UpdateTS, promo, score, tmpNbAchat, site, Source, OptinPartner, Optin, visibleDesinscrire, CompteEMVactif, Comment, DateValidationCheck', 'safe', 'on'=>'search'),
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
			'Invoice' => array(self::HAS_MANY, '\Business\Invoice', array( 'IDInternaute' => 'ID' ) ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'Civility' => 'Civility',
			'Firstname' => 'Firstname',
			'Lastname' => 'Lastname',
			'Birthday' => 'Birthday',
			'Email' => 'Email',
			'Address' => 'Address',
			'CP' => 'Cp',
			'City' => 'City',
			'Country' => 'Country',
			'Phone' => 'Phone',
			'IDAffiliatePlatform' => 'Idaffiliate Platform',
			'CreationDate' => 'Creation Date',
			'UpdateTS' => 'Update Ts',
			'promo' => 'Promo',
			'score' => 'Score',
			'tmpNbAchat' => 'Tmp Nb Achat',
			'site' => 'Site',
			'Source' => 'Source',
			'OptinPartner' => 'Optin Partner',
			'Optin' => 'Optin',
			'visibleDesinscrire' => 'Visible Desinscrire',
			'CompteEMVactif' => 'Compte Emvactif',
			'Comment' => 'Comment',
			'DateValidationCheck' => 'Date Validation Check',
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

		$criteria->compare('ID',$this->ID,true);
		$criteria->compare('Civility',$this->Civility,true);
		$criteria->compare('Firstname',$this->Firstname,true);
		$criteria->compare('Lastname',$this->Lastname,true);
		$criteria->compare('Birthday',$this->Birthday,true);
		$criteria->compare('Email',$this->Email,true);
		$criteria->compare('Address',$this->Address,true);
		$criteria->compare('CP',$this->CP,true);
		$criteria->compare('City',$this->City,true);
		$criteria->compare('Country',$this->Country,true);
		$criteria->compare('Phone',$this->Phone,true);
		$criteria->compare('IDAffiliatePlatform',$this->IDAffiliatePlatform);
		$criteria->compare('CreationDate',$this->CreationDate,true);
		$criteria->compare('UpdateTS',$this->UpdateTS,true);
		$criteria->compare('promo',$this->promo,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('tmpNbAchat',$this->tmpNbAchat);
		$criteria->compare('site',$this->site,true);
		$criteria->compare('Source',$this->Source,true);
		$criteria->compare('OptinPartner',$this->OptinPartner);
		$criteria->compare('Optin',$this->Optin,true);
		$criteria->compare('visibleDesinscrire',$this->visibleDesinscrire);
		$criteria->compare('CompteEMVactif',$this->CompteEMVactif,true);
		$criteria->compare('Comment',$this->Comment,true);
		$criteria->compare('DateValidationCheck',$this->DateValidationCheck,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}