<?php
/**
 * Description of MedController
 *
 * @author AL.
 */
class MedController extends AdminController {

    public function init(){
        parent::init();
        $action = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
        if( $action == 'Med/login' || $action == 'Med/index' ){
            // Url de la page de login ( pour les redirections faites par les Rules ) :
            Yii::app()->user->loginUrl = array( '/Med/login' );
        }

        // Default page title :
        $this->setPageTitle( 'Med Controller' );
    }

    public function filters(){
        return array( 'accessControl' );
    }

    public function accessRules(){
        return array(
            array(
                'allow',
                'users' => array('@'),
                'roles' => array( 'ADMIN', 'ADMIN_ALERTS_ANAC', 'CP_IT' )
            ),
            array(
                'allow',
                'actions' => array( 'login', 'logout' ),
                'users' => array('*')
            ),
            array('allow'),
        );
    }

    public function actionIndex()
	{
        $user = \Business\User::load( Yii::app()->user->getId() );
        !$user  ? $this->redirect('login') : $this->render( '//product/index' );
	}

    /**
     * @author AL.
     * @param $idUser
     * @param $dateConstat
     * Retourne tous les messages envoyés au client après la date de shoot(Date de référence) jusqu’à celle de constat.
     */
    public function actionGetListReflationUserByIdUserAndDateConstat($idUser, $dateConstat)
    {

        $dateCons = new DateTime($dateConstat); //Date format
        $campsHisByUser = \Business\CampaignHistory::findAllCampaignHistorybyIdUSer($idUser); //Récupérer les campaigns history par l'id User
        $arrayCum = []; //Initialiser le tableau récupérateur

        foreach($campsHisByUser as $camp) {
            $subCampaign    = \Business\SubCampaign::loadByIdSubCamp($camp->idSubCampaign);//Récupérer la subCampaign par son ID
            $userBehaviors = $camp->UserBehavior;//Récupérer l'objet userBehavior qui correspond à la campaign active

            foreach ($userBehaviors as $userBeh) {
                $dateRef = new DateTime($camp->initialShootDate);//Date format

                //Récupérer que les campaigns qui ont une date après la date de shoot et avant la date de constat
                if( $dateRef <= $dateCons ) {
                    $subCampaignRef = \Business\SubCampaignReflation::loadByIdSubCamp($camp->idSubCampaign, $userBeh->reflation);
                    $array = [];
                    /*$array['idCampHist']        = $camp->id;
                    $array['dateReference']     = $camp->initialShootDate;
                    $array['idUser']            = $camp->idUser;
                    $array['idSubCampaign']     = $camp->idSubCampaign;
                    $array['position']          = $subCampaign->position;*/
                    $array['number']            = $subCampaignRef->number;
                    /*$array['view']              = $subCampaignRef->view;
                    $array['idUserBehavior']    = $userBeh->id;
                    $array['idCampaignHist']    = $userBeh->idCampaignHistory;
                    $array['reflation']         = $userBeh->reflation;*/
                    array_push($arrayCum, $array);
                }
            }
        }

        $json = json_encode($arrayCum);//Convertir le tableau en format jSon
        print_r($json);
    }

    /**
     * @author AL.
     * @param $idUser
     * @param $idCampaignHistory
     * @param $reflation
     * @return int|null
     * retourne:
     * 0 si la ligne n’existe pas au niveau de la table UserBehavior ou bien si le client n’a pas cliqué sur un lien BDC,
     * 1 si le client a cliqué sur un lien BDC.
     */
    public function getBdcClick($idUser, $idCampaignHistory, $reflation)
    {
        $res = null;
        $campsHistory = \Business\CampaignHistory::findAllCampaignHistorybyIdUSer($idUser);

        foreach ($campsHistory as $camp) {
            $userBehaviors = $camp->UserBehavior;
            foreach ($userBehaviors as $userBeh) {
                if($idUser == $camp->idUser && $idCampaignHistory == $camp->id && $reflation == $userBeh->reflation && $userBeh->bdcClicks == 1) {
                    $res = 1;
                } else {
                    $res = 0;
                }
            }
        }
        return $res;
    }

    /**
     * @author AL.
     * @param $idUser
     * @param $idSubCampaignReflation
     * Vérifier si le client a ouvert le message ou non, si non le champ ouverture aura comme valeur 0 ainsi que pour le BDC et l’achat,
     * sinon nous allons besoin de la méthode getBdcClick
     */
    public function actionGetOpenLinkMailByUserAndSubCampaignReflation($idUser, $idSubCampaignReflation)
    {
        $arrayCum = [];
        $openLinkMails = \Business\Openedlinkmail::loadOpenedLmBySubCampReflAndUser($idUser, $idSubCampaignReflation);
        if(!$openLinkMails) {
            $arrayCum[] = 0;
        } else {
            $campsHistory = \Business\CampaignHistory::findAllCampaignHistorybyIdUSer($idUser);
            foreach ($campsHistory as $campHist) {
                $subCampReflations = \Business\SubCampaignReflation::loadByIdSubCampaignAndIdSubCampRefl($campHist->SubCampaign->id, $idSubCampaignReflation);
                if($subCampReflations) {
                    $arrayCum[] = $this->getBdcClick($idUser, $campHist->id, $subCampReflations->number);
                }
            }

        }

        //Convertir le tableau en format jSon
        $json = json_encode($arrayCum);
        print_r($json);
    }

    /**
     * @author AL.
     * @param $idUser
     * @param $listCampaign
     * Retourne la liste des fids achetées par le client en récupérant la reflation(message par lequel qui a effectué l’achat).
     */
    public function actionGetListPurshased($idUser, $listCampaign)
    {
        $user = \Business\User::loadById($idUser);
        $invoices = \Business\Invoice::getByEmail($user->email);
        $arrayCum = [];
        foreach ($invoices as $inv) {
            $array = [];
            $array['campaign'] = $inv->campaign;
            $array['priceStep'] = $inv->priceStep;
            //$pp[] = $inv->attributes;
            //$campaign = \Business\Campaign::loadByRef($inv->campaign);
            //$subCampaign = \Business\SubCampaign::loadByCampaign($campaign->id);
            //$reflation = \Business\SubCampaignReflation::loadByIdSubCampaign($subCampaign->id);
            array_push($arrayCum, $array);
        }
        echo "<pre>";
        print_r(json_encode($arrayCum, JSON_PRETTY_PRINT));
        echo "</pre>";
        /*$campsHistory = \Business\CampaignHistory::findAllCampaignHistorybyIdUSer($idUser);
        foreach ($campsHistory as $campHist) {
            $idCampaign = \Business\SubCampaign::loadByIdSubCamp($campHist->idSubCampaign)->idCampaign;
            $subCampReflations = \Business\SubCampaignReflation::loadByIdSubCampaignAndIdSubCampRefl($campHist->SubCampaign->id, $idSubCampaignReflation);
            if($subCampReflations) {
                $arrayCum[] = $this->getBdcClick($idUser, $campHist->id, $subCampReflations->number);
            }
        }*/
    }


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

    public function actionHasRole($idUser)
    {
        $user = \Business\User::load( Yii::app()->user->getId() );
        !$user  ? $this->redirect('login') : $this->render( '//product/index' );

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

    public function actionDetailsAlert($idAlert)
    {
       $alert = \Business\Alert::loadById($idAlert);
       $alertInfo =[];
        $alertInfo = $alert->attributes;
        echo "<hr>";
        $ecart = \Business\Ecart::model()->loadById($alert->idEcart);
        echo '<pre>';
        $alertInfo [$alert->idEcart]=$ecart->attributes;
       echo '<pre>';
       print_r(json_encode($alertInfo,JSON_PRETTY_PRINT));

    }

    public function actionHasRoleOld()
    {
        $user = \Business\User::load( Yii::app()->user->getId() );
        !$user  ? $this->redirect('login') : $this->render( '//product/index' );

        $role_user = 0;

        $euipeByPorteur = [
            'fr_evamaria'	=> ['ahmed.lamhoujeb@kindyinfomaroc.com', 'test_cp_anac@kindyinfomaroc.com'],
            'fr_rinalda'	=> ['test_coll_anac@kindyinfomaroc.com'],
        ];

        if(Yii::App()->User->checkAccess("ADMIN_ALERTS_ANAC")) {
            $role_user = 1;
        } else {
            $porteur = \Yii::app()->params['porteur'];
            echo \Yii::app()->user->getState('User')->email;
            if (in_array(\Yii::app()->user->getState('User')->email, $euipeByPorteur[$porteur])) {
                if(Yii::App()->User->checkAccess("CP_IT")) {
                    $role_user = 2;
                } else {
                    $role_user = 3;
                }
            }
        }

        echo "<pre>";
        print_r(json_encode($role_user, JSON_PRETTY_PRINT));
        echo "</pre>";

        /*if($role === 'ADMINISTRATEUR') {
            if(Yii::App()->User->checkAccess("ADMIN_ALERTS_ANAC")) {
                return true;
            } else {
                return false;
            }
        } else if ($role === 'COLLABORATEUR') {
            $porteur = \Yii::app()->params['porteur'];
            if (in_array(Yii::app()->user->getState('User')->email, $euipeByPorteur[$porteur])) {

            }
        }*/



        //echo $role_user;
    }

    public function actionHasRoleOld2($idUser)
    {
        $user = \Business\User::load( Yii::app()->user->getId() );
        !$user  ? $this->redirect('login') : $this->render( '//product/index' );

        $porteur = \Yii::app()->params['porteur'];
        $euipeByPorteur = [
            'fr_evamaria'	=> ['ahmed.lamhoujeb@kindyinfomaroc.com', 'test_cp_anac@kindyinfomaroc.com'],
            'fr_rinalda'	=> ['test_coll_anac@kindyinfomaroc.com'],
        ];

        $email = \Business\User::loadById($idUser)->email;

        $roles = self::getAssignedRoles($idUser);
        $rolesNames = [];
        foreach ($roles as $role) {
            $rolesNames[] = $role->name;
        }
        $role_user = [];

        $role_user[] = 0;

        if(in_array("ADMIN_ALERTS_ANAC", $rolesNames)) {
            $role_user[] = 1;
        } else if (in_array($email, $euipeByPorteur[$porteur])) {
            if(in_array("CP_IT", $rolesNames)) {
                $role_user[] = 2;
            } else {
                $role_user[] = 3;
            }
        }

        $json = json_encode($role_user);
        print_r($json);
    }




}