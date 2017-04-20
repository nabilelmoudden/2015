<?php 

/**
 * This is the model class for table "anacondaSubdivision".
 *
 * The followings are the available columns in table 'anacondaSubdivision':
 * @property integer $id
 * @property string $emailUser
 * @property integer $purchasedOldAnaconda
 * @property integer $subdivised
 *
 * @package Models.AnacondaSubdivision
 */
class AnacondaSubdivision extends ActiveRecord
{
	public $id;
	public $emailUser;
	public $purchasedOldAnaconda;
	public $subdivised=0;
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
		return 'anacondaSubdivision';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('purchasedOldAnaconda, subdivised', 'numerical', 'integerOnly'=>true),
		);
	} 

	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'emailUser' => 'User Email',
			'purchasedOldAnaconda' => 'Number Of Purshased Old Fids Anaconda',
			'subdivised' => 'Subdivised'
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
		$criteria->select = 'id, emailUser, purchasedOldAnaconda, subdivised';
		$criteria->compare('id',$this->id);
		$criteria->compare('emailUser',$this->emailUser,true);
		$criteria->compare('purchasedOldAnaconda',$this->purchasedOldAnaconda,true);
		$criteria->compare('subdivised',$this->subdivised,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}