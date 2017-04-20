<?php

/**
 * This is the model class for table "anacondatrigger".
 *
 * The followings are the available columns in table 'anacondatrigger':
 * @property integer $id
 * @property integer $idTrigger
 * @property string $nameTrigger
 * @property integer $idSubCampaign
 *
 * @package Models.AnacondaTrigger
 */
class AnacondaTrigger extends ActiveRecord
{
	public $id;
	public $idTrigger;
	public $nameTrigger;
	public $idSubCampaign;
		
	public static $master_db;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AnacondaTrigger the static model class
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
		return 'anacondaTrigger';
	}
	
	public function getDbConnection() {
        self::$master_db = Yii::app()->db;
        if (self::$master_db instanceof CDbConnection) {
            self::$master_db->setActive(true);
            return self::$master_db;
        }
        else
        {
            throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));
        }
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, idSubCampaign', 'numerical', 'integerOnly'=>true),
			array('nameTrigger', 'length', 'max'=>50),
			array('idTrigger', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idSubCampaign, nameTrigger, idTrigger', 'safe', 'on'=>'search'),
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
			'Subcampaign' => array(self::BELONGS_TO, 'Subcampaign', 'idSubCampaign'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'idTrigger'=>' id trigger SF',
			'nameTrigger' => 'Nom du trigger',
			'idSubCampaign' => 'Id de la subcampaign',
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
		$criteria->compare('idTrigger',$this->idTrigger);
		$criteria->compare('nameTrigger',$this->nameTrigger);
		$criteria->compare('idSubCampaign',$this->idSubCampaign);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	} 
}