<?php

/**
 * This is the model class for table "campaign". 
 *
 * The followings are the available columns in table 'campaign':
 * @property integer $id
 * @property string $label
 * @property integer $type
 * @property string $ref
 * @property integer $idNextCampaign
 * @property integer $campaignStatus
 * @property integer $isAnaconda
 * @property integer $idLotCampaign
 *
 * The followings are the available model relations:
 * @property Subcampaign[] $SubCampaign
 *
 * @package Models.Campaign
 */
class Campaign extends ActiveRecord
{
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
		return 'campaign';
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
			array('label', 'length', 'max'=>50),
			array('label','required', 'message'=>'Please enter a value for {attribute}.'),
			array('ref','required', 'message'=>'Please enter a value for {attribute}.'),
			array('ref', 'unique', 'message'=>'Référence existe déjà, veuillez choisir une autre référence svp!'),
			array( 'ref', 'ext.RefValidator' ),
			//array('date_shoot', 'date', 'format'=>'yyyy-M-d', 'message'=>'Format date: YYYY-MM-DD'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, type, ref', 'safe', 'on'=>'search'),
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
			'SubCampaign' => array(self::HAS_MANY, 'Subcampaign', 'idCampaign'),
			'Invoice' => array(self::HAS_MANY, 'Invoice', array( 'ref' => 'campaign' ) ),
			'CampaignShoot' => array(self::HAS_MANY, 'CampaignShoot', 'id_campaign'),
			'LotCampaign' => array(self::BELONGS_TO, 'LotCampaign', 'idLotCampaign'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'label' => 'Label',
			'type' => 'Type',
			'ref' => 'Reference',
			'date_shoot' => 'Date de Shoot',
			'duree_shoot' => 'Duree de Shoot',
			'chainable' => 'Fid chainable',
			'split' => 'Split test',
			'nb_produit' => '2 Produits',
			'commentaire_shoot' => 'Commentaire Planning de Shoot',
			'date_creation_cdc_prev' => 'date previsionnelle de creation du CDC',
			'date_creation_cdc' => 'date de creation CDC',
			'state_project' => 'Etat du CDC'
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
		$criteria->compare('label',$this->label,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('ref',$this->ref);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}