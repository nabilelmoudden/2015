<?php 

/**
 * @author Jalal BENSAAD
 * This is the model class for table "user".
 */
 
class User_V1 extends CActiveRecord
{
    public $id;
	public $civility;
	public $firstName;
	public $lastName;
	public $email;
	public $birthday;	
	public $creationDate;
	public $savToMonitor;
	public $version;
	public $password;
	public $address;
	public $country;
	public $city;
	public $phone;
	public $savComments;
	public $zipCode;
	public $visibleDesinscrire;
	public $CompteEMVactif;
  
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function TableName(){
		return 'internaute';
	}

	/**
	 * @return array validation rules for model attributes.
	 */

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Civility', 'length', 'max'=>5),
			array('Firstname, Lastname, Source', 'length', 'max'=>50),
			array('Email', 'length', 'max'=>100),
			array('ID, Civility, Firstname, country, city, phone, savComments, Lastname, Birthday, Email, CreationDate, savToMonitor, CP, visibleDesinscrire, CompteEMVactif', 'safe', 'on'=>'search'),
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
			'creationDate' => 'Creation Date',
			'savToMonitor' => 'To Monitor ( SAV )',
			'version'	=> 'V1',
			'address'	=> 'address',
			'password'	=> 'password',
			'country'	=> 'country',
			'city'	=> 'city',
			'phone'	=> 'phone',
			'savComments'	=> 'savComments',
			'zipCode'	=> 'CP'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */

	public function search(){
		$criteria = new CDbCriteria;
        $criteria->select = ' ID AS id, Civility AS civility, Firstname AS firstName, "" AS password, City AS city, Phone AS phone, Comment AS savComments, address AS address, CP AS zipCode, Country AS country, Lastname AS lastName, Email AS email, Birthday AS birthday, CreationDate as creationDate, "NULL" as savToMonitor, "V1" AS version, CompteEMVactif , visibleDesinscrire';
        $criteria->compare('id',$this->ID);
		$criteria->compare('civility',$this->civility);
		$criteria->compare('firstName',$this->firstName);
		$criteria->compare('lastName',$this->lastName);
		$criteria->compare('email',$this->email);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('creationDate',$this->creationDate);
		$criteria->compare('savToMonitor',$this->savToMonitor);
		$criteria->compare('version',$this->version);
		$criteria->compare('password',$this->password);
		$criteria->compare('address',$this->address);
		$criteria->compare('country',$this->country);
		$criteria->compare('zipCode',$this->zipCode);
		$criteria->compare('city',$this->city);
		$criteria->compare('phone',$this->phone);
		$criteria->compare('savComments',$this->savComments);
		$criteria->compare('visibleDesinscrire',$this->visibleDesinscrire);
		$criteria->compare('CompteEMVactif',$this->CompteEMVactif);	
		$criteria->order = 'CreationDate DESC';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria
		,  'pagination' => array(
                'pageSize' => 20,
            ),
		));
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchMail( $email ){
		$criteria=new CDbCriteria;
        $criteria->select = ' ID AS id, Civility AS civility, Firstname AS firstName, "" AS password, city AS city, phone AS phone, Comment AS savComments, address AS address, CP AS zipCode, country AS country, LastName AS lastName, Email AS email, Birthday AS birthday, CreationDate as creationDate, "NULL" as savToMonitor, "V1" AS version , visibleDesinscrire';
        $criteria->compare('email',$email,true);
        $criteria->compare('id',$this->ID);
		$criteria->compare('civility',$this->civility);
		$criteria->compare('firstName',$this->firstName);
		$criteria->compare('lastName',$this->lastName);
		$criteria->compare('email',$this->email);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('creationDate',$this->creationDate);
		$criteria->compare('savToMonitor',$this->savToMonitor);
		$criteria->compare('zipCode',$this->zipCode);
		$criteria->compare('version',$this->version);
		// $criteria->limit = 100;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria
		));
	}
	
	public function getUserById2($id){
		$criteria = new CDbCriteria();
		$criteria->select = ' ID AS id, Civility AS civility, Firstname AS firstName, "" AS password, city AS city, phone AS phone, "" AS savComments, address AS address, CP AS zipCode, country AS country, LastName AS lastName, Email AS email, Birthday AS birthday, CreationDate as creationDate, "NULL" as savToMonitor, "V1" AS version , visibleDesinscrire';
		$criteria->compare('id', $id);
		$criteria->limit = 1;
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria
		));
	}	
}