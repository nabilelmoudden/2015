<?php

/**
 * This is the model class for table "site".
 *
 * The followings are the available columns in table 'site':
 * @property integer $id
 * @property string $code
 * @property string $devise
 * @property string $codeDevise
 * @property string $nameDevise
 * @property string $country
 */
class Site extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Site the static model class
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
		return 'site';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, devise, codeDevise, nameDevise, country', 'required'),
			array('code', 'length', 'max'=>2),
			array('devise, nameDevise', 'length', 'max'=>10),
			array('codeDevise', 'length', 'max'=>3),
			array('country', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, devise, codeDevise, nameDevise, country', 'safe', 'on'=>'search'),
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
            'AffiliatePlatform' => array(self::HAS_MANY, 'Affiliateplatform', 'idSite'),
			'Invoice' => array(self::HAS_MANY, 'Invoice', 'codeSite'),
			'PricingGrid' => array(self::HAS_MANY, 'Pricinggrid', 'idSite'),
			'PaymentProcessorType' => array(self::HAS_MANY, 'PaymentprocessorType', 'idSite'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'devise' => 'Devise',
			'codeDevise' => 'Code Devise',
			'nameDevise' => 'Name Devise',
			'country' => 'Country',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('devise',$this->devise,true);
		$criteria->compare('codeDevise',$this->codeDevise,true);
		$criteria->compare('nameDevise',$this->nameDevise,true);
		$criteria->compare('country',$this->country,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}