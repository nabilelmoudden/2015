<?php

/**
 * Description of UserAccess
 *
 * @author JulienL
 * @package UserLogin
 */
class UserAccess extends CWebUser
{
	/**
	 * Connecte a nouveau l'utilisateur courant
	 * ( Utiliser lors d'un changement de porteur/DB )
	 * @return boolean
	 */
	public function relogin(){
		$Login				= new \LoginForm();
		$Login->attributes	= array(
			'username' => $this->getState('User')->email,
			'password' => $this->getState('User')->password
		);

		if( $Login->validate() && $Login->login() )
			return true;
		else
			return false;
	}

	/**
	 * Controle les droits d'accces
	 * @param string $operation Name of the operation required (here, a role).
     * @param mixed $params (opt) Parameters for this operation, usually the object to access.
	 * @return bool Permission granted?
	 */
	public function checkAccess($operation, $params = array()){
		if(empty($this->id))
            return false;

		// Recupere les roles de l'utilisateur :
		$role = $this->getState("User")->Role;
        if( !is_array($role) || count($role) <= 0 )
			return false;

		// Boucle autour des roles pour controler si l'utilisateur a acces :
		for( $i=0; $i<count($role); $i++ )
			if( $role[$i]->name === $operation )
				return true;

		// sinon l'utilisateur n'a pas acces :
        return false;
	}
}

?>