<?php 

/**
 * This is the model class for table "ecartDelivery".
 *
 * The followings are the available columns in table 'ecartDelivery':
 * @property integer $id
 * @property integer $step
 * @property integer $fidPosition
 * @property integer $buyerdJ
 * @property integer $buyerdJ1
 * @property integer $buyerdJ2
 * @property integer $delivered
 * @property integer $testDeliveries
 * @property integer $idEcart
 * @property integer $idSubCampaign
 *
 * @package Models.EcartDelivery
 */
class EcartDelivery extends ActiveRecord
{
	public $id;
 	public $step;
 	public $fidPosition;
 	public $buyerdJ;
 	public $buyerdJ1;
	public $buyerdJ2;
	public $delivered;
 	public $testDeliveries;
	public $idEcart;
 	public $idSubCampaign;
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
		return 'ecartDelivery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('step, fidPosition, buyerdJ, buyerdJ1, buyerdJ2, delivered, testDeliveries', 'numerical', 'integerOnly'=>true),
		);
	} 
	/**
	 *
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array (
				'SubCampaign' => array (
						self::BELONGS_TO,
						'\Business\SubCampaign',
						'idSubCampaign'
				),
				 'Ecart' => array (
						self::BELONGS_TO,
						'\Business\Ecart',
						'idEcart'
	
				) ,
		);
	}

	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'step' => 'Etape',
			'fidPosition' => 'Position de la FID',
			'buyerdJ' => 'Acheteur J',
			'buyerdJ1' => 'Acheteur J-1',
			'buyerdJ2' => 'Acheteur J-2',
			'delivered' => 'Livrés',
			'testDeliveries' => 'Livraison de test',
			'idEcart' => 'ID Ecart',
			'idSubCampaign' => 'ID SubCampaign',
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
		$criteria->select = 'id, step, fidPosition, buyerdJ, buyerdJ2, delivered, testDeliveries, idEcart, idSubCampaign';
		$criteria->compare('id',$this->id);
		$criteria->compare('step',$this->step,true);
		$criteria->compare('fidPosition',$this->fidPosition,true);
		$criteria->compare('buyerdJ',$this->buyerdJ,true);
		$criteria->compare('buyerdJ2',$this->buyerdJ2,true);
		$criteria->compare('delivered',$this->delivered,true);
		$criteria->compare('testDeliveries',$this->testDeliveries,true);
		$criteria->compare('idEcart',$this->idEcart,true);
		$criteria->compare('idSubCampaign',$this->idSubCampaign,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}