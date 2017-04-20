<?php

/**
 * @author AL.
 * This is the model class for table "alert".
 *
 * The followings are the available columns in table 'alert':
 * @property integer $id
 * @property string $creationDate
 * @property integer $statut
 * @property integer $idSubCampaign
 * @property integer $idEcart
 *
 *
 * @package Models.Alert
 */
class EcartShoot extends ActiveRecord
{
    public $id;
    public $activityHour;
    public $shootType;
    public $expected;
    public $shooted;
    public $unjoin;
    public $quarantaine;
    public $positionCampaign;
    public $standByIn;
    public $standByOut;
    public $reactivated;
    public $idEcart;
    public $idSubCampaignReflation;


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
		return 'ecartShoot';
	}

	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('statut', 'numerical', 'integerOnly'=>true, 'message'=>'Please enter an integer status'),
			//array('date_shoot', 'date', 'format'=>'yyyy-M-d', 'message'=>'Format date: YYYY-MM-DD'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, activityHour, shootType,expected,shooted,unjoin,quarantaine,positionCampaign', 'safe', 'on'=>'search'),
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
			//'AlertUser' => array(self::HAS_MANY, 'AlertUser', 'idAlert'),
			//'Comment' => array(self::HAS_MANY, 'Comment', 'idAlert' ),
			'Ecart' => array(self::BELONGS_TO, 'Ecart', 'idEcart'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'activityHour' => 'activityHour',
            'shooted' => 'Shooted',
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
		$criteria->compare('activityHour',$this->activityHour,true);
		$criteria->compare('shootType',$this->shootType);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}