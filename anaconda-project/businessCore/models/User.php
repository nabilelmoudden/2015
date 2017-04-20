<?php 

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $civility
 * @property string $firstName
 * @property string $lastName
 * @property string $birthday
 * @property string $email
 * @property string $address
 * @property string $addressComp
 * @property string $zipCode
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $phone
 * @property string $creationDate
 * @property string $updateTS
 * @property integer $score
 * @property integer $optinPartner
 * @property integer $optin
 * @property integer $visibleDesinscrire
 * @property string $compteEMVactif
 * @property string $password
 * @property integer $status
 * @property integer $savToMonitor
 * @property integer $savComments
 * @property integer $indiceImplication
 * @property integer $totalIndice
 * @property string $dateGpChange
 * @property integer $bannReason
 * @property integer $countSoftBounce
 * @property string $intialDate
 * @property string $dateBanning
 * @property string $origin
 * @property string $reactivationDate
 * @property string $quarantaine
 *
 * The followings are the available model relations:
 * @property Abandonedcaddy[] $AbandonedCaddy
 * @property Evtemv[] $EvtEMV
 * @property Invoice[] $Invoice
 * @property Leadaffiliateplatfom[] $LeeadAffiliatePlatform
 * @property Log[] $log
 * @property Role[] $Role
 *
 * @package Models.User
 */
class User extends ActiveRecord
{
	public $id;
	public $civility;
	public $firstName;
	public $lastName;
	public $birthday;
	public $email;
	public $address;
	public $addressComp;
	public $zipCode;
	public $city;
	public $state;
	public $country;
	public $phone;
	public $creationDate;
	public $updateTS;
	public $score;
	public $optinPartner;
	public $optin;
	public $visibleDesinscrire;
	public $compteEMVactif;
	public $password;
	public $status;
	public $savToMonitor;
	public $savComments;
	public $indiceImplication;
	public $totalIndice;
	public $dateGpChange;
	public $bannReason;
	public $countSoftBounce;
	public $intialDate;
	public $dateBanning;
	public $origin;
	public $reactivationDate;
	public $quarantaine;
	public $version;
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
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('score, optinPartner, optin, visibleDesinscrire, status, savToMonitor, indiceImplication, totalIndice, bannReason, countSoftBounce', 'numerical', 'integerOnly'=>true),
			array('civility', 'length', 'max'=>5),
			array('firstName, lastName, state, password', 'length', 'max'=>50),
			array('email, addressComp', 'length', 'max'=>100 ),
			array('address, city, country', 'length', 'max'=>64),
			array('zipCode', 'length', 'max'=>12),
			array('phone', 'length', 'max'=>32),
			array('compteEMVactif', 'length', 'max'=>24),
			array('birthday, creationDate, updateTS, savComments, dateGpChange, intialDate, dateBanning', 'safe'),
			array('email', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('civility, firstName, lastName, state, birthday, email, address, addressComp, zipCode, city, country, phone, creationDate, updateTS, score, optinPartner, optin, visibleDesinscrire, compteEMVactif, password, status, savToMonitor, savComments, indiceImplication, totalIndice, bannReason, countSoftBounce, dateGpChange, intialDate, dateBanning', 'safe', 'on'=>'search'),
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
			'AbandonedCaddy' => array(self::HAS_MANY, 'Abandonedcaddy', 'idAdmin'),
			'EvtEMV' => array(self::HAS_MANY, 'Evtemv', 'idUser'),
			'Invoice' => array(self::HAS_MANY, 'Invoice', array( 'email' => 'emailUser' ) ),
			'LeeadAffiliatePlatform' => array(self::HAS_MANY, 'Leadaffiliateplatfom', 'idUser'),
			'Log' => array(self::HAS_MANY, 'Log', 'idUser'),
			'Role' => array(self::MANY_MANY, 'Role', 'userrole(idUser, idRole)'),
			'CampaingHistory' => array(self::HAS_MANY, 'CampaingHistory', 'idUser'),
			'Openedlinkmail' => array(self::HAS_MANY, 'Openedlinkmail', 'idUser'),
			'Reflationuser' => array(self::HAS_MANY, 'Reflationuser', 'idUser'),
				
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'civility' => 'Civility',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'birthday' => 'Birthday',
			'email' => 'Email',
			'address' => 'Address',
			'zipCode' => 'Zip Code',
			'city' => 'City',
			'State' => 'State',
			'country' => 'Country',
			'phone' => 'Phone',
			'creationDate' => 'Creation Date',
			'updateTS' => 'Update Ts',
			'score' => 'Score',
			'optinPartner' => 'Optin Partner',
			'optin' => 'Optin',
			'visibleDesinscrire' => 'Visible Desinscrire',
			'compteEMVactif' => 'Compte Emvactif',
			'password' => 'Password',
			'status' => 'Status',
			'savToMonitor' => 'To Monitor ( SAV )',
			'savComments' => 'SAV Comments',
			'version'	=> 'V2',
			'indiceImplication'	=> 'Indice Implication',
			'totalIndice'	=> 'Total Indice',
			'bannReason'	=> 'Bann Reason',
			'countSoftBounce'	=> 'Count Soft Bounce',
			'dateGpChange'	=> 'Date Gp Change',
			'intialDate'	=> 'Intial Date',
			'dateBanning'	=> 'Date Banning',
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
		$criteria->select = 'id, civility, firstName, lastName, birthday, email, address, zipCode, city, country, phone, creationDate, updateTS, score,  optinPartner, optin, visibleDesinscrire, compteEMVactif, password, status, savToMonitor, savComments, "V2" AS version';
		$criteria->compare('id',$this->id);
		$criteria->compare('civility',$this->civility,false);
		$criteria->compare('firstName',$this->firstName,true);
		$criteria->compare('lastName',$this->lastName,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('zipCode',$this->zipCode,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('creationDate',$this->creationDate,true);
		$criteria->compare('updateTS',$this->updateTS,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('optinPartner',$this->optinPartner);
		$criteria->compare('optin',$this->optin);
		$criteria->compare('visibleDesinscrire',$this->visibleDesinscrire);
		$criteria->compare('compteEMVactif',$this->compteEMVactif,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('status',$this->status);
        $criteria->compare('savToMonitor',$this->savToMonitor);
        $criteria->compare('savComments',$this->savComments, true);
		$criteria->compare('version',$this->version);
		$criteria->compare('indiceImplication',$this->indiceImplication);
		$criteria->compare('totalIndice',$this->totalIndice);
		$criteria->compare('bannReason',$this->bannReason);
		$criteria->compare('countSoftBounce',$this->countSoftBounce);
		$criteria->compare('dateGpChange',$this->dateGpChange);
		$criteria->compare('intialDate',$this->intialDate);
		$criteria->compare('dateBanning',$this->dateBanning);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));;
	}
}