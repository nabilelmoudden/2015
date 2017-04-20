<?php



class AllUsers extends ActiveRecord{
	/**
	 * @return string the associated database table name
	 */
	public function rawTableName(){
		return 'user';
	}

	public $porteur;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return array(
			array('civility', 'length', 'max'=>5),
			array('firstName, lastName, state, password', 'length', 'max'=>50),
			array('email, addressComp', 'length', 'max'=>100 ),
			array( 'email', 'required' ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('civility, firstName, lastName, email, creationDate, version', 'safe', 'on'=>'search'),
		);
	}



	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'civility' => 'Civility',  
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'email' => 'Email',
			'creationDate' => 'Creation Date',
			'savToMonitor' => 'To Monitor ( SAV )'
		);
	}

	

	// ************************** SETTER ************************** //

	public function setPorteur( $porteur )

	{

		$this->porteur = $porteur;

		return \Controller::loadConfigForPorteur( $porteur );

	}



	

	/**

	 * Retrieves a list of models based on the current search/filter conditions.

	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.

	 */

	public function search($customProfil = NULL){
	    if(Yii::app()->request->getParam( 'Business\AllUsers' ) == NULL){
			
			$allUsers_v1     = new \Business\User_V1;
			$allUsers_v2     = new \Business\User;
			// Create filter model and set properties
			Yii::import("application.classes.Filtersform"); 
			$filtersForm  = new FiltersForm;
			
			//Test if is the first loading of page customerProfile
			if($customProfil != NULL){
				//Not load any data
				$records = array();
			}
			else{			
				if( Yii::app()->request->getParam( 'monitor' ) !== NULL )
				{$records      = $allUsers_v2->searchClientToMonitor(false, false, Yii::app()->request->getParam('monitor'))->data;}
				else
				{$records      = array_merge($allUsers_v2->search()->data, $allUsers_v1->search()->data);}
			}
			
		}else{
			
			$allUsers_v1     = new \Business\User_V1;
			$allUsers_v2     = new \Business\User;
			// Create filter model and set properties
			Yii::import("application.classes.Filtersform"); 
			$filtersForm  = new FiltersForm;
			$attributes = Yii::app()->request->getParam( 'Business\AllUsers' );
			if(($attributes['email'] !== '' || $attributes['civility'] !== '' || $attributes['firstName'] !== '' || $attributes['lastName'] !== '') && Yii::app()->request->getParam( 'monitor' ) == NULL){
				$records      = array_merge($allUsers_v1->search(false, false, $attributes['email'], $attributes['civility'], $attributes['lastName'], $attributes['firstName'], Yii::app()->request->getParam( 'monitor' ))->data , 
											$allUsers_v2->search(false, false, $attributes['email'], $attributes['civility'], $attributes['lastName'], $attributes['firstName'])->data);

			}elseif(Yii::app()->request->getParam( 'monitor' ) !== NULL)

			{$records      = array_merge($allUsers_v2->search(false, false, $attributes['email'], $attributes['civility'], $attributes['lastName'], $attributes['firstName'], Yii::app()->request->getParam( 'monitor' ))->data);}

			else

			{$records      = array_merge($allUsers_v1->search()->data , $allUsers_v2->search()->data);}

		}

	      

	    

	    $filteredData = $filtersForm->filter($records);

	    

	    $provAll = new CArrayDataProvider($filteredData,
                        array(
                           'keyField'=>false,
                           'sort' => array( //optional and sortring
                                'attributes' => array('id', 'civility','firstName', 'lastName','email','creationDate', 'saveToMonitor', 'visibleDesinscrire', 'version'),
                            ),
                            'pagination' => array('pageSize' => 20) //optional add a pagination
                        ));
		return $provAll;
	}
}

