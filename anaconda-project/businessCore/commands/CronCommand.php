<?php

\Yii::import( 'ext.MailHelper' );
\Yii::import( 'ext.AnacondaBehavior' );
\Yii::import( 'ext.CurlHelper' );
\Yii::import( 'ext.Class_API', true );

/**
 * Command line for exectucing CRON action
 */
class CronCommand extends CConsoleCommand
{
	private $anacondaMails = array(
            'yacine.rami@kindyinfomaroc.com',
            'saad.hdidou@kindyinfomaroc.com',
            'soufiane.balkaid@kindyinfomaroc.com',
			'zakaria.chniber@kindyinfomaroc.com'
 
    );
    private $adminMails = array(
        //'julienl@esoter2015.com',
        //'laurent.dere@ld-ci.com',
        'othmane.halhouli.ki@gmail.com'
        //'jalal.bensaad@kindyinfomaroc.com',
        //'fabienc@esoter2015.com'
        //'Chemseddine123.Elgarrai@kindyinfomaroc.com'
    );

    private $adminMailsHB = array(
        //'julienl@esoter2015.com',
        //'laurent.dere@ld-ci.com',
        'othmane.halhouli.ki@gmail.com',
        'jalal.bensaad@kindyinfomaroc.com',
        //'fabienc@esoter2015.com'
        'youssef.harrati.ki@gmail.com'
    );

    private $IspadminMails = array(
        'assia.najm.ki@gmail.com',
        'zakaria.elouafi.ki@gmail.com',
    );

    private $SeuiladminMails = array(
        
        'ismail.elmanti.ki@gmail.com',
    );

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

    /*public function run($args){
        //Code here to select the config file to load
        //$args are any arguments you have passed in the command line
        // Ajouter par Othmane pour recuperer les arguments du cron. Et mis en place de la solution EMV54 des porteurs separes- 24-02-2016
        switch ($args[0]){
            case 'majDB':
                if(isset($args[2]))
                {
                    $_GET['site']=$args[2];
                    $_GET['site']=explode('=',$_GET['site']);
                    $_GET['site']=$_GET['site'][1];
                }
                if(isset($args[1]))
                {
                    $porteur =explode('=',$args[1]);
                    $this->actionMajDB($porteur[1],true);
                }
                else
                {
                    $this->actionMajDB(false,true);
                }
                break;

            case 'majDBV2':
                if(isset($args[2]))
                {
                    $_GET['site']=$args[2];
                    $_GET['site']=explode('=',$_GET['site']);
                    $_GET['site']=$_GET['site'][1];
                }
                if(isset($args[1]))
                {
                    $porteur =explode('=',$args[1]);
                    $this->actionMajDBV2($porteur[1],true);
                }
                else
                {
                    $this->actionMajDBV2(false,true);
                }
                break;

            case 'ExecuteRequestEmvPending':
                if(isset($args[1]))
                {
                    $porteur =explode('=',$args[1]);
                    $this->actionExecuteRequestEmvPending($porteur[1],true);
                }
                else
                {
                    $this->actionExecuteRequestEmvPending(false,true);
                }
                break;

            case 'export':
                    if(isset($args[2]))
                    {
                        $type =explode('=',$args[2]);
                    }
                    if(isset($args[1]))
                    {
                        $porteur =explode('=',$args[1]);
                        $this->actionExport($porteur[1],$type[1]);
                    }
                    else
                    {
                        $this->actionExport(false,$type[1]);
                    }
                break;

            case 'export2':
                $this->actionExport2(false);
                break;

            case 'readCsvQ':
                if(isset($args[2]))
                {
                    $datet =explode('=',$args[2]);
                }
                if(isset($args[1]))
                {
                    $porteur =explode('=',$args[1]);
                    $this->actionReadCsv($porteur[1],$datet[1]);
                }
                else
                {
                    $this->actionReadCsv(false,$datet[1]);
                }
                break;
            case 'exportVG':
                $this->actionExportVG(false);
                break;
            default :
                break;
        }
        // FIN Ajouter par Othmane pour recuperer les arguments du cron. Et mis en place de la solution EMV54 des porteurs separes- 24-02-2016
    }*/
    /**
     * @return string the help information for the shell command
     */
    public function getHelp()
    {
        return <<<EOD
USAGE
  yiic cron [action]

DESCRIPTION

PARAMETERS

EOD;
    }

    /**
     * Recupere les Hard/Soft bounces du ou des porteurs ens se connectant a l'API EMV
     * @param string|bool $porteur Nom du porteur ou False pour tous les porteurs.
     */
    public function actionHBSB( $porteur = false, $sendMail = true )
    {
        if( !empty($porteur) )
        {
            if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
                $msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
            else
            {
                $msg	= NULL;
                $isConf = false;
                $err	= NULL;
                $start	= time();

                // Compte ACQUISITION
                if( isset(\Yii::app()->params['EMV_ACQ']['login']) && !empty(\Yii::app()->params['EMV_ACQ']['login']) )
                {
                    $HB = new \EmvExportTreatment( \Yii::app()->params['EMV_ACQ'] );

                    // Export Hard Bounce :
                    if( $HB->insertHardBounce() == false )
                        $err .= 'probleme durant la recuperation des hard bounces ( ACQ ) : '.$HB->getError().'<br />';

                    // Export Soft Bounce :
                    if( $HB->insertSoftBounce() == false )
                        $err .= 'probleme durant la recuperation des soft bounces ( ACQ ) : '.$HB->getError().'<br />';

                    // Export desabonne
                    if( $HB->insertDesabonne() == false )
                        $err .= 'probleme durant la recuperation des desabonnes ( ACQ ) : '.$HB->getError().'<br />';

                    $isConf = true;

                    if( $err == NULL )
                        $msg .= '<div style="color:green"><u>'.$porteur.' ( ACQ )</u> : Recuperation effectue en '.( time() - $start ).' secondes</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' ( ACQ )</u> : '.$err.'</div>';
                }

                // Compte FIDELISATION
                if( isset(\Yii::app()->params['EMV_FID']['login']) && !empty(\Yii::app()->params['EMV_FID']['login']) )
                {
                    $HB = new \EmvExportTreatment( \Yii::app()->params['EMV_FID'] );

                    // Export Hard Bounce :
                    if( $HB->insertHardBounce() == false )
                        $err .= 'probleme durant la recuperation des hard bounces ( FID ) : '.$HB->getError().'<br />';

                    // Export Soft Bounce :
                    if( $HB->insertSoftBounce() == false )
                        $err .= 'probleme durant la recuperation des soft bounces ( FID ) : '.$HB->getError().'<br />';

                    // Export desabonne
                    if( $HB->insertDesabonne() == false )
                        $err .= 'probleme durant la recuperation des desabonnes ( FID ) : '.$HB->getError().'<br />';

                    $isConf = true;

                    if( $err == NULL )
                        $msg .= '<div style="color:green"><u>'.$porteur.'</u> ( FID ) : Recuperation effectue en '.( time() - $start ).' secondes</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.'</u> ( FID ) : '.$err.'</div>';
                }

                if( !$isConf )
                    $msg = '<div style="color:red"><u>'.$porteur.'</u> : Aucune configuration de l\'API EMV</div>';
            }

            if( $sendMail )
                return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Export HB / SB', $msg );
            else
                return $msg;
        }
        else
        {
            $msg = NULL;
            foreach( $GLOBALS['porteurMap'] as $portRef => $portName )
                $msg .= $this->actionHBSB( $portRef, false );

            return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Export HB / SB', $msg );
        }
    }

    public function actionSharePorteur( $portSrc, $portDst )
    {
        $start	= time();
        $msg	= NULL;

        // Partage :
        $Share	= new \SharePorteur( $portSrc, $portDst );
        if( $Share->transfer() )
            $msg .= '<div style="color:green">Transfert de <u>'.$portSrc.'</u> vers <u>'.$portDst.'</u> effectue avec succes en '.( time() - $start ).' secondes</div>';
        else
            $msg .= '<div style="color:red">Probleme lors du transfert de <u>'.$portSrc.'</u> vers <u>'.$portDst.'</u> : '.implode( '<br />', $Share->getError() ).'</div>';

        return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Share Porteur', $msg );
    }

    public function actionMajDB( $porteur = false, $sendMail = true )
    {
        if( !empty($porteur) )
        {
            if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
                $msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
            else
            {
                $msg	= NULL;
                $start	= time();

                // Compte ACQUISITION
                if( isset(\Yii::app()->params['EMV_ACQ']) && is_array(\Yii::app()->params['EMV_ACQ']) )
                {
                    $Maj = new EmvImportTreatment( \Yii::app()->params['EMV_ACQ'] );
                    if( ($nb = $Maj->updateClientInEMV()) !== false )
                        $msg .= '<div style="color:green"><u>'.$porteur.' ( ACQ )</u> : Mise a jour effectue en '.( time() - $start ).' secondes ( '.$nb.' clients )</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' ( ACQ )</u> : probleme durant la mise a jour des clients : '.$Maj->getError().'</div>';
                }

                // Compte FIDELISATION
                if( isset(\Yii::app()->params['EMV_FID']) && is_array(\Yii::app()->params['EMV_FID']) )
                {
                    $Maj = new EmvImportTreatment( \Yii::app()->params['EMV_FID'] );
                    if( ($nb = $Maj->updateClientInEMV()) !== false )
                        $msg .= '<div style="color:green"><u>'.$porteur.' ( FID )</u> : Mise a jour effectue en '.( time() - $start ).' secondes ( '.$nb.' clients )</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' ( FID )</u> : probleme durant la mise a jour des clients : '.$Maj->getError().'</div>';
                }
            }

            if( $sendMail )
                return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Maj DB', $msg );
            else
                return $msg;
        }
        else
        {
            $msg = NULL;
            foreach( $GLOBALS['porteurMap'] as $portRef => $portName )
            {
                /*if( $portRef == 'fr_rinalda' )
                    continue;*/

                $msg .= $this->actionMajDB( $portRef, false );
            }

            return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Maj DB', $msg );
        }
    }
    public function actionMajDBSepares( $porteur = false, $sendMail = true, $site = false )
    {
        $_GET['site']=$site;
        if( !empty($porteur) )
        {
            if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
                $msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
            else
            {
                $msg	= NULL;
                $start	= time();

                // Compte ACQUISITION
                if( isset(\Yii::app()->params['EMV_ACQ']) && is_array(\Yii::app()->params['EMV_ACQ']) )
                {
                    $Maj = new EmvImportTreatment( \Yii::app()->params['EMV_ACQ'] );
                    if( ($nb = $Maj->updateClientInEMV()) !== false )
                        $msg .= '<div style="color:green"><u>'.$porteur.' - '.$site.' ( ACQ )</u> : Mise a jour effectue en '.( time() - $start ).' secondes ( '.$nb.' clients )</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' - '.$site.' ( ACQ )</u> : probleme durant la mise a jour des clients : '.$Maj->getError().'</div>';
                }

                // Compte FIDELISATION
                if( isset(\Yii::app()->params['EMV_FID']) && is_array(\Yii::app()->params['EMV_FID']) )
                {
                    $Maj = new EmvImportTreatment( \Yii::app()->params['EMV_FID'] );
                    if( ($nb = $Maj->updateClientInEMV()) !== false )
                        $msg .= '<div style="color:green"><u>'.$porteur.' - '.$site.' ( FID )</u> : Mise a jour effectue en '.( time() - $start ).' secondes ( '.$nb.' clients )</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' - '.$site.' ( FID )</u> : probleme durant la mise a jour des clients : '.$Maj->getError().'</div>';
                }
            }

            if( $sendMail )
                return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Maj DB', $msg );
            else
                return $msg;
        }
        else
        {
            $msg = NULL;
            foreach( $GLOBALS['porteurMap'] as $portRef => $portName )
            {
                /*if( $portRef == 'fr_rinalda' )
                 continue;*/

                $msg .= $this->actionMajDB( $portRef, false );
            }

            return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Maj DB', $msg );
        }
    }

    public function actionMajDBV2( $porteur = false, $sendMail = true )
    {
        if( !empty($porteur) )
        {
            if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
                $msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
            else
            {
                $msg	= NULL;
                $start	= time();

                // Compte ACQUISITION
                if( isset(\Yii::app()->params['EMV_ACQ']) && is_array(\Yii::app()->params['EMV_ACQ']) )
                {
                    $Maj = new EmvImportTreatment( \Yii::app()->params['EMV_ACQ'] );
                    if( ($nb = $Maj->updateClientInEMVV2()) !== false )
                        $msg .= '<div style="color:green"><u>'.$porteur.' ( ACQ )</u> : Mise a jour effectue en '.( time() - $start ).' secondes ( '.$nb.' clients )</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' ( ACQ )</u> : probleme durant la mise a jour des clients : '.$Maj->getError().'</div>';
                }

                // Compte FIDELISATION
                if( isset(\Yii::app()->params['EMV_FID']) && is_array(\Yii::app()->params['EMV_FID']) )
                {
                    $Maj = new EmvImportTreatment( \Yii::app()->params['EMV_FID'] );
                    if( ($nb = $Maj->updateClientInEMVV2()) !== false )
                        $msg .= '<div style="color:green"><u>'.$porteur.' ( FID )</u> : Mise a jour effectue en '.( time() - $start ).' secondes ( '.$nb.' clients )</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' ( FID )</u> : probleme durant la mise a jour des clients : '.$Maj->getError().'</div>';
                }
            }

            if( $sendMail )
                return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Maj DB V2', $msg );
            else
                return $msg;
        }
        else
        {
            $msg = NULL;
            foreach( $GLOBALS['porteurMap'] as $portRef => $portName )
            {
                /*if( $portRef == 'fr_rinalda' )
                    continue;*/

                $msg .= $this->actionMajDBV2( $portRef, false );
            }

            return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Maj DB', $msg );
        }
    }
    public function actionMajDBV2Separes( $porteur = false, $sendMail = true, $site =false )
    {
        $_GET['site']=$site;
        if( !empty($porteur) )
        {
            if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
                $msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
            else
            {
                $msg	= NULL;
                $start	= time();

                // Compte ACQUISITION
                if( isset(\Yii::app()->params['EMV_ACQ']) && is_array(\Yii::app()->params['EMV_ACQ']) )
                {
                    $Maj = new EmvImportTreatment( \Yii::app()->params['EMV_ACQ'] );
                    if( ($nb = $Maj->updateClientInEMVV2()) !== false )
                        $msg .= '<div style="color:green"><u>'.$porteur.' - '.$site.' ( ACQ )</u> : Mise a jour effectue en '.( time() - $start ).' secondes ( '.$nb.' clients )</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' - '.$site.' ( ACQ )</u> : probleme durant la mise a jour des clients : '.$Maj->getError().'</div>';
                }

                // Compte FIDELISATION
                if( isset(\Yii::app()->params['EMV_FID']) && is_array(\Yii::app()->params['EMV_FID']) )
                {
                    $Maj = new EmvImportTreatment( \Yii::app()->params['EMV_FID'] );
                    if( ($nb = $Maj->updateClientInEMVV2()) !== false )
                        $msg .= '<div style="color:green"><u>'.$porteur.' - '.$site.' ( FID )</u> : Mise a jour effectue en '.( time() - $start ).' secondes ( '.$nb.' clients )</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' - '.$site.' ( FID )</u> : probleme durant la mise a jour des clients : '.$Maj->getError().'</div>';
                }
            }

            if( $sendMail )
                return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Maj DB V2', $msg );
            else
                return $msg;
        }
        else
        {
            $msg = NULL;
            foreach( $GLOBALS['porteurMap'] as $portRef => $portName )
            {
                /*if( $portRef == 'fr_rinalda' )
                 continue;*/

                $msg .= $this->actionMajDBV2( $portRef, false );
            }

            return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Maj DB', $msg );
        }
    }
    public function actionMajProspect( $porteur = false, $sendMail = true )
    {
        if( !empty($porteur) )
        {
            if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
                $msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
            else
            {
                $msg	= NULL;
                $start	= time();

                // Compte ACQUISITION
                if( isset(\Yii::app()->params['EMV_ACQ']) && is_array(\Yii::app()->params['EMV_ACQ']) )
                {
                    $Maj = new \EmvExportTreatment( \Yii::app()->params['EMV_ACQ'] );
                    if( ($nb = $Maj->majProspectInDB()) !== false )
                        $msg .= '<div style="color:green"><u>'.$porteur.' ( ACQ )</u> : Mise a jour des prospects effectue en '.( time() - $start ).' secondes ( '.$nb.' clients )</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' ( ACQ )</u> : probleme durant la mise a jour des prospects : '.$Maj->getError().'</div>';
                }

                // Compte FIDELISATION
                if( isset(\Yii::app()->params['EMV_FID']) && is_array(\Yii::app()->params['EMV_FID']) )
                {
                    $Maj = new \EmvExportTreatment( \Yii::app()->params['EMV_FID'] );
                    if( ($nb = $Maj->majProspectInDB()) !== false )
                        $msg .= '<div style="color:green"><u>'.$porteur.' ( FID )</u> : Mise a jour des prospects effectue en '.( time() - $start ).' secondes ( '.$nb.' clients )</div>';
                    else
                        $msg .= '<div style="color:red"><u>'.$porteur.' ( FID )</u> : probleme durant la mise a jour des prospects : '.$Maj->getError().'</div>';
                }
            }

            if( $sendMail )
                return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Maj Prospect', $msg );
            else
                return $msg;
        }
        else
        {
            $msg = NULL;
            foreach( $GLOBALS['porteurMap'] as $portRef => $portName )
            {
                $msg .= $this->actionMajProspect( $portRef, false );
            }

            return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Maj Prospect', $msg );
        }
    }

    public function actionCurlSendRequest( $url )
    {
        \Yii::import( 'ext.CurlHelper' );

        $Curl = new \CurlHelper();
        $Curl->setTimeout( CURL_TIMEOUT );
        return $Curl->sendRequest( urldecode($url) );
    }

    public function actionExecuteRequestEmvPending( $porteur = false, $sendMail = true )
    {

        if( !empty($porteur) )
        {
            $msg = NULL;

            if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
                $msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
            else {
                $Req = new \Business\RequestRouterEMV();
                $Req->executed = \Business\RequestRouterEMV::PENDING;
                $Res = $Req->search()->getData();

                if (is_array($Res) && count($Res) > 0) {
                    for ($i = 0; $i < count($Res); $i++) {
                        $Invoice = \Business\Invoice::load($Res[$i]->idInvoice);
                        if ($Invoice->invoiceStatus != \Business\Invoice::INVOICE_PAYED) {
                            if ($Invoice->invoiceStatus == \Business\Invoice::INVOICE_IN_PROGRESS) {
                                if (($response = $Res[$i]->sendRequest()) !== \WebForm::RES_OK) {
                                    if ($porteur == 'de_althea') {
                                        $mailUser = $Invoice->emailUser;
                                        $idU = \Business\User::loadByEmail($mailUser)->id;
                                        $camp = $Invoice->campaign;
                                        $message_user = new \Business\Message_User();
                                        $message_user->date_shoot = date("Y-n-j H:i:s");
                                        $message_user->status_message = '0';
                                        $message_user->pricingGrid = $Invoice->refPricingGrid;
                                        $message_user->user_id = $idU;
                                        $message_user->aparURL = "/" . $porteur . "/" . $camp . "/lmap";
                                        $message_user->save();
                                        $msg .= $Invoice->emailUser . "--->" . $idU . "--->" . $camp;
                                    }
                                    $msg .= '<div style="color:red"><h1>' . $porteur . ', ID ' . $Res[$i]->id . '</h1><u>Url :</u> ' . $Res[$i]->url . '<br /><u>Response :</u> ' . $response . '<br /><u>WebForm :</u> ' . $Res[$i]->type . '</div>';
                                }
                            }
                        }
                    }
                }
            }
            if( $sendMail && $msg != NULL )
                return \MailHelper::sendMail( $this->adminMails, 'Cron', "Execute pending request EMV", $msg );
            else
                return $msg;
        }
        else
        {
            $msg = NULL;
            foreach( $GLOBALS['porteurMap'] as $portRef => $portName )
            {
                $msg .= $this->actionExecuteRequestEmvPending( $portRef, false );
            }

            if( $msg != NULL )
                return \MailHelper::sendMail( $this->adminMails, 'Cron', 'Execute pending request EMV', $msg );
            else
                return true;
        }
    }

    /**
    Youssef HARRATI
    le: 27/08/2015
     */
    public function actionExport($port = false,$type = false) {
        $p = $port?$port:Yii::app()->request->getParam('port');
        $t = $type?$type:Yii::app()->request->getParam('type');
        set_time_limit(1800);
        $this->exportDataMarketing($p, $t);
    }

    public function actionExport2($port = false) {
        set_time_limit(1800);
        $start = time();
        $p = $port?$port:Yii::app()->request->getParam('port');
        $msg = "<div><h2><center><u>Importation de toutes les campagnes</u></center></h2></div>";
        if(!$p && $p != null)
            $msg .= $this->exportDataMarketingNew($p);
        else{
            foreach($GLOBALS['porteurMap'] AS $porteur=>$folder){
                try{
                    $msg .= $this->exportDataMarketingNew($porteur);
                }catch(CDbException  $e){
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : '.$e->errorInfo.'</div>';
                }
            }
        }
        $msg .= '<br /><br /><div><b>Total Recuperation effectue en ' . ( time() - $start ) . ' secondes</b></div><br /><br />';
        echo $msg;
        \MailHelper::sendMail($this->adminMailsHB, 'Cron', 'Export MKT ( tous les porteurs )', $msg);
    }

    /**
    Youssef HARRATI
    le: 01/09/2015
     */
    public function actionExportVG($port = false) {
        set_time_limit(1800);
        $msg = "";
        $start = time();
        $p = $port?$port:Yii::app()->request->getParam('port');
        if(!$p && $p != null)
            $msg .= $this->exportDataMarketingVG($p);
        else{
            foreach($GLOBALS['porteurMap'] AS $porteur=>$folder){
                try{
                    $msg .= $this->exportDataMarketingVG($porteur);
                }catch(CDbException  $e){
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : '.$e->errorInfo.'</div>';
                }
            }
        }
        $msg .= '<br /><br /><div><b>Total Recuperation effectue en ' . ( time() - $start ) . ' secondes</b></div><br /><br />';
        echo $msg;
        \MailHelper::sendMail($this->adminMailsHB, 'Cron', 'Export MKT hard bounces VG ( tous les porteurs )', $msg);
    }

    public function actionReadCsv($port = false,$date = false) {
        \Yii::import('ext.CsvImporter');
        $p = $port?$port:Yii::app()->request->getParam('port');
        $d = $date?$date:Yii::app()->request->getParam('date');

        if (!isset($GLOBALS['porteurMap'][$p]) || !\Controller::loadConfigForPorteur($p))
            die('<div style="color:red"><u>' . $p . '</u> : Le porteur est introuvable</div>');
        else {
            if (!is_dir(TMP_DIR . '/MktData'))
                die('<div style="color:red"><u>' . $p . '</u> : pas de data marketing</div>');
            $folder = TMP_DIR . '/MktData/' . $d;
            if (!is_dir($folder))
                die('<div style="color:red"><u>' . $p . '</u> : pas de data marketing pour la date: ' . $d . '</div>');
            $folder .= '/' . $p;
            if (!is_dir($folder))
                die('<div style="color:red"><u>' . $p . '</u> : pas de data marketing pour ce porteur: ' . $p . '</div>');
            $folder .= '/';

            $global_data = [];
            $stats = ['UNJOIN', 'OPENED', 'CLICK_DETAIL', 'HBOUNCED', 'SBOUNCED', 'COMPLAINTS', 'DELIVERED', 'QUARANTINED'];
            //$stats = ['QUARANTINED'];

            foreach ($stats as $stat) {

                $base_folder = $folder . $stat . '/';
                $file = $base_folder . 'details.csv';
                if (!file_exists($file))
                    continue;
                $importer = new CsvImporter($file, true, ';');
                $data = $importer->get();
                foreach ($data as $line) {
                    $FID = substr($line['NAME'], 0, 3);
                    $ct = 0;
                    if (substr($line['NAME'], 0, 6) == $FID . ' CT') {
                        $ct = 1;
                    } elseif (substr($line['NAME'], 0, 6) == $FID . ' ct') {
                        $ct = 1;
                    }
                    if (array_key_exists($line['CID'], $global_data)) {
                        if (isset($global_data[$line['CID']][$stat])) {
                            $global_data[$line['CID']][$stat] += $line['COUNT'];
                        } else {
                            $global_data[$line['CID']][$stat] = $line['COUNT'];
                        }
                    } else {
                        $global_data[$line['CID']] = ['CID' => $line['CID'], 'TID' => $line['ID'], 'NAME' => $line['NAME'], 'FID' => strtolower($FID), 'CT' => $ct, 'SEND_DATE' => $line['SEND_DATE'], 'DATE' => $line['DATE'], $stat => $line['COUNT']];
                    }
                }
            }
            //print_r($global_data);die;
            $columns = ['opened', 'unjoin', 'sbounced', 'delivered', 'complaints', 'click_detail', 'hbounced', 'quarantined'];
            $i = 0;
            $j = 0;
            foreach ($global_data as $value) {
                $MktDataEMV = new MktDataEMV();
                if ($MktDataEMV->findByAttributes(array('campagn_id' => $value['CID'], 'name' => $value['NAME'], 'send_date' => $value['SEND_DATE']))) {
                    $MktDataEMV = $MktDataEMV->findByAttributes(array('campagn_id' => $value['CID'], 'name' => $value['NAME'], 'send_date' => $value['SEND_DATE']));
                    echo $value['NAME'] . ": Exists<br />";
                    $j++;
                }
                $MktDataEMV->campagn_id = $value['CID'];
                $MktDataEMV->trigger_id = $value['TID'];
                $MktDataEMV->name = $value['NAME'];
                $MktDataEMV->fid = $value['FID'];
                $MktDataEMV->send_date = $value['SEND_DATE'];
                $MktDataEMV->import_date = $value['DATE'];
                $MktDataEMV->isCT = $value['CT'];
                foreach ($columns as $column) {
                    if (isset($value[strtoupper($column)])) {
                        $MktDataEMV->$column = $value[strtoupper($column)];
                    } else {
                        $MktDataEMV->$column = null;
                    }
                }
                if ($MktDataEMV->save()) {
                    echo $value['NAME'] . ": OK<br />";
                    $i++;
                } else {
                    echo ("KO");
                }
            }
            echo '<div style="color: green;">' . ($i - $j) . ' rows inserted</div>';
            echo '<div style="color: green;">' . $j . ' rows updated </div>';
        }
    }

    public function actionReadCsvQ($port = false,$date = false) {
        \Yii::import('ext.CsvImporter');
        $p = $port?$port:Yii::app()->request->getParam('port');
        $d = $date?$date:Yii::app()->request->getParam('date');

        if (!isset($GLOBALS['porteurMap'][$p]) || !\Controller::loadConfigForPorteur($p))
            die('<div style="color:red"><u>' . $p . '</u> : Le porteur est introuvable</div>');
        else {
            if (!is_dir(TMP_DIR . '/MktData'))
                die('<div style="color:red"><u>' . $p . '</u> : pas de data marketing</div>');
            $folder = TMP_DIR . '/MktData/' . $d;
            if (!is_dir($folder))
                die('<div style="color:red"><u>' . $p . '</u> : pas de data marketing pour la date: ' . $d . '</div>');
            $folder .= '/' . $p;
            if (!is_dir($folder))
                die('<div style="color:red"><u>' . $p . '</u> : pas de data marketing pour ce porteur: ' . $p . '</div>');
            $folder .= '/';

            $global_data = [];
            // $stats = ['UNJOIN', 'OPENED', 'CLICK_DETAIL', 'HBOUNCED', 'SBOUNCED', 'COMPLAINTS', 'DELIVERED', 'QUARANTINED'];
            $stats = ['QUARANTINED'];

            foreach ($stats as $stat) {

                $base_folder = $folder . $stat . '/';
                $file = $base_folder . 'details.csv';
                if (!file_exists($file))
                    continue;
                $importer = new CsvImporter($file, true, ';');
                $data = $importer->get();
                foreach ($data as $line) {
                    $FID = substr($line['NAME'], 0, 3);
                    $ct = 0;
                    if (substr($line['NAME'], 0, 6) == $FID . ' CT') {
                        $ct = 1;
                    } elseif (substr($line['NAME'], 0, 6) == $FID . ' ct') {
                        $ct = 1;
                    }
                    if (array_key_exists($line['CID'], $global_data)) {
                        if (isset($global_data[$line['CID']][$stat])) {
                            $global_data[$line['CID']][$stat] += $line['COUNT'];
                        } else {
                            $global_data[$line['CID']][$stat] = $line['COUNT'];
                        }
                    } else {
                        $global_data[$line['CID']] = ['CID' => $line['CID'], 'TID' => $line['ID'], 'NAME' => $line['NAME'], 'FID' => strtolower($FID), 'CT' => $ct, 'SEND_DATE' => $line['SEND_DATE'], 'DATE' => $line['DATE'], $stat => $line['COUNT']];
                    }
                }
            }
            //print_r($global_data);die;
            // $columns = ['opened', 'unjoin', 'sbounced', 'delivered', 'complaints', 'click_detail', 'hbounced', 'quarantined'];
            $columns = ['quarantined'];
            $i = 0;
            $j = 0;
            foreach ($global_data as $value) {
                $MktDataEMV = new MktDataEMV2();
                if ($MktDataEMV->findByAttributes(array('campagn_id' => $value['CID'], 'name' => $value['NAME'], 'send_date' => $value['SEND_DATE']))) {
                    $MktDataEMV = $MktDataEMV->findByAttributes(array('campagn_id' => $value['CID'], 'name' => $value['NAME'], 'send_date' => $value['SEND_DATE']));
                    echo $value['NAME'] . ": Exists<br />";
                    $j++;
                }
                $MktDataEMV->campagn_id = $value['CID'];
                $MktDataEMV->trigger_id = $value['TID'];
                $MktDataEMV->name = $value['NAME'];
                $MktDataEMV->fid = $value['FID'];
                $MktDataEMV->send_date = $value['SEND_DATE'];
                $MktDataEMV->import_date = $value['DATE'];
                $MktDataEMV->isCT = $value['CT'];
                foreach ($columns as $column) {
                    if (isset($value[strtoupper($column)])) {
                        $MktDataEMV->$column = $value[strtoupper($column)];
                    } else {
                        $MktDataEMV->$column = null;
                    }
                }
                if ($MktDataEMV->save()) {
                    echo $value['NAME'] . ": OK<br />";
                    $i++;
                } else {
                    echo ("KO");
                }
            }
            echo '<div style="color: green;">' . ($i - $j) . ' rows inserted</div>';
            echo '<div style="color: green;">' . $j . ' rows updated </div>';
        }
    }

    private function exportDataMarketingVG($porteur) {
        if (!is_dir(TMP_DIR . '/MktData'))
            mkdir(TMP_DIR . '/MktData', 0777);
        $folder = TMP_DIR . '/MktData/' . date("Y-m-d");
        if (!is_dir($folder))
            mkdir($folder, 0777);
        $folder .= '/';
        $msg = "";
        $this->delete_old_directories(TMP_DIR . '/MktData/');
        if (!empty($porteur)) {
            if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur))
                $msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable</div>';
            else {
                $msg = NULL;
                $err = NULL;
                $output = '';
                $start = time();

                // Compte ACQUISITION
                if (isset(\Yii::app()->params['MKT_EMV_ACQ']['login']) && !empty(\Yii::app()->params['MKT_EMV_ACQ']['login'])) {
                    $HB = new \EmvExportTreatment(\Yii::app()->params['MKT_EMV_ACQ']); //CMD_EMV_ACQ

                    $output = $HB->exportDataMarketingHB_VG($porteur, $folder, false);
                    if ($output == false)
                        $err .= 'probleme durant la recuperation des hard bounces ( ACQ ) : ' . $HB->getError() . '<br />';

                    if ($err == NULL)
                        $msg .= '<div><b><u style="color:green">' . $porteur . ' ( ACQ )</u> : Recuperation des stats pour le Compte ACQUISITION</b> effectue en ' . ( time() - $start ) . ' secondes</div>';
                    else
                        $msg .= '<div style="color:red"><u>' . $porteur . ' ( ACQ )</u> : ' . $err . '</div>';
                } else
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';

                if (isset(\Yii::app()->params['MKT_EMV_FID']['login']) && !empty(\Yii::app()->params['MKT_EMV_FID']['login'])) {
                    $HB = new \EmvExportTreatment(\Yii::app()->params['MKT_EMV_FID']); //CMD_EMV_ACQ

                    $output = $HB->exportDataMarketingHB_VG($porteur, $folder, true);
                    if ($output == false)
                        $err .= 'probleme durant la recuperation des hard bounces ( ACQ ) : ' . $HB->getError() . '<br />';

                    if ($err == NULL)
                        $msg .= '<div><b><u style="color:green">' . $porteur . ' ( FID )</u> : Recuperation des stats pour le Compte FID</b> effectue en ' . ( time() - $start ) . ' secondes</div><br /><br />';
                    else
                        $msg .= '<div style="color:red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div>';
                } else
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV pour le Compte FID</div><br /><br />';
            }
        }
        return $msg;

    }

    private function exportDataMarketing($porteur, $type) {
        if (!is_dir(TMP_DIR . '/MktData'))
            mkdir(TMP_DIR . '/MktData', 0777);
        $folder = TMP_DIR . '/MktData/' . date("Y-m-d");
        if (!is_dir($folder))
            mkdir($folder, 0777);
        $folder .= '/';
        $this->delete_old_directories(TMP_DIR . '/MktData/');
        $msg = "";
        if (!empty($porteur)) {
            if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur))
                $msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable</div>';
            else {
                $msg = NULL;
                $err = NULL;
                $output = '';
                $start = time();

                // Compte ACQUISITION
                if (isset(\Yii::app()->params['MKT_EMV_ACQ']['login']) && !empty(\Yii::app()->params['MKT_EMV_ACQ']['login'])) {
                    $HB = new \EmvExportTreatment(\Yii::app()->params['MKT_EMV_ACQ']); //CMD_EMV_ACQ
                    switch ($type) {
                        case 'OPENED':
                            $output = $HB->exportDataMarketingOpened($porteur, $folder);
                            if ($output == false)
                                $err .= 'probleme durant la recuperation des OPENED ( ACQ ) : ' . $HB->getError() . '<br />';
                            break;
                        case 'UNJOIN':
                            $output = $HB->exportDataMarketingUnjoin($porteur, $folder);
                            if ($output == false)
                                $err .= 'probleme durant la recuperation des UNJOIN ( ACQ ) : ' . $HB->getError() . '<br />';
                            break;
                        case 'CLICK_DETAIL':
                            $output = $HB->exportDataMarketingClick($porteur, $folder);
                            if ($output == false)
                                $err .= 'probleme durant la recuperation des CLICK_DETAIL ( ACQ ) : ' . $HB->getError() . '<br />';
                            break;
                        case 'COMPLAINTS':
                            $output = $HB->exportDataMarketingComplaints($porteur, $folder);
                            if ($output == false)
                                $err .= 'probleme durant la recuperation des COMPLAINTS ( ACQ ) : ' . $HB->getError() . '<br />';
                            break;
                        case 'SBOUNCED':
                            $output = $HB->exportDataMarketingSB($porteur, $folder);
                            if ($output == false)
                                $err .= 'probleme durant la recuperation des soft bounces ( ACQ ) : ' . $HB->getError() . '<br />';
                            break;
                        case 'HBOUNCED':
                            $output = $HB->exportDataMarketingHB($porteur, $folder);
                            if ($output == false)
                                $err .= 'probleme durant la recuperation des hard bounces ( ACQ ) : ' . $HB->getError() . '<br />';
                            break;
                        case 'DELIVERED':
                            $output = $HB->exportDataMarketingDelivered($porteur, $folder);
                            if ($output == false)
                                $err .= 'probleme durant la recuperation des DELIVERED ( ACQ ) : ' . $HB->getError() . '<br />';
                            break;
                        case 'QUARANTINED':
                            try {
                                $conf = \Yii::app()->params['CMD_EMV_ACQ'];
                                $soap = new SoapClient($conf['wdsl']);
                                $Res = $soap->openApiConnection(array( 'login' => $conf['login'], 'pwd' => $conf['pwd'], 'key' => $conf['key'] ));
                            } catch (Exception $E) {
                                $err .= 'probleme durant la recuperation des segments ( ACQ ) : ' . $E->detail . '<br />';
                                break;
                            }
                            $token = $Res->return;
                            $output = $HB->exportDataMarketingQuarantined($porteur, $folder, array($soap, $token));
                            if ($output == false)
                                $err .= 'probleme durant la recuperation des QUARANTINED ( ACQ ) : ' . $HB->getError() . '<br />';
                            break;
                    }
                    if ($err == NULL)
                        $msg .= $output . '<br /><div style="color:green"><u>' . $porteur . ' ( ACQ )</u> : Recuperation effectue en ' . ( time() - $start ) . ' secondes</div>';
                    else
                        $msg .= '<div style="color:red"><u>' . $porteur . ' ( ACQ )</u> : ' . $err . '</div>';
                } else
                    $msg = '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV</div>';
            }
        }
        echo $msg;
        return \MailHelper::sendMail($this->adminMailsHB, 'Cron', 'Export MKT ' . $type . ' (' . $porteur . ')', $msg);
    }

    private function exportDataMarketingNew($porteur) {
        if (!empty($porteur)) {
            if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur))
                $msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable</div>';
            else {
                $msg = NULL;
                $err = NULL;
                $acq = false;
                $fid = false;
                $output = [];
                $output_fid = [];
                $start = time();
                $site = substr($porteur,0,2);

                // Compte ACQUISITION
                if (isset(\Yii::app()->params['MKT_EMV_ACQ']['wdsl_rpt']) && !empty(\Yii::app()->params['MKT_EMV_ACQ']['wdsl_rpt'])) {
                    $HB = new \EmvExportTreatment(\Yii::app()->params['MKT_EMV_ACQ']); //CMD_EMV_ACQ
                    try {
                        $conf = \Yii::app()->params['MKT_EMV_ACQ'];
                        $soap = new SoapClient($conf['wdsl_rpt']);
                        $Res = $soap->openApiConnection(array(
                            'login' => $conf['login'],
                            'pwd' => $conf['pwd'],
                            'key' => $conf['key']
                        ));
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>' . $E->detail->ConnectionServiceException->status . ' ('. $E->detail->ConnectionServiceException->description .')</b><br />';
                        $msg = '<div style="color: red"><u>' . $porteur . ' ( ACQ )</u> : ' . $err . '</div><br /><br />';
                        $acq = true;
                    }
                    if(!$acq){
                        $token = $Res->return;
                        $output = $HB->exportDataMarketingNew($porteur, array($soap, $token));
                        if($output[1]=='ko'){
                            $err .= 'Erreur lors de l\'importation ';
                            $msg = '<div style="color: red"><u>' . $porteur . ' ( ACQ )</u> : ' . $err . '</div><br /><br />';
                            print_r($output[2]);
                            $acq = true;
                        }

                        $msg .= '<div><b><u style="color:green">' . $porteur . ' ( ACQ )</u> : Recuperation des stats pour le Compte ACQUISITION</b></div>';
                        $msg .= '<br />';
                        $msg .= $output[2];
                    }
                } else{
                    $msg = '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
                    $acq = true;
                }

                if (isset(\Yii::app()->params['MKT_EMV_FID']['wdsl_rpt']) && !empty(\Yii::app()->params['MKT_EMV_FID']['wdsl_rpt'])) {
                    $HB = new \EmvExportTreatment(\Yii::app()->params['MKT_EMV_FID']); //CMD_EMV_ACQ
                    try {
                        $conf = \Yii::app()->params['MKT_EMV_FID'];
                        $soap = new SoapClient($conf['wdsl_rpt']);
                        $Res = $soap->openApiConnection(array(
                            'login' => $conf['login'],
                            'pwd' => $conf['pwd'],
                            'key' => $conf['key']
                        ));
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>' . $E->detail->ConnectionServiceException->status . ' ('. $E->detail->ConnectionServiceException->description .')</b><br />';
                        $msg = '<div style="color: red"><u>' . $porteur . ' ( ACQ )</u> : ' . $err . '</div><br /><br />';
                        $fid = true;
                    }
                    if(!$fid){
                        $token = $Res->return;
                        $output_fid = $HB->exportDataMarketingNew($porteur, array($soap, $token));
                        if($output_fid[1]=='ko'){
                            $err .= 'Erreur lors de l\'importation ';
                            $msg = '<div style="color: red"><u>' . $porteur . ' ( ACQ )</u> : ' . $err . '</div><br /><br />';
                            print_r($output_fid[2]);
                            return $msg;
                        }
                        $msg .= '<div><b><u style="color:green">' . $porteur . ' ( ACQ )</u> : Recuperation des stats pour le Compte FID</b></div>';
                        $msg .= '<br />';
                        $msg .= $output_fid[2];
                    }
                }
                else{
                    $msg = '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV pour le Compte FID</div>';
                    $fid = true;
                }
                $items = [];
                if(!empty($output) && !empty($output_fid)){
                    $items = array_merge($output[0],$output_fid[0]);
                }else{
                    if(!empty($output)){
                        $items = $output[0];
                    }
                    else if(!empty($output_fid)){
                        $items = $output_fid[0];
                    }
                }



                if(!$acq || !$fid){
                    $i = 0;
                    $j = 0;

                    foreach ($items as $value) {
                        $MktDataEMV = new MktDataEMV2();
                        if ($MktDataEMV->findByAttributes(array('campagn_id' => $value['campagn_id'], 'name' => $value['name'], 'send_date' => $value['send_date']))) {
                            $MktDataEMV = $MktDataEMV->findByAttributes(array('campagn_id' => $value['campagn_id'], 'name' => $value['name'], 'send_date' => $value['send_date']));
                            $j++;
                        }
                        $MktDataEMV->campagn_id = $value['campagn_id'];
                        $MktDataEMV->trigger_id = $value['trigger_id'];
                        $MktDataEMV->name = $value['name'];
                        $MktDataEMV->fid = $value['fid'];
                        $MktDataEMV->send_date = $value['send_date'];
                        $MktDataEMV->import_date = $value['import_date'];
                        $MktDataEMV->isCT = $value['isCT'];
                        $MktDataEMV->opened = $value['opened'];
                        $MktDataEMV->unjoin = $value['unjoin'];
                        $MktDataEMV->sbounced = $value['sbounced'];
                        $MktDataEMV->delivered = $value['delivered'];
                        $MktDataEMV->complaints = $value['complaints'];
                        $MktDataEMV->click_detail = $value['click_detail'];
                        $MktDataEMV->hbounced = $value['hbounced'];
                        $MktDataEMV->quarantined = $value['quarantined'];
                        $MktDataEMV->site = $site;

                        if ($MktDataEMV->save()) {
                            $i++;
                        }else{
                            die("error");
                        }
                    }
                    $msg .= '<div><u style="color: green;">inserted rows:</u> ' . ($i - $j) . '</div>';
                    $msg .= '<div><u style="color: green;">updated rows:</u> ' . $j . '</div>';

                    $msg .= '<div><b><u style="color:green">' . $porteur . ' ( ACQ )</u> : Recuperation effectue en ' . ( time() - $start ) . ' secondes</b></div><br /><br />';
                }
            }
        }
        return $msg;
    }

    private function delete_old_directories($dir) {
        $old = date('Y-m-d', strtotime(' - 7 days'));
        $old = new DateTime($old);
        $old = $old->format('Ymd');
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (is_dir($dir . $item)) {
                $now = new DateTime($item);
                $now = $now->format('Ymd');
                if ($now <= $old) {
                    if ($this->deleteDirectory($dir . $item))
                        echo ("Folder: " . $dir . $item . " Deleted successfully<br />");
                    else
                        echo ("Folder: " . $dir . $item . " Couldn't be deleted<br />");
                }
            }
        }
    }

    private function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public function actionIspCron()
    {




        \Yii::import( 'ext.IspGlobalMAP' );
        $IspGlobalMAP=new \IspGlobalMAP();

        $ispCrudBD=new \IspCrudBD();
        $msg="";

        $start=time();

        foreach($GLOBALS['porteurMap'] AS $key=>$value){


            $lng=explode('_', $key);
            $site=$lng[0];
            $_GET['site']=$site;
            \Controller::loadConfigForPorteur($key);


            $msg.=$key.": ".$ispCrudBD->conloaddb1($key,$value);
            $msg .=$key.": ".$ispCrudBD->updateidmessage($key,$value);
            $msg .=$key.": ".$ispCrudBD->updatenamemessage($key,$value);
            $msg .=$key.": ".$ispCrudBD->updatesite($value);

            echo '</br>';

        }
        $msg .= '<br /><br /><div><b>Total Recuperation effectue en ' . ( time() - $start ) . ' secondes</b></div><br /><br />';
        echo $msg;
        \MailHelper::sendMail($this->IspadminMails, 'Cron', 'Export MKT compaign ( tous les porteurs )', $msg);

    }
    public function actionTest()
    {

        $Segment=new \Segment();

        \Yii::import( 'ext.HBporteur' );
        $HBporteur=new \HBporteur();
        foreach($GLOBALS['porteurMap'] as $key=>$value){
            $lng=explode('_', $key);
            $site=$lng[0];
            $_GET['site']=$site;
            \Controller::loadConfigForPorteur($key);

            $token=$Segment->gettoken($value,'acq');
            echo '<br>';
            echo ( '<div><b><u style="color:green">'.$key.' : '.\Yii::app()->params['CMD_EMV_ACQ']['login'].'</u></b></div>');

            \Yii::import( 'ext.HBswitch' );
            $HBswitch=new \HBswitch();
            $id = $HBswitch->HBswi($key);

            /*******----------------------------------------Mise à jour du segment-------------------------------------------------------------------******/
            $columnName='EMVADMIN5';
            $firstAbsoluteDate=date("Y-m-d", strtotime('-1 day'));
            $operator='ABSOLUTE_ON';
            $Critersegment_array=$Segment->updatesegment($token, $id, $columnName, $firstAbsoluteDate, $operator);
            $SegmentCriter=$Critersegment_array[0];

            /*******----------------------------------------Export des @ HB à partir du segment-------------------------------------------------------------------******/

            $mailinglistId=$id;
            $operationType='QUARANTINED_MEMBERS';
            $fieldSelection='EMAIL';
            $dedupFlag='false';
            $fileFormat='pipe';
            $exporsegment_array=$Segment->exportHB($token, $mailinglistId, $operationType, $fieldSelection, $dedupFlag, $fileFormat);
            $Segmentexport=$exporsegment_array[0][0];
            echo('<div><b><u style="color:green">Id de l\'export: '.$Segmentexport.'</u></b></div>');

            /*******----------------------------------------Recuperer Status de l'export HB----------------------------------***/
            $Idexp=$Segmentexport;
            $exporsegment_array=$Segment->statusexport($token, $Idexp);
            $statexpor=$exporsegment_array[0][0];

            /*******----------------------------------------Recuperer les adresses de l'export HB----------------------------------***/
            while ($statexpor!="SUCCESS"){
                $Idexp=$Segmentexport;
                $exporsegment_array=$Segment->statusexport($token, $Idexp);
                $statexpor=$exporsegment_array[0][0];

            }
            $Idexport=$Segmentexport;
            $exporsegment_array=$Segment->downloadexport($token, $Idexport);
            $downloadexpor=$exporsegment_array[0]['fileContent'];
            $tabMails = explode("\n", $downloadexpor);
            $cont = (count($tabMails)-2);
            echo '<div style="color:blue"><u>Nombre d\'adresse HB pour le porteur '.$key.' est de : '.$cont.'</u></div>';
            if ($cont == 0){
                echo '<div style="color:red"><u>' . $key . '</u> : Pas de HardBounce</div>';
            }
            for ($j=1; $j < $cont; $j++) {
                $email = explode(" ", $tabMails[$j]);

                $lemail=$email[0];

                $mysql=$Segment->updateInteraute($lemail, $key);
            }
            $HBaff=$Segment->HBaffil($key);
        }
    }


    public function actionStoreinfos()

    {




        \Yii::import( 'ext.SeuilGlobalMAP' );
        $SeuilGlobalMAP=new \SeuilGlobalMAP();

        $date_actuel_cron=date('Y-m-d');
        $date_premiere_cron='2016-09-01';


        $msg="";
        $texte="";

        $start=time();

        foreach($GLOBALS['porteurMap'] AS $key=>$value){
            $msg="";
            $msg .=$key.'<br/>';
            if($key=='se_rmay'){sleep(20);}
            if($key=='br_laetizia'){sleep(20);}
            if($key=='ca_rinalda'){sleep(20);}
            // à ne pas decommenter if($key=='de_rmay'){sleep(10);}
            if($key=='fr_rinalda'){sleep(10);}
            if($key=='uk_aasha'){sleep(10);}

            /* a ne pas decommenter   Yii::app()->session['porteur'] = $key;*/
            $lng=explode('_', $key);
            $site=$lng[0];
            $_GET['site']=$site;
            \Controller::loadConfigForPorteur($key);

            $seuilCronFunctions=new \SeuilCronFunctions();

            /*----------- CLICK ET ACHAT------------------------------------------------------------------*/
            $msg  .="<div>----------- <b>stokage base de données</b>-----------</div>";
            $msg .=$seuilCronFunctions->StoreInfosinDB($date_actuel_cron,$date_premiere_cron,$key);
            $msg  .="<div>----------- <b>Mise à jour click</b>-----------</div>";
            $msg .=$seuilCronFunctions->UpdateDBClick($key,$value,$date_actuel_cron,$date_premiere_cron);

            /*----------- ALERTE------------------------------------------------------------------*/
            \Yii::import( 'ext.SeuilPorteur' );
            $SeuilPorteur=new \SeuilPorteur($key);
            $porte=$SeuilPorteur->SeuilPorteur($key);

            $seuilCronFunctions->SendMailClick($date_premiere_cron,$date_actuel_cron,$key,$value,$porte);

            $seuilCronFunctions->SendMailAchat($date_premiere_cron,$date_actuel_cron,$key,$value,$porte);
            /*-----------FIN ALERTE------------------------------------------------------------------*/



            \MailHelper::sendMail($this->SeuiladminMails, 'Cron', 'Seuil technique '.$key, $msg);




        }



    }
	
	//============================================================== Crons Anaconda =========================================================================//
   /************************************************************** Init Anaconda ***************************************************************************/
   /**
    * @author Yacine RAMI
    * @desc Ce cron se charge de la rï¿½cupertation des nouveau leads ï¿½ integrer dans le processus Anaconda, et les initialiser au niveau de la BD et Smart Focus.
    * ce cron doit etre exï¿½cutï¿½ chaque jour aprï¿½s l'envoi toutes les vagues STC.
	* @param string $porteur
	* @param boolean $sendMail
    */
	public function actionInitAnaconda( $porteur = false, $sendMail = true )
	   {
	       if( !empty($porteur) )
	       {
	           if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
	               $msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
	               else
	               {
	                   //initialisation des variables $msg : corps du mail ï¿½ envoyer lors de l'execution du cron, $gp : groupe de prix initial des leads ï¿½ integrer dans le processus Anaconda.
	                   $msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';
	                   $listR3=null;
	                   $listVP=null;
	                   $listVPV1=null;
	                   $listVPV2=null;
	                   $listToImport=null;
	                   $countVP=null;
	                   $countR3=null;
	                   $gp=2;
	                   $dir="../../";
	                   
	                   
						
	  					
	                   $listR3=Business\LeadAffiliatePlatform::getReceivedR3();
	                   $listVPV2=\Business\Invoice::getPayedVP();
	                   $listVPV1=\Business\Invoice_V1::getPayedVPV1();
	                   
	                   
	                   foreach ($listR3 as $cussus)
	                   {
	                     	if($user = \Business\User::loadByEmail($cussus))
	                     	{	
	                     		$user->setOriginByUser(3);
	                     		$user->save();
	                     	}
	                     	     
	                   	
	                   }
	                   
	                   foreach ($listVPV2 as $cussus)
	                   {
	                   	if($user = \Business\User::loadByEmail($cussus))
	                   	{
	                   		$user->setOriginByUser(2);
	                   		$user->save();
	                   	}
	                   
	                   	 
	                   }
	                   
	                   foreach ($listVPV1 as $cussus)
	                   {
	                   	if($user = \Business\User::loadByEmail($cussus))
	                   	{
	                   		$user->setOriginByUser(2);
	                   		$user->save();
	                   	}
	                   
	                   
	                   }
	  
	                   //rï¿½cupere les leads qui on reï¿½u la 3 ï¿½me relance de la VP sans  ou bien achetï¿½ la VP
	                   
	                       
	                      
	                      
	  
	                       
	                       //concatï¿½ner les trois listes
	                       $listVP=array_merge($listVPV2,$listVPV1);
	                       $listToImport=array_merge($listR3,$listVP);
	                       array_unshift($listToImport,"EMAIL");
	  						
	                       $countR3=sizeof($listR3);
	                       $countVP=sizeof($listVP);
	                       
	                       $msg.='<br/><div style="color:green">Nouveaux leads VP et R3 export&eacute;s avec succ&egrave;s, le : '.date('Y-m-d')."</div>".$countR3." R3.<br> ".$countVP." VP.";
	                       
	                       // ShootDate J+1
	                       $dateToday = new DateTime(date('Y-m-d'));
	                       $shootDate=$dateToday->format('d/m/Y');
	  
	                       //Segmentation : mise ï¿½ jour fiche client + BD
	                       $statutSegmentation=false;
	                       $statutSegmentation=\AnacondaBehavior::segmentation($listToImport,$shootDate,$gp,$dir);
	  
	                       if($statutSegmentation==1)
	                       {
	                           $msg.='<br/><div style="color:green">Nouveaux leads VP et R3 import&eacute;s avec succ&egrave;s, au niveau de Smart Focus et la BDD le  : '.date('Y-m-d')."</div><br/>";
	                       }
	                       else if($statutSegmentation==2)
	                       {
	                           $msg.='<br/><div style="color:red">Importation Smart Focus KO  : '.date('Y-m-d')."</div><br/>";
	                       }
	                       else if($statutSegmentation==3)
	                       {
	                           $msg.='<br/><div style="color:red">La premi&egrave;re FID Anaoncda inexsistante : '.date('Y-m-d')."</div><br/>";
	                       }
	                       else
	                       {
	                           $msg.='<br/><div style="color:green">Nouveaux leads VP et R3 import&eacute;s avec succ&egrave;s le '.date('Y-m-d').'<br/>Sauf  les leads suivants qui n\'existent pas dans la BD : </div><br/>';
	                           foreach($statutSegmentation as $emailKO)
	                           {
	                               $msg.='<br/><div style="color:red">'.$emailKO.'</div><br/>';
	                           }
	                       }
	  
	                   
	  				
	               }
	              
	               if( $sendMail )
	               {
	               		echo $msg;
	                  	\MailHelper::sendMail( $this->anacondaMails, 'Cron', 'init Anaconda', $msg );
	                   	return $msg;
	                  
	               }
	               else
	                   return $msg;
	       }
	   }
	   

	  /*********************************************************************  / Init Anaconda ***********************************************************************************/
	   /************************************************************** Init Anaconda ***************************************************************************/
	   /**
	    * @author Yacine RAMI
	    * @desc Duplication de la mï¿½thode actioninitAnaconda pour les tests dans des Controllers.
	    */
	   public function actionInitAnaconda2( $porteur = false, $sendMail = true )
		{
	       if( !empty($porteur) )
	       {
	           if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
	               $msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
	               else
	               {
	                   //initialisation des variables $msg : corps du mail ï¿½ envoyer lors de l'execution du cron, $gp : groupe de prix initial des leads ï¿½ integrer dans le processus Anaconda.
	                   $msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';
	                   $listR3=null;
	                   $listVP=null;
	                   $listVPV1=null;
	                   $listToImport=null;
	                   $countVP=null;
	                   $countR3=null;
	                   $gp=2;
	                   $dir="../";
	                   
	                   
						
	  					
	                   $listR3=Business\LeadAffiliatePlatform::getReceivedR3();
	                   $listVPV2=\Business\Invoice::getPayedVP();
	                   $listVPV1=\Business\Invoice_V1::getPayedVPV1();
	  
	                   //rï¿½cupere les leads qui on reï¿½u la 3 ï¿½me relance de la VP sans  ou bien achetï¿½ la VP
	                   
	                       
	                      
	                      
	  
	                       
	                       //concatï¿½ner les trois listes
	                       $listVP=array_merge($listVPV2,$listVPV1);
	                       $listToImport=array_merge($listR3,$listVP);
	                       array_unshift($listToImport,"EMAIL");
	  						
	                       $countR3=sizeof($listR3);
	                       $countVP=sizeof($listVP);
	                       
	                       $msg.='<br/><div style="color:green">Nouveaux leads VP et R3 export&eacute;s avec succ&egrave;s, le : '.date('Y-m-d')."</div>".$countR3." R3.<br> ".$countVP." VP.";
	                       
	                       // ShootDate J+1
	                       $dateToday = new DateTime(date('Y-m-d'));
	                       $shootDate=$dateToday->format('d/m/Y');
	  
	                       //Segmentation : mise ï¿½ jour fiche client + BD
	                       $statutSegmentation=false;
	                       $statutSegmentation=\AnacondaBehavior::segmentation($listToImport,$shootDate,$gp,$dir);
	  
	                       if($statutSegmentation==1)
	                       {
	                           $msg.='<br/><div style="color:green">Nouveaux leads VP et R3 import&eacute;s avec succ&egrave;s, au niveau de Smart Focus et la BDD le  : '.date('Y-m-d')."</div><br/>";
	                       }
	                       else if($statutSegmentation==2)
	                       {
	                           $msg.='<br/><div style="color:red">Importation Smart Focus KO  : '.date('Y-m-d')."</div><br/>";
	                       }
	                       else if($statutSegmentation==3)
	                       {
	                           $msg.='<br/><div style="color:red">La premi&egrave;re FID Anaoncda inexsistante : '.date('Y-m-d')."</div><br/>";
	                       }
	                       else
	                       {
	                           $msg.='<br/><div style="color:green">Nouveaux leads VP et R3 import&eacute;s avec succ&egrave;s le '.date('Y-m-d').'<br/>Sauf  les leads suivants qui n\'existent pas dans la BD : </div><br/>';
	                           foreach($statutSegmentation as $emailKO)
	                           {
	                               $msg.='<br/><div style="color:red">'.$emailKO.'</div><br/>';
	                           }
	                       }
	  
	                   
	  				
	               }
	              
	               if( $sendMail )
	               {
	               		echo $msg;
	                  	\MailHelper::sendMail( $this->anacondaMails, 'Cron', 'init Anaconda', $msg );
	                   	return $msg;
	                  
	               }
	               else
	                   return $msg;
	       }
	   }
	   
	   
	   /*********************************************************************  / Init Anaconda ***********************************************************************************/
   
   	/************************************************************************** Deliver Stand By  ***********************************************************************************/
	 /**
	  * @author Yacine RAMI
	  * @desc Livraison des fids Anaconda en mode Stand By.
	  * @param string $porteur dossier porteur
	  * @param boolean $sendMail envoyer email
	  * @throws \EsoterException
	  * @return string $msg message alimentï¿½
	  */
	   
	public function actionDeliverStandBy($porteur = false, $numCron, $sendMail = true)
    {
    	$msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';
          
        if( !empty($porteur) )
        {
        	if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
        	$msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
        	else
        	{
                      
            	//Initialiser les objets
                $list=null;
                $product=null;
                $subCamp=null;
                $campaign=null;
                $invoices=null;
                $user=null;
                
                //S'il existent des campaigns en Stand By
                if($list=\Business\CampaignHistory::loadStandByCampaigns($numCron))
                {
                  
                	foreach($list as $camph)
                    {
                    	if(!$camph->SubCampaign->isInter())
                    	{
                    		\AnacondaBehavior::execWebFormPayment($camph);
                    		$camph->status=2;
                    		$camph->save();
                    		$msg.="campaign History id :".$camph->id." <span style='color:green;'>will be delivered for : ".$camph->SubCampaign->Product->ref."</span><br/>";
                    	}
                    	
                    }                
                  
                 }
                 else
                 {
                 	$msg.="<span style='color:red;'>No Campaign Histories to be delivered</span><br/>";
                 }
                      
               }
              	         
               if( $sendMail )
               {
               	echo $msg;
               	\MailHelper::sendMail( $this->anacondaMails, 'Cron', 'Deliver Stand By', $msg );
               	return $msg;
               	 
               }
               else
               	return $msg;
               
           }
          
    }
    
    /************************************************************************** / Deliver Stand By  **********************************************************************************/
    /*************************************************************************** Update Openers List DE ***********************************************************************************/
   /**
    * @author Yacine RAMI
    * @desc Recupere la liste des ouvreurs pour chaque vague et decale la date de shoot modifiable au niveau de la BDD et SF.
    * @param string $porteur 
    * @param boolean $sendMail
    * @param integer $shoot
    */
    public function actionUpdateOpenersListDE($porteur=false,$shoot,$sendMail=true)
    {
    	$msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';
    	
    	$dateToday=date('Y-m-d');
    	//calcul J - 1
    	$dateYesterday = new DateTime(date('Y-m-d'));
		$dateYesterday->sub(new DateInterval('P1D'));
		$dateYesterday=$dateYesterday->format('Y-m-d');

    	
    	if( !empty($porteur) )
    	{
    		if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
    		$msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
    		else
    		{
    				
				//Recuperation des ouvreurs par vague de shoot
    			$openersList=\AnacondaBehavior::getOpenersList($shoot);
    					    					
    			if($openersList)
    			{
    				//Nom du fichier d'import update EMV DE
    				$fileName="AnacondaData/Imports/" . $porteur . "/" . "UpdateEMVDE" . "/" . date("F_j_Y") . "/mise_a_jour_emv_DE_" . date("F_j_Y_G_i_s") . ".txt";
    					
    				//Decalage de la date DE dans la BDD et Smart Focus
    				if(AnacondaBehavior::updateEMVDE($openersList,"../../"))
    				{
    					$msg.="<br/> <span style='color:green;'> le champ DE Anaconda a &eacute;t&eacute; d&eacute;cal&eacute; avec succ&egrave;s pour ".count($openersList)." leads.
    							<br/> veuillez v&eacute;rifier le fichier d'import : <br/> $fileName
    							</span>";
    				}
    			}	
    			else
    			{
    				//Alimentation du message dans le cas d'une liste Vide
    				$msg.="<br/> <span style='color:red;'> DE_Anaconda ne sera d&eacute;cal&eacute;e pour aucun lead.</span>";
    			}
    				
    		}
    			
    		if( $sendMail )
    		{
    			echo $msg;
    			\MailHelper::sendMail( $this->anacondaMails, 'Cron', 'update Openers List DE', $msg );
    			return $msg;
    				 
    		}
    		else
    			return $msg;
    	}
    	
    }
   
    /************************************************************************** / Update Openers List DE **********************************************************************************/
    
    /**************************************************************************  Reactivate *****************************************************************************************/
    public function actionReactivate($porteur=false,$sendMail=true)
    {
    	
    	$msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';
    	
    	if( !empty($porteur) )
    	{
    		if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
    			$msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
    			else
    			{ 
			    	$date=new \DateTime();
			    	$date->sub( new DateInterval('P2M') );
			    	$users=\Business\User::loadInactiveUsers($date->format('Y-m-d'));
			    	 
			    	 
			    	foreach ($users as $user){
			    		$campaign = \AnacondaBehavior::getNextCampaign($user->id);
			    		$subCampaign=\Business\SubCampaign::loadByCampaignAndPosition( $campaign->id, 1 );
			    		
			    		$lastCampaignHistory=\Business\CampaignHistory::getLastCampaignHistorybyIdUSer($user->id);
			    		//taritement fid suivante
			    		$newCampaignHistory=new \Business\CampaignHistory();
			    
			    		$gp=\AnacondaBehavior::getGridPriceReactivationByUser($user->email);
			    
			    		$token[ '__m__' ] = $user->email;
			    		$shootDate=new \DateTime();
			    		$shootDate->add( new DateInterval('P1D') );
			    		$token[ '__date__' ] =$shootDate->format('m/d/Y');
						$token[ '__h__' ] =$lastCampaignHistory->behaviorHour;
			    		$token[ '__gp__' ]=$gp;
			    		$token[ '__s__' ] =$campaign->ref."_0";
			    
			    		
			    
			    		$newCampaignHistory->modifiedShootDate=$shootDate->format('Y-m-d');
			    		$newCampaignHistory->initialShootDate=$shootDate->format('Y-m-d');
			    		$newCampaignHistory->groupPrice=$gp;
			    		$newCampaignHistory->status=0;
			    		$newCampaignHistory->behaviorHour=$lastCampaignHistory->behaviorHour;
			    		$newCampaignHistory->idUser=$user->id;
			    		$newCampaignHistory->idSubCampaign=$subCampaign->id;
			    		$newCampaignHistory->save();
			    
			    		//unbann
			    
			    		$user->dateBanning=NULL;
			    		$user->bannReason=0;
			    		$user->save();
			    
			    		//recupereation du webform de passage inter fid
			    		$wf_interfid =\Yii::app()->params['wf_interfid'];
			    		$webForm = str_replace( array_keys($token), $token, $wf_interfid );
			    
			    		//execution du webform
			    		$Curl	= new \CurlHelper();
			    		$Curl->setTimeout(CURL_TIMEOUT);
			    		$Curl->sendRequest( $webForm );
			    		
			    		$msg.="<br/> <span style='color:green;'> R&eacute;activation OK pour:  ".$user->email."</span>";
			    		 
			    	}
			    	}
			    	 
			    	if( $sendMail )
			    	{
			    		echo $msg;
			    		\MailHelper::sendMail( $this->anacondaMails, 'Cron', 'Reactivation', $msg );
			    		return $msg;
			    			
			    	}
			    	else
			    		return $msg;
			    	}
    }
    /**************************************************************************  /Reactivate ****************************************************************************************/
    /************************************************************************* Bann Anaconda Users **********************************************************************************/
    
    public function actionBannAnacondaUsers($porteur=false,$sendMail=true)
    {
    	$msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';
    	$countInactifs=0;
    	$countSB=0;
    	
    	if( !empty($porteur) )
    	{
    		if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
    			$msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
    		else
    		{
    			//Recuperation des leads inactifs
    			$listEmailsInactifs=\AnacondaBehavior::getInactiveUsers2("../");
    			
    			
    			if($listEmailsInactifs)
    			{
    				//Pour chaque lead affecter la valeur 1 comme raison de bann => Bann a cause de l'inactivite, si le lead n'est pas encore bannï¿½
    				foreach($listEmailsInactifs as $email)
    				{
    					$user=null;
    					if($user = \Business\User::loadByEmail($email))
    					{
    						if($user->bannReason != 1 && $user->bannReason != 2)
    						{
	    						$user->bannReason=1;
	    						$user->dateBanning=date( \Yii::app()->params['dbDateTime']);
	    						$user->save();
	    						$countInactifs++;
    						}
    					}
    				}
    				
    			}
    			
    			// Alimenter le message en affichant le nombre des leads inactifs bannï¿½s
    			$msg.= '<div style="green"> Bann des leads inactifs est effectu&eacute; pour : '.$countInactifs.' leads</div>';
    			
    			
    			//Recuperation des leads qui ont atteint 10 SoftBouncesSuccessives
    			$listEmailsSB7=\AnacondaBehavior::GetSBBannList(7);
    			$listEmailsSB15=\AnacondaBehavior::GetSBBannList(15);
    			
    			 
    			if($listEmailsSB7)
    			{
    				$listInactifs=\AnacondaBehavior::getInactiveUsersSB("../");
    				unset ( $listInactifs[0] );
    				// Pour chaque lead affecter la valeur 2 comme raison de bann => Bann a cause des Soft Bounces successives, si le lead n'est pas encore bannï¿½
    				foreach($listEmailsSB7 as $email)
    				{
    					if(in_array($email,$listInactifs))
    					{
	    					$user=null;
	    					if($user = \Business\User::loadByEmail($email))
	    					{
	    						if($user->bannReason != 1 && $user->bannReason != 2)
	    						{
		    						$user->bannReason=2;
		    						$user->dateBanning=date( \Yii::app()->params['dbDateTime']);
		    						$user->countSoftBounce=0;
		    						$user->save();
		    						$countSB++;
	    						}
	    					}
	    				}
    				}
    				
    			}
    			
    			if($listEmailsSB15)
    			{
    				// Pour chaque lead affecter la valeur 2 comme raison de bann => Bann a cause des Soft Bounces successives, si le lead n'est pas encore bannï¿½
    				foreach($listEmailsSB15 as $email)
    				{
    						$user=null;
    						if($user = \Business\User::loadByEmail($email))
    						{
    							if($user->bannReason != 1 && $user->bannReason != 2)
    							{
    								$user->bannReason=2;
    								$user->dateBanning=date( \Yii::app()->params['dbDateTime']);
    								$user->countSoftBounce=0;
    								$user->save();
    								$countSB++;
    							}
    						}
    				}
    			
    			}
    			
    			// Alimenter le message en affichant le nombre des leads SB bannï¿½s
    			$msg.= '<div style="green"> Bann des leads g&eacute;n&eacute;rant des Soft Bounces est effectu&eacute; pour : '.$countSB.' leads</div>';
    			 
    		}
    		
    		if( $sendMail )
    		{
    			echo $msg;
    			\MailHelper::sendMail( $this->anacondaMails, 'Cron', 'Bann Anaconda Users', $msg );
    			return $msg;
    		
    		}
    		else
    			return $msg;
    	}
    			
    }
    
    /*********************************************************************** / Bann Anaconda Users **********************************************************************************/
   
   /***********************************************************************  Indice d implication STC Version1.1 ***********************************************/
    public function actionPurchasedAnacondaStcV1($porteur=false,$sendMail=true)
    {
		
		if (! empty ( $porteur )) {
			if (! isset ( $GLOBALS ['porteurMap'] [$porteur] ) || ! \Controller::loadConfigForPorteur ( $porteur ))
				$msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable</div>';
			else {
				$invoices = new \Business\Invoice ();
				$invoices = $invoices->getPurchasedStcOfBeforOneDay ();
				$msg = '<br/><div style="color:green">';
				$msg .= 'nombre des clients dont l\'indice d\'implication seras mise Ã  jour Pour le porteur ' . $GLOBALS ['porteurMap'] [$porteur] . ' est : ' . count ( $invoices ) . '<br><br>';
			
				$msg.= "<table border=2>
    									<th>Email</th>
										<th>Site</th>
    									<th>Produit achet&eacute</th>
    									<th> Relance du produit</th>
										<th> Nombre de point</th>";
				foreach ( $invoices as $invoice ) {
					$newUser = new \Business\User ();
					$msg.='<tr>';
					$user = $newUser->loadByEmail ( $invoice->emailUser );
					$msg .= '<td>' . $invoice->emailUser . '</td>';
					$msg .= '<td>' . $invoice->codeSite . '</td>';
					$msg .= '<td>' . $invoice->RecordInvoice [0]->refProduct . '</td>';
					$msg .= '<td>' . $invoice->priceStep . '</td>';
					switch ($invoice->RecordInvoice [0]->refProduct) {
						case 'stg_3' :
						case 'stg_4':
						case 'stg_5':
						case 'stg_6':
						case 'stg_7':
						case 'inter' : 
						case 'as':
						case 'as2':
						case 'as3':
						case 'as4':
						case 'stc_3' :
						case 'stc_4' :
						case 'stc_5' :
						case 'stc_6' :
						case 'stc_7' :
							if ($invoice->priceStep != 1503 && $invoice->priceStep != 1504) {
								$user->updateIndiceInplicationByEmail ( 3 );
								$msg .= '<td> 3 points </td>';
							}
							break;
						case 'stc_2' :
						case 'vp' :
						case 'stg_2' :
							if($GLOBALS ['porteurMap'] [$porteur] == 'se_rmay')
							{
								if ($invoice->priceStep != 1503 && $invoice->priceStep != 1504) {
									$user->updateIndiceInplicationByEmail ( 3 );
									$msg .= '<td> 3 points </td>';
								}
								
							}
							else 
							{
							switch ($invoice->priceStep) {
								case 1 :
								case 2 :
								case 3 :
									$user->updateIndiceInplicationByEmail ( 3 );
									$msg .= '<td>  3  </td>';
									break;
								case 4 :
								case 5 :
								case 6 :
									$user->updateIndiceInplicationByEmail ( 2 );
									$msg .= '<td>  2  </td>';
									break;
								case 7 :
								case 8 :
								case 9 :
									$user->updateIndiceInplicationByEmail ( 1 );
									$msg .= '<td>  1  </td>';
									break;
								default :
									break;
							}
							break;
							}
							case 'VP' :
								switch ($invoice->priceStep) {
									case 1 :
									case 2 :
									case 3 :
										$user->updateIndiceInplicationByEmail ( 3 );
										$msg .= '<td>  3  </td>';
										break;
									case 4 :
									case 5 :
									case 6 :
									case 7 :
									case 8 :
										$user->updateIndiceInplicationByEmail ( 2 );
										$msg .= '<td>  2  </td>';
										break;
									case 9 :
									case 10 :
									case 11 :
									case 12 :
									case 13 :
										$user->updateIndiceInplicationByEmail ( 1 );
										$msg .= '<td>  1  </td>';
										break;
									default :
										break;
								}
								break;
							case 'stc_1':
								if($GLOBALS ['porteurMap'] [$porteur] == 'se_rmay')
								{
									switch ($invoice->priceStep) {
										case 1 :
										case 2 :
										case 3 :
											$user->updateIndiceInplicationByEmail ( 3 );
											$msg .= '<td>  3  </td>';
											break;
										case 4 :
										case 5 :
										case 6 :
											$user->updateIndiceInplicationByEmail ( 2 );
											$msg .= '<td>  2  </td>';
											break;
										case 7 :
										case 8 :
										case 9 :
											$user->updateIndiceInplicationByEmail ( 1 );
											$msg .= '<td>  1  </td>';
											break;
										default :
											break;
								}
								break;
								}
						default :
							break;
					}
					$msg .= '</tr>';
				}
			}
			
			if ($sendMail) {
				$msg.= "</table>";
				$msg .= 'Ex&eacute;cut&eacute; le : ' . date ( \Yii::app ()->params ['dbDateTime'] ) . '</div><br/>';
				echo $msg;
				\MailHelper::sendMail ( $this->anacondaMails, 'Cron', 'Indice d\'implication', $msg );
				return $msg;
			} else
				return $msg;
		}
    }
    /*********************************************************************** / Indice d implication STC Version1.1 ***********************************************/
    
    /***********************************************************************  Indice d implication STC ***********************************************/
    public function actionPurchasedAnacondaStc($porteur=false,$sendMail=true)
    {
    
    	$type = explode('_',$porteur);
    	$cont = count($type) - 1 ;
    	$type = $type[$cont] ;
    
    	if (! empty ( $porteur )) {
    			
    		if (! isset ( $GLOBALS ['porteurMap'] [$porteur] ) || ! \Controller::loadConfigForPorteur ( $porteur ))
    
    			$msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable</div>';
    
    
    			else {
    					
    				/**************************V1********************/
    				$invoicesV1 = new \Business\PaymentTransaction () ;
    				$invoicesV1 =  $invoicesV1->getPurchasedStcOfBeforOneDay() ;
    
    				/**************************V2********************/
    				$invoices = new \Business\Invoice ();
    				$invoices = $invoices->getPurchasedStcOfBeforOneDay ();
    					
    
    				$count = count ( $invoices ) + count ( $invoicesV1 ) ;
    
    
    				$msg = '<br/><div style="color:green">';
    				$msg .= 'nombre des clients dont l\'indice d\'implication seras mise Ã  jour Pour le porteur ' . $GLOBALS ['porteurMap'] [$porteur] . ' est : ' . $count . '<br><br>';
    
    				$msg.= "<table border=2>
    									<th>Email</th>
										<th>Site</th>
    									<th>Produit achet&eacute</th>
    									<th> Relance du produit</th>
										<th> Nombre de point</th>";
    					
    
    				// V1
    
    				foreach ( $invoicesV1 as $invoicev1 ) {
    
    					$newUser = new \Business\User ();
    					$msg.='<tr>';
    					$user = $newUser->loadByEmail ( $invoicev1->email );
    					$msg .= '<td>' . $invoicev1->email . '</td>';
    					$msg .= '<td>' . $invoicev1->Site . '</td>';
    					$msg .= '<td>' . $invoicev1->productRef . '</td>';
    					$msg .= '<td>' . $invoicev1->refDiscount . '</td>';
    
    
    					if (preg_match('/asile/', $invoicev1->productRef) || preg_match('/inter/', $invoicev1->productRef) || preg_match('/conttele/', $invoicev1->productRef) )
    					{ 	$user->updateIndiceInplicationByEmail ( 3 );
    					$msg .= '<td> 3 points </td>'; }
    
    					else { switch($invoicev1->refDiscount) {
    
    						case 1 :
    						case 2 :
    						case 3 :
    							$user->updateIndiceInplicationByEmail ( 3 );
    							$msg .= '<td>  3 points  </td>';
    							break;
    						case 4 :
    						case 5 :
    						case 6 :
    							$user->updateIndiceInplicationByEmail ( 2 );
    							$msg .= '<td>  2 points </td>';
    							break;
    
    						case 7 :
    						case 8 :
    						case 9 :
    							if($type == "Rinalda" ) { $user->updateIndiceInplicationByEmail ( 2 );
    							$msg .= '<td>  2 points </td>'; break; }
    							else                  { $user->updateIndiceInplicationByEmail ( 1 );
    							$msg .= '<td>  1 points </td>'; break; }
    
    						case 10 :
    						case 11 :
    						case 12 :
    						case 13 :
    							$user->updateIndiceInplicationByEmail ( 1 );
    							$msg .= '<td>  1 points </td>';
    							break;
    
    						default :
    							break ;
    
    
    					}   } }
    
    
    					// V2
    
    
    					foreach ( $invoices as $invoice ) {
    						$newUser = new \Business\User ();
    						$msg.='<tr>';
    						$user = $newUser->loadByEmail ( $invoice->emailUser );
    						$msg .= '<td>' . $invoice->emailUser . '</td>';
    						$msg .= '<td>' . $invoice->codeSite . '</td>';
    						$msg .= '<td>' . $invoice->RecordInvoice [0]->refProduct . '</td>';
    						$msg .= '<td>' . $invoice->priceStep . '</td>';
    						switch ($invoice->RecordInvoice [0]->refProduct) {
    							case 'stg_3' :
    							case 'stg_4':
    							case 'stg_5':
    							case 'stg_6':
    							case 'stg_7':
    							case 'INTER' :
    							case 'as':
    							case 'as2':
    							case 'as3':
    							case 'as4':
    							case 'stc_3' :
    							case 'stc_4' :
    							case 'stc_5' :
    							case 'stc_6' :
    							case 'stc_7' :
    
    								$user->updateIndiceInplicationByEmail ( 3 );
    								$msg .= '<td> 3 points </td>';
    									
    								break;
    							case 'stc_2' :
    							case 'vp' :
    							case 'stg_2' :
    								if($GLOBALS ['porteurMap'] [$porteur] == 'se_rmay')
    								{
    										
    									$user->updateIndiceInplicationByEmail ( 3 );
    									$msg .= '<td> 3 points </td>';
    										
    
    								}
    								else
    								{
    									switch ($invoice->priceStep) {
    										case 1 :
    										case 2 :
    										case 3 :
    											$user->updateIndiceInplicationByEmail ( 3 );
    											$msg .= '<td>  3 points </td>';
    											break;
    										case 4 :
    										case 5 :
    										case 6 :
    											$user->updateIndiceInplicationByEmail ( 2 );
    											$msg .= '<td>  2 points </td>';
    											break;
    										case 7 :
    										case 8 :
    										case 9 :
    											$user->updateIndiceInplicationByEmail ( 1 );
    											$msg .= '<td>  1 points </td>';
    											break;
    										default :
    											break;
    									}
    										
    								}
    								break;
    							case 'VP' :
    								switch ($invoice->priceStep) {
    									case 1 :
    									case 2 :
    									case 3 :
    										$user->updateIndiceInplicationByEmail ( 3 );
    										$msg .= '<td>  3 points </td>';
    										break;
    									case 4 :
    									case 5 :
    									case 6 :
    									case 7 :
    									case 8 :
    										$user->updateIndiceInplicationByEmail ( 2 );
    										$msg .= '<td>  2 points </td>';
    										break;
    									case 9 :
    									case 10 :
    									case 11 :
    									case 12 :
    									case 13 :
    										$user->updateIndiceInplicationByEmail ( 1 );
    										$msg .= '<td>  1 points </td>';
    										break;
    									default :
    										break;
    								}
    								break;
    							case 'stc_1':
    								if($GLOBALS ['porteurMap'] [$porteur] == 'se_rmay')
    								{
    									switch ($invoice->priceStep) {
    										case 1 :
    										case 2 :
    										case 3 :
    											$user->updateIndiceInplicationByEmail ( 3 );
    											$msg .= '<td>  3 points </td>';
    											break;
    										case 4 :
    										case 5 :
    										case 6 :
    											$user->updateIndiceInplicationByEmail ( 2 );
    											$msg .= '<td>  2 points </td>';
    											break;
    										case 7 :
    										case 8 :
    										case 9 :
    											$user->updateIndiceInplicationByEmail ( 1 );
    											$msg .= '<td>  1 points  </td>';
    											break;
    										default :
    											break;
    									}
    										
    								}
    								break;
    							default :
    								break;
    						}
    						$msg .= '</tr>';
    					}
    			}
    				
    
    			if ($sendMail) {
    				$msg.= "</table>";
    				$msg .= 'Ex&eacute;cut&eacute; le : ' . date ( \Yii::app ()->params ['dbDateTime'] ) . '</div><br/>';
    				echo $msg;
    				\MailHelper::sendMail ( $this->anacondaMails, 'Cron', 'Indice d\'implication', $msg );
    				return $msg;
    			} else
    				return $msg;
    	}
    }
    /*********************************************************************** / Indice d implication STC ***********************************************/
    
    
    /************************************************************************  Update Soft Bounce ***********************************************/
    public function actionUpdateSoftBounce($porteur=false,$sendMail=true){
    	
    	$msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';
    	 
    	if( !empty($porteur) )
    	{
    		if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
    			$msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
    			else
    			{
    	 
			    	$porteurMapp = Yii::app()->params['porteur'];
			    	\Controller::loadConfigForPorteur($porteurMapp);
			    	 
			    	//rï¿½cupï¿½ration des parametres de l'API
			    	$mkt_wdsl =\Yii::app()->params['MKT_EMV_ACQ']['wdsl'];
			    	$mkt_login =\Yii::app()->params['MKT_EMV_ACQ']['login'];
			    	$mkt_pwd =\Yii::app()->params['MKT_EMV_ACQ']['pwd'];
			    	$mkt_key =\Yii::app()->params['MKT_EMV_ACQ']['key'];
			    	 
			    	$class_api = new Class_API($mkt_wdsl,$mkt_login,$mkt_pwd,$mkt_key);
			    	 
			    	//token de connexion
			    	$token = $class_api->connexion();
			    	$triggers=array();
			    	 
			    	$result=$class_api->soap_client->getExportableCampaigns(
			    			array('token'=> $token,
			    					'page'=>'1',
			    					'perPage'=>'100',
			    					'campaignType'=>'TRIGGER'
			    			)
			    			);
			    	 
			    	$nbTotalItems=$result->return->nbTotalItems;
			    	$nbrPages=ceil($nbTotalItems/100);
			    	 
			    	if (isset($result->return->campaigns->campaign))
			    	{
			    		$triggers=array_merge($triggers, $result->return->campaigns->campaign);
			    	}
			    	 
			    	for($i=1;$i<$nbrPages;$i++)
			    	{
			    		$result=$class_api->soap_client->getExportableCampaigns(
			    				array('token'=> $token,
			    						'page'=>$i+1,
			    						'perPage'=>'100',
			    						'campaignType'=>'TRIGGER'
			    				)
			    				);
			    		$triggers=array_merge($triggers, $result->return->campaigns->campaign);
			    	}
			    	 
			    	$ab= new AnacondaBehavior();
			    	foreach ($triggers as $trigger) {
			    		 
			    		$output_array_inter=array();
						$output_array_asile=array();
			    		preg_match("/(.*)\s*>\s*LDV\s*(.*)\s*anaconda\s*(.*)/", $trigger->name, $output_array_inter);
			    		preg_match("/(.*)\s*>\s*R1\s*(.*)\s*anaconda\s*(.*)/", $trigger->name, $output_array_asile);
			    		 
			    		if(isset($output_array_inter[1]) || isset($output_array_asile[1]))
			    		{
			    			$anacondaTrigger=\Business\AnacondaTrigger::loadByIdTrigger($trigger->triggerId);
			    			if(!isset($anacondaTrigger))
			    			{
			    			if(isset($output_array_inter[1])){
								$name=$output_array_inter[1];
								$inter=1;
							}
							else{
								$name=$output_array_asile[1];
								$inter=0;
							}
			    
			    				$campaign=\Business\Campaign::loadByRef(trim($name));
			    				if(!empty($campaign))
			    				{
			    					if(stripos($name, 'ct')!== false && $campaign->hasCT()){
			    						$position=2;
			    						$tmp=explode('ct', $name);
			    						$name=$tmp[0];
			    					}
			    					else
			    					{
			    						$position=1;
			    					}
			    					 
			    					$subCampaign=\Business\SubCampaign::loadByCampaignAndPosition( $campaign->id, $position );
			    					if(isset($subCampaign)){
			    						$anacondaTrigger= new \Business\AnacondaTrigger();
			    						$anacondaTrigger->idTrigger=$trigger->triggerId;
			    						$anacondaTrigger->nameTrigger=$trigger->name;
			    						$anacondaTrigger->idSubCampaign=$subCampaign->id;
			    						$anacondaTrigger->save();
			    						 
			    						$ab->updateSbSegment($position, $trigger->triggerId, $inter);
			    						 
			    					}
			    					 
			    					
			    				}
			    			}
			    		}
			    	}
			    	
			    	$ab1= new AnacondaBehavior();
			    	$ab1->setSb("../../");
    			}
    	}
    	if( $sendMail )
    	{
    		echo $msg;
    		\MailHelper::sendMail( $this->anacondaMails, 'Cron', 'Soft Bounce', $msg );
    		return $msg;
    	
    	}
    	else
    		return $msg;
    }
    
    /***********************************************************************  /Update Soft Bounce ***********************************************/
    
    /***********************************************************************  /Passage interCampaignR4R2 ***********************************************/
    public function actionInterCampaignR4R2($porteur=false,$sendMail=true)
    {
    	 
    	$msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';
    	 
    	if( !empty($porteur) )
    	{
    		if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
    			$msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
    			else
    			{
    				
    				$AB= new AnacondaBehavior();
    				$list=$AB->getR4R2List();
    				
    			  	if($list){
    			  		
    			  		$AB->passInterCampaignR4R2($list);
    			  		
		 					$msg.='Le passage interCampaignR4R2 non payé est effectué pour les clients suivants:';
    			  			$msg.= "<br/><br/>";
    			  			$msg.= "<table border=2>
    									<th>id</th>
    									<th>User Email</th>
    									<th>Product Ref</th>
    									<th> Date Modifiable DE</th>";
    			  		
    			  			foreach($list as $row)
    			  			{
    			  				$msg.= "<tr>";
    			  				$msg.= "<td>".$row->id."</td>";
    			  				$msg.= "<td>".$row->User->email."</td>";
    			  				$msg.= "<td>".$row->SubCampaign->Campaign->ref."</td>";
    			  				$msg.= "<td>".$row->modifiedShootDate."</td>";
    			  				$msg.= "</tr>";
    			  			}
    			  		
    			  			$msg.= "</table>";
    			  		
    			  		
    			  	}
    			  
    				
    			}
    			 
    			if( $sendMail )
    			{
    				echo $msg;
    				\MailHelper::sendMail( $this->anacondaMails, 'Cron', 'Passage interCampaign', $msg );
    				return $msg;
    
    			}
    			else
    				return $msg;
    	}
    }
    /***********************************************************************  /Passage interCampaignR4R2 ***********************************************/
   //========================================================================= / Crons Anaconda ====================================================================================//
  
   //========================================================================= / Crons Anaconda ====================================================================================//
  
    /************************************************************************** Deliver Stand By Inter  ***********************************************************************************/
    /**
     * @author Saad HDIDOU
     * @desc Livraison des fids Anaconda Inter en mode Stand By.
     * @param string $porteur dossier porteur
     * @param boolean $sendMail envoyer email
     * @throws \EsoterException
     * @return string $msg message alimentï¿½
     */
    
    public function actionDeliverStandByInter($porteur = false, $numCron, $sendMail = true)
    {
    	$msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';
    
    	if( !empty($porteur) )
    	{
    		if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
    			$msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
    			else
    			{
    
    				//Initialiser les objets
    				$list=null;
    				$product=null;
    				$subCamp=null;
    				$campaign=null;
    				$invoices=null;
    				$user=null;
    
    				//S'il existent des campaigns en Stand By
    				if($list=\Business\CampaignHistory::loadInterStandByCampaigns($numCron))
    				{
    
    					foreach($list as $camph)
    					{
    						if($camph->SubCampaign->isInter())
    						{
    							\AnacondaBehavior::pauseLastInterFid($camph);
    							\AnacondaBehavior::execWebFormPayment($camph);
    							$camph->status=1;
    							$camph->save();
    							$subCampCt=\Business\SubCampaign::loadByCampaignAndPosition( $camph->SubCampaign->idCampaign, 2 );
    							$camphCt=\Business\CampaignHistory::loadByUserAndSubCampaign( $camph->idUser, $subCampCt->id );
    							$camphCt->status=0;
    							$camphCt->save();
    							$msg.="campaign History id :".$camph->id." <span style='color:green;'>will be delivered for : ".$camph->SubCampaign->Product->ref."</span><br/>";
    						}
    						 
    					}
    
    				}
    				else
    				{
    					$msg.="<span style='color:red;'>No Campaign Histories to be delivered</span><br/>";
    				}
    
    			}
    
    			if( $sendMail )
    			{
    				echo $msg;
    				\MailHelper::sendMail( $this->anacondaMails, 'Cron', 'Deliver Stand By', $msg );
    				return $msg;
    				 
    			}
    			else
    				return $msg;
    				 
    	}
    
    }
    
    /************************************************************************** / Deliver Stand By  **********************************************************************************/


    /*********************************** Cron ImportErrorList ***********************************************************************/

    /**
     * @author Fouad DANI
     * @desc Retrieves a list of uploads and their details.
     * @return	msg of uploads and their details
     * @param string porteur and string sendMail
     */


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

    /*********************************** /Cron ImportErrorList ***********************************************************************/
    public function actionSetQuarantaine($porteur = false, $sendMail = true){
    	if( !empty($porteur) ){
    		if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) ){
    			$msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
    			echo $msg;
    		}else{
    			$dir="../../";
    			
    			\AnacondaBehavior::getImportErrorList($dir);
    			
    				if( $sendMail ){
    					// \MailHelper::sendMail( $this->smartFocusSupportMails, 'Cron', 'Fonctionnalité d\'import via API', $msg );
    					echo $msg;
    					return $msg;
    				}
    
    			}
    		}
    	}

    
    
    }
       

?>