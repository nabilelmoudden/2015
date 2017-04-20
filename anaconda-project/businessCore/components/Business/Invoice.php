<?php

namespace Business;

/**
 * Description of Invoice
 *
 * @author JulienL
 * @package Business.Invoice
 */
class Invoice extends \Invoice
{
	/**
	* Invoice Status
	*/
	const INVOICE_CADDIE		= 0;
	const INVOICE_IN_PROGRESS	= 1;
	const INVOICE_PAYED			= 2;
	const INVOICE_ERROR			= 3;
	const INVOICE_CANCEL		= 4;

	/**
	* Refund Status
	*/
	const INVOICE_REFUND_NOT_ASKED		= 0;
	const INVOICE_REFUND_IN_PROGRESS	= 11;
	const INVOICE_REFUNDED				= 12;
	const INVOICE_REFUND_REFUSED		= 22;

	public function init(){
		parent::init();
		$this->onAfterSave	= array( $this, 'checkRefundStatus' );
	}
	/**
	 * Controle si le refundStatus de la commande a changé apres une mise a jour
	 * Si c'est le cas, controle si c'est un client a surveiller
	 * @return boolean
	 */

	protected function checkRefundStatus(){
		// Si c'est une mise a jour de l'invoice
		if( !$this->isNewRecord ){
			$oldAttributes	= $this->getOldAttributes();
			$newAttributes	= $this->getAttributes();

			// Si le refundStatus a changer, on verifie si le client est a surveiller

			if( isset($oldAttributes['refundStatus']) && isset($newAttributes['refundStatus']) && $oldAttributes['refundStatus'] != $newAttributes['refundStatus'] )
				self::checkIfClientIsToMonitor( $this->emailUser );
		}
		return true;
	}

	/**
	 * Ajoute une valeur additionnel
	 * @param string $name
	 * @param mixed $val
	 * @param int $indexRecord
	 * @return boolean
	 */

	public function setAdditionnalValue($name, $val, $indexRecord = 0){
		if( !$this->RecordInvoice[ $indexRecord ] )
			return false;

		if( ($RecordInvoiceAnnexe = $this->RecordInvoice[ $indexRecord ]->RecordInvoiceAnnexe) == NULL){
			$RecordInvoiceAnnexe				= new \Business\RecordInvoiceAnnexe();
			$RecordInvoiceAnnexe->idPoste		= $this->RecordInvoice[ $indexRecord ]->id;
			$RecordInvoiceAnnexe->productExt	= new \StdClass();
		}

		$additionnalValues					= $RecordInvoiceAnnexe->productExt;
		$additionnalValues->$name			= $val;
		$RecordInvoiceAnnexe->productExt	= $additionnalValues;
		return $RecordInvoiceAnnexe->save();
	}

	/**
	 * Retourne une valeur additionnel
	 * @param string $name
	 * @param int $indexRecord
	 * @return mixed
	 */

	public function getAdditionnalValue( $name, $indexRecord = 0 ){
		if( isset($this->RecordInvoice[ $indexRecord ]) )
			return false;

		$RecordInvoiceAnnexe = $this->RecordInvoice[ $indexRecord ]->RecordInvoiceAnnexe;
		return isset($RecordInvoiceAnnexe->productExt->$name) ? $RecordInvoiceAnnexe->productExt->$name : false;
	}

	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */

	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'User' => array(self::BELONGS_TO, '\Business\User', array( 'emailUser' => 'email' ) ),
			'AbandonedCaddy' => array(self::BELONGS_TO, '\Business\AbandonedCaddy', 'idAbandonedCaddy'),
			'RecordInvoice' => array(self::HAS_MANY, '\Business\RecordInvoice', 'idInvoice'),
			'Site' => array(self::BELONGS_TO, '\Business\Site', 'codeSite'),
			'Campaign' => array(self::BELONGS_TO, '\Business\Campaign', array( 'campaign' => 'ref' ) )
		);
	}


	/**
	 * Rajoute une fonctionnalité specifique a la classe
	 * @return array
	 */
	public function behaviors(){
		return array_merge(
					parent::behaviors(),
					array( 'ActiveRecordBehavior' => 'application.components.ActiveRecordBehavior' )
				);
	}

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */

	public function search($order = false, $pageSize = 0){
		$Provider = parent::search($pageSize);
		// $Provider = $this->searchRefundInvoice( $order, $pageSize );
		if($pageSize == false)
			$Provider->setPagination(false);
		else
			$Provider->pagination->pageSize = $pageSize;
		if( $order != false )
			$Provider->criteria->order = $order;
		return $Provider;
	}


	public function getId(){
		return $this->id;
	}

	/**
	 * Créé une entré dans la table Invoice pour l'utilisateur courant
	 * @param \Business\User $User Utilisateur
	 * @param \Business\SubCampaign $SubCampaign SubCampaign
	 * @param \Business\Site $site Site
	 * @param \Bdc $Bdc Bon de commande
	 * @return	boolean	True / False si l'insert est bien passé
	 */
	public function create($User, $SubCampaign, $Site, $Bdc ){
		$this->creationDate		= date( \Yii::app()->params['dbDateTime'] );
		$this->emailUser		= $User->email;
		$this->codeSite			= $Site->code;
    	$this->campaign			= $SubCampaign->Campaign->ref;
		$this->invoiceStatus	= self::INVOICE_CADDIE;
		$this->refundStatus		= self::INVOICE_REFUND_NOT_ASKED;
		$this->currency			= $Site->codeDevise;
		$this->paymentProcessor	= $Bdc->getPaymentProcessorType()->ref;

		$PariteInvoice			= new \Business\PariteInvoice('search');
		$this->parity			= $PariteInvoice->loadByDevise($Site->codeDevise)->parite;

		if($Bdc->getPaymentProcessorType()->name == 'DINEROMAIL'){
			$this->subPaymentProcessor	= $Bdc->getPaymentProcessorType()->name;
		}

		if($this->updateUserWithBdcInfo($Bdc , $this->emailUser) &&  $this->save()){
			$this->refInterne	= $Bdc->getPaymentProcessorType()->getParam('prefix').'_'.$this->id;
			return true;
		}else{
			return false;
		}

	}



	/**
	 * Ajoute un Record a l'invoice
	 * @param \Business\Product $Product Reference du produit
	 * @param int $qty Quantite
	 * @param \Business\PriceEngine $PriceEngine Price Engine
	 * @param \Business\SubCampaignReflation $SubCampaignReflation SubCampaignReflation
	 * @return boolean true / false
	 */
	public function addRecord( $Product, $qty, $PriceEngine, $SubCampaignReflation ){
		if( !$this->id )
			throw new \EsoterException( 100, \Yii::t( 'error', '100' ) );
		if( !is_object($PriceEngine) || !($PG = $PriceEngine->getPrice( $SubCampaignReflation )) ){
			$this->errorNumber	= 1;
			$this->errorMessage	= 'No pricing grid found';
			$this->save();
			throw new \EsoterException( 106, \Yii::t( 'error', '106' ) );
		}
		$this->refBatchSelling		= $PriceEngine->getRefBatchSelling();
		$this->priceStep			= $PriceEngine->getPriceStep();
		$this->refPricingGrid		= $PriceEngine->getRefPricingGrid();
		$RecordInvoice				= new \Business\RecordInvoice();
		$RecordInvoice->idInvoice	= $this->id;
		$RecordInvoice->refProduct	= $Product->ref;
		$RecordInvoice->qty			= $qty;
		$RecordInvoice->priceATI	= $PG->priceATI;
		$RecordInvoice->priceVAT	= $PG->priceVAT;
		return $RecordInvoice->save();
	}

	/**
	 * Finalise l'invoice
	 * @return \Business\ConfigPaymentProcessor
	 */
	public function finalize(){
		$this->invoiceStatus = self::INVOICE_IN_PROGRESS;
		foreach( $this->RecordInvoice as $RI )
			if( $RI->RecordInvoiceAnnexe != NULL && !$RI->RecordInvoiceAnnexe->save() )
				return false;
		// Met a jour l'invoice :
		if( $this->User->save() && $this->save() )
			return $this->engagePayment();
		else
			return false;
	}

	/**
	 * Retourne le montant total de l'invoice
	 * @return	float	Total
	*/
	public function getUserByMail(){
		$user = \Business\User::loadByEmail($this->emailUser);
		if( $user != NULL )
			return $user->firstName.' '.$user->lastName;
		return '';
	}

   /**
	 * Retourne le montant total de l'invoice
	 * @return	float	Total
	*/
	public function getFirstNameByMail(){
		$user = \Business\User::loadByEmail($this->emailUser);
		if( $user != NULL )
			return $user->firstName;
		return '';
	}

	/**
	 * Retourne le montant total de l'invoice
	 * @return	float	Total
	*/
	public function getlastNameByMail(){
		$user = \Business\User::loadByEmail($this->emailUser);
		if( $user != NULL )
			return $user->lastName;
		return '';
	}


	/**
	 * Excute web form Update les infos clients EMV
	 * @return string|false Retour de la requete, false en cas de probleme
	 */

	public function SendToEMV($type = 'UrlRefundDone'){
		$porteur = \Yii::app()->params['porteur'];
		$user = \Business\User::loadByEmail($this->emailUser);

		/*if($type == 'UrlRefundDone'){
			$url_WebForm = $GLOBALS['UrlRefundDone'][$porteur];
		}else{
			if($type == 'UrlRefundReceived'){
			   $url_WebForm = $GLOBALS['UrlRefundReceived'][$porteur];
			}else if($type == 'UrlResendProduct'){
				$url_WebForm = $GLOBALS['UrlResendProduct'][$porteur];
			}
		}*/

		if($GLOBALS['porteurWithTwoSFAccounts'][$porteur] && $user->compteEMVactif != ''){
			$porteur = $GLOBALS['SFAccountsMap'][$user->compteEMVactif] ;
		}

		switch ($type){
			case "UrlRefundDone":
				$url_WebForm = $GLOBALS['UrlRefundDone'][$porteur];
				break;
			case "UrlRefundReceived":
				$url_WebForm = $GLOBALS['UrlRefundReceived'][$porteur];
				break;
			case "UrlResendProduct":
				$url_WebForm = $GLOBALS['UrlResendProduct'][$porteur];
				break;
		}

		$WF = new \WebForm($url_WebForm);
		$WF->setTokenWithInvoice($this);
		return $WF->execute( true );
	}

	/**
	 * Engage le paiement
	 * @return \Business\ConfigPaymentProcessor	Config Payment Processor
	 * @throws \EsoterException
	 */
	protected function engagePayment(){
		$PP = \Business\PaymentProcessor::loadByRef( $this->paymentProcessor, $this->id );
		if( !is_object($PP) )
			throw new \EsoterException( 103, \Yii::t( 'error', '103' ) );
		return $PP->engagePayment();
	}



	/**
	 * Permet de mettre a jour l'utilisateur en DB par rapport a BDC
	 * @param \Bdc $bdc Bon de commande
	 * @return bool	true / false
	 */
	private function updateUserWithBdcInfo( $Bdc , $email){
		if( !is_object($this->User)){
			$User              = new \Business\User();
			$map	= array(
								'civility' => 'Civility',
								'firstName' => 'First Name',
								'lastName' => 'Last Name',
								'birthday' => 'Birthday',
								'email' => 'Email',
								'address' => 'Address',
								'zipCode' => 'Zip Code',
								'city' => 'City',
								'country' => 'Country',
								'addressComp' => 'addressComp',
								'optin' => 'Optin',
								'compteEMVactif' => 'Compte Emvactif'
							);

			foreach($map as $key => $value){
				if(!empty($value)){
					if( $key == 'birthday' ){
						$User->birthday = $Bdc->getBirthday( \Yii::app()->params['dbDateTime'] );
					}else{
					    $User->$key = $Bdc->$key;
					}
				}
			}

		    $User->creationDate = date( \Yii::app()->params['dbDateTime'] );
		    $this->User = $User;
			return true;
			//return false;

		}else{
		  foreach( $Bdc as $k => $v )
		  {
			    $User = new \Business\User( 'search' );
				if( !empty($v) && property_exists($User, $k) && $this->User->$k != $v )
				{
					if( $k == 'birthday' ){
						$this->User->birthday = $Bdc->getBirthday( \Yii::app()->params['dbDateTime'] );
					}else{
						$this->User->$k = $v;
					}
				}
		  }

		  return true;
		}
	}


	/**
	 * Envoi une requete a EMV
	 * @param string $type	Voir \Business\RouterEMV::$tabType
	 * @return boolean
	 * @throws \EsoterException
	 */
	public function sendRequestToEMV( $type ){
		if( !in_array( $type, \Business\RouterEMV::$tabType ) )
			throw new \EsoterException( 109, \Yii::t( 'error', '109' ).' : '.$type );

		if( count($this->RecordInvoice) <= 0 )
			return false;

		for( $i=0; $i<count($this->RecordInvoice); $i++ ){
			$Product	= $this->RecordInvoice[ $i ]->Product;
			$Router		= $Product->RouterEMV( array( 'condition' => 'type = "'.$type.'"' ) );

			for( $j=0; $j<count($Router); $j++ ){
			    if( ($type == \Business\RouterEMV::URL_INTENTION_CHEQUE) || ($type == \Business\RouterEMV::URL_PAYMENT_DM) || ($type == \Business\RouterEMV::URL_INTENTION_MULTIBANCO) || ($type == \Business\RouterEMV::URL_PAYMENT_MULTIBANCO) || ($type == \Business\RouterEMV::URL_PAIEMENT_VG)){
					if( !$Router[$j]->sendRequest( $this ,false ,true ) )
						return false;
			    }else{
					if($type == \Business\RouterEMV::URL_PAIEMENT ){
						// mail('sd.mimouni@gmail.com', 'paiement ruck 2 ', 'paiement : '.\Yii::app()->params['porteur'].' and email: '.$this->User->email);
						if( !$Router[$j]->sendRequest( $this ,false ,false ) )
							return false;
					}else{

						if( !$Router[$j]->sendRequest( $this ) )
							return false;

					}
			    }

			}
		}
		return true;
	}


	/**
	 * Retourne les refs des produits de l'invoice
	 * @return array	Refs Products
	 */
	public function getProductsRef(){
		if(count($this->RecordInvoice) <= 0 )
			return false;
		$ret = array();
		for($i=0; $i<count($this->RecordInvoice); $i++ )
			$ret[] = $this->RecordInvoice[$i]->refProduct;
		return $ret;
	}

	/**
	 * Retourne Url produits de l'invoice
	 * @return array Url Product
	 */
	public function getUrlProducts(){

		$porteur = \Yii::app()->params['porteur'];
		if($porteur!='en_aasha' && $porteur!='en_alisha'){
			$ConfDNS      = \Business\Config::loadByKeyAndSite( 'DNS' );
		}else{
			$ConfDNS      = \Business\Config::loadByKey( 'DNS' );
		}
		$Urlparams      = $_SERVER['REQUEST_URI'];
		$repertoir      = explode("/", $Urlparams);
		$date 		    = date_create($this->creationDate);
		$date_naissance = date_create($this->getUser()->birthday);

		//$SubCampaign	= \Business\SubCampaign::load( $idSubCampaign );

		if(count($this->RecordInvoice) <= 0 )
			return false;

		for($i=0; $i<count($this->RecordInvoice); $i++ ){
			$ret = $this->RecordInvoice[$i]->refProduct;
			if($this->RecordInvoice[$i]->Product != null)
				$asile_type = $this->RecordInvoice[$i]->Product->asile_type;
			else
				$asile_type = "";

				}

		$sp = explode("_", $ret);
		$num_tags = count($sp) - 1;

		//if(($this->campaign =='nsc' || $this->campaign =='acm' || $this->campaign =='pss') && $sp[$num_tags] ==1){
		if($asile_type == "asile" && $sp[$num_tags] ==1) {
			$tr = 1;
			$sp[$num_tags] = 2;
		}else{
			$tr = ($ret == 'VG')? 1 : 111;
		}

		if($sp[$num_tags] != 1 && $sp[$num_tags] != 2){
			$sp[$num_tags] = 1;
		}
		
		$this->refPricingGrid =  ((int)$this->refPricingGrid != 0 ) ? $this->refPricingGrid : 1 ;
		
		$Myurl = $ConfDNS->value.'/'.$repertoir[1].'/index.php/Site/index?ref='.$this->campaign.'&tr='.$tr.'&gp='.$this->refPricingGrid.'&bs='.$this->refBatchSelling.'&sp='.$sp[$num_tags].'&p='.$this->getUser()->firstName.'&n='.$this->getUser()->lastName.'&d='.date_format($date_naissance, 'd/m/Y').'&x='.$this->getUser()->civility.'&m='.$this->emailUser.'&site='.$this->codeSite.'&de='.date_format($date, 'm/d/Y').'&sd='.date_format($date, 'm/d/Y');
		return $Myurl;
	}

	/**
	 * Retourne le montant total de l'invoice
	 * @return	float	Total
	 */
	public function getTotalInvoice(){
		if( count($this->RecordInvoice) <= 0 )
			return false;
		$total = 0;
		for( $i=0; $i<count($this->RecordInvoice); $i++ )
			$total += $this->RecordInvoice[$i]->priceATI;
		return $total;
	}

	public function getChronoById(){
	  return $this->chrono;
	 }

	 public function getRefInterneById(){
	  return $this->ref1Transaction;
	 }


	/**
	 * Retourne un tableau contenant la description de chaque produit de la commande
	 * @return array Tableau de description
	 */
	public function getDescription(){
		if( count($this->RecordInvoice) <= 0 )
			return false;
		$desc = array();
		for( $i=0; $i<count($this->RecordInvoice); $i++ ){
				if(is_object($this->RecordInvoice[$i]->Product))
					$desc[] = $this->RecordInvoice[$i]->Product->description;
			}
		return $desc;
	}



	/**
	 * Recherche les invoices avec le refundStatus = 11 OU 12
	 * @param int $pageSize	Nb de result par page
	 * @return \CActiveDataProvider	CActiveDataProvider
	 */
	public function searchRefundInvoice( $order = false, $emailUser = '',$paymentProcessor = '', $refundStatus='' , $pageSize = 0, $type = ""){
		$Provider = $this->search($order, $pageSize);
		if($type == 'Check')
			$Provider->criteria->addCondition(' paymentProcessor like "%Check%"');
		if($type == 'CB'){
		    $Provider->criteria->select = ' *, emailUser AS pricePaid1 ';
			$Provider->criteria->addCondition(' paymentProcessor not like "%Check%"');
		}
		$Provider->setPagination(false);
		if($emailUser != ''){
			$Provider->criteria->addCondition(' emailUser LIKE "%'.$emailUser.'%" ');
		}
		if($paymentProcessor != ''){
			$Provider->criteria->addCondition(' paymentProcessor LIKE "%'.$paymentProcessor.'%" ');
		}
		if($refundStatus != ''){
			if(strpos('Information incomplète',$refundStatus) !== false){
				$Provider->criteria->addCondition(' refundStatus = 22 ');
			}
			elseif (strpos('In progress',$refundStatus) !== false) {
				$Provider->criteria->addCondition(' refundStatus = 11 ');
			}
		}
		$Provider->criteria->addCondition('refundStatus = 11 OR refundStatus = 22');
		return $Provider;
	}

	public function searchRefundInvoiceNoProgress( $order = false, $emailUser = '',$paymentProcessor = '', $refundStatus='' , $pageSize = 0, $type = ""){

		$dateDebut = $_SESSION['dateDebut'] ;
		$dateFin   = $_SESSION['dateFin'];

		$Provider = $this->search($order, $pageSize);
		if($type == 'Check')
			$Provider->criteria->addCondition(' paymentProcessor like "%Check%"');
		if($type == 'CB'){
			$Provider->criteria->select = ' *, emailUser AS pricePaid1 ';
			$Provider->criteria->addCondition(' paymentProcessor not like "%Check%"');
		}
		$Provider->setPagination(false);
		if($emailUser != ''){
			$Provider->criteria->addCondition(' emailUser LIKE "%'.$emailUser.'%" ');
		}
		if($paymentProcessor != ''){
			$Provider->criteria->addCondition(' paymentProcessor LIKE "%'.$paymentProcessor.'%" ');
		}
		if($refundStatus != ''){
			if(strpos('Information incomplète',$refundStatus) !== false){
				$Provider->criteria->addCondition(' refundStatus = 22 ');
			} elseif (strpos('In progress',$refundStatus) !== false) {
				$Provider->criteria->addCondition(' refundStatus = 11 ');
			} elseif ('TREATED' == $refundStatus ) {
				$Provider->criteria->addCondition(' refundStatus = 12 OR refundStatus = 22 ');
			}
		}
		if ('TREATED' !== $refundStatus ) :
			$Provider->criteria->addCondition('refundStatus = 11 OR refundStatus = 22');
		endif;
		$Provider->criteria->addCondition('invoiceStatus != 1');

		if ('TREATED' == $refundStatus ) :
			$Provider->criteria->addCondition("modificationDate BETWEEN '".$dateDebut."' AND '".$dateFin."'");
		else:
			$Provider->criteria->addCondition("creationDate BETWEEN '".$dateDebut."' AND '".$dateFin."'");
			//$Provider->criteria->addCondition(" DATE(creationDate) >= '".$dateDebut."' AND DATE(creationDate) <= DATE_ADD('".$dateFin."', INTERVAL 1 DAY)");
		endif;

		return $Provider;
	}

	public function searchInvoicesOfMB( $order = false, $emailUser = '', $pageSize = 0, $type = ""){
		$Provider = $this->search($order, false);
		$Provider->criteria->addCondition(' paymentProcessor like "%MultiBanco%"');
		$Provider->criteria->limit = 1000;
		$Provider->setPagination(false);
		if($emailUser != ''){
			$Provider->criteria->addCondition(' emailUser LIKE "%'.$emailUser.'%" ');
		}
		// $Provider->criteria->addCondition('refundStatus = 11');
		return $Provider;
	}


	/**
	 * Recherche les invoices check dont les coordonnes client sont incompletes
	 * @return \CActiveDataProvider
	 */
	public function searchIncompleteClientData( $order = false, $emailUser = '', $pageSize = 0 ){
		$Provider	= $this->searchRefundInvoice( $order, $emailUser = '', '','', $pageSize);
		$Provider->criteria->with = 'User';
		$Provider->criteria->addCondition( '( User.firstName = "" OR User.lastName = "" OR User.address = "" OR User.zipCode = "" OR User.city = "" OR User.country = "" )' );
		$Provider->criteria->addCondition( 'refundDate IN ( '.implode( ',', \Business\PaymentProcessorType::getNamePaymentProcessorByType( PP_TYPE_CHECK, true ) ).' )' );
		$Provider->criteria->compare( 'emailUser', $emailUser, true, 'AND' );
		return $Provider;
	}



	/**
	 * Recherche les invoices check dont les coordonnes client sont incompletes
	 * @return \CActiveDataProvider
	 */
	public function searchClientToMonitor($order = false, $emailUser = '', $pageSize = 0 ){
		$Provider = $this->searchRefundInvoice($order, $emailUser = '','','', $pageSize );
		$Provider->criteria->with = 'User';
		$Provider->criteria->addCondition('( User.savToMonitor = "1" )');
		$Provider->criteria->compare('emailUser', $emailUser, true, 'AND');
		return $Provider;
	}

	/**
	 * Recherche les invoices check en attente / complete
	 * @param	bool	$pending	Seulement les invoices check en attente ( numCheck == NULL )
	 * @return \CActiveDataProvider
	 */

	public function searchInvoiceCheck( $pending = true, $order = false, $emailUser = '', $pageSize = 0 , $email =  '', $chrono = '', $f_name = '', $l_name = ''){

		$Provider	= $this->searchRefundInvoice( $order,'', '', '', $pageSize );
		$PP			= new \Business\PaymentProcessorType( 'search' );
		$PP->type	= 2;

		foreach( $PP->search()->getData() as $P )
			$Provider->criteria->compare('paymentProcessor', $P->ref, false, 'OR' );
		if( $pending )
			$Provider->criteria->addCondition('numCheck IS NULL' );
		else
			$Provider->criteria->addCondition('numCheck IS NOT NULL' );

		//$Provider->criteria->compare( 'emailUser', $this->emailUser );
		//echo $email.'_'.$emailUser.'_'.$this->emailUser;exit;
		if($email !== ''){
			$Provider->criteria->addCondition(' emailUser LIKE "%'.$email.'%" ');
		}
		if($chrono !== ''){
			$Provider->criteria->addCondition(' chrono LIKE "%'.$chrono.'%" ');
		}

		if($f_name !== ''){

			$user  = new \Business\User;
			$users = $user->search( false, 20 ,'', '', '', $f_name);

			if(!empty($users->data)){

				$mail = array();
				foreach($users->data as $user)
					$mail[] = $user->email;
				if(!empty($mail))
					$Provider->criteria->addInCondition('emailUser', $mail);


			}else{

				$Provider->criteria->addCondition(' chrono LIKE "%'.$f_name.'%" ');
			}
		}

		if($l_name !== ''){

			$user  = new \Business\User;
			$users = $user->search( false, 20 ,'', '', $l_name,'');

			if(!empty($users->data)){

				$mail = array();
				foreach($users->data as $user)
					$mail[] = $user->email;
				if(!empty($mail))
					$Provider->criteria->addInCondition('emailUser', $mail);


			}else{

				$Provider->criteria->addCondition(' chrono LIKE "%'.$l_name.'%" ');
			}
		}

		return $Provider;
	}


	/**
	 * Recherche l'invoice pour un utilisateur precis, ayant acheté un produit precis
	 * @param string $email
	 * @param string $refProduct
	 * @return \CActiveDataProvider
	 */
	public function searchInvoiceForUserAndProduct($email, $refProduct){
		$Provider = $this->search( 'creationDate DESC' );
		$Provider->criteria->compare( 'emailUser', $email );
		$Provider->criteria->compare( 'invoiceStatus', self::INVOICE_PAYED );
		$Provider->criteria->with = 'RecordInvoice';
		$Provider->criteria->addCondition( '( RecordInvoice.refProduct = "'.$refProduct.'" )' );
		return $Provider;
	}
	
	/**
	 * Recherche l'invoice pour un utilisateur precis, ayant acheté un produit precis
	 * @param string $email
	 * @return \CActiveDataProvider
	 */
	public function searchInvoiceForUser($email){
		$Provider = $this->search( 'creationDate DESC' );
		$Provider->criteria->compare( 'emailUser', $email );
		$Provider->criteria->compare( 'invoiceStatus', self::INVOICE_PAYED );
		return $Provider;
	}
	
/**
	 * Recherche l'invoice en cours d'un utilisateur precis, ayant acheté un produit precis
	 * @param string $email
	 * @param string $refProduct
	 * @return \CActiveDataProvider
	 */
	public function searchInvoiceProgressForUserAndProduct( $email, $idSubCampaign, $order='DESC' ){
	    $SubCampaign	= \Business\SubCampaign::load( $idSubCampaign );
		$Provider = $this->search( 'creationDate '.$order);
		$Provider->criteria->compare( 'emailUser', $email );
		$Provider->criteria->compare( 'invoiceStatus', self::INVOICE_IN_PROGRESS );
		$Provider->criteria->with = 'Campaign';
		$Provider->criteria->addCondition( '( Campaign.id = "'.$SubCampaign->idCampaign.'" )' );
		return $Provider;
	}


	public function searchInvoiceCheckMB($order = false, $pageSize = 100, $email = '', $chrono = '', $f_name = '', $l_name = ''){

		$Provider	= $this->searchInvoicesOfMB( $order,'', $pageSize );
		$PP			= new \Business\PaymentProcessorType( 'search' );
		$PP->type	= 4;

		foreach( $PP->search()->getData() as $P ){
			$Provider->criteria->compare('paymentProcessor', $P->ref, false, 'AND' );
		}
		$Provider->criteria->addCondition('numCheck IS NULL' );

		//$Provider->criteria->compare( 'emailUser', $this->emailUser );
		//echo $email.'_'.$emailUser.'_'.$this->emailUser;exit;
		if($email != ''){
			$Provider->criteria->addCondition(' emailUser LIKE "%'.$email.'%" ');
		}
		if($chrono != ''){
			$Provider->criteria->addCondition(' chrono LIKE "%'.$chrono.'%" ');
		}

		if($f_name != ''){

			$user  = new \Business\User;
			$users = $user->search( false, 20 ,'', '', '', $f_name);

			if(!empty($users->data)){

				$mail = array();
				foreach($users->data as $user)
					$mail[] = $user->email;
				if(!empty($mail))
					$Provider->criteria->addInCondition('emailUser', $mail);


			}else{

				$Provider->criteria->addCondition(' chrono LIKE "%'.$f_name.'%" ');
			}
		}

		if($l_name != ''){

			$user  = new \Business\User;
			$users = $user->search( false, 20 ,'', '', $l_name,'');

			if(!empty($users->data)){

				$mail = array();
				foreach($users->data as $user)
					$mail[] = $user->email;
				if(!empty($mail))
					$Provider->criteria->addInCondition('emailUser', $mail);


			}else{

				$Provider->criteria->addCondition(' chrono LIKE "%'.$l_name.'%" ');
			}
		}
		return $Provider;
	}

	/**
	 * Retourne le status de l'invoice sous la forme d'un texte
	 * @return string|boolean status
	 */
	public function humanInvoiceStatus(){
		switch( $this->invoiceStatus ){
			default :
			case self::INVOICE_CADDIE:
				return 'Caddie';
			case self::INVOICE_IN_PROGRESS:
				return 'In progress';
			case self::INVOICE_PAYED:
				return 'Payed';
			case self::INVOICE_ERROR:
				return 'Error';
			case self::INVOICE_CANCEL:
				return 'Canceled';
		}
		return false;
	}

	/**
	 * Retourne le PriceATI de l'invoice sous la forme d'un texte
	 * @return string|boolean status
	 */
	public function getPriceATI(){
		$invoices = \Business\Invoice::load($this->id);
		$priceATI = '';
		if( !count($invoices->RecordInvoice) <= 0 )
			$priceATI   		 = $invoices->RecordInvoice[0]->priceATI;
		return $priceATI;
	}

	public function getPricePaid(){
		$invoices = \Business\Invoice::load($this->id);
		$pricePaid = '';
		if(strpos($this->paymentProcessor, 'Check') == false){
			if( !count($invoices->RecordInvoice) <= 0 )
				$pricePaid   		 = $invoices->RecordInvoice[0]->priceATI;

			return $pricePaid;
		}else{
			return $this->pricePaid;
		}
		return $this->refInterne;
	}


	/**
	 * Retourne le status du remboursement sous la forme d'un texte
	 * @return string|boolean status
	 */
	public function humanRefundStatus(){
		switch( $this->refundStatus ){
			default :
			case self::INVOICE_REFUND_NOT_ASKED :
				return '-';
			case self::INVOICE_REFUND_IN_PROGRESS :
				return 'In progress';
			case self::INVOICE_REFUND_REFUSED :
				return 'Information incomplète';
			case self::INVOICE_REFUNDED :
				return 'Refunded';
		}
		return false;
	}



	// *************************** ASCESSEUR *************************** //
	/**
	 * @return \Business\User
	 */
	public function getUser(){
		return $this->User;
	}



	// *************************** STATIC *************************** //

	static function model($className=__CLASS__){
        return parent::model($className);
    }



	/**
	 *
	 * @param type $id
	 * @return \Business\Invoice
	 */
	static public function load( $id ){
		return self::model()->findByPk( $id );
	}



	/*
	 * Charge l'instance Invoice correspondant a la refInterne
	 * @param	int	$ref	RefInterne
	 * @return	\Business\Invoice		Instance Invoice
	 */
	static public function loadByRefInterne($ref){
		return self::model()->findByAttributes( array( 'refInterne' => $ref ) );
	}



	/*
	 * Charge l'instance Invoice correspondant a la ref1Transaction
	 * @param	int	$ref1	Ref1Transaction
	 * @return	\Business\Invoice		Instance Invoice
	 */
	static public function loadByRef1( $ref1 ){
		return self::model()->findByAttributes( array( 'ref1Transaction' => $ref1 ) );
	}



	/**
	 * Recherche les invoices grace a l'adresse mail de l'utilisateur
	 * @param string $email	Email de l'utilisateur
	 * @param string $campaign	Campagne
	 * @return \Business\Invoice	Invoice
	 */
	static public function loadByMail( $email, $campaign = false ){
		if( $campaign == false )
			return self::model()->findAllByAttributes( array( 'emailUser' => $email ) );
		else
			return self::model()->findAllByAttributes( array( 'emailUser' => $email, 'campaign' => $campaign ) );

	}



	/**

	 * Retourne la ou les commandes pour un utilisateur et pour une reference de produit

	 * @param string $email

	 * @param string $refProduct

	 * @return \Business\Invoice

	 */

	static public function loadByEmailAndProduct( $email, $refProduct ){
		return self::model()->with( array
			(
				'RecordInvoice' => array( 'condition' => 'refProduct="'.$refProduct.'"' )
			) )->findAll();
	}



	/**

	 * Verifie si le client est a surveiller, controle si le client a plus de 10 commandes et si le ratio de remboursement est superieur ou egal a 30%

	 * @param string $email	Email de l'utilisateur

	 * @return boolean	true / false

	 */

	static public function checkIfClientIsToMonitor( $email ){
		$nbInvoices			= self::Model()->count( 'emailUser=:email', array( 'email' => $email ) );
		if( $nbInvoices < REFUND_MONITOR_MIN_INVOICE )
			return false;

		$nbInvoicesRefunded	= self::Model()->count( 'emailUser=:email AND ( refundStatus=:refunded OR refundStatus=:refundInProg )', array( 'email' => $email, 'refunded' => self::INVOICE_REFUNDED, 'refundInProg' => self::INVOICE_REFUND_IN_PROGRESS ) );
		$User				= \Business\User::loadByEmail( $email );
		$ratio				= ( $nbInvoicesRefunded * 100 ) / $nbInvoices;
		if( $ratio >= REFUND_MONITOR_RATIO ){
			$User->savToMonitor	= 1;
			\Yii::app()->user->setFlash( "notice", \Yii::t( 'SAV', 'toMonitor' ) );
		}
		else
			$User->savToMonitor	= 0;
		return $User->save();
	}


	/**
	 * Recherche l'invoice  check en attente/complete pour un utilisateur precis, ayant acheté un produit precis
	 * @param bool $pending	Seulement les invoices check en attente ( numCheck == NULL )
	 * @param string $email
	 * @param string $refProduct
	 * @return \CActiveDataProvider
	 */
	public function checkCanPayByAsynch( $email, $refProduct ){
		if(!empty($email)){
		$nbInvoicesAsynch	= self::Model()->with( array('RecordInvoice' => array( 'condition' => 'refProduct="'.$refProduct.'" and emailUser="'.$email.'" and  invoiceStatus="'.self::INVOICE_IN_PROGRESS.'" and chrono!=""' )))->findAll();
		}else{
			$nbInvoicesAsynch	= 11;
		}
	    return count($nbInvoicesAsynch);
	}

	/**
	 * Recherche l'invoice  check en complete pour un utilisateur precis, ayant acheté un produit precis
	 * @param bool $pending	Seulement les invoices check en attente ( numCheck == NULL )
	 * @param string $email
	 * @param string $refProduct
	 * @return \CActiveDataProvider
	 */
	public function InvoicePayedUser( $email, $refProduct ){
		if(!empty($email)){
		$nbInvoiceValider	= self::Model()->with( array('RecordInvoice' => array( 'condition' => 'refProduct="'.$refProduct.'" and emailUser="'.$email.'" and  invoiceStatus="'.self::INVOICE_PAYED.'" ' )))->findAll();
		}else{
			$nbInvoiceValider	= 0;
		}
	    return count($nbInvoiceValider);
	}

    /**
	 * Recherche nombre invoice complete pour un utilisateur precis, ayant acheté un produit precis
	 * @param bool $pending	Seulement les invoices check en attente ( numCheck == NULL )
	 * @param string $email
	 * @param string $refProduct
	 * @return
	 */

	public function InvoiceCanPay($email, $refProduct ){
		$nbInvoicesPay	= self::Model()->with( array('RecordInvoice' => array( 'condition' => 'refProduct="'.$refProduct.'" and emailUser="'.$email.'" and  invoiceStatus="'.self::INVOICE_PAYED.'" ' )))->findAll();
	    return count($nbInvoicesPay);
	}

	/**

	 * Recherche nombre invoice complete pour un utilisateur precis, ayant acheté un produit 1
	 * @param bool $pending	Seulement les invoices check en attente ( numCheck == NULL )
	 * @param string $email
	 * @param string $refProduct
	 * @return

	 */

	public function InvoiceCanPay_pro1($email, $refProduct ){

		$Tb_refProduct = explode('_',$refProduct);
		$refProduct = $Tb_refProduct[0]."_1";

		$nbInvoicesPay	= self::Model()->with( array('RecordInvoice' => array( 'condition' => 'refProduct="'.$refProduct.'" and emailUser="'.$email.'" and  invoiceStatus="'.self::INVOICE_PAYED.'" ' )))->findAll();
	    return count($nbInvoicesPay);
	}

	public function SetOpeningDate($email, $refProduct, $date){
		$invoice = self::model()->findByAttributes(array('emailUser' => $email, 'invoiceStatus' => 2));
		if(isset($invoice->date_ouverture) && $invoice->date_ouverture == '0000-00-00 00:00:00'){
			$invoice->date_ouverture = $date;
			$save = $invoice->save();
		}
	}

	public function getMailById(){
		return $this->emailUser;
	}

	public function getPRN($type = ''){
		if($type == '')
			$type = $this->paymentProcessor;
		if($type !== ''){
			$PRN = \Business\PaymentProcessorType::loadByRef($type);
			if(!empty($PRN))
				$prn = $PRN->getAttributes();
			return isset($prn['param']->mercantId) ? $prn['param']->mercantId : '';
		}
	}

	// *************** Fonction de validation de voeux >> HH
	public function ValideVoeux($Vname,$VdateNaissance,$Voeu1,$Voeu2,$Voeu3,$email,$ref,$valid){
		/*if( !$this->id )
			throw new \EsoterException( 100, \Yii::t( 'error', '100' ) );*/
		// Vérifier si le client est bien valider sa commande
		if($this->searchInvoiceForUserAndProduct($email,$ref))
			{
				$infosVoeux=json_encode(array( "Nom"  => $Vname, "DateNaissance" => $VdateNaissance, "Voeu1" => $Voeu1,"Voeu2" => $Voeu2,"Voeu3" => $Voeu3,"valid"=>$valid));
				$invoice = self::model()->findByAttributes(array('emailUser' => $email, 'invoiceStatus' => 2, 'campaign' => $ref));
				$invoice->setAdditionnalValue('Voeux',json_decode((string)$infosVoeux));

			}
	}
		
public function setMultiNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$appel){
		
		// Vérifier si le client est bien valider sa commande
		if($this->searchInvoiceForUserAndProduct($email,$refProduct))
			{	 
		//::::::::::::::::::::: Generation de series de chiffres:::::::::::::::::::::::::// 				
			$Rand="";
				for($i=1;$i<=$nbreS;$i++)
				{	
						
						$RandSup10=array();
						$RandInf10=array();
						// Partie >10
						$sup=0;
						$inf=0;
						while($sup<$nbreNC-$nbreMid)
						{	
							$RandSup10Nbre=mt_rand($mid,$max);
							 if (!in_array($RandSup10Nbre,$RandSup10)) 
								 {
									$RandSup10[]=$RandSup10Nbre;
									$sup++;								
								 }
						 
						}
						// Partie <10
						while($inf<$nbreMid)
						{	
							
							$RandInf10Nbre=mt_rand(1,$mid-1);
							 if (!in_array($RandInf10Nbre,$RandInf10)) 
								 {
									$RandInf10[]=$RandInf10Nbre;
									$inf++;								
								 }
							
						}
					// Concatiner les 2 tableaux <10 et >10	
					$Rand1 = array_merge($RandSup10,$RandInf10);
					// Changer l'ordre des element de tableau
					shuffle($Rand1);
					// Transformer le tableau de numero à une la chaine.
					$Rand1=implode($separateur,$Rand1).$sepS;	
					$Rand.=$Rand1;		
				}
					
					$Rand=rtrim($Rand,$sepS);
					
			//::::::::::::::::::::::::::::::::::::::::::::::// 
			
				$NumsChance=json_encode(array( "NumsChance"  => $Rand));
				
				$MyTab = $this->searchInvoiceForUserAndProduct($email,$refProduct)->getData();

				if(!isset($MyTab[0]->id)){

				return $Rand;
				}
				elseif(isset($MyTab[0]->id)){
					$invoice = \Business\Invoice::load($MyTab[0]->id);
			
				}else{
					
					$invoice = self::model()->findByAttributes(array('emailUser' => $email, 'invoiceStatus' => 2));
				}
				if(is_object($invoice))
				{	
					$invoice->setAdditionnalValue('NumsChance',json_decode((string)$NumsChance));
					$NumsChance_n = json_decode($NumsChance);
					$NumsChance_final=explode($sepS,$NumsChance_n->NumsChance);
					
					if($appel)
						return $NumsChance_final;
					else
					return $NumsChance_n;
				}else{
					
				
				    return $Rand;
				}			
			
			
		}
		
		
		else{
		
		return $Rand;
		
		}
		
}


//Amélioration effectuée par CHNIBER Zakaria le 01/04/2016
public function setNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$exceptions){
		
		
// Vérifier si le client a bien validé sa commande
		if($this->searchInvoiceForUserAndProduct($email,$refProduct))
			{	 
		//::::::::::::::::::::: Generation de series de chiffres:::::::::::::::::::::::::// 				
			$Rand="";
				for($i=1;$i<=$nbreS;$i++)
				{	
						
						$RandSup10=array();
						$RandInf10=array();
						// Partie >10
						$sup=0;
						$inf=0;
						while($sup<$nbreNC-$nbreMid)
						{	
							$RandSup10Nbre=mt_rand($mid,$max);
							 if (!in_array($RandSup10Nbre,$RandSup10)) 
								 {
									 if(!empty($exceptions)){
										 if(!in_array($RandSup10Nbre,$exceptions)){ 
											$RandSup10[]=$RandSup10Nbre;
											$sup++;
											
											}
									 
									 }else{
											$RandSup10[]=$RandSup10Nbre;
											$sup++;	
										}
									
								 }
						 
						}
						// Partie <10
						while($inf<$nbreMid)
							{	
								
								$RandInf10Nbre=mt_rand(1,$mid-1);
								 if (!in_array($RandInf10Nbre,$RandInf10)) 
									 {	
										if(!empty($exceptions)){
											if(!in_array($RandInf10Nbre,$exceptions)){
												$RandInf10[]=$RandInf10Nbre;
												$inf++;	
												}	
											}else{
												$RandInf10[]=$RandInf10Nbre;
												$inf++;	
											}
										 
																	
									 }
								
							}
					// Concaténer les 2 tableaux <10 et >10	
					$Rand1 = array_merge($RandSup10,$RandInf10);
					// Changer l'ordre des elements du tableau
					shuffle($Rand1);
					// Transformer le tableau de numero à une chaine.
					$Rand1=implode($separateur,$Rand1).$sepS;	
					$Rand.=$Rand1;		
				}
					
					$Rand=rtrim($Rand,$sepS);
					
			//::::::::::::::::::::::::::::::::::::::::::::::// 
			
				$NumsChance=json_encode(array( "NumsChance"  => $Rand));
				
				$MyTab = $this->searchInvoiceForUserAndProduct($email,$refProduct)->getData();
				if(!isset($MyTab[0]->id)){
				return $Rand;
				}
				elseif(isset($MyTab[0]->id)){
					$invoice = \Business\Invoice::load($MyTab[0]->id);
					echo $Rand;
				}else{
					$invoice = self::model()->findByAttributes(array('emailUser' => $email, 'invoiceStatus' => 2));
				}
				if(is_object($invoice))
				{
					$invoice->setAdditionnalValue('NumsChance',json_decode((string)$NumsChance));
					return $Rand;
				}else{
				
				
				    return $Rand;
				}			
			
			
		}
		
		
		else{
		
		return $Rand;
		
		}
		
		
}

	
	// *************** Fonction d'Ajout Numero de Chance ***************** >> Hania
	public function setNumsChance($email,$refProduct,$format,$sep,$min1,$max1,$min2,$max2){

		// Vérifier si le client est bien valider sa commande
		if($this->searchInvoiceForUserAndProduct($email,$refProduct))
			{
		//:::::::::::::::::::::::::::::::::::::::::::::://
				$exp=explode(":", $format);
				$Rand="";
				foreach ($exp as &$ex)
					{
							if(strtolower($ex)=="x")
							{
							$ex = rand($min1,$max1);
							}

							else if(strtolower($ex)=="y")
							{
							$ex = rand($min2,$max2);
							}
					 }
						unset($ex);

					foreach ($exp as &$ex)
						{
							$Rand.=" ".$ex." ".$sep;
						}

					 unset($ex);
					 // traitement de redoublons
					 	$Rand=rtrim($Rand, $sep);
						$Rand=explode($sep, $Rand);
						$Rand1=array_unique($Rand);
						$exp_diff = array_diff_key($Rand,$Rand1);
						$exp_diff = array_values($exp_diff);

						for($j=0;$j<count($exp_diff);$j++)
						{
						   for($k=1;$k<=$max1;$k++)
						   {
								$exp_diff[$j] = $exp_diff[$j] + $k;

									if(!in_array($exp_diff[$j],$Rand1))
									{
										array_push($Rand1,$exp_diff[$j]);
										break;
									}
							}
						}

					 $Rand=implode($sep,$Rand1);
			//:::::::::::::::::::::::::::::::::::::::::::::://

				$NumsChance=json_encode(array( "NumsChance"  => $Rand));

				$MyTab = $this->searchInvoiceForUserAndProduct($email,$refProduct)->getData();
				if(isset($MyTab[0]->id)){
					$invoice = \Business\Invoice::load($MyTab[0]->id);
					echo $Rand;
				}else{
					$invoice = self::model()->findByAttributes(array('emailUser' => $email, 'invoiceStatus' => 2));
				}
				if(is_object($invoice))
				{
					$invoice->setAdditionnalValue('NumsChance',json_decode((string)$NumsChance));
					//return $NumsChance;
					echo $Rand;
				}else{
				    echo $Rand;
				}
			}
		}
/*************************************************/
// *************** Fonction d'Ajout de Multi Numero de Chance ***************** >> Creer par HANIA
	public function setMultiNumsChance($email,$refProduct,$nbreNC,$max,$nbreS){

		// Vérifier si le client est bien valider sa commande
		if($this->searchInvoiceForUserAndProduct($email,$refProduct))
			{
		//::::::::::::::::::::: Generation de series de chiffres::::::::::::::::::::::::://
			$Rand="";
			for($i=1;$i<=$nbreS;$i++)
			{

					$RandSup10=array();
					$RandInf10=array();
					// Partie >10
					$sup=0;
					$inf=0;
				 	while($sup<$nbreNC-2)
					{
						$RandSup10Nbre=mt_rand(10,$max);
						 if (!in_array($RandSup10Nbre,$RandSup10))
							 {
								$RandSup10[]=$RandSup10Nbre;
								$sup++;
							 }

					}
					// Partie <10
					while($inf<2)
					{

						$RandInf10Nbre=mt_rand(1,9);
						 if (!in_array($RandInf10Nbre,$RandInf10))
							 {
								$RandInf10[]=$RandInf10Nbre;
								$inf++;
							 }

					}
				// Concatiner les 2 tableaux <10 et >10
				$Rand1 = array_merge($RandSup10,$RandInf10);
				// Changer l'ordre des element de tableau
				shuffle($Rand1);
				// Transformer le tableau de numero à une la chaine.
				$Rand1=implode(" - ",$Rand1)."|";
				$Rand.=$Rand1;
			}

				$Rand=rtrim($Rand,"|");

			//:::::::::::::::::::::::::::::::::::::::::::::://

				$NumsChance=json_encode(array( "NumsChance"  => $Rand));

				$MyTab = $this->searchInvoiceForUserAndProduct($email,$refProduct)->getData();
				if(isset($MyTab[0]->id)){
					$invoice = \Business\Invoice::load($MyTab[0]->id);
					echo $Rand;
				}else{
					$invoice = self::model()->findByAttributes(array('emailUser' => $email, 'invoiceStatus' => 2));
				}
				if(is_object($invoice))
				{
					$invoice->setAdditionnalValue('NumsChance',json_decode((string)$NumsChance));
					echo $Rand;
				}else{
				    echo $Rand;
				}
			}
		}

// *************** Fonction d'Ajout de Un Numero de Chance ***************** >> Addapté par Rida
	public function setOneNumChanceV2($email,$refProduct,$nbreNC,$max,$nbreS){

		// Vérifier si le client est bien valider sa commande
		if($this->searchInvoiceForUserAndProduct($email,$refProduct))
			{
		//::::::::::::::::::::: Generation de series de chiffres::::::::::::::::::::::::://
				$Rand="";
				$RandInf10=array();
				$RandInf10[]=mt_rand(1,9);
				// Concatiner les 2 tableaux <10 et >10
				$Rand1 = $RandInf10;
				// Transformer le tableau de numero à une la chaine.
				$Rand1=implode(" - ",$Rand1)."|";
				$Rand.=$Rand1;
				$Rand=rtrim($Rand,"|");
			//:::::::::::::::::::::::::::::::::::::::::::::://

				$NumsChance=json_encode(array( "NumsChance"  => $Rand));

				$MyTab = $this->searchInvoiceForUserAndProduct($email,$refProduct)->getData();
				if(isset($MyTab[0]->id)){
					$invoice = \Business\Invoice::load($MyTab[0]->id);
					//echo $Rand;
				}else{
					$invoice = self::model()->findByAttributes(array('emailUser' => $email, 'invoiceStatus' => 2));
				}
				if(is_object($invoice))
				{
					$invoice->setAdditionnalValue('NumsChance',json_decode((string)$NumsChance));
					return $NumsChance;
				}else{
				    //echo $Rand;
				}
			}
		}
		/*************** Fonction d'Ajout de Multi Numero de jour avec un interval definit ***************** >> Creer par soufiane*/

public function setMultiJoursAleatoir($email,$refProduct,$nbreNC,$min,$max,$NumsJours){
		if(empty($NumsJours))
		{
			$NumsJours='0|';
		}
		if($this->searchInvoiceForUserAndProduct($email,$refProduct))
			{
		//::::::::::::::::::::: Generation de series de chiffres::::::::::::::::::::::::://
					$Rand1=array();
					$sup=0;
					while($sup<$nbreNC)
					{

						$RandSup10Nbre=mt_rand($min,$max);
						 if (!in_array($RandSup10Nbre,$Rand1))
							 {
								$Rand1[]=$RandSup10Nbre;
								$sup++;
							 }
					 }
			// trier le tableau
				 $Rands = explode('|',$NumsJours);
				 $Rands[0]+=1;
				 $NumsJours=implode('|',$Rands);
				 sort($Rand1);

				 $Rand1=implode(" - ",$Rand1)."|";
				 $NumsJours.=$Rand1;




			//:::::::::::::::::::::::::::::::::::::::::::::://

				$NumsChance=json_encode(array( "NumsChance"  => $NumsJours));

				$MyTab = $this->searchInvoiceForUserAndProduct($email,$refProduct)->getData();
				if(isset($MyTab[0]->id)){
					$invoice = \Business\Invoice::load($MyTab[0]->id);
				echo str_replace('|','',$Rand1);
				}else{
					$invoice = self::model()->findByAttributes(array('emailUser' => $email, 'invoiceStatus' => 2));
				}
				if(is_object($invoice))
				{
					$invoice->setAdditionnalValue('NumsChance',json_decode((string)$NumsChance));
					return $NumsChance;
				}else{
				echo str_replace('|','',$Rand1);

				}
			}
}
/*************************************************/
/*************************************************/

		public function getRefInterne(){
			return $this->refInterne;
		}

		public function getRefTransaction(){

			if( $this->ref1Transaction != NULL ){
				return $this->ref1Transaction;
			}else{
				return $this->chrono;
			}

		}

		public function GetDeviseinformativecheque(){

			if( $this->deviseinformativecheque != NULL ){
				return $this->deviseinformativecheque;
			}else{
				return $this->currency;
			}

		}

		public function GetDateRefund(){

			return $this->refundDate;

		}
		/*public function GetPaymentProcessorRefunded(){

			return $this->paymentProcessor;

		}*/
		public function GetPaymentProcessorRefunded(){
			$PRN = \Business\PaymentProcessorType::loadByRef($this->paymentProcessor);
			if(!empty($PRN))
				$prn = $PRN->getAttributes();
			$cmp = isset($prn['param']->company) ? $prn['param']->company : '';
			if(!strstr($this->paymentProcessor,'Internationnal') && !strstr($this->paymentProcessor,'National'))
				$company = $cmp;
			else
				$company = explode(' ',$cmp)[0];

			$company = !empty($company)?' - '.$company:'';
			//die($company);
			//$company = isset($prn['param']->company) ? ' - '.$prn['param']->company : '';
			return $this->paymentProcessor.$company;

		}

		public function getCampaign(){
			return $this->campaign;
		}

		public function getAgent(){
			return $this->agent;
		}

		public function getNameporetur()
		{
			$porteur = \Yii::app()->params['porteur'];

			return  $GLOBALS['porteurMap'][$porteur];

		}

		/*************************************************/
		public function search3($dateDebut,$dateFin)
		{

			$Provider = parent::search();

			$car="'";
			$v1="`refundStatus` >=11";
			$v2=" and refundDate >= '".$dateDebut."' and refundDate <= '".$dateFin.$car;


			$requete =$v1.$v2;



			$Provider->criteria->condition = $requete;



			return $Provider;
		}

	/**
	 * load Invoices based on email and invoice Type
	 *
	 * @param $email
	 * @param $invoices Default : INVOICE_PAYED
	 * @param $orderBy (Default : DESC)
	 * @return Array of \Invoice objects List
	 **/
	public static function getByEmail($email, $invoice = self::INVOICE_PAYED, $orderBy = 'DESC')
	{
		// INVOICE_IN_PROGRESS
		// INVOICE_PAYED
		return self::model()->with([
				'RecordInvoice' => array( 'condition' => 'RecordInvoice.idInvoice=t.id' )
			])->findAllByAttributes([
			'emailUser' => $email,
			'invoiceStatus' => $invoice,
		], array(
			'order' => "creationDate $orderBy",
			'condition' => 'priceStep not in (1504, 1503) '
		));
	}
		
		
	//********************************** Dep de Prix *****************************************************************//
	
		/**
		 * @param \Business\Invoice
		 * @param \Relance tr $trInvoice
		 * @return step de relance
		 */	
		public function StepByInterval($trInvoice)
		{
			$Step = 1;
			
				if($trInvoice >= 1 && $trInvoice <= 4)
					$Step = 1;
				elseif($trInvoice > 4 && $trInvoice <= 7)
					$Step = 2;
				elseif($trInvoice > 7)
					$Step = 3;

			return $Step;
		}
		
		/**
		 * @param \Business\Invoice
		 * @param \Relance tr $trInvoice
		 * @return step de relance pour Althea Vp
		 */	
		public function StepByIntervalAltheaVp($trInvoice)
		{
			$Step = 1;
			
				if($trInvoice >= 1 && $trInvoice <= 4)
					$Step = 1;
				elseif($trInvoice >= 5 && $trInvoice <= 7)
					$Step = 2;
				elseif($trInvoice >= 8 && $trInvoice <= 10)
					$Step = 3;
				elseif($trInvoice >= 11 && $trInvoice <= 13)
					$Step = 4;
				elseif($trInvoice >= 14)
					$Step = 5;
					
			return $Step;
		}
		
		/**
		 * @param \Business\Invoice
		 * @param \Relance tr $trInvoice
		 * @return step de relance pour Althea asile 1
		 */	
		public function StepByIntervalAltheaAsile($trInvoice)
		{
			$Step = 1;
			
				if($trInvoice >= 1 && $trInvoice <= 3)
					$Step = 1;
				elseif($trInvoice >= 4 && $trInvoice <= 5)
					$Step = 2;
				elseif($trInvoice >= 6 && $trInvoice <= 7)
					$Step = 3;

			return $Step;
		}
		/**
		 * @param \Business\invoice $priceStep
		 * @param \step step de la chaine
		 * @return	last step with chaine
		 */	
		public function lastStepInvoice($trLastStep, $chaine = 'inter', $currentStep = 0, $trStepAsile1 = 0, $trStepAsile2 = 0)
		{
			if($chaine == "althea-asile-vp")
			{
				$priceStep = 1;
				$StepVp = $this->StepByIntervalAltheaVp($trLastStep);
				if($currentStep == 1)
				{
					$priceStep = $StepVp;
				}
				elseif($currentStep > 1)
				{
					$priceStep = $StepVp + $currentStep - 1;
				}
				if($priceStep  > 7)
					$priceStep  = 7;
			}
			elseif($chaine == "althea-asile-vps")
			{
				$priceStep = 1;
				$priceStep = $currentStep + 4;
				if($priceStep  > 7)
							$priceStep  = 7;
			}
			else if($chaine == "althea-inter-vp")
			{
				
				$priceStep = 1;
				$lastStep = $this->StepByIntervalAltheaVp($trLastStep);
				
				if($currentStep == 1)
				{
					$priceStep = $lastStep;
				}
				elseif($currentStep > 1)
				{
					$priceStep = $lastStep + $currentStep - 1;
				}
				if($priceStep  > 7)
							$priceStep  = 7;
				
			}
			else if($chaine == "althea-inter-vps")
			{
				
				$priceStep = 1;
				$priceStep = $currentStep + 4;
				if($priceStep  > 7)
							$priceStep  = 7;
				
			}
			else
			{
				$priceStep = 1;
				if($chaine == "inter")
				{
					
					if($trLastStep == 1503 || $trLastStep == 1504)
						$priceStep = 6;
					elseif($trLastStep >= 1 && $trLastStep <= 3)
						$priceStep = 2;
					elseif($trLastStep > 3 && $trLastStep <= 6)
						$priceStep = 3;
					elseif($trLastStep > 6)
						$priceStep = 4;
					return $priceStep;
				}
				elseif($chaine == "asile2")
					$priceStep = $this->getStepAsile2($trLastStep, $currentStep);
				elseif($chaine == "asile3")
					$priceStep =  $this->getStepAsile3($trStepAsile2, $currentStep, $trStepAsile1);
				elseif($chaine == "asile4")
					$priceStep = $this->getStepAsile4($trLastStep, $trStepAsile2, $trStepAsile1);
				
				if($priceStep  > 4)
							$priceStep  = 4;
			}
			return $priceStep;
		}
		
		/**
		 * @param \Business\Site
		 * @param \Tr d'achat d'Asile1 $trLastStep, le step courant de Asile2 $currentStep 
		 * @return	Step d'achat du produit asile2
		 */	
		public function getStepAsile2($trLastStep, $currentStep)
		{
			$Laststeep = $this->StepByInterval($trLastStep);
				if($Laststeep == 1)
					$priceStep = $currentStep;
				else
					$priceStep = $currentStep + ($Laststeep - 1);	
			return $priceStep;
		}
		
		/**
		 * @param \Business\Site
		 * @param \Tr d'achat d'Asile2 $trStepAsile2, le step courant de Asile3 $currentStep, Tr d'achat d'Asile1 $trStepAsile1, 
		 * @return	Step d'achat du produit asile3
		 */	
		public function getStepAsile3($trStepAsile2, $currentStep, $trStepAsile1)
		{
			$stepAsile2 = $this->StepByInterval($trStepAsile2);
			return $this->getStepAsile2($trStepAsile1, $stepAsile2) + ($currentStep - 1);
		}
		
		/**
		 * @param \Business\Site
		 * @param \Tr d'achat d'Asile3 $trLastStep, Tr d'achat d'Asile2 $trStepAsile2, Tr d'achat d'Asile1 $trStepAsile1, 
		 * @return	Step d'achat du produit asile4
		 */	
		public function getStepAsile4($trLastStep, $trStepAsile2, $trStepAsile1)
		{
			return $this->getStepAsile3($trStepAsile2 , $this->StepByInterval($trLastStep), $trStepAsile1);	
		}
		
		
	/**
	 * Recherche l'invoice pour un utilisateur precis, ayant acheté un produit precis
	 * @param string $email
	 * @param string $refProduct
	 * @return \CActiveDataProvider
	 */
	public function searchAllInvoiceUser($email, $refCompagne, $chaine){
		
		$Provider = $this->search( 'creationDate ASC' );
		$Provider->criteria->compare( 'emailUser', $email );
		$Provider->criteria->compare( 'campaign', $refCompagne );
		$Provider->criteria->compare( 'invoiceStatus', self::INVOICE_PAYED );
		
		$Provider->criteria->with = array('RecordInvoice' => array(
		'with' => 'Product',
		));
		
		$Provider->criteria->addCondition( '( Product.asile_type LIKE "%'.$chaine.'%" )' );
		
		return $Provider;
	}
	/**
	 * ***************** Fin Dep de prix ******************************************************************************************************
	 */
	
	/**
	 * retourne le nombre d'achat du client (invoice , internaute)
	 * 
	 * @author soufiane balkaid
	 * @param  $email
	 * @param $invoices Default	: INVOICE_PAYED
	 * @param $period : 1 => last 4 month , 2 => before 4 month
	 * @return nuber of purchased
	 *        
	 */
	public function getNbrPurchased($email, $period) {
		$nbrOfPurchased = 0;
		$PM = new \Business\PaymentTransaction ();
		
		$Provider = $this->search ( 'creationDate ASC' );
		$Provider->criteria->compare ( 'emailUser', $email );
		$Provider->criteria->compare ( 'invoiceStatus', self::INVOICE_PAYED );
		if ($period == 1) {
			$Provider->criteria->addCondition ( ' creationDate between cast(ADDDATE( CURRENT_DATE, INTERVAL -4 MONTH ) as datetime) and cast(CURRENT_DATE as datetime) ' );
			$nbrOfPurchased += count ( $Provider->getData () );
			$nbrOfPurchased += count ( $PM->getByEmail ( $email, 1, 'DESC', 1 ) );
			
			
		} elseif ($period == 2) {
			$Provider->criteria->addCondition ( ' creationDate < cast(ADDDATE( CURRENT_DATE, INTERVAL -4 MONTH ) as datetime) ' );
			$nbrOfPurchased += count ( $Provider->getData () );
			$nbrOfPurchased += count ( $PM->getByEmail ( $email, 1, 'DESC', 2 ) );
			
		}
		else{
		$nbrOfPurchased += count ( $Provider->getData () );
		$nbrOfPurchased += count ( $PM->getByEmail ( $email ) );
		}
		return $nbrOfPurchased;
	} 
	/***************************************************** get Nbr Purshased Old Anaconda By Email *************************************************/
	/**
	 * @author Yacine RAMI
	 * @desc Retourne le nombre de fid Anaconda ayant ete acheter avant le cycle Anaconda
	 * @param string $email
	 */
	public static function getNbrPurshasedOldAnacondaByEmail($email)
	{
		$criteria = new \CDbCriteria;
		$criteria->condition="emailUser = '".$email."' AND invoiceStatus = ".self::INVOICE_PAYED." AND DATEDIFF( NOW(),`creationDate`) < 365";
		$anacondaOldRefs=\Yii::app()->params['anaconda_old_refs'];
		$criteria->addInCondition('campaign',$anacondaOldRefs,'AND');
		return count(self::model()->findAll( $criteria ));
	}
	 
	/**************************************************  / get Nbr Purshased Old Anaconda By Email **************************************************/
	/**
	 * @author Soufiane Balkaid
	 * @desc Retourne le nombre de fid Anaconda achete
	 * @param string $email
	 */
	public static function getPurchasedAnaconda($email){
		$criteria = new \CDbCriteria;
		$criteria->condition="emailUser = '".$email."' AND invoiceStatus = ".self::INVOICE_PAYED." and V2_campaign.isAnaconda is not null";
		$criteria->join='inner join V2_campaign on V2_campaign.ref=t.campaign';
		return count(self::model()->findAll( $criteria ));
	}
	/**
	 * @author Soufiane Balkaid
	 * @desc Retourne les invoices de la chaine STC achete hier
	 */
	public  function getPurchasedStcOfDay()
	{
		$dateNow = new \DateTime();
		$criteria = new \CDbCriteria;
		$criteria->condition = "creationDate like '%" . $dateNow->format ( 'Y-m-d' ) . "%' and campaign='STC' AND invoiceStatus = " . self::INVOICE_PAYED;
	    return self::model()->findAll( $criteria );

	}
	
	/**
	 * @author Saad HDIDOU
	 * @desc retourner la reference de la VP selon le porteur
	 * @return string
	 */
	private static function getNomenclatureVP(){
		$porteur = \Yii::app()->params['porteur'];
		if($porteur == 'fr_evamaria' || $porteur == 'fi_laetizia' || $porteur == 'fr_myriana' )
			return 'STC_2';
		elseif($porteur == 'fr_ivana' || $porteur == 'fr_davina' || $porteur == 'fr_althea' || $porteur == 'de_althea'|| $porteur == 'se_althea' || $porteur == 'au_althea')
			return "stc_2";
		else
				return 'stc_1';
	}
	
	/**
	 * @author Saad HDIDOU
	 * @desc: recuperer les clients qui ont achete le produit VP
	 */
	static public function getPayedVP()
	{
		$date = new \DateTime();
		$date->sub( new \DateInterval('P1D') );
		$ref=self::getNomenclatureVP();
		$criteria = new \CDbCriteria;
		$criteria->alias = 'Invoice';
		$criteria->join='LEFT JOIN V2_recordinvoice Record ON Record.idInvoice = Invoice.id LEFT JOIN V2_user User ON User.email=Invoice.emailUser';
		$criteria->condition = 'Invoice.creationDate LIKE \'%'.$date->format('Y-m-d').'%\' AND (Record.refProduct LIKE \'%voypayant%\' OR LCASE(Record.refProduct) IN (\'stg_2\',\''.$ref.'\',\'voypayant\', \'vp\')) AND Invoice.invoiceStatus= 2 AND User.intialDate IS NULL AND (User.visibleDesinscrire IS NULL OR User.visibleDesinscrire = 0) ';
		$list = self::model()->findAll($criteria);
		return array_map(function($element) { return $element['emailUser'];}, $list);
	}
    /**
     * return le nombre de commandes anaconda acheté par le client $user entre la date $DateFrom et la date $DateTo
     * @param $idUser
     * @param $DateFrom
     * @param $DateTo
     * @return int
     */
    public static function purchasedAnacondaByPeriod($idUser,$DateFrom,$DateTo){
        $criteria = new \CDbCriteria;
        $criteria->condition="u.id = ".$idUser." AND invoiceStatus = ".self::INVOICE_PAYED." and V2_campaign.isAnaconda is not null and modificationDate between '".$DateFrom."' and '".$DateTo."'";
        $criteria->join='inner join V2_campaign on V2_campaign.ref=t.campaign inner join V2_user u on u.email=t.emailUser';
        return count(self::model()->findAll( $criteria ));
    }
    /////////////////////////////////////////////////// Reactivation ///////////////////////////////////////////////////////////////////////
    /**
     * @author Anas HILAMA
     * @param string $emaiL
     * @desc date de validation d'achat
     *
     */
    static public function LoadByEmailPayedInv($email) {

        $BornSup = date("Y-m-d H:i:s") ;
        $BornInf = date("Y-m-d H:i:s", strtotime("- 1 day")) ;
        $criteria = new \CDbCriteria;
        $criteria->alias = "Invoice" ;
        $criteria->condition = "Invoice.emailUser ='".$email."' AND Invoice.invoiceStatus = 2 AND ( Invoice.modificationDate BETWEEN '".$BornInf."' AND'".$BornSup."')"  ;
        $list = self::model()->findAll($criteria);
        return array_map(function($element) { return $element['modificationDate'];}, $list);
    }


    /////////////////////////////////////////////////////// Moteur de test ///////////////////////////////////////////////////////////////////
    /**
     * @author Yacine RAMI
     * @param string $email
     * @param string $refProduct
     */
    static public function loadByEmailAndProductPayed( $email, $refProduct ){

        return self::Model()->with( array('RecordInvoice' => array( 'condition' => 'refProduct="'.$refProduct.'" and emailUser="'.$email.'" and  invoiceStatus="'.self::INVOICE_PAYED.'" ' )))->find();
    }

    /**
     * @author Soufiane BALKAID
     * @param string $email
     * @param date $date
     */
    public static function getPurchasedSTCByInterval($date1,$date2,$email)
    {

        $criteria = new \CDbCriteria;
        $criteria->condition = "creationDate between  '" .  $date1  . " 00:00:00' and '".  $date2  . " 23:59:59' AND  emailUser like '".$email."' and campaign='STC' AND invoiceStatus = " . self::INVOICE_PAYED;

        return self::model()->findAll( $criteria );
    }

    /**
     * @author Soufiane BALKAID
     * @param string $email
     * @param date $date
     */
    public static function getPurchasedAnacondaFid($date1,$date2,$email)
    {

        $criteria = new \CDbCriteria;

        $criteria->condition = "creationDate between  '" .  $date1  . " 00:00:00' and '".  $date2  . " 23:59:59' AND  emailUser like '".$email."' and campaign='an_%' AND invoiceStatus = " . self::INVOICE_PAYED;

        return self::model()->findAll( $criteria );
    }


}
?>
