

<?php
/**
 * Description of AdminController
 *
 * @author JulienL
 */
\Yii::import( 'ext.MailHelper' );

class FikriController extends AdminController
{
	public $layout	= '//product/menu';
	
	public function init(){
		parent::init();
		$action = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
		if( $action=='Product/login' || $action=='Product/index' ||  $action == 'Product/campaign' ){
			// Url de la page de login ( pour les redirections faites par les Rules ) :
			Yii::app()->user->loginUrl = array( '/Product/login' );
		}

		// Default page title :
		$this->setPageTitle( 'Product Administration' );
	}
	//*****************************actions
	public function actions()
	{
		return array(
			'coco'=>array(
				'class'=>'CocoAction',
			),
		);
	}
	// ************************** RULES / FILTER ************************** //
	public function filters(){
		return array( 'accessControl' );
	}
	



	public function accessRules(){
		return array(
			array(
				'allow',
				'users' => array('@'),
				'roles' => array( 'ADMIN', 'ADMIN_PRODUCT'  )
			),
			array(
				'allow',
				'actions' => array( 'login', 'logout' ),
				'users' => array('*')
			),
			array('allow'),
		);
	}

	// ************************** ACTION ************************** //
	public function actionIndex(){

		/* added by Mounir 03/02/2016 */
		$user = \Business\User::load( Yii::app()->user->getId() );
		!$user  ? $this->redirect('login') : $this->render( '//product/index' );
	}

	

	
	


	/*************************** CDC V3 (by: Youssef HARRATI) ********************************/

	public function isTwoSFAccounts(){
		if($GLOBALS['porteurWithTwoSFAccounts'][$_SESSION['porteur']])
			return true;
		else
			return false;
	}

	public function nbProducts($id){
		$sub = new \Business\SubCampaign( );
		$subs = $sub->loadByCampaign($id);
		return $subs;
	}


/***********************************************Code Ajouté par Marouane FIKRI ::: Anaconda Moteur de test ********************************
    /**
     *F_01
     * By FIKRIM
     *
     * @param $idSubCampaign
     * @param $number
     * @return reflation object
     */

    public function actionloadBySubcampaignAndMessageNumber( $idSubCampaign, $number)
    {
    	$SubCampReflation = Business\SubCampaignReflation::loadByIdSubCampAndNumber( $idSubCampaign, $number);
        echo "<pre>";
        print_r(json_encode($SubCampReflation->attributes,JSON_PRETTY_PRINT));


	}

    /**
     * Cette méthode va retourner l’ensemble des leads qui ont reçu en dernier le message (SubcampaignReflation) sélectionné.
     * @param $dateRef
     * @param $dateConstat
     * @param $idSubcampRef
     * @param $indice
     * @return array
     */
    public function actionloadByDateAndIndice($dateRef,$dateConstat,$idSubcampRef,$indice)
    {
        $fu = [];

        $ml = \Business\Reflationuser::findByDateAndIndice($dateRef,$dateConstat,$idSubcampRef,$indice);
        foreach ($ml as $m) {
            $filtredUser = \Business\User::loadById($m->idUser);

            $fu[] =$filtredUser->attributes;
        }
        echo "<pre>";
        print_r(json_encode($fu, JSON_PRETTY_PRINT));
        return $fu;
    }

    /**
     * cette fonction retourne le groupe de prix pour le user et subcampaign donnée
     * @param $idUser
     * @param $idSubCampaign
     * @return array
     */

    public function actionGetGpByUserAndSubcampaign($idUser,$idSubCampaign)
    {
        $tabgp=[];
        $gps = \Business\CampaignHistory::getGpByUserAndSubcampaign($idUser,$idSubCampaign);
        $i=0;
        foreach ($gps as $gp){
            $tabgp [$i]['gp']= $gp->groupPrice;
            $tabgp [$i]['initialShootDate']= $gp->initialShootDate;
            $i++;
        }
        echo "<pre>";
        print_r(json_encode($tabgp,JSON_PRETTY_PRINT));
        echo "</pre>";
        return $tabgp;
    }

    /**
     * cette fonction filter les users selon le gp donnée
     * @param $gp
     */
    public function actionFilterListByGP($gp)
    {
        $tabusers = [];
        $histories = \Business\CampaignHistory::filterUserByGP($gp);

        foreach ($histories as $history){
            $tabusers []= \Business\User::load($history->idUser);
        }


        echo "<pre>";
        print_r(json_encode($tabusers,JSON_PRETTY_PRINT));
        echo "</pre>";
    }


    /**
     * cette fonction affiche le somme des click effectué par un utilisateur avant la date donnée
     * @param $date
     * @param $idUser
     */

    public function actionsumBDCClicksBeforeDate($date,$idUser)
    {
        $tabclick=[];
        //la fonction return tous les click de l'utilisateur
        $clicks = \Business\UserBehavior::sumBDCClicksBeforeDate($date,$idUser);
        foreach ($clicks as $click){
            $tabclick[]=$click->attributes;
        }
        //affichage de la somme des click de l'utilisateur
        echo "<pre>";
        print_r(json_encode(count($tabclick),JSON_PRETTY_PRINT));
        echo "</pre>";


    }

    /**
     * récuperer les ouvertures selon un $user entre deux dates données
     * @param $idUser
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function actionOpenByPeriod($idUser,$dateFrom,$dateTo)
    {
        $tabopeneds=[];
        $openeds= \Business\Openedlinkmail::OpenByPeriod($idUser,$dateFrom,$dateTo);
        foreach ($openeds as $opened){
            $tabopeneds [] = $opened->attributes;

        }

        echo count($tabopeneds);
        echo "<hr>";
        echo "<pre>";
        print_r(json_encode($tabopeneds,JSON_PRETTY_PRINT));
        echo "</pre>";
        return $tabopeneds;

    }
    /**
     * récuperer les click selon un $user entre deux dates données
     * @param $idUser
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function actionBdcClicksByPeriod($idUser,$dateFrom,$dateTo)
    {
        $tabClicked=[];
        $Clickeds = \Business\UserBehavior::bdcClicksByPeriod($idUser,$dateFrom,$dateTo);
        foreach ($Clickeds as $clicked){
            $tabClicked [] = $clicked->attributes;

        }

        echo count($tabClicked);
        echo "<hr>";
        echo "<pre>";
        print_r(json_encode($tabClicked,JSON_PRETTY_PRINT));
        echo "</pre>";
        return $tabClicked;

    }

    /**
     * return le nombre de commandes anaconda acheté par le client $user entre la date $DateFrom et la date $DateTo
     * @param $idUser
     * @param $dateFrom
     * @param $dateTo
     */
    public static function actionPurchasedAnacondaByPeriod($idUser,$dateFrom,$dateTo){
        $nb= \Business\Invoice::purchasedAnacondaByPeriod($idUser,$dateFrom,$dateTo);
        var_dump($nb);
    }

    /**
     * cette fonction retourne la derniere reflation reçu
     * @param $idUser
     * @param $dateRef
     */

    public static function actionlastReceivedReflation($idUser,$dateRef) {
        $reflations = \Business\Reflationuser::lastReceivedReflation($idUser,$dateRef);
        self::_print_r($reflations->attributes);

    }

    /**
     * cette fonction permet de changer le statut de l'alerte
     * @param $idAlert
     * @param $statut
     */
    public function actionSetStatut($idAlert,$statut)
    {
        $alert = \Business\Alert::loadById($idAlert);
        echo "Alert : ".$alert->id;
        echo " Old statut : ".$alert->statut;
        $alert->statut= $statut;
        if ($alert->save()){
            echo " new statut : ".$alert->statut;
        }else{
            echo "error while savin the alert";
        }

    }
    /**
     * cette fonction filtre les alertes selon le type et la date
     * @param $startDate
     * @param $endDate
     * @param $type
     */
    public function actionGetListAlertByFilter($startDate,$endDate,$type)
    {
        $alerts = \Business\Alert::GetListAlertByFilter($startDate,$endDate,$type);
        $tabAlert =[];
        foreach ($alerts as $alert){
            $tabAlert []= $alert->attributes;
        }
        echo "<pre>";
        print_r(json_encode($tabAlert,JSON_PRETTY_PRINT));
    }
    /**
     * cette fonction permet de creer une notification en creant un alert apartir des donneés en parametres
     * @param $idEcart
     * @param $statut
     * @param $type
     * @param $idSubCampaign
     * @return string
     */
    public function actionBuildNotification($idEcart, $statut, $type, $idSubCampaign )
    {
        $product = \Business\Product::loadBySubCamp($idSubCampaign);
        $refProduct = $product->ref;
        $alert = new \Business\Alert();
        $alert->statut = $statut;
        $alert->idSubCampaign = $idSubCampaign;
        $alert->idEcart = $idEcart;
        $typeEcart= constant("\\Business\\Ecart::TYPE_$type");
        if ($alert->save()){
            $message = "l'alert  ".$alert->id." à été ajouté <br> contien un : ".$typeEcart." <br>le produit concernée est : ".$refProduct;
        }else{
            $message= "error while savin the alert";
        }
        $sendNotification = $this->sendNotification("Alert ".$typeEcart,$message,$idEcart);
        if ($sendNotification){
            echo "Notification Sent<br>";
        }else{
            echo "error while sending the notification<br>";
        }



        echo $message;
        return $message;
    }

    /**
     *
     * @author FIKRI
     * @param $objet
     * @param $message
     * @param $idEcart
     * @return string
     */
    public function sendNotification($objet,$message,$idEcart)
    {


        $sendAlert  = \MailHelper::sendMail('marouane.marouane@kindyinfomaroc.com','Alert@kindyinfomaroc.com',$objet,$message);
        if ($sendAlert){
            $out= "alert Sent";
        }else{
            $out ="Probleme :: ereur d'envoi de l'alert";
        }
        return $out;
    }



}

?>

