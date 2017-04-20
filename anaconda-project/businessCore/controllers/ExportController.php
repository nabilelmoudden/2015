<?php

\Yii::import('ext.MailHelper');

/**
 * Description of SiteController
 *
 * @author Youssef HARRATI
 * @package Controllers
 */
class ExportController extends Controller {

    /**
     * Initialise le controller generique des site
     * Instancie l'Objet Context
     * @throws EsoterException	Si l'instanciation du Context a posé probleme
     */
    private $adminMails = array(
        'youssef.harrati@kindyinfomaroc.com',
        'othmane.halhouli@kindyinfomaroc.com',
		'harrati.youssef@gmail.com'
    );

    public function init() {
        parent::init();
    }

    public function actionExport($port = false,$type = false) {
        $p = $port?$port:Yii::app()->request->getParam('port');
        $t = $type?$type:Yii::app()->request->getParam('type');
        set_time_limit(1800);
        $this->exportDataMarketing($p, $t);
    }
	
	public function actionExportVG() {
		set_time_limit(1800);
        $port = Yii::app()->request->getParam('port');
        $start = time();
		$msg = "";
		if(Yii::app()->request->getParam('port')!=null)
		{$msg .= $this->exportDataMarketingVG($port);}
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
		\MailHelper::sendMail($this->adminMails, 'Cron', 'Export MKT hard bounces VG ( tous les porteurs )', $msg);
    }
	
	public function actionExport2($port = false) {
        set_time_limit(1800);
		$start = time();
		$p = $port?$port:Yii::app()->request->getParam('port');
		
		$msg = "<div><h2><center><u>Importation de toutes les campagnes</u></center></h2></div>";
		if($p != null)
		{$msg .= $this->exportDataMarketingNew($p);}
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
		\MailHelper::sendMail($this->adminMails, 'Cron', 'Export MKT ( tous les porteurs )', $msg);
    }

    public function actionReadCsv($p = false,$d = false) {
        \Yii::import('ext.CsvImporter');
		$port = $p?$p:Yii::app()->request->getParam('port');
        $date = $d?$d:Yii::app()->request->getParam('date');
        
        if (!isset($GLOBALS['porteurMap'][$port]) || !\Controller::loadConfigForPorteur($port))
		{die('<div style="color:red"><u>' . $port . '</u> : Le porteur est introuvable</div>');}
        else {
            if (!is_dir(TMP_DIR . '/MktData'))
			{die('<div style="color:red"><u>' . $port . '</u> : pas de data marketing</div>');}
            $folder = TMP_DIR . '/MktData/' . $date;
            if (!is_dir($folder))
			{die('<div style="color:red"><u>' . $port . '</u> : pas de data marketing pour la date: ' . $date . '</div>');}
            $folder .= '/' . $port;
            if (!is_dir($folder))
			{die('<div style="color:red"><u>' . $port . '</u> : pas de data marketing pour ce porteur: ' . $port . '</div>');}
            $folder .= '/';

            $global_data = [];
            $stats = ['UNJOIN', 'OPENED', 'CLICK_DETAIL', 'HBOUNCED', 'SBOUNCED', 'COMPLAINTS', 'DELIVERED', 'QUARANTINED'];
            

            foreach ($stats as $stat) {

                $base_folder = $folder . $stat . '/';
                $file = $base_folder . 'details.csv';
                if (!file_exists($file))
				{continue;}
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
	
	public function actionReadCsvQ($p = false,$d = false) {
        \Yii::import('ext.CsvImporter');
		
		$port = $p?$p:Yii::app()->request->getParam('port');
        $date = $d?$d:Yii::app()->request->getParam('date');

        if (!isset($GLOBALS['porteurMap'][$port]) || !\Controller::loadConfigForPorteur($port))
		{die('<div style="color:red"><u>' . $port . '</u> : Le porteur est introuvable</div>');}
        else {
            if (!is_dir(TMP_DIR . '/MktData'))
			{die('<div style="color:red"><u>' . $port . '</u> : pas de data marketing</div>');}
            $folder = TMP_DIR . '/MktData/' . $date;
            if (!is_dir($folder))
			{die('<div style="color:red"><u>' . $port . '</u> : pas de data marketing pour la date: ' . $date . '</div>');}
            $folder .= '/' . $port;
            if (!is_dir($folder))
			{die('<div style="color:red"><u>' . $port . '</u> : pas de data marketing pour ce porteur: ' . $port . '</div>');}
            $folder .= '/';

            $global_data = [];
            
            $stats = ['QUARANTINED'];

            foreach ($stats as $stat) {

                $base_folder = $folder . $stat . '/';
                $file = $base_folder . 'details.csv';
                if (!file_exists($file))
				{continue;}
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
		{ mkdir(TMP_DIR . '/MktData', 0777);}
        $folder = TMP_DIR . '/MktData/' . date("Y-m-d");
        if (!is_dir($folder))
		{mkdir($folder, 0777);}
        $folder .= '/';
		$msg = "";
        $this->delete_old_directories(TMP_DIR . '/MktData/');
        if (!empty($porteur)) {
            if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur))
			{$msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable</div>';}
            else {
                $msg = NULL;
                $err = NULL;
                $output = '';
                $start = time();

                // Compte ACQUISITION
                if (isset(\Yii::app()->params['MKT_EMV_ACQ']['login']) && !empty(\Yii::app()->params['MKT_EMV_ACQ']['login'])) {
                    $HB = new \EmvExportTreatment(\Yii::app()->params['MKT_EMV_ACQ']); //CMD_EMV_ACQ
					
					$output = $HB->exportDataMarketingHB_VG($porteur, $folder,false);
					if ($output == false)
					{$err .= 'probleme durant la recuperation des hard bounces ( ACQ ) : ' . $HB->getError() . '<br />';}
                            
                    if ($err == NULL)
					{$msg .= '<div><b><u style="color:green">' . $porteur . ' ( ACQ )</u> : Recuperation des stats pour le Compte ACQUISITION</b> effectue en ' . ( time() - $start ) . ' secondes</div>';}
                    else
					{$msg .= '<div style="color:red"><u>' . $porteur . ' ( ACQ )</u> : ' . $err . '</div>';}
                } else
				{$msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';}
				
				if (isset(\Yii::app()->params['MKT_EMV_FID']['login']) && !empty(\Yii::app()->params['MKT_EMV_FID']['login'])) {
                    $HB = new \EmvExportTreatment(\Yii::app()->params['MKT_EMV_FID']); //CMD_EMV_ACQ
					
					$output = $HB->exportDataMarketingHB_VG($porteur, $folder,true);
					if ($output == false)
					{$err .= 'probleme durant la recuperation des hard bounces ( ACQ ) : ' . $HB->getError() . '<br />';}
                            
                    if ($err == NULL)
					{$msg .= '<div><b><u style="color:green">' . $porteur . ' ( FID )</u> : Recuperation des stats pour le Compte FID</b> effectue en ' . ( time() - $start ) . ' secondes</div><br /><br />';}
                    else
					{$msg .= '<div style="color:red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div>';}
                } else
				{$msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV pour le Compte FID</div><br /><br />';}
            }
        }
        return $msg;
        
    }
	
    private function exportDataMarketing($porteur, $type) {
        if (!is_dir(TMP_DIR . '/MktData'))
		{mkdir(TMP_DIR . '/MktData', 0777);}
        $folder = TMP_DIR . '/MktData/' . date("Y-m-d");
        if (!is_dir($folder))
		{mkdir($folder, 0777);}
        $folder .= '/';
        $this->delete_old_directories(TMP_DIR . '/MktData/');
        if (!empty($porteur)) {
            if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur))
			{$msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable</div>';}
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
							{$err .= 'probleme durant la recuperation des OPENED ( ACQ ) : ' . $HB->getError() . '<br />';}
                            break;
                        case 'UNJOIN':
                            $output = $HB->exportDataMarketingUnjoin($porteur, $folder);
                            if ($output == false)
							{$err .= 'probleme durant la recuperation des UNJOIN ( ACQ ) : ' . $HB->getError() . '<br />';}
                            break;
                        case 'CLICK_DETAIL':
                            $output = $HB->exportDataMarketingClick($porteur, $folder);
                            if ($output == false)
							{$err .= 'probleme durant la recuperation des CLICK_DETAIL ( ACQ ) : ' . $HB->getError() . '<br />';}
                            break;
                        case 'COMPLAINTS':
                            $output = $HB->exportDataMarketingComplaints($porteur, $folder);
                            if ($output == false)
							{$err .= 'probleme durant la recuperation des COMPLAINTS ( ACQ ) : ' . $HB->getError() . '<br />';}
                            break;
                        case 'SBOUNCED':
                            $output = $HB->exportDataMarketingSB($porteur, $folder);
                            if ($output == false)
							{$err .= 'probleme durant la recuperation des soft bounces ( ACQ ) : ' . $HB->getError() . '<br />';}
                            break;
                        case 'HBOUNCED':
                            $output = $HB->exportDataMarketingHB($porteur, $folder);
                            if ($output == false)
							{$err .= 'probleme durant la recuperation des hard bounces ( ACQ ) : ' . $HB->getError() . '<br />';}
                            break;
                        case 'DELIVERED':
                            $output = $HB->exportDataMarketingDelivered($porteur, $folder);
                            if ($output == false)
							{$err .= 'probleme durant la recuperation des DELIVERED ( ACQ ) : ' . $HB->getError() . '<br />';}
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
							{$err .= 'probleme durant la recuperation des QUARANTINED ( ACQ ) : ' . $HB->getError() . '<br />';}
                            break;
                    }
                    if ($err == NULL)
					{$msg .= $output . '<br /><div style="color:green"><u>' . $porteur . ' ( ACQ )</u> : Recuperation effectue en ' . ( time() - $start ) . ' secondes</div>';}
                    else
					{$msg .= '<div style="color:red"><u>' . $porteur . ' ( ACQ )</u> : ' . $err . '</div>';}
                } else
				{$msg = '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV</div>';}
            }
        }
        echo $msg;
        return \MailHelper::sendMail($this->adminMails, 'Cron', 'Export MKT ' . $type . ' (' . $porteur . ')', $msg);
    }

    private function exportDataMarketingNew($porteur) {
       if (!empty($porteur)) {
            if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur))
			{$msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable</div>';}
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
						$msg = '<div style="color: red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div><br /><br />';
						$fid = true;
                    }
					if(!$fid){
						$token = $Res->return;
						$output_fid = $HB->exportDataMarketingNew($porteur, array($soap, $token));
						if($output_fid[1]=='ko'){
							$err .= 'Erreur lors de l\'importation ';
							$msg = '<div style="color: red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div><br /><br />';
							print_r($output_fid[2]);
							return $msg;
						}
						$msg .= '<div><b><u style="color:green">' . $porteur . ' ( FID )</u> : Recuperation des stats pour le Compte FID</b></div>';
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
					
					$msg .= '<div><b><u style="color:green">' . $porteur . ' ( ACQ + FID )</u> : Recuperation effectue en ' . ( time() - $start ) . ' secondes</b></div><br /><br />';
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
					{echo ("Folder: " . $dir . $item . " Deleted successfully<br />");}
                    else
					{echo ("Folder: " . $dir . $item . " Couldn't be deleted<br />");}
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

}
