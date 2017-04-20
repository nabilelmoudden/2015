<?php 

/**
 * This is the model class for table "anacondasettings".
 *
 * The followings are the available columns in table 'anacondasettings':
 * @property integer $id
 * @property integer $groupPrice
 * @property integer $nextStepSum
 * @property integer $previousStepClicks
 * @property integer $durationNext
 * @property integer $durationPrevious
 * @property integer $idFirstCampaign
 *
 * @package Models.AnacondaSettings
 */
class AnacondaSettings extends ActiveRecord
{
	public $id;
	public $groupPrice;
	public $nextStepSum;
	public $previousStepClicks;
	public $durationNext;
	public $durationPrevious;
	public $idFirstCampaign;
	public $MaxGroupPrice;
	public $MinGroupPrice;
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
	 * @return AnacondaSettings the static model class
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
		return 'anacondaSettings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('groupPrice, nextStepSum, previousStepClicks, durationNext, durationPrevious', 'numerical', 'integerOnly'=>true),
		);
	} 

	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'groupPrice' => 'Price Group',
			'nextStepSum' => 'Sum To Next GP',
			'previousStepClicks' => 'Clicks To Previous GP',
			'durationNext' => 'Duration to Next GP',
			'durationPrevious' => ' Duration to Previous GP',
			'idFirstCampaign' => 'First Campaign ID'
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
		$criteria->select = 'id, groupPrice, nextStepSum, previousStepClicks, durationNext, durationPrevious';
		$criteria->compare('id',$this->id);
		$criteria->compare('groupPrice',$this->groupPrice,true);
		$criteria->compare('nextStepSum',$this->nextStepSum,true);
		$criteria->compare('previousStepClicks',$this->previousStepClicks,true);
		$criteria->compare('durationNext',$this->durationNext,true);
		$criteria->compare('durationPrevious',$this->durationPrevious,true);
	
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));;
	}
}