

<?php
/**
 * Description of AdminController
 *
 * @author JulienL
 */
\Yii::import( 'ext.MailHelper' );

class AlertController extends AdminController
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

		$user = \Business\User::load( Yii::app()->user->getId() );
		!$user  ? $this->redirect('login') : $this->render( '//product/index' );
	}


    /**
     * cette fonction permet de creer un alert selon les donnees
     * @param $commentDescription
     * @param $statut
     * @param $idSubCampaign
     * @param $idEcart
     */
    public function actionCreateAlert($commentDescription,$statut, $idSubCampaign, $idEcart)
{
    $date = new DateTime('now');

    $newAlert = new \Business\Alert();
    $newAlert->creationDate = $date->format('Y-m-d H:i:s');
    $newAlert->statut = $statut;
    $newAlert->idSubCampaign = $idSubCampaign;
    $newAlert->idEcart = $idEcart;

    if ($newAlert->save()) {
        $comment = new Business\CommentAlert();
        $comment->creationDate = $date->format('Y-m-d H:i:s');
        $comment->description = $commentDescription;
        $comment->idAlert = $newAlert->id;
        if (!$comment->save()){
            echo "error while saving the comment";
            print_r($comment->getErrors());
        }
        print_r("OK! Saved.");
    } else {
        echo "error while saving the new Alert";
        print_r($newAlert->getErrors());
    }
}

    /**
     * cette fonction permet de creer un alert User
     * @param $idAlert
     * @param $idUser
     * @param $action
     *
     */
    public function actionCreateAlertUser($idAlert, $idUser, $action)
    {
        $date = new DateTime('now');
        $newAlertUser = new \Business\AlertUser();
        $newAlertUser->idAlert = $idAlert;
        $newAlertUser->idUser = $idUser;
        $newAlertUser->creationDate = $date->format('Y-m-d H:i:s');
        $newAlertUser->action = $action;

        if ($newAlertUser->save()) {
            print_r("OK! Saved.");
        } else {
            echo '<pre>';
            print_r($newAlertUser->attributes);
        }
    }

    /**
     *
     * @param null $userId
     * @return array
     */
    public static function getAssignedRoles($userId=null)
    {
        $user = Yii::app()->getUser();
        if( $userId===null && $user->isGuest===false )
            $userId = $user->id;
        $roles = [];
        $rolesByUser = \Business\UserRole::loadByUser($userId);
        foreach ($rolesByUser as $role) {
            $roles[] = \Business\Role::load($role->idRole);
        }

        return $roles;
    }

    /**
     * cette fonction permet de retourner le role de l'utilisateur
     * @param $idUser
     */
    public function actionHasRole($idUser)
    {
        $user = \Business\User::load( Yii::app()->user->getId() );
        !$user  ? $this->redirect('login') : $this->render( '//MoteurTest/alert' );

        $porteur = \Yii::app()->params['porteur'];

        $roles = self::getAssignedRoles($idUser);
        $rolesNames = [];
        foreach ($roles as $role) {
            $rolesNames[] = $role->name;
        }
        $role_user = [];
        $PorteurBase = \Business\PorteurCompany::model()->loadByAbr($porteur);
        if(!$PorteurBase) {
            $role_user[] = 0;
        } else {
            $userIsExist = \Business\UserPorteur::model()->loadByUserAndPorteur($idUser, $PorteurBase->id);
            if(in_array("ADMIN_ALERTS_ANAC", $rolesNames)) {
                $role_user[] = 1;
            } else {
                if (!$userIsExist) {
                    $role_user[] = 0;
                } else {
                    if(in_array("CP_IT", $rolesNames)) {
                        $role_user[] = 2;
                    } else {
                        $role_user[] = 3;
                    }
                }
            }
        }

        $json = json_encode($role_user);
        print_r($json);
    }

    /***
     * @param $idAlert
     * @param $type
     */
    public function actionDetailsAlert($idAlert,$type)
    {
        $alert = \Business\Alert::loadById($idAlert);
        echo "<pre>";
        $tabAlert = [];
        $tabEcartType =[];
        $tabComment = [];
        $tabAlert = $alert->attributes;
        $comments = \Business\CommentAlert::getCommentsByidAlert($idAlert);
        foreach ($comments as $comment){
            $tabComment []= $comment->attributes;
        }
        $ecart =$alert->Ecart;
        $tabAlert ['comment'] = $tabComment;
        $tabAlert['ecart'] = $ecart->attributes;
        //test sur le type d'ecart pour le récuperer de la table corespondante ecartshoot ou gp ou delivery
        if ($type==1){
            $ecartTypes = \Business\EcartShoot::getEcartShootByidEcart($ecart->id);
        }elseif ($type==2){
            $ecartTypes = \Business\EcartDelivery::getEcartDeliveryByidEcart($ecart->id);
        }elseif ($type==3){
            $ecartTypes = \Business\HistoryGp::getHistoryGpByidEcart($ecart->id);
        }
        foreach ($ecartTypes as $ecartType){
            $tabEcartType [] = $ecartType->attributes;
        }
        if ($type==1){
            $tabAlert['ecart']['EcartShoot'] = $tabEcartType;
        }elseif ($type==2){
            $tabAlert['ecart']['EcartDelivery'] = $tabEcartType;
        }elseif ($type==3){
            $tabAlert['ecart']['HistoryGp'] = $tabEcartType;
        }
        print_r(json_encode($tabAlert,JSON_PRETTY_PRINT));
        echo "</pre>";

    }


    //changer le statut de l'alert by Marouane FIKRI
    /**
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

    //changer le statut de l'alert
    /**
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
    //changer le statut de l'alert
    /**
     * @param $idEcart
     * @param $statut
     * @param $type
     * @param $idSubCampaign
     * @return string
     */
    //public function actionBuildNotification($id_ecart, $statut, $type, $id_subCompaign )
    public function actionBuildNotification($idEcart, $statut, $type, $idSubCampaign )
    {
        $date=new DateTime('now');
        $product = \Business\Product::loadBySubCamp($idSubCampaign);
        $refProduct = $product->ref;
        $alert = new \Business\Alert();
        $alert->statut = $statut;
        $alert->creationDate = $date->format('Y-m-d H:i:s');
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

