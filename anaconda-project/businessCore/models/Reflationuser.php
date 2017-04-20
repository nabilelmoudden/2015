<?php 

/**
 * This is the model class for table "reflationuser".
 *
 * The followings are the available columns in table 'reflationuser':
 * @property integer $id
 * @property string $shootDate
 * @property integer $indiceImplication
 * @property integer $isNewLead
 * @property integer $openerJ1
 * @property integer $notOpenerJ2
 * @property integer $buyerJ2
 * @property integer $idUser
 * @property integer $idSubCampaignReflation
 *
 * @package Models.Reflationuser
 */
class Reflationuser extends ActiveRecord
{
	public $id;
	public $shootDate;
	public $indiceImplication;
	public $isNewLead;
	public $openerJ1;
	public $notOpenerJ2;
	public $buyerJ2;
	public $idUser;
	public $idSubCampaignReflation;
	public $email;
	public $linkmailName;
	public static $master_db;
	
	
		public function getDbConnection() {
        self::$master_db = Yii::app()->db;
        if (self::$master_db instanceof CDbConnection) {
            self::$master_db->setActive(true);
            return self::$master_db;
        }
        else
		{throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));}
    }
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Reflationuser the static model class
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
		return 'reflationuser';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('isNewLead, openerJ1, notOpenerJ2, buyerJ2', 'numerical', 'integerOnly'=>true),
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
		$criteria->select = 'id, shootDate, idUser, idSubCampaignReflation';
		$criteria->compare('id',$this->id);
		$criteria->compare('shootDate',$this->shootDate,true);
		$criteria->compare('idUser',$this->idUser,true);
		$criteria->compare('idSubCampaignReflation',$this->idSubCampaignReflation,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('linkmailName',$this->linkmailName,true);
	
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));;
	}
	
	
	
	
	
	
	
}