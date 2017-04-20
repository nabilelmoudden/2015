<?php

/**
 * This is the model class for table "subcampaignreflation".
 *
 * The followings are the available columns in table 'subcampaignreflation':
 * @property integer $id
 * @property integer $number
 * @property integer $idSubCampaign
 * @property integer $offsetPriceStep
 * @property string $view
 * @property string $templateProd
 *
 * The followings are the available model relations:
 * @property Subcampaign $SubCampaign
 */
class Subcampaignreflation extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Subcampaignreflation the static model class
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
		return 'subcampaignreflation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number, idSubCampaign, offsetPriceStep', 'numerical', 'integerOnly'=>true),
			array( 'view, templateProd', 'ext.RefValidator' ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, number, idSubCampaign, offsetPriceStep, view, templateProd', 'safe', 'on'=>'search'),
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
			'SubCampaign' => array(self::BELONGS_TO, 'Subcampaign', 'idSubCampaign'),
			'Openedlinkmail' => array(self::HAS_MANY, 'Openedlinkmail', 'idSubCampaignReflation'),
			'Reflationuser' => array(self::HAS_MANY, 'Reflationuser', 'idSubCampaignReflation'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'number' => 'Number',
			'idSubCampaign' => 'Id Sub Campaign',
			'offsetPriceStep' => 'Offset Price Step',
			'view' => 'View',
			'templateProd' => 'Template Product'
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
		$criteria->compare('number',$this->number);
		$criteria->compare('idSubCampaign',$this->idSubCampaign);
		$criteria->compare('offsetPriceStep',$this->offsetPriceStep);
		$criteria->compare('view',$this->view, true);
		$criteria->compare('templateProd',$this->templateProd, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
