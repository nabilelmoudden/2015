<?php
/**
 * Description of Controller controller
 *
 * @author YacineR
 * @package Controllers
 */


// importer l'extension AnacondaBehavior
\Yii::import('ext.AnacondaBehavior');


class AnacondaTestEngineController extends AdminController
{
    public $layout = '//product/menu';

    /**
     * Initialisation du controleur
     */
    public function init()
    {
        parent::init();
        // Url de la page de login ( pour les redirections faites par les Rules ) :
        Yii::app()->user->loginUrl = array('/TestEngine/login');
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

        $this->render('//TestEngine/index');
    }

    public function actionSettings()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/settings');
    }
    public function actionMoteurTesT()
    {
    	$this->layout = "";  
    	$this->renderPartial('//TestEngine/index');
    }
     


    public function actionOrganiserFid()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/organiserFid');

    }

    public function actionLots()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/lots');

    }

    public function actionEditLots()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/ajouterFid');

    }

    public function actionUpdateSettings()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/updatesettings');

    }

    public function actionAjouterEnchainement()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/ajouterEnchainement');
    }

    public function actionDeplacer()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/deplacer');

    }

    public function actionReplace()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/replace');

    }

    public function actionWebforms()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/webforms');

    }

    public function actionSegments()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/segments');

    }

    public function actionPermuter()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/permuter');

    }

    public function actionConfirmation()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/confirmation');

    }

    public function actionOpensubdivision()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/subdivision');

    }

    public function actionwaitingSubdivision()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/waitingsubdivision');

    }

    public function actionInfobulle()
    {
        $this->layout = "";
        $this->renderPartial('//TestEngine/infobulle');

    }

    public function actionInscriptionLeads()
    {
        $nbrEmails = Yii::app()->request->getParam('nbr');
        $bol = AnacondaBehavior::InscriptionLeads($nbrEmails);

        echo $bol;
    }

    public function actionGenerationLeads()
    {
        $nbrEmails = Yii::app()->request->getParam('nbr');
        $chemin = AnacondaBehavior::generationLeads($nbrEmails);
        echo $chemin;
    }

    /*
    * start action Simulation d'ouverture leads added by DANI Fouad
     */
    public function actionSimulationOuvertureLeads()
    {
        $nbrEmails = Yii::app()->request->getParam('nbr');
        $activityHour = Yii::app()->request->getParam('hour');
        $bol = AnacondaBehavior::SimulationOuvertureLeads($nbrEmails, $activityHour);
        echo  $bol;
    }
    
    /*
     * start action d�calage de leads added by MESKI Mohamed
     */
    public function actionDecalageDeLeads()
    {
    	$nbrEmails = Yii::app()->request->getParam('nbr');
    	$bol = AnacondaBehavior::DecalageDeLeads($nbrEmails);
    	echo $bol;
    }

    // ************************** Fin Action dialog added by mohamed meski************************** //
    // ************************** Traitement metier added by mohamed meski ************************** //

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

    public function actionChangeDisplay()
    {
        $data = json_decode(file_get_contents("php://input"));
        $display = $data->display;
        if ($display) {
            $display = 1;
        } else {
            $display = 0;
        }
        $settings = \Business\PorteurSettings::getAllSettings();
        if (count($settings) > 0) {
            foreach ($settings as $s) {
                $s->display = $display;
                $s->save();
            }

        } else {
            $setting = new \Business\PorteurSettings();
            $setting->display = $display;
            $setting->save();
        }
        $settings = \Business\PorteurSettings::getAllSettings();
        echo CJSON::encode($settings);
    }

    public function actionGetPorteurSettings()
    {

        $settings = \Business\PorteurSettings::getAllSettings();
        echo CJSON::encode($settings);

    }

    //test si la subdivision est faite
    public function actionIsSubdivised()
    {

        $ab = new AnacondaBehavior();
        $messages = array();
        $messages['value'] = $ab->isSubdivised();
        echo CJSON::encode($messages, false);

    }

    //la subdivision initial 
    public function actionSubdivision()
    {

        $ab = new AnacondaBehavior();
        $messages = array();
        $messages['value'] = $ab->Subdivision();
        echo CJSON::encode($messages, false);

    }
    public function actionResubdiviser()
    {
    	$ab =new AnacondaBehavior();
    	$messages = array();
    	$messages['value'] = $ab->ReSubdivise();
    	echo CJSON::encode($messages, false);
    }
    //subdiviser un nombre de leads donn�e
    public function actionSubdiviseByNumber()
    {
        $data = json_decode(file_get_contents("php://input"));
        $number = $data->nbrSubdivision;
        $ab = new AnacondaBehavior();
        $messages = array();
        $messages['value'] = $ab->SubdiviseByNumber($number);
        echo CJSON::encode($messages, false);

    }

    //retourne toutes les fids  anaconda added by mohamed meski
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

    //settings save added by mohamed meski
    public function actionSaveSettings()
    {

        $settings = new \Business\AnacondaSettings();
        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $settings->groupPrice = $data->gp;
        $settings->nextStepSum = $data->ns;
        $settings->previousStepClicks = $data->ps;
        $settings->durationNext = 0;
        $settings->durationPrevious = $data->dp;

        if ($settings->save()) {
            $messages['succes'] = "un nouveau enregistrement de anaconda settings pour le gp:" . $data->gp . " a ete cree";
        } else {
            $messages['erreur'] = "impossible de creer l enregistrement";
        }


        echo json_encode($messages, JSON_UNESCAPED_UNICODE);


    }

    //get all settings retourne tous les enregistrement de anaconda settingsGP added by mohamed meski
    public function actionGetAllSettings()
    {

        $anacondasettings = \Business\AnacondaSettings::getAllSettings();
        echo CJSON::encode($anacondasettings);

    }

    //get tous les lots
    public function actionGetAllLots()
    {

        $lots = \Business\LotCampaign::getAllLots();

        echo CJSON::encode($lots);


    }

    //Lots save added by mohamed meski
    public function actionSaveLots()
    {

        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $nombre_lot = $data->lot->numlot;
        $max = $data->max;
        $total = $nombre_lot + $max;

        for ($i = $max + 1; $i <= $total; $i++) {
            $lot = new \Business\LotCampaign();
            $lot->numLot = $i;
            $lot->creationDate = new CDbExpression('NOW()');
            $lot->save();
        }

        echo json_encode($messages, JSON_UNESCAPED_UNICODE);


    }

    //set is anaconda added by mohamed meski
    public function actionSetIsAnaconda()
    {

        $campaign = new \Business\Campaign();
        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $fids_anaconda = $data->fids_anaconda;
        $num_lot = $data->num;
        foreach ($fids_anaconda as $fid) {
            $campaign = \Business\Campaign::load($fid->id);
            $campaign->isAnaconda = 1;
            $campaign->campaignStatus = 0;
            $campaign->idLotCampaign = $num_lot->id;
            $campaign->save();
        }
        echo json_encode($messages, JSON_UNESCAPED_UNICODE);


    }


    //retirer d'anaconda
    public function actionRetirer()
    {

        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $camp = $data->campaign;
        $pre = $data->previous;
        $ne = $data->next;
        $campaign = \Business\Campaign::load($camp);
        $campaign->isAnaconda = null;
        $campaign->idNextCampaign = null;
        $campaign->idLotCampaign = null;
        $campaign->campaignStatus = 0;
        $campaign->save();
        if ($pre != false && $ne != false) {
            $previous = \Business\Campaign::load($pre);
            $next = \Business\Campaign::load($ne);
            $previous->idNextCampaign = $next->id;
            $previous->save();
        }
        if ($pre != false && $ne == false) {
            $previous = \Business\Campaign::load($pre);
            $previous->idNextCampaign = null;
            $previous->save();
        }

        echo json_encode($messages, JSON_UNESCAPED_UNICODE);


    }

    //deplacer vers un autre lot
    public function actionSetLot()
    {

        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $camp = $data->campaign;
        $pre = $data->previous;
        $ne = $data->next;
        $lot = $data->lot;
        $campaign = \Business\Campaign::load($camp);
        $campaign->idNextCampaign = null;
        $campaign->idLotCampaign = $lot->id;
        $campaign->save();
        if ($pre != false && $ne != false) {
            $previous = \Business\Campaign::load($pre);
            $next = \Business\Campaign::load($ne);
            $previous->idNextCampaign = $next->id;
            $previous->save();
        }
        if ($pre != false && $ne == false) {
            $previous = \Business\Campaign::load($pre);
            $previous->idNextCampaign = null;
            $previous->save();
        }
    }

    //permuter deux fids
    public function actionPermuterDeuxCampaigns()
    {

        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $camp = $data->campaign;
        $pre = $data->previous;
        $ne = $data->next;
        $camp_permuter = $data->permuteravec;
        $pre_permuter = $data->previous_permuter;
        $ne_permuter = $data->next_permuter;
        $campaign = \Business\Campaign::load($camp);
        $permuteravec = \Business\Campaign::load($camp_permuter);
        $campaign->idNextCampaign = null;
        $permuteravec->idNextCampaign = null;
        $idlotcampaign = $campaign->idLotCampaign;
        $idlotcampaign_permuter = $permuteravec->idLotCampaign;
        $campaign->idLotCampaign = $idlotcampaign_permuter;
        $permuteravec->idLotCampaign = $idlotcampaign;
        $campaign->save();
        $permuteravec->save();

        if ($ne == $camp_permuter) {
            if ($pre != false) {
                $previous = \Business\Campaign::load($pre);
                $previous->idNextCampaign = $permuteravec->id;
                $previous->save();
            }
            if ($ne != false) {
                $permuteravec->idNextCampaign = $camp;
                $permuteravec->save();
            }
            if ($ne_permuter != false) {
                $next_permuter = \Business\Campaign::load($ne_permuter);
                $campaign->idNextCampaign = $next_permuter->id;
                $campaign->save();
            }
        } else if ($pre == $camp_permuter) {
            if ($pre != false) {
                $campaign->idNextCampaign = $camp_permuter;
                $campaign->save();
            }
            if ($ne != false) {
                $next = \Business\Campaign::load($ne);
                $permuteravec->idNextCampaign = $next->id;
                $permuteravec->save();
            }
            if ($pre_permuter != false) {
                $previous_permuter = \Business\Campaign::load($pre_permuter);
                $previous_permuter->idNextCampaign = $campaign->id;
                $previous_permuter->save();
            }

        } else {
            if ($pre != false) {
                $previous = \Business\Campaign::load($pre);
                $previous->idNextCampaign = $permuteravec->id;
                $previous->save();
            }
            if ($ne != false) {
                $next = \Business\Campaign::load($ne);
                $permuteravec->idNextCampaign = $next->id;
                $permuteravec->save();
            }
            if ($pre_permuter != false) {
                $previous_permuter = \Business\Campaign::load($pre_permuter);
                $previous_permuter->idNextCampaign = $campaign->id;
                $previous_permuter->save();
            }
            if ($ne_permuter != false) {
                $next_permuter = \Business\Campaign::load($ne_permuter);
                $campaign->idNextCampaign = $next_permuter->id;
                $campaign->save();
            }
        }

    }

    //permuter deux fids
    public function actionRemplacerPar()
    {

        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $camp = $data->campaign;
        $pre = $data->previous;
        $ne = $data->next;
        $remplacer = $data->remplacerpar;
        $campaign = \Business\Campaign::load($camp);
        $remplacerpar = \Business\Campaign::load($remplacer);
        $campaign->campaignStatus = 1;
        $campaign->save();
        $remplacerpar->isAnaconda = 1;
        $remplacerpar->idLotCampaign = $campaign->idLotCampaign;
        $remplacerpar->save();
        if ($pre != false) {
            $previous = \Business\Campaign::load($pre);
            $previous->idNextCampaign = $remplacerpar->id;
            $previous->save();
        }
        if ($ne != false) {
            $next = \Business\Campaign::load($ne);
            $remplacerpar->idNextCampaign = $next->id;
            $remplacerpar->save();
        }

    }

    //supprimer settings
    public function actionDeleteSetting()
    {

        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $anacondasetting = \Business\AnacondaSettings::load($id);
        $anacondasetting->delete();
        $messages['succes'] = "le setting a ete bien supprime";
        echo json_encode($messages, JSON_UNESCAPED_UNICODE);


    }

    //supprimer settings
    public function actionDeleteLot()
    {

        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $lot = \Business\LotCampaign::load($id);
        $lot->delete();
        $messages['succes'] = "le lot a ete bien supprime";
        echo json_encode($messages, JSON_UNESCAPED_UNICODE);


    }

    //get Settings
    public function actionGetSetting()
    {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $anacondasetting = \Business\AnacondaSettings::load($id);
        echo json_encode($anacondasetting, JSON_UNESCAPED_UNICODE);


    }


    //update settings added by mohamed meski
    public function actionUpdateSetting()
    {

        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $anacondasettings = \Business\AnacondaSettings::load($data->id);
        $anacondasettings->nextStepSum = $data->nextStepSum;
        $anacondasettings->previousStepClicks = $data->previousStepClicks;
        $anacondasettings->durationNext = $data->durationNext;
        $anacondasettings->durationPrevious = $data->durationPrevious;

        if ($anacondasettings->save()) {
            $messages['succes'] = "le setting a ete bien modifie ";
        } else {
            $messages['erreur'] = "impossible de modifier le setting";
        }

        echo json_encode($messages, JSON_UNESCAPED_UNICODE);


    }

    //update settings added by mohamed meski
    public function actionGetAnacondaGraph()
    {

       
        $anacondaGraph = array();
        $lots = null;

        $lots = \Business\LotCampaign::getAllLots();
        foreach ($lots as $lot) {
            $anacondaGraph[] = $lot;

        }
        $anacondacampaings = \Business\Campaign::anacondaCampaigns();
        foreach ($anacondacampaings as $fid) {
            $anacondaGraph[] = $fid;
        }

        echo CJSON::encode($anacondaGraph);
    }

    //reourne les fids qui peut etre suivante d'une fid added by mohamed meski
    public function actionGetNextFids()
    {
        $anacondacampaings = \Business\Campaign::anacondaCampaigns();
        $nextfids = array();
        foreach ($anacondacampaings as $camp) {
            $test = array();
            $test['campaign'] = $camp;
            $test['lot'] = $camp->LotCampaign;
            $nextfids[] = $test;

        }

        echo CJSON::encode($nextfids, false);
    }

    public function actiongetLotWithCampaigns()
    {

        $lots = \Business\LotCampaign::getAllLots();
        $lotswithcampaigns = array();
        foreach ($lots as $lot) {
            $test = array();
            $test['campaigns'] = $lot->Campaigns;
            $test['lot'] = $lot;
            $lotswithcampaigns[] = $test;

        }

        echo CJSON::encode($lotswithcampaigns, false);

    }

    //update settings added by mohamed meski
    public function actionRetirerNext()
    {
        $data = json_decode(file_get_contents("php://input"));
        $campaign = \Business\Campaign::load($data->campaign);
        $campaign->idNextCampaign = null;
        $campaign->save();
    }


    //set next campaign added by mohamed meski
    public function actionSetNextFid()
    {
        $data = json_decode(file_get_contents("php://input"));
        $previous = $data->previous;
        $suivant = $data->suivant;
        $pre = \Business\Campaign::load($previous);
        $pre->idNextCampaign = $suivant;
        $pre->save();
    }

    // ************************** Fin Traitement metier added by mohamed meski ************************** //
    public function actionAnacondaSetting()
    {



        $anaset = null;
        if (!($anaset = \Business\AnacondaSettings::load(20)))
            return false;

        echo "<br/>" . $anaset->groupPrice;
        echo "<br/>" . $anaset->nextStepSum;
        echo "<br/>" . $anaset->previousStepClicks;
        echo "<br/>" . $anaset->durationNext;
        echo "<br/>" . $anaset->durationPrevious;
        echo "<br/>" . $anaset->idFirstCampaign;


        echo "<br/><br/><br/>";

        $analist = null;

        if (!($analist = \Business\AnacondaSettings::loadByGroupPrice(3)))
            return false;

        var_dump($analist[0]->durationNext);
        echo "<table border=2>
		    			<th>Group Price</th>
		    			<th>Next Step Sum</th>
		    			<th>Previous Step Clicks</th>
		    			<th>Duration Next</th>
		    			<th>Duration Previous</th>
		    			<th>id First Campaign</th>";
        for ($i = 0; $i < sizeof($analist); $i++) {

            echo "<tr>";
            echo "<td>" . $analist[$i]->groupPrice . "</td>";
            echo "<td>" . $analist[$i]->nextStepSum . "</td>";
            echo "<td>" . $analist[$i]->previousStepClicks . "</td>";
            echo "<td>" . $analist[$i]->durationNext . "</td>";
            echo "<td>" . $analist[$i]->durationPrevious . "</td>";
            echo "<td>" . $analist[$i]->idFirstCampaign . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }


    public function actionLotCampaign()
    {

        echo "create lotCampaign";
        $LotCamp = new \Business\LotCampaign();
        $LotCamp->numLot = 1;
        $LotCamp->creationDate = date(\Yii::app()->params['dbDateTime']);
        $LotCamp->save();


    }


    //---------------------------------------------------------


    public function actionImport()
    {


        $ab = new AnacondaBehavior();

        $ab->import("../Anaconda Data/test_import.txt");


    }

    //---------------------------------------------------------

    public function actionFileToArray()
    {


        $ab = new AnacondaBehavior();

        $listEmails = $ab->fileToArray("../Anaconda Data/test_import.txt");
        foreach ($listEmails as $email) {
            echo $email;
            echo "<br>";
        }


    }

    //----------------------------------------------------------

    public function actionFirstCampaign()
    {

        $ab = new AnacondaBehavior();

        $firstCampaign = new \Business\Campaign();
        $firstCampaign = $ab->getFirstCampaign();
        echo $firstCampaign['ref'];
    }

    public function actioncreateWebforms()
    {

        $messages = array();
        $data = json_decode(file_get_contents("php://input"));
        $idCampaign = $data->id;
        $ab = new AnacondaBehavior();

        $messages = $ab->createWebFormByCampaign($idCampaign);

        echo CJSON::encode($messages);
    }

    public function actioncreateSegments()
    {
        $data = json_decode(file_get_contents("php://input"));
        $idCampaign = $data->id;
        $ab = new AnacondaBehavior();
        $messages = $ab->CreateSegmentByCampagin($idCampaign, 8);
        echo CJSON::encode($messages);
    }
}

?>