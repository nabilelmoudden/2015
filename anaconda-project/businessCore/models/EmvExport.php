<?php

/**
 * This is the model class for table "EmvExport".
 *
 * The followings are the available columns in table 'EmvExport':
 * @property string $EMAIL
 * @property string $SOURCE
 * @property integer $HBQ_REASON
 * @property string $DATEUNJOIN
 * @property string $DATEJOIN
 * @property int $type
 *
 */
class EmvExport extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EmvExport the static model class
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
		return 'EmvExport';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('EMAIL, type', 'required'),
			array('HBQ_REASON, type', 'numerical', 'integerOnly'=>true),
			array('EMAIL', 'length', 'max'=>255),
			array('SOURCE', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('EMAIL, SOURCE, HBQ_REASON, DATEUNJOIN, DATEJOIN, type', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'EMAIL' => 'Email',
			'SOURCE' => 'Source',
			'HBQ_REASON' => 'Hbq Reason',
			'DATEUNJOIN' => 'Date Unjoin',
			'DATEJOIN' => 'Date Join',
			'type' => 'Type',
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

		$criteria->compare('EMAIL',$this->EMAIL,true);
		$criteria->compare('SOURCE',$this->SOURCE,true);
		$criteria->compare('HBQ_REASON',$this->HBQ_REASON);
		$criteria->compare('DATEUNJOIN',$this->DATEUNJOIN,true);
		$criteria->compare('DATEJOIN',$this->DATEJOIN,true);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}