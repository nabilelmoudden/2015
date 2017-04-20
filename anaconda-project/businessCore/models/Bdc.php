<?php

/**
 * Description of Bdc Model
 *
 * @author JulienL
 *
 * @package BDC
 */
class Bdc extends CFormModel
{
	public $price;
	public $idSite;

	public $civility;
	public $firstName;
	public $lastName;
	public $address;
	public $addressComp;
	public $zipCode;
	public $city;
	public $state;
	public $country;
	public $email;
	public $birthday;
	public $birthdayHour;
	public $cpf;
	public $paymentType;
	public $StringCTdate;
	public $Message;
	public $optin;
	public $compteEMVactif;

	public function __construct( $idSite, $price )
	{
		parent::__construct();

		$this->price	= $price;
		$this->idSite	= $idSite;
	}

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
				// Utiliser pour attribuer massivement de nouvel valeur ( Ex : $this->attributes = Yii::app()->request->getParam( 'Bdc' ); )
				array( 'civility, firstName, lastName, address, addressComp, zipCode, city, state, country, email, birthday, birthdayHour, cpf, StringCTdate, paymentType', 'safe' ),

				// PaymentType obligatoire dans tous les cas :
				array( 'paymentType', 'required', 'strict' => true ),
			);
	}

	/**
	 * Ajoute un element obligatoire a la saisie du BDC
	 * @param string $attr	Element a rendre obligatoire
	 * @return boolean
	 */
	public function addRequired( $attr )
	{
		$Validator	= $this->validatorList;

		if( $attr != 'addressComp' )
		{$Validator->add( CValidator::createValidator( 'required', $this, $attr ) );}

		switch( $attr )
		{
			case 'civility' :
				$Validator->add( CValidator::createValidator( 'in', $this, $attr, array( 'range' => array( 'm', 'md', 'mlle' ) ) ) );
				break;

			case 'email' :
			case 'firstName' :
			case 'lastName' :
				$Validator->add( CValidator::createValidator( 'length', $this, $attr, array( 'max' => 100 ) ) );
				break;

			case 'country' :
			case 'address' :
			case 'addressComp' :
			case 'city' :
			case 'state' :
				$Validator->add( CValidator::createValidator( 'length', $this, $attr, array( 'max' => 64 ) ) );
				break;

			case 'zipCode' :
				$Validator->add( CValidator::createValidator( 'numerical', $this, $attr, array( 'integerOnly' => true ) ) );
				break;

			case 'birthday' :
			case 'birthdayHour' :
				$Validator->add( CValidator::createValidator( 'length', $this, $attr, array( 'max' => 10 ) ) );
				break;

			case 'cpf' :
				$Validator->add( CValidator::createValidator( 'length', $this, $attr, array( 'max' => 24 ) ) );
				break;
		}

		return true;
	}

	/**
	 * Retourne le paymentProcessor par defuat/choisit
	 * @return \Business\PaymentProcessorType
	 */
	public function getPaymentProcessorType()
	{
		if( $this->paymentType <= 0 )
		{return false;}

		return \Business\PaymentProcessorType::load( $this->paymentType );
	}

	/**
	 * Retourne la date d'anniversaire ds le format desiré
	 * @param string $format
	 * @return string
	 */
	public function getBirthday( $format = false )
	{
		if( empty($this->birthday) )
		{return false;}

		$Date = \DateTime::createFromFormat( \Yii::app()->params['formatDate'], $this->birthday );
		return ( $format == false ) ? $Date : $Date->format( $format );
	}
}

?>