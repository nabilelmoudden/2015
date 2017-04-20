<?php  



namespace Business;
/**
 * Description of Product_V1
 *
 * @author Salah Eddine
 * @package Business.Campaign
 *
 */



class User_V1 extends \User_V1  {//implements Interface_Camp
	static public $Version;
	public function init(){
		parent::init(); 	
	}

	/**
	 * Decode les valeurs additionnels apres recuperation en DB
	 * @return boolean
	 */
	protected function loadAdditionnalValues(){

	}



	/**
	 * Encode les valeurs additionnels avant sauvegarde en DB
	 * @return boolean
	 */
	protected function saveAdditionnalValues(){

	}



	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.

		return array(

			'Log' => array(self::HAS_MANY, '\Business\Log', 'idProduct'),
		    'PricingGrid_V1' => array(self::HAS_MANY, 'PricingGrid_V1', 'IDProduct'),
			'RouterEMV' => array(self::HAS_MANY, '\Business\RouterEMV', 'idProduct'),
			'RecordInvoice' => array(self::HAS_MANY, '\Business\Recordinvoice', array( 'Ref' => 'refProduct' ) ),

		);

	}



	/**

	 * Recherche

	 * @param string $order Ordre

	 * @param int $pageSize	Nb de result par page

	 * @return CActiveDataProvider	CActiveDataProvider 

	 */

	public function search( $order = false, $pageSize = 10000, $email = '', $civility = '', $lastName = '', $firstName = ''){
		$Provider = parent::search();
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;
		if($email !== '')
			$Provider->criteria->compare('email', $email, true);
		if($civility  !== '')
			$Provider->criteria->compare('civility',$civility, false);
		if($lastName  !== '')
			$Provider->criteria->compare('lastName',$lastName, true);
		if($firstName  !== '')
			$Provider->criteria->compare('firstName',$firstName, true);
		if( $order != false )
			$Provider->criteria->order = $order; 
		return $Provider;
	} 
	
	
	/**
	 * @param int $id
	 */
	 Static public function getUserById($internauteId){
		return self::model()->findByAttributes(array( 'ID' => $internauteId )); 
	}

     /**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider 
	 */
	public function searchMail( $order = false, $pageSize = 50, $email = ''){
		$Provider = parent::searchMail($email);
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;
		if( $order != false )
			$Provider->criteria->order = $order; 
		return $Provider;

	} 

	/**

	 * Verifie qu'un type de PaymentProcessor est disponible pour le produit

	 * @param string $refPP

	 * @return boolean	true / false

	 */

	public function isPaymentProcessorAvailable( $refPP )

	{

		/*if( empty($this->paymentType) )

			return false;



		$paymentType = explode( ',', $this->paymentType );

		return in_array( $refPP, $paymentType );*/

	}



	/**
	 * Retourne le PaymentProcessorSet disponible pour le site passé en argument
	 * @param int $idSite
	 * @return array[\Business\PaymentProcessorSet]
	 */
	public function getPaymentProcessorTypeForSite( $idSite ){
	
	}

	
	/**
	 * Retourne le nom complet de l'utilisateur
		* @return string	Nom complet
	 */
	public function name(){
		return ucfirst($this->firstName).' '.strtoupper($this->lastName);
	}

	/**
	 * Test si un champs du BDC est disponible pour ce produit
	 * @param string $type
	 * @param string $name
	 * @return boolean
	 */
	public function isBdcFields( $type, $name ){

	}



	/**

	 * Retourne un parametre du priceModel

	 * @param string $name

	 * @return mixed

	 */

	public function getParamPriceModel( $name )

	{

	}

	/**
	 * Retourne un objet DateTime representant la date de naissance
	 * @return \DateTime
	 */
	
	public function getBirthday( $format = false )
	{	
		if( $this->Birthday == '0000-00-00 00:00:00' )
			return false;
		if( $this->Birthday == '')
	        $this->Birthday = '1985-02-02 15:59:29';
	        
	    $this->Birthday = date("Y-m-d H:i:s", strtotime($this->Birthday));
	        
		$Date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $this->Birthday);
		
		return ( $format == false ) ? $Date : $Date->format($format);
	}


	/**
	 * Retourne le numero du signe astrologique
	 * @return int
	 */
	public function getNumberSignAstro()
	{
		\Yii::import( 'ext.DateHelper' );
		return \DateHelper::getAstroSign( $this->getBirthday( 'Y-m-d' ) );
	}

	/**
	 * Retourne le nom du signe astrologique
	 * @return string
	 */
	public function getSignAstro()
	{
		\Yii::import( 'ext.DateHelper' );
		$tab = \DateHelper::getAstroSignByNumber( $this->getNumberSignAstro() );
		return ( isset($tab[1]) ) ? $tab[1] : false;
	}

	/**

	 * Retourne un parametre du PaymentProcessorSet
	 * @param string $name
	 * @return mixed

	 */

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\Product_V1
	 */

	static public function load( $id )

	{

		return self::model()->findByPk( $id );

	}

	

	/**

	 *

	 * @param type $id

	 * @return \Business\Product_V1

	 */

	static public function deleteUserById( $id ){

		// $campaign  = \Business\Campaign::load($id); 

		// print_r($campaign->getData());exit;

		// print_r(getProductById);exit;

		return self::model()->deleteUserById( $id );

	}



	/**

	 *

	 * @param type $ref

	 * @return \Business\Product_V1

	 */

	static public function loadByRef( $ref ){

		return self::model()->findByAttributes( array( 'Ref' => $ref ) );

	}



	static public function getVersion( ){	

		$Version='V1';

		return $Version;

	}

	

	static public function loadByAP( $id = NULL)
	{

		$User_V1	= new \Business\User_V1( 'search' );

		$DataProvider = $User_V1->search();

		if( $DataProvider->getTotalItemCount() > 0 )

			return $DataProvider->getData();

		return false;
	}

	/**
	 * Recupere un User par son adresse mail
	 * @param type $mail
	 * @return \Business\User
	 */
	
	static public function loadByEmail( $mail ){
		return self::model()->findByAttributes(array( 'Email' => $mail ));
	}

	
	/**
	 * Retourne le nombre d'offset price step distinct des SubCampaignReflation
	 * @return int
	 */
	public function countDistinctOffsetPriceStep(){
		if( !is_array($this->SubCampaignReflation) || count($this->SubCampaignReflation) <= 0 )
			return false;
		$tab = array();
		for( $i=0; $i<count($this->SubCampaignReflation); $i++ )
			$tab[ $this->SubCampaignReflation[$i]->offsetPriceStep ] = 1;
		return count($tab);
	}

	
	/**
	 * Excute web form Update les infos clients EMV
	 * @return string|false Retour de la requete, false en cas de probleme
	 */
	public function SendToEMV($type = 'updateClient'){
		$porteur = \Yii::app()->params['porteur'];
		/*$acq = false;
		if($GLOBALS['porteurWithTwoSFAccounts'][$porteur]){
			if($this->CompteEMVactif != '' && strpos($this->CompteEMVactif, 'FID') == false)
				$acq = true;
		}
		if($type == 'updateClient'){
			$url_WebForm = ($acq ?  $GLOBALS['porteurWebformUpdateClient'][$porteur.'_acq'] : $GLOBALS['porteurWebformUpdateClient'][$porteur]);
		}elseif($type == 'inscrire'){
			$url_WebForm = ($acq ?  $GLOBALS['porteurWebformInscrirClient'][$porteur.'_acq'] : $GLOBALS['porteurWebformInscrirClient'][$porteur]);
		}elseif($type == 'desincrire'){
			$url_WebForm = ($acq ?  $GLOBALS['porteurWebformDesinscrirClient'][$porteur.'_acq'] : $GLOBALS['porteurWebformDesinscrirClient'][$porteur]);
		}*/
		if($GLOBALS['porteurWithTwoSFAccounts'][$porteur] && $this->CompteEMVactif != ''){
			$porteur = $GLOBALS['SFAccountsMap'][$this->CompteEMVactif] ;
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
		$WF->setTokenWithUser_V1($this);
		return $WF->execute( true );
	}
}
?>