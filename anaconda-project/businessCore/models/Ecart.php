<?php 

/**
 * This is the model class for table "ecart".
 *
 * The followings are the available columns in table 'ecart':
 * @property integer $id
 * @property Date $creationDate 
 * @property integer $type
 *
 * @package Models.Ecart
 */
class Ecart extends ActiveRecord
{
	public $id;
 	public $creationDate;
 	public $type;
	public static $master_db;
	
	
	public function getDbConnection() 
	{
        self::$master_db = Yii::app()->db;
        if (self::$master_db instanceof CDbConnection) {
            self::$master_db->setActive(true);
            return self::$master_db;
        }
        else
            throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));
    }
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AnacondaSubdivision the static model class
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
		return 'ecart';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type', 'numerical', 'integerOnly'=>true),
		);
	} 
	/**
	 *
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array ();
	}

	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'creationDate' => 'Date Creation',
			'type' => 'Type',
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->select = 'id, creationDate, type';
		$criteria->compare('id',$this->id);
		$criteria->compare('creationDate',$this->creationDate,true);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}