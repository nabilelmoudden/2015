<?php



/**
 * This is the model class for table "segmentation".
 *
 * The followings are the available columns in table 'segmentation':
 * @property integer $id
 * @property string $nom_fid
 * @property string $date_shoot
 * @property integer $nb_adresses
 * @property integer $nb_jour_shoot
 * @property string $source
 * @property string $dateCreation
 * @property string $site
 */
class Segmentation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Segmentation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function TableName()
	{
		return 'segmentation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nom_fid, date_shoot, nb_adresses, nb_jour_shoot, source, dateCreation, site', 'required'),
			array('nb_adresses, nb_jour_shoot', 'numerical', 'integerOnly'=>true),
			array('nom_fid', 'length', 'max'=>255),
			array('source', 'length', 'max'=>50),
			array('site', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nom_fid, date_shoot, nb_adresses, nb_jour_shoot, source, dateCreation, site', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nom_fid' => 'Nom Fid',
			'date_shoot' => 'Date Shoot',
			'nb_adresses' => 'Nb Adresses',
			'nb_jour_shoot' => 'Nb Jour Shoot',
			'source' => 'Source',
			'dateCreation' => 'Date Creation',
			'site' => 'Site',
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
		$criteria->compare('nom_fid',$this->nom_fid,true);
		$criteria->compare('date_shoot',$this->date_shoot,true);
		$criteria->compare('nb_adresses',$this->nb_adresses);
		$criteria->compare('nb_jour_shoot',$this->nb_jour_shoot);
		$criteria->compare('source',$this->source,true);
		$criteria->compare('dateCreation',$this->dateCreation,true);
		$criteria->compare('site',$this->site,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}