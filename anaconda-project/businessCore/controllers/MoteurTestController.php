<?php
/**
 * Description of Controller controller
 *
 * @author YacineR
 * @package Controllers
 */


// importer l'extension AnacondaBehavior
\Yii::import('ext.AnacondaBehavior');


class MoteurTestController extends AdminController
{
    public $layout = '//product/menu';

    /**
     * Initialisation du controleur
     */
    public function init()
    {
        parent::init();
        // Url de la page de login ( pour les redirections faites par les Rules ) :
        Yii::app()->user->loginUrl = array('/MoteurTest/login');
        // Default page title :
        $this->setPageTitle('Login Administration');
    }

    // ************************** RULES / FILTER ************************** //
    public function filters()
    {
        return array('accessControl', 'postOnly + delete');
    }


    public function accessRules()
    {
        return array(
            array(
                'allow',
                'users' => array('@'),
                'roles' => array('ADMIN')
            ),
            array(
                'allow',
                'actions' => array('login'),
                'users' => array('*')
            ),
            array('deny'),
        );
    }

    // ************************** ACTION pour ouverture des dialogs added by mohamed meski************************** //

    public function actionPurshased()
    {

        $invoice = AnacondaBehavior::getSubdivisionGPByMail("astridcarlssondinis@yahoo.fr");
        echo $invoice;

    }

    public function actionIndex()
    {
        $this->render('//MoteurTest/home');
    }

    public function actionSimulation()
    {
        $this->render('//MoteurTest/simulation');
    }
    public function actionAlert()
    {
        $this->render('//MoteurTest/alert');
    }

    public function actionLayout()
    {
        $this->render('//MoteurTest/layout');
    }

    public function actionEcartshoot()
    {
        $this->render('//MoteurTest/ecartshoot');
    }
    public function actionEchantillonnage()
    {
        $this->render('//MoteurTest/echantillonnage');
    }

    public function actionEcartlivraison()
    {
        $this->renderPartial('//MoteurTest/ecartlivraison');

    }

    public function actionBase()
    {
        $this->render('//MoteurTest/base');

    }
    //retourne toutes les fids qui ne sont pas anaconda
    public function actionAllCampaigns()
    {

        $allcampaings = \Business\Campaign::allCampaigns();
        echo CJSON::encode($allcampaings);

    }


    public function actiongetUserRoles()
    {
        $info = Yii::App()->User->checkAccess("ADMIN");

        echo CJSON::encode($info);

    }

    public function actionGetPorteurSettings()
    {

        $settings = \Business\PorteurSettings::getAllSettings();
        echo CJSON::encode($settings);

    }


    //get tous les lots
    public function actionGetAllLots()
    {

        $lots = \Business\LotCampaign::getAllLots();

        echo CJSON::encode($lots);


    }

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

        echo CJSON::encode($tabAlert);

    }


    public function actionAnacondaCampaigns()
    {

        $anacondacampaings = \Business\Campaign::anacondaCampaigns();
        $list = array();
        foreach ($anacondacampaings as $camp) {
            $temp = array();
            $temp['campaign'] = $camp;
            $subcampaigns = $camp->SubCampaign;
            if (count($subcampaigns) > 0) {
                foreach ($subcampaigns as $subcampaign) {
                    $tempo = array();
                    $tempo['product'] = $subcampaign->Product;
                    $tempo['webforms'] = $subcampaign->Product->RouterEMV;
                    $tempo['segments'] = $subcampaign->AnacondaSegment;
                    $temp['products'][] = $tempo;
                }

            }

            $temp['lot'] = $camp->LotCampaign;
            $list[] = $temp;

        }

        echo CJSON::encode($list, false);

    }




}

?>