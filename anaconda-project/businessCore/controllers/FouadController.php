<?php

/**
 * Description of Controller controller
 *
 * @author DANI Fouad
 * @package Controllers
 */


// importer l'extension AnacondaBehavior
\Yii::import('ext.AnacondaBehavior');
\Yii::import( 'ext.MailHelper' );
Yii::import('ext.Class_API', true);

class FouadController extends AdminController
{

    public $layout = '';

    //Fonctionnalité d'import via API
    private $anacondaImportErrorMails = array(
        'fouad.dani@kindyinfomaroc.com'
    );
    private $smartFocusSupportMails = array(
        'fouad.dani@kindyinfomaroc.com',
        // 'supportch@smartfocus.com',
        // 's.benaissa@kindyinfomaroc.com',
        // 'zakaria.chniber@kindyinfomaroc.com'
    );

    public function actionIndex()
    {
        $ab = new AnacondaBehavior();

        $listEmails = $ab->fileToArray("../Anaconda Data/test_import.txt");
        $gp = 3;
        $ab->ShootPlanification($listEmails, "egg", "10/10/2017", $gp);
    }


    public function actionImportErrorList($porteur = false, $sendMail = true){
        if( !empty($porteur) ){
            if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) ){
                $msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
                echo $msg;
            }else{
                // calcule de nombre des imports erronés
                $countImport = \AnacondaBehavior::getImportErrorList();

                if(empty($countImport)){
                    $countImport = 0;
                }
                // load le nombre des import enregistrer dans la BDD
                $porteurSettings = \Business\PorteurSettings::getAllSettings();
                $porteurSetting  = \Business\PorteurSettings::load($porteurSettings[0]->id);

                if (empty($porteurSetting->countImport)) {
                    $porteurSetting->countImport = 0;
                }
                // recalculer le nouveau nombre des imports erronés
                $countImport += $porteurSetting->countImport;

                if ($countImport < 7) {
                    $porteurSetting->countImport = $countImport;
                    $porteurSetting->save();
                    $msg  = '<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).' sur le Porteur: '.$GLOBALS['porteurMap'][$porteur].'</div><br/>';
                    $msg .= '<br/><div style="color:#0c6380">le nombre des imports de statuts qui causent une anomalie a atteint : ' .$countImport.' imports erronés</div> <br>';
                    if( $sendMail ){
                        // \MailHelper::sendMail( $this->anacondaImportErrorMails, 'Cron', 'Fonctionnalité d\'import via API', $msg );
                        echo $msg;
                        return $msg;
                    }
                }else{
                    // load le nombre des import enregistrer dans la BDD
                    $porteurSettings = \Business\PorteurSettings::getAllSettings();
                    $porteurSetting  = \Business\PorteurSettings::load($porteurSettings[0]->id);
                    // rénitialisation du compteur des imports erronés
                    $porteurSetting->countImport = 0;
                    // Enregistrer le nouveau comptage des imports erronés
                    $porteurSetting->save();

                    //initialisation des variables $msg : corps du mail à envoyer lors de l'execution du cron ImportErrorList.
                    $msg ='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).' sur le Porteur: '.$GLOBALS['porteurMap'][$porteur].'</div><br/>';

                    $msg .= '<br/><div style="color:#0c6380">Je vous contacte afin de vous informer que le nombre des imports de statuts qui causent une anomalie a atteint:'.$countImport .' imports erronés</div> <br>';
                    $msg .= '<br/><div style="color:#0c6380">Merci de supprimer ces imports du porteur <span  style="color:red; font-weight: bold">'.$GLOBALS['porteurMap'][$porteur].'</span> afin que la fonctionnalité ne sera pas bloquée.</div> <br>';

                    if( $sendMail ){
                        // \MailHelper::sendMail( $this->smartFocusSupportMails, 'Cron', 'Fonctionnalité d\'import via API', $msg );
                        echo $msg;
                        return $msg;
                    }

                }
            }
        }
    }

    public function actionLastCampaign()
    {
        $idUser = $_GET['idUser'];
        $dateConstat = $_GET['dateConstat'];
        $campaignHistory = \Business\CampaignHistory::getLastCampaignHistoryByIdUserBeforDate($idUser, $dateConstat);
        $subCampaign = \Business\CampaignHistory::getLastCampaignByIdUserBeforDateConstat($idUser, $dateConstat);
        $json = \CJSON::encode($campaignHistory);
        echo '<pre>';
        print_r($json);
        echo '</pre><br></pre>';
        $json = \CJSON::encode($subCampaign);
        print_r($json);
        echo '</pre>';
    }

    public function actionListCampaignHistoryByIdUserBetweenDate()
    {
        $idUser = $_GET['idUser'];
        // $dateConstat = $_GET['dateConstat'];
        $dateRef = $_GET['dateRef'];
        //$liste = \Business\CampaignHistory::getListCampaignHistoryByIdUserBetweenDate($idUser, $dateRef, $dateConstat);
        $list = \Business\CampaignHistory::getListCampaignHistoryByIdUserAndDateRef($idUser, $dateRef);
        $json = \CJSON::encode($list);
        echo  $json;
    }

    public function actionGetNextFid()
    {

        $idCampaign = $_GET['idCampaign'];
        echo "NextFid de la campaign " . $idCampaign . "<br>";
        $subCampaign = \Business\Campaign::getNextFid($idCampaign);
        $json = \CJSON::encode($subCampaign);
        print_r($json);

    }


    public function actionGetNextShoot()
    {
        $idCampaignHistory = $_GET['idCampaignHistory'];
        $CampaignHistory = \Business\CampaignHistory::load($idCampaignHistory);
        $nextCampaignHistory = \Business\CampaignHistory::getNextShoot($CampaignHistory);
        $json = \CJSON::encode($nextCampaignHistory);
        print_r($json);
    }

    public function actionGetNextGP()
    {
        $idCampaignHistory = $_GET['idCampaignHistory'];
        $CampaignHistory = \Business\CampaignHistory::load($idCampaignHistory);
        $nextCampaignHistory = \Business\CampaignHistory::getNextGP($CampaignHistory);
        $json = \CJSON::encode($nextCampaignHistory);
        print_r($json);
    }
    public function actionGetNextIndexImplication()
    {
        $idCampaignHistory = $_GET['idCampaignHistory'];
        $currentCampaignHistory = \Business\CampaignHistory::load($idCampaignHistory);
        $nextCampaignHistory = \Business\CampaignHistory::getNextIndexImplication($currentCampaignHistory);
        $json = \CJSON::encode($nextCampaignHistory);
        print_r($json);
    }

    public function actionNextIndexImplication()
    {
        $idCampaignHistory = $_GET['idCampaignHistory'];
        $dateRef = $_GET['dateRef'];
        $CampaignHistory = \Business\CampaignHistory::load($idCampaignHistory);
        $nextCampaignHistory = \AnacondaBehavior::getNextIndexImplication($CampaignHistory, $dateRef);
        $json = \CJSON::encode($nextCampaignHistory);
        print_r($json);
    }
}

?>