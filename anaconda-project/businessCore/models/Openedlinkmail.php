<?php 

/**
 * This is the model class for table "openedlinkmail".
 *
 * The followings are the available columns in table 'openedlinkmail':
 * @property integer $id
 * @property string $openedDate
 * @property integer $idUser
 * @property integer $idSubCampaignReflation
 *
 * @package Models.Openedlinkmail
 */
class Openedlinkmail extends ActiveRecord
{
	public $id;
	public $openedDate;
	public $idUser;
	public $idSubCampaignReflation;
	public $activityHour;
	public $modifiedShootDate;
	public $shiftDe;
	public static $master_db;
	
	
		public function getDbConnection() {
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
	 * @return Openedlinkmail the static model class
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
		return 'openedlinkmail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idUser, idSubCampaignReflation', 'required'),
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
				'Subcampaignreflation' => array(self::BELONGS_TO, 'Subcampaignreflation', 'idSubCampaignReflation'),
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
			'openedDate' => 'Mail Openning Date',
			'idUser' => 'id User',
			'idSubCampaignReflation' => 'id SubCampaign Reflation'
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
		$criteria->select = 'id, openedDate, idUser, idSubCampaignReflation';
		$criteria->compare('id',$this->id);
		$criteria->compare('openedDate',$this->openedDate,true);
		$criteria->compare('idUser',$this->idUser,true);
		$criteria->compare('idSubCampaignReflation',$this->idSubCampaignReflation,true);
		$criteria->compare('activityHour',$this->activityHour,true);
		$criteria->compare('modifiedShootDate',$this->modifiedShootDate,true);
		$criteria->compare('shiftDE',$this->shiftDE,true);
	
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));;
	}
	
	
	
	
	
	
	
}