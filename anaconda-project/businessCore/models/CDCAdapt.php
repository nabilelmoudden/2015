<?php

/**
 * This is the model class for table "campaign". 
 *
 * The followings are the available columns in table 'campaign':
 * @property integer $id
 * @property string $label
 * @property integer $type
 * @property string $ref
 *
 * The followings are the available model relations:
 * @property Subcampaign[] $SubCampaign
 *
 * @package Models.Campaign
 */
class CDCAdapt extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Campaign the static model class
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
		return 'CDC_Adapt';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('date_prem_shoot', 'date', 'format'=>'yyyy-M-d', 'message'=>'Format date: YYYY-MM-DD'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idCampaign, ref, label', 'safe', 'on'=>'search'),
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
			//'Site' => array(self::BELONGS_TO, 'Site', 'site'),
			'Campaign' => array(self::BELONGS_TO, 'Campaign', 'idCampaign'),
			'CampaignAdapt' => array(self::HAS_MANY, 'CampaignAdapt', 'idCDCAdapt'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ref' => 'Réference',
			'label' => 'Label',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('ref',$this->ref, true);
		$criteria->compare('label',$this->label, true);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
