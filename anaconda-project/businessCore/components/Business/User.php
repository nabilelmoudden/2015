<?php

namespace Business;

/**
 * Description of User
 *
 * @author JulienL
 * @package Business.User
 */
class User extends \User
{
	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'AbandonedCaddy' => array(self::HAS_MANY, '\Business\Abandonedcaddy', 'idAdmin'),
			'EvtEMV' => array(self::HAS_MANY, '\Business\EvtEMV', 'idUser'),
			'Invoice' => array(self::HAS_MANY, '\Business\Invoice', array( 'emailUser' => 'email' ) ),
			'LeadAffiliatePlatform' => array(self::HAS_MANY, '\Business\LeadAffiliatePlatfom', 'idUser'),
			'Log' => array(self::HAS_MANY, '\Business\Log', 'idUser'),
			'Role' => array(self::MANY_MANY, '\Business\Role', $this->tableNamePrefix().'userrole(idUser, idRole)'),
			'CampaingHistory' => array(self::HAS_MANY, 'CampaingHistory', 'idUser'),
			'Openedlinkmail' => array(self::HAS_MANY, 'Openedlinkmail', 'idUser'),
		);
	}

	/**
	 * Retourne le nom complet de l'utilisateur
	 * @return string	Nom complet
	 */
	public function name(){
		return ucfirst($this->firstName).' '.strtoupper($this->lastName);
	}

	public function getRoleName(){
		return Yii::app()->user->getState("User")->Role[0]->name;
	}
	
	/**
	 * Retourne un objet DateTime representant la date de naissance
	 * @return \DateTime
	 */
	public function getBirthday( $format = false ){	
		if( $this->birthday == '0000-00-00 00:00:00' )
			return false;
		if( $this->birthday == '')
	        $this->birthday = '1985-02-02 15:59:29';
	        
	    $this->birthday = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $this->birthday)));
	        
		$Date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $this->birthday );
		
		return ( $format == false ) ? $Date : $Date->format($format);
	}


	/**
	 * Retourne le numero du signe astrologique
	 * @return int
	 */
	public function getNumberSignAstro(){
		\Yii::import( 'ext.DateHelper' );
		return \DateHelper::getAstroSign( $this->getBirthday( 'Y-m-d' ) );
	}

	/**
	 * Retourne le nom du signe astrologique
	 * @return string
	 */
	public function getSignAstro(){
		\Yii::import( 'ext.DateHelper' );
		$tab = \DateHelper::getAstroSignByNumber( $this->getNumberSignAstro() );
		return ( isset($tab[1]) ) ? $tab[1] : false;
	}

	
		
	/**
	 * Retourne le numero de l'ange
	 * @return int
	 */
	public function getNumberAngeAstro(){
		\Yii::import( 'ext.DateTabAnge' );
		return \DateTabAnge::getAstroAnge( $this->getBirthday( 'Y-m-d' ) );
	}

	/**
	 * Retourne le nom du signe astrologique
	 * @return string
	 */
	public function getAngeAstro(){
		\Yii::import( 'ext.DateTabAnge' );
		$tab = \DateTabAnge::getAstroAngeByNumber( $this->getNumberAngeAstro() );
		return ( isset($tab[1]) ) ? $tab[1] : false;
	}

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function search( $order = false, $pageSize = 1000 , $email = '', $civility = '', $lastName = '', $firstName = '', $savToMonitor = ''){ 
		$Provider = parent::search();
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;
		
		if($email !== '')
			$Provider->criteria->compare('email', $email, true);
		if($civility !== '')
			$Provider->criteria->compare('civility',$civility,false);
		if($lastName !== '')
			$Provider->criteria->compare('lastName',$lastName,true);
		if($firstName !== '')
			$Provider->criteria->compare('firstName',$firstName,true);
		if($savToMonitor !== '')
			$Provider->criteria->compare('savToMonitor', 1,true);
		if( $order != false )
			$Provider->criteria->order = $order;

		return $Provider;
	}
	
	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function searchClientToMonitor( $order = false, $pageSize = false, $savToMonitor = ''){ 
		$Provider = parent::search();
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;
			
		if($savToMonitor !== '')
			$Provider->criteria->compare('savToMonitor', 1,true);
		
		if( $order != false )
			$Provider->criteria->order = $order;

		return $Provider;
	}

	/**
	 * Recherche des administateur
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function searchAdmin( $order = false, $pageSize = 0 )
	{
		$Provider = $this->search( $order, $pageSize );

		$UserRole					= new \Business\UserRole();
		$Provider->criteria->alias	= 'User';
		$Provider->criteria->join	= 'INNER JOIN '.$UserRole->tableName().' as UserRole ON UserRole.idUser = User.id';
		$Provider->criteria->group	= 'User.id';

		return $Provider;
	}

	/**
	 * Defini les roles attribué a l'utilisateur
	 * @param array $role	Roles a attribuer
	 * @return boolean
	 */
	public function setAdmin( $roles )
	{
		// On vide les roles existants :
		$UserRole			= new \Business\UserRole( 'search' );
		$UserRole->deleteAll( 'idUser = '.$this->id );

		// On affecte les nouveaux roles :
		if( is_array($roles) && count($roles) > 0 )
		{
			foreach( $roles as $idRole )
			{
				$UserRole			= new \Business\UserRole();
				$UserRole->idUser	= $this->id;
				$UserRole->idRole	= $idRole;

				if( !$UserRole->save() )
					return false;
			}
		}

		return true;
	}


	public function setAdmin2( $roles, $id )
	{
		
		// On vide les roles existants :
		$UserRole			= new \Business\UserRole( 'search' );
		$UserRole->deleteAll("idUser = $id");

		// On affecte les nouveaux roles :
		if( is_array($roles) && count($roles) > 0 )
		{
			foreach( $roles as $nameRole )
			{

				$UserRole			= new \Business\UserRole();
				$role = \Role::model()->findByAttributes(array('name'=>$nameRole));
				$UserRole->idUser	= $id;
				$UserRole->idRole	= $role->id;

				if( !$UserRole->save() )
					return false;
			}
		}

		return true;

	}



	public function setAdminAll( $roles, $listSite )
	{
		\Controller::loadConfigForPorteur($listSite);
		// On vide les roles existants :
		$UserRole			= new \Business\UserRole( 'search' );
		$UserRole->deleteAll( 'idUser = '.$this->id );

		// On affecte les nouveaux roles :
		if( is_array($roles) && count($roles) > 0 )
		{
			foreach( $roles as $nameRole )
			{
				$UserRole			= new \Business\UserRole();
				$role = \Role::model()->findByAttributes(array('name'=>$nameRole));
				//$id = \Role::model()->getId($role->name, $listSite);
				
				$UserRole->idUser	= $this->id;
				$UserRole->idRole	= $role->id;

				if( !$UserRole->save() )
					return false;
			}
		}

		return true;
	}
	/**
	 * Verifie si un role est attribué a l'utilisateur
	 * @param int $idRole Id du role
	 * @return bool true/false
	 */
	public function isRole( $idRole )
	{
		foreach( $this->Role as $Role )
		{
			if( $Role->id == $idRole )
				return true;
		}
		return false;
	}

	/**
	 * Retourne le nom des differents roles, separé par une virgule, attribué a l'utilisateur
	 * @return string
	 */
	public function getNameAllRoles()
	{
		$ret = NULL;
		foreach( $this->Role as $Role )
			$ret .= $Role->name.', ';
		return substr( $ret, 0, -2 );
	}
	
	/**
	 * Excute web form Update les infos clients EMV
	 * @return string|false Retour de la requete, false en cas de probleme
	 */
	Public function SendToEMV($type = 'updateClient'){
		$porteur = \Yii::app()->params['porteur'];
		/*$acq = false;
		if($GLOBALS['porteurWithTwoSFAccounts'][$porteur]){
			if($this->compteEMVactif != '' && strpos($this->compteEMVactif, 'FID') == false)
				$acq = true;
		}
		if($type == 'updateClient'){
			$url_WebForm = ($acq ?  $GLOBALS['porteurWebformUpdateClient'][$porteur.'_acq'] : $GLOBALS['porteurWebformUpdateClient'][$porteur]);
		}elseif($type == 'inscrire'){
			$url_WebForm = ($acq ?  $GLOBALS['porteurWebformInscrirClient'][$porteur.'_acq'] : $GLOBALS['porteurWebformInscrirClient'][$porteur]);
		}elseif($type == 'desincrire'){
			$url_WebForm = ($acq ?  $GLOBALS['porteurWebformDesinscrirClient'][$porteur.'_acq'] : $GLOBALS['porteurWebformDesinscrirClient'][$porteur]);
		}*/
		
		if($GLOBALS['porteurWithTwoSFAccounts'][$porteur] && $this->compteEMVactif != ''){
			$porteur = $GLOBALS['SFAccountsMap'][$this->compteEMVactif] ;
		}
		
		
		switch ($type){
			case "updateClient":
				$url_WebForm =  $GLOBALS['porteurWebformUpdateClient'][$porteur];
				break;
			case "inscrire":
				$url_WebForm = $GLOBALS['porteurWebformInscrirClient'][$porteur];
				break;
			case "desincrire":
				$url_WebForm = $GLOBALS['porteurWebformDesinscrirClient'][$porteur];
				break;
		}
		
		$WF = new \WebForm($url_WebForm);
		$WF->setTokenWithUser( $this );
		return $WF->execute( true );
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\User
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 * Recupere un User par son adresse mail
	 * @param type $mail
	 * @return \Business\User
	 */
	static public function loadByEmail( $mail )
	{
		/*if(!self::model()->findByAttributes( array( 'email' => $mail ) ))
		{
			$array = array( ['id'] => 0,		
							['civility']   => '',
							['firstName']    => '',
							['lastName']    => '',
							['birthday']    => '',
							['email']    => $mail,
							['address']    => '',
							['zipCode']    => '',
							['city']    => '',
							['state']    => '',
							['country']    => '',
							['phone']    => '',
							['creationDate']    => '',
							['updateTS']    => '',
							['score']    => 0,
							['optinPartner']    => 0,
							['optin']   => 0,
							['visibleDesinscrire']   => 0,
							['compteEMVactif']    => '',
							['password']    => '',
							['status']    => 0,
							['savToMonitor']    => 0,
							['savComments']    => 0
						);
						
				return (object)$array;
			
		}else{
			
			//return self::model()->findByAttributes( array( 'email' => $mail ));
			//$criteria=new CDbCriteria;
			///$criteria->addInCondition('email', $mail);
            
		
		}*/
		
		return self::model()->findByAttributes(array( 'email' => $mail ));
	}
//------------------------------------
/*	public function insertNewuserSites($post)
	{
		$error = [];
		$i=0;

		
		foreach($post['Sites'] AS $sites){

				@include(SERVER_ROOT.$GLOBALS['listPorteur'][$sites]['folder'].'/confi.php');
				if(isset($bdd_server)){
					if(!mysql_connect($bdd_server, $bdd_login, $bdd_mdp)){
					$error[] = 'error: ' . mysql_error();
					}
			}
			if(isset($bdd_database)){
				if(!mysql_select_db($bdd_database)){
					$error[] = 'error DB: ' . mysql_error();
					continue;
			}
			}
				//------------------------
				$sql = "SELECT email as userid
				FROM   V2_user
				WHERE  V2_user.email ='".$post['email']."'";

				$result = mysql_query($sql);

			if (!$result) {
				echo "Could not successfully run query ($sql) from DB " ;
				exit;
			}

				if (mysql_num_rows($result) == 0) {
					//echo "No rows found, nothing to print so am exiting";
					$datebirth= date("d-m-Y", strtotime($post['birthday']));
				$query = "INSERT INTO `V2_user`(`civility`, `firstName`, `lastName`, `birthday`, `email`, `address`, `addressComp`, `zipCode`, `city`, `state`, `country`, `phone`, `creationDate`, `updateTS`, `score`, `optinPartner`, `optin`, `visibleDesinscrire`, `compteEMVactif`, `password`, `status`, `savToMonitor`, `savComments`, `AuthorizedIP`) VALUES ('".$post['civility']."','".$post['firstName']."','".$post['lastName']."','".$datebirth."','".$post['email']."','".$post['address']."','','".$post['zipCode']."','".$post['city']."','','".$post['country']."','".$post['phone']."','".date("Y-m-d H:i:s")."','','','','','','','".$post['password']."','','".$post['savToMonitor']."','".$post['savComments']."','".$post['AuthorizedIP']."')";
				$query2 = "delete from V2_userrole where idUser=(select id from V2_user where V2_user.email='".$post['email']."')";
					if(!mysql_query($query) && !mysql_query($query2))
					$error[] = 'create error: ' . mysql_error();
				for($j=0;$j<count($post['roles']);$j++){
					
					if(SERVER_ROOT.$GLOBALS['listPorteur'][$sites]['port-name']=="Theodor DE")
				$query3 ="INSERT INTO `V2_userrole` (`idUser`, `idRole`) select u.id,r.id from V2_user u,V2_role r WHERE u.email='".$post['email']."' and r.id =".$post['roles'][$j]+4;
						else
				$query3 ="INSERT INTO `V2_userrole` (`idUser`, `idRole`) select u.id,r.id from V2_user u,V2_role r WHERE u.email='".$post['email']."' and r.id =".$post['roles'][$j];
					
									if(!mysql_query($query3))
					$error[] = 'create error: ' . mysql_error();
				
														}

				}
				else{
					//echo "result found";
					$datebirth= date("d-m-Y", strtotime($post['birthday']));
				$query = "UPDATE V2_user SET `civility`='".$post['civility']."', `firstName`='".$post['firstName']."', `lastName`='".$post['lastName']."', `birthday`='".$datebirth."', `email`='".$post['email']."', `address`='".$post['address']."', `addressComp`='', `zipCode`='".$post['zipCode']."', `city`='".$post['city']."', `state`='', `country`='".$post['country']."', `phone`='".$post['phone']."', `creationDate`='".date("Y-m-d H:i:s")."', `updateTS`='', `score`='', `optinPartner`='', `optin`='', `visibleDesinscrire`='', `compteEMVactif`='', `password`='".$post['password']."', `status`='', `savToMonitor`='".$post['savToMonitor']."', `savComments`='".$post['savComments']."', `AuthorizedIP`='".$post['AuthorizedIP']."' WHERE V2_user.email = '".$post['email']."'";
					if(!mysql_query($query))
					$error[] = 'create error: ' . mysql_error();
					$query4 = "delete from V2_userrole where idUser=(select id from V2_user where V2_user.email='".$post['email']."')";
					if(!mysql_query($query4))
						$error[] = 'create error: ' . mysql_error();
					
				for($j=0;$j<count($post['roles']);$j++){
				$query3 ="INSERT INTO `V2_userrole` (`idUser`, `idRole`) select u.id,r.id from V2_user u,V2_role r WHERE u.email='".$post['email']."' and r.id =".$post['roles'][$j];
									if(!mysql_query($query3))
					$error[] = 'create error: ' . mysql_error();
														}

				}


			mysql_free_result($result);

				$i++;

			}
		
		if($i == 0)
			return false;
		else
			return true;
	}*/
	//----------------------------------------
	//------------------------------------
	public function sendMail($post2)
	{
		/*apelle du fichier  mail et smtp*/
					spl_autoload_unregister(array('YiiBase', 'autoload'));
					try {
						\Yii::import('ext.SMTP', true);
						\Yii::import('ext.PHPMailer', true);
					} catch (Exception $e) {
						spl_autoload_register(array('YiiBase', 'autoload'));
						throw $e;
					}
					/*fin apelle du fichier  mail et smtp*/

					$mail = new \PHPMailer();
					$mail->IsSMTP(); // telling the class to use SMTP
					$mail->SMTPAuth = true;     // turn on SMTP authentication 
					$mail->SMTPSecure = "TLS";
					$mail->Host = "178.32.45.183"; // SMTP server
					$mail->Username = "kindy@votredevenir.com";
					$mail->FromName = "Adminesys";
					$mail->Password = "M4d_b_4@";
					$mail->CharSet = "utf-8";
					$mail->ContentType = "text/html";
					$mail->From = "kindy@votredevenir.com";
					$mail->AddAddress($post2['email']);
					$mail->Subject = "login"; 		
					$mail->returnBody($post2['firstName'],$post2['email'],$post2['password']) ;
					$mail->WordWrap = 50;
					if(!$mail->Send()) 
					{
					   echo 'Message was not sent.';
					   echo 'Mailer error: ' . $mail->ErrorInfo;
					}
					else
					{
					   echo 'Message has been sent.';
					}
					/*fin de l'envoi*/
	}
	

	/**
	 * @author soufiane balkaid
	 * @desc renvoie l'indice d'implication d'un user
	 * @param @email
	 * @return int 
	 */

	public function getEmailByIdUser($idUser) {
		return self::model ()->findByAttributes ( array (
				'id' => $idUser
		) )->email;
		
	}
	
	

	public function getIndiceImplicationByUser($email) {
		return self::model()->findByAttributes ( array (
				'email' => $email 
		) )->indiceImplication;
	}
	
	/**
	 * @author soufiane balkaid
	 * @desc renvoie  le total d'indice d'implication d'un user
	 * @param @email
	 * @return int
	 */
	
	public function getTotalImplication() {
		return $this->totalIndice;
	}
	/**
	 * @author soufiane balkaid
	 * @desc update le total d'indice d'implication et l'indice d'un user
	 * @param @email
	 */
	public function updateTotalIndice() {
		
		$this->totalIndice = $this->totalIndice + $this->indiceImplication;
		$this->indiceImplication = 0;
		$this->save ();
	}
	//----------------------------------------
	
	
	
	/****************************************** Load By SB Banns ****************************************************************/
	/**
	 * @author Yacine RAMI
	 * @desc Retourne la liste des utilisateur ayant atteint un nombre de soft bounces passe en parametre.
	 * @param integer	$csb nombre softbounces.
	 * @return	User[] liste des Users.
	 */
	static public function loadBySBBann($csb)
	{
		return self::model()->findAll(array(
				'condition'=>'countSoftBounce = :csb',
				'params'=>array(':csb'=>$csb),
		));	
	}
	
	/************************************* / Load By SB Banns *******************************************************************/
	
	/************************************** Load Subscribed  ********************************************************************/
	/**
	 * @author Yacine RAMI
	 * @desc Retourne la liste des utilisateur qui ne sont pas desabonnes.
	 * @return	User[] liste des Users.
	 */
	static public function loadSubscribed()
	{
		return self::model()->findAll(array(
				'condition'=>'visibleDesinscrire IS NULL OR visibleDesinscrire=0',
  		));
	}
	
	/************************************************ / Load Subscribed ***************************************************************/
	
	/****************************** MAJ indice d implication ***********************************************************/
	/**
	 * maj indice d implication selon le step d achat
	 * @param integer	step d achat.
	 * @return
	 */
	public function updateIndiceImplication($step)
	{
		switch ($step)
		{
			//ldv ou r1
			case 1:
			case 2:
				$this->indiceImplication += 3;
				$this->save();
				break;
			//r2 ou r3
			case 3:
			case 4:
				$this->indiceImplication += 2;
				$this->save();
				break;
			//r4
			case 5:
				$this->indiceImplication += 1;
				$this->save();
				break;
			default:
				break;
				
		}
	}
	

	public function indiceImplication($step, $position)
	{
		if ($position == 1) {
			switch ($step) {
				// ldv ou r1
				case 1 :
				case 2 :
					$indice = 3;
				// r2 ou r3
				case 3 :
				case 4 :
					$indice = 2;
				// r4
				case 5 :
					$indice = 1;
				default :
					break;
			}
		} else if ($position == 2) {
			switch ($step) {
				// ldv
				case 1 :
					$indice = 3;
				// r1
				case 2 :
					$indice = 2;
				// r2
				case 3 :
					$indice = 1;
				default :
					break;
			}
		}
		return $indice;
	}



	 public  function updateIndiceInplicationByEmail($point){
		$this->indiceImplication+=$point;
		$this->save();
	}
	
	/**
	 * @author Saad HDIDOU
	 * @desc r�cuperer les clients inactifs
	 * @param $date
	 * @return User[] liste des Users
	 */
	static public function loadInactiveUsers($date)
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = 'dateBanning LIKE \'%'.$date.'%\'';
		
		return self::model()->findAll($criteria); 
	}


    public static function testML()
    {
        return "Test";
    }
//************************************ Amileoration de la Reactivation ***************************************************//

	/**
	 * @author Anas HILAMA
	 * @desc r�cuperer les clients inactifs qui ont fait un une ouverture
	 * @param
	 * @return User[] liste des Users
	 */


	static public function getReactUsersByOpening() {



		$criteria = new \CDbCriteria;
		$criteria->alias = "User" ;
		$criteria->join  =  "INNER JOIN  `V2_openedlinkmail` `olm` ON User.id = olm.idUser" ;
		$criteria->condition = " User.bannReason = 1 AND ( olm.openedDate BETWEEN  '".date("Y-m-d H:i:s", strtotime("- 1 day"))."'  AND '".date("Y-m-d H:i:s")."')" ;


				return self::model()->findAll( $criteria );


	}
	/**
	 * @author Anas HILAMA
	 * @desc r�cuperer les clients inactifs qui ont fait un BdcClick
	 * @param
	 * @return User[] liste des Users
	 */

	static public function getReactUsersByClick() {



		$criteria = new \CDbCriteria;
		$criteria->alias = "User" ;
		$criteria->join  =  "INNER JOIN  `V2_userBehavior` `behavior` ON User.id = behavior.id" ;

		$criteria->condition = " User.bannReason = 1  AND  ( behavior.lastDateClick BETWEEN  '".date("Y-m-d H:i:s", strtotime("- 1 day"))."'  AND '".date("Y-m-d H:i:s")."')" ;


		return self::model()->findAll( $criteria );


	}

	//************************************ FIN  Amileoration de la Reactivation ***************************************************//

	/****************************** /Load Subscribed visibleDesinscrire***********************************************************/

    /**
	 * author Marouane
     * @param idSubCampaign
     * @param number
     * @return list of users
     */
    public function loadBySubcampaignAndMessageNumber()
	{
		return self::model()->findAll();
	}
	/**
	 * author Marouane
     * @param idSubCampaign
     * @param number
     * @return list of users
     */
    public static function loadByIndice($indice)

    {
        return self::model()->findAllByAttributes(array('indiceImplication' => $indice));

    }

    public static function loadById($id)
    {
        return self::model()->findByPk($id);
    }


	public function getOriginByUser()
	{
		return $this->origin;
	}

	static public function getUsersByOriginAndDate($date,$origin)
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = 'origin = 1 AND intialDate Like \''.$date->format('Y-m-d').'%\'' ;
		return self::model()->findAll( $criteria );
	}


	static public function getReactivatedUsersByDate($date)
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = 'reactivationDate Like \''.$date->format('Y-m-d').'%\'' ;
		return self::model()->findAll( $criteria );
	}
}

?>
