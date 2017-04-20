<?php

/**
 * This is the model class for table "campaign".
 *
 * The followings are the available columns in table 'campaign':
 * @property integer $id
 * @property string $label
 * @property integer $type
 *
 * The followings are the available model relations:
 * @property Subcampaign[] $SubCampaign
 *
 * @package Models.Campaign
 */
class Achat extends ActiveRecord
{
	public $year = "all";
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
		return 'demande_achat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			//array('idAffiliatePlatform', 'numerical', 'integerOnly'=>true, 'min'=>1),
			array('service_demandeur, service_acheteur, date, designation, date_livraison', 'required', 'message'=>'Ce champ est obligatoire.'),
			//array('dateInvoice', 'date', 'format'=>'yyyy-M-d', 'message'=>'Format date: YYYY-MM-DD'),
			array('id, date, designation, service_demandeur, service_acheteur, date_livraison, statut', 'safe', 'on'=>'search'),
		);
	}
 
	/** 
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
				'LigneAchat' => array(self::HAS_MANY, 'LigneAchat', 'idAchat'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Achat',
			'date' => 'Date',
			'titre' => 'Titre',
			'date_livraison' => 'Date livraison souhaitée',
		);
	}


	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->addSearchCondition('id', $this->id);
		$criteria->addSearchCondition('service_demandeur', $this->service_demandeur);
		$criteria->addSearchCondition('service_acheteur', $this->service_acheteur);
		$criteria->addSearchCondition('date_livraison', $this->date_livraison);		
		$criteria->addSearchCondition('designation', $this->designation);
		$criteria->addSearchCondition('statut', $this->statut);	
				
		if($this->year != "all")
		{$criteria->addSearchCondition('date',$this->date);}
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,'pagination'=>array(
        'pageSize'=>25,
    ),
		));
	}


	public function searchValideCompte($val,$demandeur)
	{
		$criteria=new CDbCriteria;
		if ($demandeur=="") {
			$criteria->addSearchCondition('id', $this->id);
		$criteria->addSearchCondition('service_demandeur', $this->service_demandeur);
		$criteria->addSearchCondition('service_acheteur', $this->service_acheteur);
		$criteria->addSearchCondition('date_livraison', $this->date_livraison);		
		$criteria->addSearchCondition('designation', $this->designation);
		$criteria->compare('date',$this->date, true);
		$criteria->compare('statut',$val, true);
		
		}
		else {
		$criteria->addSearchCondition('id', $this->id);
		
		$criteria->addSearchCondition('service_acheteur', $this->service_acheteur);
		$criteria->addSearchCondition('date_livraison', $this->date_livraison);		
		$criteria->addSearchCondition('designation', $this->designation);
		$criteria->compare('date',$this->date, true);
		$criteria->compare('statut',$val, true);
		$criteria->compare('service_demandeur',$demandeur, true);
	}


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,'pagination'=>array(
        'pageSize'=>25,
    ),
		));
	}

	public function searchByService($val)
	{
		$criteria=new CDbCriteria;
		$criteria->addSearchCondition('id', $this->id);
		$criteria->addSearchCondition('service_demandeur', $val);
		$criteria->addSearchCondition('service_acheteur', $this->service_acheteur);
		$criteria->addSearchCondition('date_livraison', $this->date_livraison);		
		$criteria->addSearchCondition('designation', $this->designation);
		$criteria->compare('date',$this->date, true);		
		$criteria->compare('statut',$this->statut);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,'pagination'=>array(
        'pageSize'=>25,
    ),
		));
	}



}
