<?php

/**
 * This is the model class for table "affiliateCampaign".
 *
 * The followings are the available columns in table 'affiliateCampaign':
 * @property integer $id
 * @property string $label
 * @property string $description
 *
 * The followings are the available model relations:
 * @property ChoiceTrackingCode[] $ChoiceTrackingCode
 * @property RouterPS[] $RouterPS
 *
 * @package Models.AffiliatePlatform
 */
class AffiliateCampaign extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AffiliateCampaign the static model class
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
		return 'affiliateCampaign';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('label', 'required'),
			array('label', 'length', 'max'=>50),
			array('description', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, description', 'safe', 'on'=>'search'),
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
			'ChoiceTrackingCode' => array(self::HAS_MANY, 'ChoiceTrackingCode', 'idAffiliateCampaign'),
			'RouterPS' => array(self::HAS_MANY, 'RouterPS', 'idAffiliateCampaign'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'label' => 'Label',
			'description' => 'Description',
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
		$criteria->compare('label',$this->label,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}