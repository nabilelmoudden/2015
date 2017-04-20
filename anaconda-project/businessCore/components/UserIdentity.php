<?php
/**
 * Class UserIdentity
 *
 * @author Julien L
 * @package UserLogin
 */
class UserIdentity extends CUserIdentity
{
	private $id;

	public function authenticate(){
		$User = \Business\User::loadByEmail( $this->username );
		if( $User != NULL ){
			if( $User->password === $this->password ){
				$this->id	= $User->id;

				// Transmis a la classe de gestion des droits ( UserAccess )
				// Accessible : Yii::app()->user->User
				$this->setState( 'User', $User );

				$this->errorCode = self::ERROR_NONE;
			}
			else
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}
		else
			$this->errorCode = self::ERROR_USERNAME_INVALID;

		return !$this->errorCode;
	}

	public function getId()
	{
		return $this->id;
	}
}
