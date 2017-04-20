<?php

/**
 * Description of HardSoftBounce
 *
 * @author JulienL
 */
class EmvExportTreatment extends \EMVMemberAPI
{
	protected $configEMV;
	private $perPage = 100;

	public function __construct( $confEMV )
	{
		parent::__construct( $confEMV['wdsl'], $confEMV['login'], $confEMV['pwd'], $confEMV['key'] );

		set_time_limit( 1800 );
		\Yii::import( 'ext.CSVHelper' );

		$this->configEMV = $confEMV;
	}

	/**
	 * Genere l'export EMV, recupere l'export et l'insere en base de donnée
	 * Export Hard Bounce
	 * @return boolean
	 */
	public function insertHardBounce()
	{
		try
		{
			if( !isset($this->configEMV['seg_HB_id']) || $this->configEMV['seg_HB_id'] <= 0 )
				return '"seg_HB_id" n\'est pas configuré';

			$newExport	= TMP_DIR.'/HBSB/'.\Yii::app()->params['porteur'].'/'.$this->configEMV['seg_HB_id'].'.csv';
			$oldExport	= TMP_DIR.'/HBSB/'.\Yii::app()->params['porteur'].'/'.$this->configEMV['seg_HB_id'].'.old.csv';

			if( is_file($newExport) )
				copy ( $newExport, $oldExport );

			if( $this->createAndWaitExport( $this->configEMV['seg_HB_id'], $this->configEMV['seg_HB_id'].'.csv', EMVMemberAPI::QUARANTINED_MEMBERS ) )
			{
				// Reactive la connection a SQL ( peut poser probleme si l'export prend trop de temps, voir var MySQL "wait_timeout" ) :
				\Yii::app()->db->init();

				$Csv = new CSVHelper( $newExport );
				foreach( $Csv as $i => $row )
				{
					\Yii::app()->db->setActive( true );

					if( $i == 0 ||count($row) < 3 )
						continue;

					if( !($Insert = \Business\EmvExport::load($row[0])) )
						$Insert	= new \Business\EmvExport();
					else
						continue;

					$Date				= DateTime::createFromFormat( 'd/m/Y H:i:s', $row[3] );
					$DateJ				= DateTime::createFromFormat( 'd/m/Y H:i:s', $row[4] );

					$Insert->EMAIL		= $row[0];
					$Insert->SOURCE		= $row[1];
					$Insert->HBQ_REASON	= $row[2];
					$Insert->type		= \Business\EmvExport::TYPE_HB;
					$Insert->DATEUNJOIN	= ( is_object($Date) ) ? $Date->format( 'Y-m-d H:i:s' ) : NULL;
					$Insert->DATEJOIN	= ( is_object($DateJ) ) ? $DateJ->format( 'Y-m-d H:i:s' ) : NULL;

					if( !$Insert->save() )
						return false;
				}
			}
			else
				return false;

			return true;
		}
		catch( CDbException $e )
		{
			$this->error[] = $e->getMessage();
			return false;
		}
	}

	/**
	 * Genere l'export EMV, recupere l'export, le compare a l'export de la veille et insere ou supprime les differences en base de donnée
	 * Export Soft Bounce
	 * @return boolean
	 */
	public function insertSoftBounce()
	{
		try
		{
			if( !isset($this->configEMV['seg_SB_id']) || $this->configEMV['seg_SB_id'] <= 0 )
				return '"seg_SB_id" n\'est pas configuré';

			$newExport	= TMP_DIR.'/HBSB/'.\Yii::app()->params['porteur'].'/'.$this->configEMV['seg_SB_id'].'.csv';
			$oldExport	= TMP_DIR.'/HBSB/'.\Yii::app()->params['porteur'].'/'.$this->configEMV['seg_SB_id'].'.old.csv';

			if( is_file($newExport) )
				copy ( $newExport, $oldExport );

			if( $this->createAndWaitExport( $this->configEMV['seg_SB_id'], $this->configEMV['seg_SB_id'].'.csv', \EMVMemberAPI::ACTIVE_MEMBERS ) )
			{
				// Reactive la connection a SQL ( peut poser probleme si l'export prend trop de temps, voir var MySQL "wait_timeout" ) :
				\Yii::app()->db->init();

				$CsvNew = new CSVHelper( $newExport );
				foreach( $CsvNew as $i => $row )
				{
					\Yii::app()->db->setActive( true );

					if( $i == 0 ||count($row) < 3 )
						continue;

					if( !($Insert = \Business\EmvExport::load($row[0])) )
						$Insert	= new \Business\EmvExport();
					else
						continue;

					$Date				= DateTime::createFromFormat( 'd/m/Y H:i:s', $row[3] );
					$DateJ				= DateTime::createFromFormat( 'd/m/Y H:i:s', $row[4] );

					$Insert->EMAIL		= $row[0];
					$Insert->SOURCE		= $row[1];
					$Insert->HBQ_REASON	= $row[2];
					$Insert->type		= \Business\EmvExport::TYPE_SB;
					$Insert->DATEUNJOIN	= ( is_object($Date) ) ? $Date->format( 'Y-m-d H:i:s' ) : NULL;
					$Insert->DATEJOIN	= ( is_object($DateJ) ) ? $DateJ->format( 'Y-m-d H:i:s' ) : NULL;

					if( !$Insert->save() )
						return false;
				}

				// Suppression des softs bounces disparu :
				if( is_file($oldExport) )
				{
					$CsvOld = new CSVHelper( $oldExport );
					foreach( $CsvOld as $i => $oldRow )
					{
						if( $i == 0 ||count($oldRow) < 3 )
							continue;

						$isset = false;
						foreach( $CsvNew as $j => $newRow )
						{
							if( $j == 0 ||count($newRow) < 3 )
								continue;

							if( $oldRow[0] == $newRow[0] )
							{
								$isset = true;
								break;
							}
						}

						if( !$isset )
						{
							if( ($Insert = \Business\EmvExport::load($oldRow[0])) )
								$Insert->delete();
						}
					}
				}
			}
			else
				return false;

			return true;
		}
		catch( CDbException $e )
		{
			$this->error[] = $e->getMessage();
			return false;
		}
	}

	/**
	 * Genere l'export EMV, recupere l'export et l'insere en base de donnée
	 * Export Desabonné
	 * @return boolean
	 */
	public function insertDesabonne()
	{
		try
		{
			if( !isset($this->configEMV['seg_desabo_id']) || $this->configEMV['seg_desabo_id'] <= 0 )
				return '"seg_desabo_id" n\'est pas configuré';

			$newExport	= TMP_DIR.'/HBSB/'.\Yii::app()->params['porteur'].'/'.$this->configEMV['seg_desabo_id'].'.csv';
			$oldExport	= TMP_DIR.'/HBSB/'.\Yii::app()->params['porteur'].'/'.$this->configEMV['seg_desabo_id'].'.old.csv';

			if( is_file($newExport) )
				copy( $newExport, $oldExport );

			if( $this->createAndWaitExport( $this->configEMV['seg_desabo_id'], $this->configEMV['seg_desabo_id'].'.csv', EMVMemberAPI::UNJOIN_MEMBERS ) )
			{
				// Reactive la connection a SQL ( peut poser probleme si l'export prend trop de temps, voir var MySQL "wait_timeout" ) :
				\Yii::app()->db->init();

				$Csv = new CSVHelper( $newExport );
				foreach( $Csv as $i => $row )
				{
					\Yii::app()->db->setActive( true );

					if( $i == 0 ||count($row) < 3 )
						continue;

					if( !($Insert = \Business\EmvExport::load($row[0])) )
						$Insert	= new \Business\EmvExport();
					else
						continue;

					$Date				= DateTime::createFromFormat( 'd/m/Y H:i:s', $row[3] );
					$DateJ				= DateTime::createFromFormat( 'd/m/Y H:i:s', $row[4] );

					$Insert->EMAIL		= $row[0];
					$Insert->SOURCE		= $row[1];
					$Insert->HBQ_REASON	= $row[2];
					$Insert->type		= \Business\EmvExport::TYPE_DESABO;
					$Insert->DATEUNJOIN	= ( is_object($Date) ) ? $Date->format( 'Y-m-d H:i:s' ) : NULL;
					$Insert->DATEJOIN	= ( is_object($DateJ) ) ? $DateJ->format( 'Y-m-d H:i:s' ) : NULL;

					if( !$Insert->save() )
						return false;
				}
			}
			else
				return false;

			return true;
		}
		catch( CDbException $e )
		{
			$this->error[] = $e->getMessage();
			return false;
		}
	}

	/**
	 * Exporte un segment pour mettre a jour la DB sql
	 * @return boolean|string
	 */
	public function majProspectInDB()
	{
		try
		{
			if( !isset($this->configEMV['seg_majDB']) || $this->configEMV['seg_majDB'] <= 0 )
				return '"seg_majDB" n\'est pas configuré';

			$newExport	= TMP_DIR.'/HBSB/'.\Yii::app()->params['porteur'].'/'.$this->configEMV['seg_majDB'].'.csv';
			$oldExport	= TMP_DIR.'/HBSB/'.\Yii::app()->params['porteur'].'/'.$this->configEMV['seg_majDB'].'.old.csv';
			$fields		= 'EMAIL,LASTNAME,FIRSTNAME,TITLE,DATEOFBIRTH,SEGMENT,DATEJOIN';

			if( is_file($newExport) )
				copy( $newExport, $oldExport );

			if( $this->createAndWaitExport( $this->configEMV['seg_majDB'], $this->configEMV['seg_majDB'].'.csv', EMVMemberAPI::ACTIVE_MEMBERS, $fields ) )
			{
				// Reactive la connection a SQL ( peut poser probleme si l'export prend trop de temps, voir var MySQL "wait_timeout" ) :
				\Yii::app()->db->init();

				$DB				= \Yii::app()->db;
				$CSV			= new CSVHelper( $newExport );
				$colEmail		= 0;
				$colLastname	= 1;
				$colFirstname	= 2;
				$colTitle		= 3;
				$colBirth		= 4;
				$colSegment		= 5;
				$colDateJoin	= 6;
				$nbDoublon		= 0;
				$nbInsere		= 0;

				$CSV->next();

				foreach( $CSV as $row )
				{
					\Yii::app()->db->setActive( true );
					
					foreach( $row as $z => $val )
						$row[ $z ] = str_replace( '"', '', $val );

					$email		= isset($row[ $colEmail ]) ? $row[ $colEmail ] : false;
					$title		= isset($row[ $colTitle ]) ? $row[ $colTitle ] : -1;
					$firstName	= isset($row[ $colFirstname ]) ? $row[ $colFirstname ] : false;
					$lastName	= isset($row[ $colLastname ]) ? $row[ $colLastname ] : false;
					$birth		= isset($row[ $colBirth ]) && !empty($row[ $colBirth ]) ? DateTime::createFromFormat( 'd/m/Y H:i:s', $row[ $colBirth ] ) : false;
					$segment	= isset($row[ $colSegment ]) ? $row[ $colSegment ] : false;
					$dateJoin	= isset($row[ $colDateJoin ]) && !empty($row[ $colDateJoin ]) ? DateTime::createFromFormat( 'd/m/Y H:i:s', $row[ $colDateJoin ] ) : false;

					if( empty($email) )
						continue;

					$r		= 'SELECT ID, CreationDate FROM internaute WHERE Email = "'.$email.'";';
					$Res	= $DB->createCommand($r)->query();
					if( $Res->count() > 0 )
					{
						$nbDoublon++;

						foreach( $Res as $Row )
						{
							$DC = DateTime::createFromFormat( 'Y-m-d', $Row['CreationDate'] );
							if( is_object($dateJoin) && $DC->format('Y-m-d') != $dateJoin->format('Y-m-d') )
							{
								$r = 'UPDATE internaute SET CreationDate = "'.$dateJoin->format('Y-m-d').'" WHERE ID = "'.$Row['ID'].'";';
								if( $DB->createCommand($r)->execute() <= 0 )
									return $r;
							}
						}
					}
					else
					{
						$r = 'INSERT INTO internaute ( Civility, Firstname, Lastname, Birthday, Email, Source, Comment ) VALUES ( "'.$title.'", "'.$firstName.'", "'.$lastName.'", "'.( $birth ? $birth->format('Y-m-d H:i:s') : NULL ).'", "'.$email.'", "'.$segment.'", "majDB_auto" );';
						if( $DB->createCommand($r)->execute() <= 0 )
							return $r;

						$nbInsere++;
					}
				}

				
			}
			else
				return false;

			return true;
		}
		catch( CDbException $e )
		{
			$this->error[] = $e->getMessage();
			return false;
		}
	}
	
	
	/**
		Youssef HARRATI
		le: 02/06/2015
	*/
	//OPENED
	public function exportDataMarketingOpened($porteur,$folder)
	{
		$this->perPage = $this->getPerPage('ALL');
		$pages =  ceil($this->perPage/100);
		$output = "";
		$i = 0;
		if(!is_dir($folder.$porteur))
			mkdir($folder.$porteur,0777);
		$newExport	= $folder.$porteur.'/OPENED';
		$oldExport	= $folder.$porteur.'/OPENED_old';
		$output .= "<div><u style=\"color:green\">dossier distination:</u> $newExport</div><br /><br />";
		if(is_dir($newExport)){
			if(is_dir($oldExport))
				$this->deleteDirectory($oldExport);
			$this->copy_directory($newExport,$oldExport);
			usleep(100000);
			$this->deleteDirectory($newExport);
			mkdir($newExport,0777);
		}else
			mkdir($newExport,0777);
		
		$err = false;
		
		$campaigns = array();
		$details = "ID;CID;NAME;SEND_DATE;DATE;COUNT\n";
		for($i=1;$i<=$pages;$i++){
			try {
				$Res = $this->getSoap()->getExportableCampaigns(
						array(
							'token' => $this->getToken(),
							'perPage' => 100,
							'page' => $i
						)
				);
				$campaigns = $Res->return->campaigns->campaign;
			} catch (Exception $e) {
				$this->error[] = "getExportableCampaigns".$e->getMessage();
				return false;
			}
		}
		
		if (count($campaigns) == 0){
			$this->error[] = 'pas de campaign';
			return false;
		}
		else{
			foreach ($campaigns as $campaign) {
				if($campaign->type != 'TRIGGER')
					continue;
				$i++;
				$k = 0;
				$param = array(
					'token' => $this->getToken(),
					'campaignId' => $campaign->campaignId,
					'operationType' => 'OPENED',
					'fieldSelection' => 'EMAIL',
					'dedupFlag' => true,
					'dedupCriteria' => 'EMAIL',
					'keepFirst' => true,
					'fileFormat' => 'PIPE'
				);
				try{
					$Res = $this->getSoap()->createDownloadByCampaign($param);
					$downloadId = $Res->return;
					$FID = substr($campaign->name, 0, 3);
					if (substr($campaign->name, 0, 6) == $FID . ' CT') {
						$FID .= '_CT';
					}
					elseif (substr($campaign->name, 0, 6) == $FID . ' ct') {
						$FID .= '_ct';
					}
					$downloadStatus = '';
					$stop			= time() + 600;
					do {
						usleep(100000);

						$Res = $this->getSoap()->getDownloadStatus(
								array(
									'token' => $this->getToken(),
									'id' => $downloadId
								)
						);
						$downloadStatus = $Res->return;


						if (time() > $stop)
							return false;
					}
					while ($downloadStatus == 'VALIDATED' || $downloadStatus == 'RUNNING');/**/
					
					$writeIn = $newExport.'/'.$FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv';
					$k = $this->getFile($downloadId,$writeIn,$campaign->name);
					if($k >= 0)
						$output .= '<div><u style="color:green">'.$campaign->name.'</u> file: ' . $FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv' . ' created</div>';
					$details .= $campaign->triggerId.";".$campaign->campaignId.";".$campaign->name.";".date("Y-m-d", strtotime($campaign->sendDate)).";".date("Y-m-d").";".$k."\n";
					$output .= '<br />';
				}
				catch( Exception $e )
				{
					$output .= '<div><u style="color:red">'.$campaign->name.'</u> error: ' . $e->getMessage().'</div>';
					$this->error[] = $campaign->name.$e->getMessage();
					
				}
			}
			$fp = fopen($newExport.'/details.csv', 'w+');
			fwrite($fp, $details);
			fclose($fp);
		}
		$add = "<div><h2><center><u>Importation des taux d'ouverture d'emails pour toutes les campagnes</u></center></h2></div>";
		$add .= "<div><u style=\"color:green\">porteur:</u> $porteur</div>";
		$add .= '<div><u style="color:green">nombre de campagnes importées: </u> ' . $i.' campagnes</div>';
		$output = $add.$output;
		if(!$err)
			return $output;
		else
			return false;
	}
	
	//UNJOIN
	public function exportDataMarketingUnjoin($porteur,$folder)
	{
		$this->perPage = $this->getPerPage('ALL');
		$pages =  ceil($this->perPage/100);
		$output = "";
		$i = 0;
		if(!is_dir($folder.$porteur))
			mkdir($folder.$porteur,0777);
		$newExport	= $folder.$porteur.'/UNJOIN';
		$oldExport	= $folder.$porteur.'/UNJOIN_old';
		$output .= "<div><u style=\"color:green\">dossier distination:</u> $newExport</div><br /><br />";
		if(is_dir($newExport)){
			if(is_dir($oldExport))
				$this->deleteDirectory($oldExport);
			$this->copy_directory($newExport,$oldExport);
			usleep(100000);
			$this->deleteDirectory($newExport);
			mkdir($newExport,0777);
		}else
			mkdir($newExport,0777);
		
		$err = false;
		
		$campaigns = array();
		$details = "ID;CID;NAME;SEND_DATE;DATE;COUNT\n";
		for($i=1;$i<=$pages;$i++){
			try {
				$Res = $this->getSoap()->getExportableCampaigns(
						array(
							'token' => $this->getToken(),
							'perPage' => 100,
							'page' => $i
						)
				);
				$campaigns = $Res->return->campaigns->campaign;
			} catch (Exception $e) {
				$this->error[] = "getExportableCampaigns".$e->getMessage();
				return false;
			}
		}
		
		if (count($campaigns) == 0){
			$this->error[] = 'pas de campaign';
			return false;
		}
		else{
			foreach ($campaigns as $campaign) {
				if($campaign->type != 'TRIGGER')
					continue;
				$i++;
				$param = array(
					'token' => $this->getToken(),
					'campaignId' => $campaign->campaignId,
					'operationType' => 'UNJOIN',
					'fieldSelection' => 'EMAIL,DATEJOIN',
					'dedupFlag' => true,
					'dedupCriteria' => 'EMAIL',
					'keepFirst' => true,
					'fileFormat' => 'PIPE'
				);
				try{
					$Res = $this->getSoap()->createDownloadByCampaign($param);
					$downloadId = $Res->return;
					$FID = substr($campaign->name, 0, 3);
					if (substr($campaign->name, 0, 6) == $FID . ' CT') {
						$FID .= '_CT';
					}
					elseif (substr($campaign->name, 0, 6) == $FID . ' ct') {
						$FID .= '_ct';
					}
					$downloadStatus = '';
					$stop			= time() + 600;
					do {
						usleep(100000);

						$Res = $this->getSoap()->getDownloadStatus(
								array(
									'token' => $this->getToken(),
									'id' => $downloadId
								)
						);
						$downloadStatus = $Res->return;


						if (time() > $stop)
							return false;
					}
					while ($downloadStatus == 'VALIDATED' || $downloadStatus == 'RUNNING');/**/
					
					$writeIn = $newExport.'/'.$FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv';
					$k = $this->getFile($downloadId,$writeIn,$campaign->name);
					if($k >= 0)
						$output .= '<div><u style="color:green">'.$campaign->name.'</u> file: ' . $FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv' . ' created</div>';
					$details .= $campaign->triggerId.";".$campaign->campaignId.";".$campaign->name.";".date("Y-m-d", strtotime($campaign->sendDate)).";".date("Y-m-d").";".$k."\n";
					
					$output .= '<br />';
				}
				catch( Exception $e )
				{
					$output .= '<div><u style="color:red">'.$campaign->name.'</u> error: ' . $e->getMessage().'</div>';
					$this->error[] = $campaign->name.$e->getMessage();
					//$err = true;
				}
			}
			$fp = fopen($newExport.'/details.csv', 'w+');
			fwrite($fp, $details);
			fclose($fp);
		}
		$add = "<div><h2><center><u>Importation des taux d'ouverture d'emails pour toutes les campagnes</u></center></h2></div>";
		$add .= "<div><u style=\"color:green\">porteur:</u> $porteur</div>";
		$add .= '<div><u style="color:green">nombre de campagnes importées: </u> ' . $i.' campagnes</div>';
		$output = $add.$output;
		if(!$err)
			return $output;
		else
			return false;
	}
	
	//CLICK_DETAIL
	public function exportDataMarketingClick($porteur,$folder)
	{
		$this->perPage = $this->getPerPage('ALL');
		$pages =  ceil($this->perPage/100);
		$output = "";
		$i = 0;
		$count = 0;
		$err_count = 0;
		if(!is_dir($folder.$porteur))
			mkdir($folder.$porteur,0777);
		$newExport	= $folder.$porteur.'/CLICK_DETAIL';
		$oldExport	= $folder.$porteur.'/CLICK_DETAIL_old';
		$output .= "<div><u style=\"color:green\">dossier distination:</u> $newExport</div><br /><br />";
		if(is_dir($newExport)){
			if(is_dir($oldExport))
				$this->deleteDirectory($oldExport);
			$this->copy_directory($newExport,$oldExport);
			usleep(100000);
			$this->deleteDirectory($newExport);
			mkdir($newExport,0777);
		}else
			mkdir($newExport,0777);
		
		$err = false;
		
		$campaigns = array();
		$details = "ID;CID;NAME;SEND_DATE;DATE;COUNT\n";
		for($i=1;$i<=$pages;$i++){
			try {
				$Res = $this->getSoap()->getExportableCampaigns(
						array(
							'token' => $this->getToken(),
							'perPage' => 100,
							'page' => $i
						)
				);
				$campaigns = $Res->return->campaigns->campaign;
			} catch (Exception $e) {
				$this->error[] = "getExportableCampaigns".$e->getMessage();
				return false;
			}
		}
		
		if (count($campaigns) == 0){
			$this->error[] = 'pas de campaign';
			return false;
		}
		else{
			foreach ($campaigns as $campaign) {
				if($campaign->type != 'TRIGGER')
					continue;
				$i++;
				$count++;
				$param = array(
					'token' => $this->getToken(),
					'campaignId' => $campaign->campaignId,
					'operationType' => 'CLICKED',
					'fieldSelection' => 'EMAIL',
					'dedupFlag' => true,
					'dedupCriteria' => 'EMAIL',
					'keepFirst' => true,
					'fileFormat' => 'PIPE'
				);
				try{
					$Res = $this->getSoap()->createDownloadByCampaign($param);
					$downloadId = $Res->return;
					$FID = substr($campaign->name, 0, 3);
					if (substr($campaign->name, 0, 6) == $FID . ' CT') {
						$FID .= '_CT';
					}
					elseif (substr($campaign->name, 0, 6) == $FID . ' ct') {
						$FID .= '_ct';
					}
					$downloadStatus = '';
					$stop			= time() + 600;
					do {
						usleep(100000);

						$Res = $this->getSoap()->getDownloadStatus(
								array(
									'token' => $this->getToken(),
									'id' => $downloadId
								)
						);
						$downloadStatus = $Res->return;


						if (time() > $stop)
							return false;
					}
					while ($downloadStatus == 'VALIDATED' || $downloadStatus == 'RUNNING');/**/
					
					$writeIn = $newExport.'/'.$FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv';
					$k = $this->getFile($downloadId,$writeIn,$campaign->name);
					if($k >= 0)
						$output .= '<div><u style="color:green">'.$campaign->name.'</u> file: ' . $FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv' . ' created</div>';
					$details .= $campaign->triggerId.";".$campaign->campaignId.";".$campaign->name.";".date("Y-m-d", strtotime($campaign->sendDate)).";".date("Y-m-d").";".$k."\n";
					
					$output .= '<br />';
				}
				catch( Exception $e )
				{
					$output .= '<div><u style="color:red">'.$campaign->name.'</u> error: ' . $e->getMessage().'</div>';
					$this->error[] = $campaign->name.$e->getMessage();
					
				}
			}
			$fp = fopen($newExport.'/details.csv', 'w+');
			fwrite($fp, $details);
			fclose($fp);
		}
		$add = "<div><h2><center><u>Importation des taux d'ouverture d'emails pour toutes les campagnes</u></center></h2></div>";
		$add .= "<div><u style=\"color:green\">porteur:</u> $porteur</div>";
		$add .= '<div><u style="color:green">nombre de campagnes importées: </u> ' . $i.' campagnes</div>';
		$output = $add.$output;
		if(!$err)
			return $output;
		else
			return false;
	}
	
	//COMPLAINTS
	public function exportDataMarketingComplaints($porteur,$folder)
	{
		$this->perPage = $this->getPerPage('ALL');
		$pages =  ceil($this->perPage/100);
		$output = "";
		$i = 0;
		if(!is_dir($folder.$porteur))
			mkdir($folder.$porteur,0777);
		$newExport	= $folder.$porteur.'/COMPLAINTS';
		$oldExport	= $folder.$porteur.'/COMPLAINTS_old';
		$output .= "<div><u style=\"color:green\">dossier distination:</u> $newExport</div><br /><br />";
		if(is_dir($newExport)){
			if(is_dir($oldExport))
				$this->deleteDirectory($oldExport);
			$this->copy_directory($newExport,$oldExport);
			usleep(100000);
			$this->deleteDirectory($newExport);
			mkdir($newExport,0777);
		}else
			mkdir($newExport,0777);
		
		$err = false;
		
		$campaigns = array();
		$details = "ID;CID;NAME;SEND_DATE;DATE;COUNT\n";
		for($i=1;$i<=$pages;$i++){
			try {
				$Res = $this->getSoap()->getExportableCampaigns(
						array(
							'token' => $this->getToken(),
							'perPage' => 100,
							'page' => $i
						)
				);
				$campaigns = $Res->return->campaigns->campaign;
			} catch (Exception $e) {
				$this->error[] = "getExportableCampaigns".$e->getMessage();
				return false;
			}
		}
		
		if (count($campaigns) == 0){
			$this->error[] = 'pas de campaign';
			return false;
		}
		else{
			foreach ($campaigns as $campaign) {
				if($campaign->type != 'TRIGGER')
					continue;
				$i++;
				$param = array(
					'token' => $this->getToken(),
					'campaignId' => $campaign->campaignId,
					'operationType' => 'COMPLAINTS',
					'fieldSelection' => 'EMAIL',
					'dedupFlag' => true,
					'dedupCriteria' => 'EMAIL',
					'keepFirst' => true,
					'fileFormat' => 'PIPE'
				);
				try{
					$Res = $this->getSoap()->createDownloadByCampaign($param);
					$downloadId = $Res->return;
					$FID = substr($campaign->name, 0, 3);
					if (substr($campaign->name, 0, 6) == $FID . ' CT') {
						$FID .= '_CT';
					}
					elseif (substr($campaign->name, 0, 6) == $FID . ' ct') {
						$FID .= '_ct';
					}
					$downloadStatus = '';
					$stop			= time() + 600;
					do {
						usleep(100000);

						$Res = $this->getSoap()->getDownloadStatus(
								array(
									'token' => $this->getToken(),
									'id' => $downloadId
								)
						);
						$downloadStatus = $Res->return;


						if (time() > $stop)
							return false;
					}
					while ($downloadStatus == 'VALIDATED' || $downloadStatus == 'RUNNING');/**/
					
					$writeIn = $newExport.'/'.$FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv';
					$k = $this->getFile($downloadId,$writeIn,$campaign->name);
					if($k >= 0)
						$output .= '<div><u style="color:green">'.$campaign->name.'</u> file: ' . $FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv' . ' created</div>';
					$details .= $campaign->triggerId.";".$campaign->campaignId.";".$campaign->name.";".date("Y-m-d", strtotime($campaign->sendDate)).";".date("Y-m-d").";".$k."\n";
					
					$output .= '<br />';
				}
				catch( Exception $e )
				{
					$output .= '<div><u style="color:red">'.$campaign->name.'</u> error: ' . $e->getMessage().'</div>';
					$this->error[] = $campaign->name.$e->getMessage();
					//$err = true;
				}
			}
			$fp = fopen($newExport.'/details.csv', 'w+');
			fwrite($fp, $details);
			fclose($fp);
		}
		$add = "<div><h2><center><u>Importation des taux d'ouverture d'emails pour toutes les campagnes</u></center></h2></div>";
		$add .= "<div><u style=\"color:green\">porteur:</u> $porteur</div>";
		$add .= '<div><u style="color:green">nombre de campagnes importées: </u> ' . $i.' campagnes</div>';
		$output = $add.$output;
		if(!$err)
			return $output;
		else
			return false;
	}
	
	//SBOUNCED
	public function exportDataMarketingSB($porteur,$folder)
	{
		$this->perPage = $this->getPerPage('ALL');
		$pages =  ceil($this->perPage/100);
		$output = "";
		$i = 0;
		if(!is_dir($folder.$porteur))
			mkdir($folder.$porteur,0777);
		$newExport	= $folder.$porteur.'/SBOUNCED';
		$oldExport	= $folder.$porteur.'/SBOUNCED_old';
		$output .= "<div><u style=\"color:green\">dossier distination:</u> $newExport</div><br /><br />";
		if(is_dir($newExport)){
			if(is_dir($oldExport))
				$this->deleteDirectory($oldExport);
			$this->copy_directory($newExport,$oldExport);
			usleep(100000);
			$this->deleteDirectory($newExport);
			mkdir($newExport,0777);
		}else
			mkdir($newExport,0777);
		
		$err = false;
		
		$campaigns = array();
		$details = "ID;CID;NAME;SEND_DATE;DATE;COUNT\n";
		for($i=1;$i<=$pages;$i++){
			try {
				$Res = $this->getSoap()->getExportableCampaigns(
						array(
							'token' => $this->getToken(),
							'perPage' => 100,
							'page' => $i
						)
				);
				$campaigns = $Res->return->campaigns->campaign;
			} catch (Exception $e) {
				$this->error[] = "getExportableCampaigns".$e->getMessage();
				return false;
			}
		}
		
		if (count($campaigns) == 0){
			$this->error[] = 'pas de campaign';
			return false;
		}
		else{
			foreach ($campaigns as $campaign) {
				if($campaign->type != 'TRIGGER')
					continue;
				$i++;
				$param = array(
					'token' => $this->getToken(),
					'campaignId' => $campaign->campaignId,
					'operationType' => 'SBOUNCED',
					'fieldSelection' => 'EMAIL,DATEJOIN',
					'dedupFlag' => true,
					'dedupCriteria' => 'EMAIL',
					'keepFirst' => true,
					'fileFormat' => 'PIPE'
				);
				try{
					$Res = $this->getSoap()->createDownloadByCampaign($param);
					$downloadId = $Res->return;
					$FID = substr($campaign->name, 0, 3);
					if (substr($campaign->name, 0, 6) == $FID . ' CT') {
						$FID .= '_CT';
					}
					elseif (substr($campaign->name, 0, 6) == $FID . ' ct') {
						$FID .= '_ct';
					}
					$downloadStatus = '';
					$stop			= time() + 600;
					do {
						usleep(100000);

						$Res = $this->getSoap()->getDownloadStatus(
								array(
									'token' => $this->getToken(),
									'id' => $downloadId
								)
						);
						$downloadStatus = $Res->return;


						if (time() > $stop)
							return false;
					}
					while ($downloadStatus == 'VALIDATED' || $downloadStatus == 'RUNNING');/**/
					
					$writeIn = $newExport.'/'.$FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv';
					$k = $this->getFile($downloadId,$writeIn,$campaign->name);
					if($k >= 0)
						$output .= '<div><u style="color:green">'.$campaign->name.'</u> file: ' . $FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv' . ' created</div>';
					$details .= $campaign->triggerId.";".$campaign->campaignId.";".$campaign->name.";".date("Y-m-d", strtotime($campaign->sendDate)).";".date("Y-m-d").";".$k."\n";
					
					$output .= '<br />';
				}
				catch( Exception $e )
				{
					$output .= '<div><u style="color:red">'.$campaign->name.'</u> error: ' . $e->getMessage().'</div>';
					$this->error[] = $campaign->name.$e->getMessage();
					
				}
			}
			$fp = fopen($newExport.'/details.csv', 'w+');
			fwrite($fp, $details);
			fclose($fp);
		}
		$add = "<div><h2><center><u>Importation des taux d'ouverture d'emails pour toutes les campagnes</u></center></h2></div>";
		$add .= "<div><u style=\"color:green\">porteur:</u> $porteur</div>";
		$add .= '<div><u style="color:green">nombre de campagnes importées: </u> ' . $i.' campagnes</div>';
		$output = $add.$output;
		if(!$err)
			return $output;
		else
			return false;
	}
	
	//HBOUNCED
	public function exportDataMarketingHB($porteur,$folder)
	{
		$this->perPage = $this->getPerPage('ALL');
		$pages =  ceil($this->perPage/100);
		$output = "";
		$i = 0;
		if(!is_dir($folder.$porteur))
			mkdir($folder.$porteur,0777);
		$newExport	= $folder.$porteur.'/HBOUNCED';
		$oldExport	= $folder.$porteur.'/HBOUNCED_old';
		$output .= "<div><u style=\"color:green\">dossier distination:</u> $newExport</div><br /><br />";
		if(is_dir($newExport)){
			if(is_dir($oldExport))
				$this->deleteDirectory($oldExport);
			$this->copy_directory($newExport,$oldExport);
			usleep(100000);
			$this->deleteDirectory($newExport);
			mkdir($newExport,0777);
		}else
			mkdir($newExport,0777);
		
		$err = false;
		
		$campaigns = array();
		$details = "ID;CID;NAME;SEND_DATE;DATE;COUNT\n";
		for($i=1;$i<=$pages;$i++){
			try {
				$Res = $this->getSoap()->getExportableCampaigns(
						array(
							'token' => $this->getToken(),
							'perPage' => 100,
							'page' => $i
						)
				);
				$campaigns = $Res->return->campaigns->campaign;
			} catch (Exception $e) {
				$this->error[] = "getExportableCampaigns ".$e->getMessage();
				return false;
			}
		}
		
		if (count($campaigns) == 0){
			$this->error[] = 'pas de campaign';
			return false;
		}
		else{
			foreach ($campaigns as $campaign) {
				if($campaign->type != 'TRIGGER')
					continue;
				$i++;
				$param = array(
					'token' => $this->getToken(),
					'campaignId' => $campaign->campaignId,
					'operationType' => 'HBOUNCED',
					'fieldSelection' => 'EMAIL,DATEJOIN',
					'dedupFlag' => true,
					'dedupCriteria' => 'EMAIL',
					'keepFirst' => true,
					'fileFormat' => 'PIPE'
				);
				try{
					$Res = $this->getSoap()->createDownloadByCampaign($param);
					$downloadId = $Res->return;
					$FID = substr($campaign->name, 0, 3);
					if (substr($campaign->name, 0, 6) == $FID . ' CT') {
						$FID .= '_CT';
					}
					elseif (substr($campaign->name, 0, 6) == $FID . ' ct') {
						$FID .= '_ct';
					}
					$downloadStatus = '';
					$stop			= time() + 600;
					do {
						usleep(100000);

						$Res = $this->getSoap()->getDownloadStatus(
								array(
									'token' => $this->getToken(),
									'id' => $downloadId
								)
						);
						$downloadStatus = $Res->return;


						if (time() > $stop)
							return false;
					}
					while ($downloadStatus == 'VALIDATED' || $downloadStatus == 'RUNNING');/**/
					
					$writeIn = $newExport.'/'.$FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv';
					$k = $this->getFile($downloadId,$writeIn,$campaign->name);
					if($k >= 0)
						$output .= '<div><u style="color:green">'.$campaign->name.'</u> file: ' . $FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv' . ' created</div>';
					$details .= $campaign->triggerId.";".$campaign->campaignId.";".$campaign->name.";".date("Y-m-d", strtotime($campaign->sendDate)).";".date("Y-m-d").";".$k."\n";
					
					$output .= '<br />';
				}
				catch( Exception $e )
				{
					$output .= '<div><u style="color:red">'.$campaign->name.'</u> error: ' . $e->getMessage().'</div>';
					$this->error[] = $campaign->name.$e->getMessage();
					//$err = true;
				}
			}
			$fp = fopen($newExport.'/details.csv', 'w+');
			fwrite($fp, $details);
			fclose($fp);
		}
		$add = "<div><h2><center><u>Importation des taux d'ouverture d'emails pour toutes les campagnes</u></center></h2></div>";
		$add .= "<div><u style=\"color:green\">porteur:</u> $porteur</div>";
		$add .= '<div><u style="color:green">nombre de campagnes importées: </u> ' . $i.' campagnes</div>';
		$output = $add.$output;
		if(!$err)
			return $output;
		else
			return false;
	}
	
	//HBOUNCED_VG
	public function exportDataMarketingHB_VG($porteur,$folder,$fid=false)
	{
		$this->perPage = $this->getPerPage('ALL');
		$pages =  ceil($this->perPage/100);
		$output = "";
		$i = 0;
		if(!is_dir($folder.$porteur))
			mkdir($folder.$porteur,0777);
		$ext = "";
		if($fid)
			$ext = "_FID";
		$newExport	= $folder.$porteur.'/HBOUNCED_VG'.$ext;
		$oldExport	= $folder.$porteur.'/HBOUNCED_VG'.$ext.'_old';
		$output .= "<div><u style=\"color:green\">dossier distination:</u> $newExport</div><br /><br />";
		if(is_dir($newExport)){
			if(is_dir($oldExport))
				$this->deleteDirectory($oldExport);
			$this->copy_directory($newExport,$oldExport);
			usleep(100000);
			$this->deleteDirectory($newExport);
			mkdir($newExport,0777);
		}else
			mkdir($newExport,0777);
		
		$err = false;
		
		$campaigns = [];
		$details = "ID;CID;NAME;SEND_DATE;DATE;COUNT\n";
		for($i=1;$i<=$pages;$i++){
			try {
				$Res = $this->getSoap()->getExportableCampaigns(
						array(
							'token' => $this->getToken(),
							'perPage' => 100,
							'page' => $i
						)
				);
				if(!is_array($Res->return->campaigns->campaign))
					$campaigns[] = $Res->return->campaigns->campaign;
				else
					$campaigns = array_merge($campaigns, $Res->return->campaigns->campaign);
				
			} catch (Exception $e) {
				$this->error[] = "getExportableCampaigns ".$e->getMessage();
				return false;
			}
		}
		
		$ids = [];
		$triggers = [4613,7041,4603,3743,6069,6066,446,51441,60646,60642,60501,
				1000023845,1000028545,1000028310,1000028306,1100564285,4181,3363,3490,
				7341,37307,35248,29829,35105,35106,10858,412,382,478,462,451,22003,
				4921,4920,4681,2235,686
		];
		
		
		if (count($campaigns) == 0){
			$this->error[] = 'pas de campaign';
			return false;
		}
		else{
			foreach ($campaigns as $campaign) {
				if($campaign->type != 'TRIGGER')
					continue;
				
				if(!in_array($campaign->triggerId, $triggers))
					continue;
				
				$i++;
				$param = array(
					'token' => $this->getToken(),
					'campaignId' => $campaign->campaignId,
					'operationType' => 'HBOUNCED',
					'fieldSelection' => 'EMAIL,DATEJOIN',
					'dedupFlag' => true,
					'dedupCriteria' => 'EMAIL',
					'keepFirst' => true,
					'fileFormat' => 'PIPE'
				);
				try{
					$Res = $this->getSoap()->createDownloadByCampaign($param);
					$downloadId = $Res->return;
					$FID = substr($campaign->name, 0, 3);
					if (substr($campaign->name, 0, 6) == $FID . ' CT') {
						$FID .= '_CT';
					}
					elseif (substr($campaign->name, 0, 6) == $FID . ' ct') {
						$FID .= '_ct';
					}
					$downloadStatus = '';
					$stop			= time() + 600;
					do {
						usleep(100000);

						$Res = $this->getSoap()->getDownloadStatus(
								array(
									'token' => $this->getToken(),
									'id' => $downloadId
								)
						);
						$downloadStatus = $Res->return;


						if (time() > $stop)
							return false;
					}
					while ($downloadStatus == 'VALIDATED' || $downloadStatus == 'RUNNING');/**/
					$f = preg_replace('/[^A-Za-z0-9\-_]/', ' ', $campaign->name);
					$writeIn = $newExport.'/'.$f.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv';
					$k = $this->getFile($downloadId,$writeIn,$campaign->name);
					if($k >= 0)
						$output .= '<div><u style="color:green">'.$campaign->name.'</u> file: ' . $f.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv' . ' created</div>';
					$details .= $campaign->triggerId.";".$campaign->campaignId.";".$campaign->name.";".date("Y-m-d", strtotime($campaign->sendDate)).";".date("Y-m-d").";".$k."\n";
					
					$output .= '<br />';
				}
				catch( Exception $e )
				{
					$output .= '<div><u style="color:red">'.$campaign->name.'</u> error: ' . $e->getMessage().'</div>';
					$this->error[] = $campaign->name.$e->getMessage();
					
				}
			}
			$fp = fopen($newExport.'/details.csv', 'w+');
			fwrite($fp, $details);
			fclose($fp);
		}
		$add = "<div><h2><center><u>Importation des taux d'ouverture d'emails pour toutes les campagnes</u></center></h2></div>";
		$add .= "<div><u style=\"color:green\">porteur:</u> $porteur</div>";
		$add .= '<div><u style="color:green">nombre de campagnes importées: </u> ' . $i.' campagnes</div>';
		$output = $add.$output;
		if(!$err)
			return $output;
		else
			return false;
	}
	
	//DELIVERED
	public function exportDataMarketingDelivered($porteur,$folder)
	{
		$this->perPage = $this->getPerPage('ALL');
		$pages =  ceil($this->perPage/100);
		$output = "";
		$i = 0;
		if(!is_dir($folder.$porteur))
			mkdir($folder.$porteur,0777);
		$newExport	= $folder.$porteur.'/DELIVERED';
		$oldExport	= $folder.$porteur.'/DELIVERED_old';
		$output .= "<div><u style=\"color:green\">dossier distination:</u> $newExport</div><br /><br />";
		if(is_dir($newExport)){
			if(is_dir($oldExport))
				$this->deleteDirectory($oldExport);
			$this->copy_directory($newExport,$oldExport);
			usleep(100000);
			$this->deleteDirectory($newExport);
			mkdir($newExport,0777);
		}else
			mkdir($newExport,0777);
		
		$err = false;
		
		$campaigns = array();
		$details = "ID;CID;NAME;SEND_DATE;DATE;COUNT\n";
		for($i=1;$i<=$pages;$i++){
			try {
				$Res = $this->getSoap()->getExportableCampaigns(
						array(
							'token' => $this->getToken(),
							'perPage' => 100,
							'page' => $i
						)
				);
				$campaigns = $Res->return->campaigns->campaign;
			} catch (Exception $e) {
				$this->error[] = "getExportableCampaigns ".$e->getMessage();
				return false;
			}
		}
		
		if (count($campaigns) == 0){
			$this->error[] = 'pas de campaign';
			return false;
		}
		else{
			foreach ($campaigns as $campaign) {
				if($campaign->type != 'TRIGGER')
					continue;
				$i++;
				$k = 0;
				$fileContent = "";
				$FID = substr($campaign->name, 0, 3);
				if (substr($campaign->name, 0, 6) == $FID . ' CT') {
					$FID .= '_CT';
				} elseif (substr($campaign->name, 0, 6) == $FID . ' ct') {
					$FID .= '_ct';
				}
				//OPENED
				$param = array(
					'token' => $this->getToken(),
					'campaignId' => $campaign->campaignId,
					'operationType' => 'OPENED',
					'fieldSelection' => 'EMAIL',
					'dedupFlag' => true,
					'dedupCriteria' => 'EMAIL',
					'keepFirst' => true,
					'fileFormat' => 'PIPE'
				);
				try{
					$Res = $this->getSoap()->createDownloadByCampaign($param);
					$downloadId = $Res->return;
					$FID = substr($campaign->name, 0, 3);
					
					$downloadStatus = '';
					$stop			= time() + 600;
					do {
						usleep(100000);

						$Res = $this->getSoap()->getDownloadStatus(
								array(
									'token' => $this->getToken(),
									'id' => $downloadId
								)
						);
						$downloadStatus = $Res->return;


						if (time() > $stop)
							return false;
					}
					while ($downloadStatus == 'VALIDATED' || $downloadStatus == 'RUNNING');/**/
					
					$lines = preg_split("/\r\n|\n|\r/", $this->getFile($downloadId,false,$campaign->name));
					for ($index = 0; $index < count($lines); $index++) {
						if ($index == 0) {
							$lines[0] = 'FID|' . $lines[0];
						} else {
							if ($lines[$index] != "") {
								$k++;
								$lines[$index] = $campaign->name . '|' . $lines[$index];
							}
						}
					}
					$fileContent .= implode("\r\n", $lines);
				}
				catch( Exception $e )
				{
					$output .= '<div><u style="color:red">'.$campaign->name.'</u> error: ' . $e->getMessage().'</div>';
					$this->error[] = $campaign->name.$e->getMessage();
					
				}
				
				//NOT_OPENED
				$param = array(
					'token' => $this->getToken(),
					'campaignId' => $campaign->campaignId,
					'operationType' => 'NOT_OPENED',
					'fieldSelection' => 'EMAIL',
					'dedupFlag' => true,
					'dedupCriteria' => 'EMAIL',
					'keepFirst' => true,
					'fileFormat' => 'PIPE'
				);
				try{
					$Res = $this->getSoap()->createDownloadByCampaign($param);
					$downloadId = $Res->return;
					$FID = substr($campaign->name, 0, 3);
					
					$downloadStatus = '';
					$stop			= time() + 600;
					do {
						usleep(100000);

						$Res = $this->getSoap()->getDownloadStatus(
								array(
									'token' => $this->getToken(),
									'id' => $downloadId
								)
						);
						$downloadStatus = $Res->return;


						if (time() > $stop)
							return false;
					}
					while ($downloadStatus == 'VALIDATED' || $downloadStatus == 'RUNNING');/**/
					
					$lines = preg_split("/\r\n|\n|\r/", $this->getFile($downloadId,false,$campaign->name));
					for ($index = 0; $index < count($lines); $index++) {
						if ($index == 0) {
							$lines[0] = '';
						} else {
							if ($lines[$index] != "") {
								$k++;
								$lines[$index] = $campaign->name . '|' . $lines[$index];
							}
						}
					}
					$fileContent .= implode("\r\n", $lines);
				}
				catch( Exception $e )
				{
					$output .= '<div><u style="color:red">'.$campaign->name.'</u> error: ' . $e->getMessage().'</div>';
					$this->error[] = $campaign->name.$e->getMessage();
					
				}
				$writeIn = $newExport.'/'.$FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv';
				
				if (!$fp = fopen($writeIn, 'w+')) {
					$output .= '<div><u style="color:red">'.$campaign->name.'</u> Impossible de creer le fichier: ' . $FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv' . ' created</div>';
				}
				$output .= '<div><u style="color:green">'.$campaign->name.'</u> file: ' . $FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv' . ' created</div>';
				fwrite($fp, $fileContent);
				fclose($fp);
				chmod($writeIn, 0777);
				$output .= "<br />";
				$details .= $campaign->triggerId.";".$campaign->campaignId.";".$campaign->name.";".date("Y-m-d", strtotime($campaign->sendDate)).";".date("Y-m-d").";".$k."\n";
			}
			$fp = fopen($newExport.'/details.csv', 'w+');
			fwrite($fp, $details);
			fclose($fp);
		}
		$add = "<div><h2><center><u>Importation des taux d'ouverture d'emails pour toutes les campagnes</u></center></h2></div>";
		$add .= "<div><u style=\"color:green\">porteur:</u> $porteur</div>";
		$add .= '<div><u style="color:green">nombre de campagnes importées: </u> ' . $i.' campagnes</div>';
		$output = $add.$output;
		if(!$err)
			return $output;
		else
			return false;
	}
	
	//QUARANTINED
	public function exportDataMarketingQuarantined($porteur,$folder, $conf)
	{
		$this->perPage = $this->getPerPage('ALL');
		$pages =  ceil($this->perPage/100);
		$output = "";
		$i = 0;
		
		if(!is_dir($folder.$porteur))
			mkdir($folder.$porteur,0777);
		$newExport	= $folder.$porteur.'/QUARANTINED';
		$oldExport	= $folder.$porteur.'/QUARANTINED_old';
		$output .= "<div><u style=\"color:green\">dossier distination:</u> $newExport</div><br /><br />";
		if(is_dir($newExport)){
			if(is_dir($oldExport))
				$this->deleteDirectory($oldExport);
			$this->copy_directory($newExport,$oldExport);
			usleep(100000);
			$this->deleteDirectory($newExport);
			mkdir($newExport,0777);
		}else
			mkdir($newExport,0777);
		
		$err = false;
		
		$campaigns = [];
		$segments = [];
		$mailingLists = [];
		$details = "ID;CID;NAME;SEND_DATE;DATE;COUNT\n";
		
		//chargement des segments
		for($i=1;$i<=$pages;$i++){
			try {
				$ResCcmd = $conf[0]->getSegmentSummaryList( array( 'token' => $conf[1], 'listOptionsEntity' => array( 'pageSize' => 100, 'page' => $i ) ) );
				$segments = $ResCcmd->return->segmentSummaryList->segmentSummary;
			} 
			catch (Exception $e) {
				$this->error[] = "getSegmentSummaryList ".$e->getMessage();
				return false;
			}
		}
		

		for($i=1;$i<=$pages;$i++){
			try {
				$Res = $this->getSoap()->getExportableCampaigns( array( 'token' => $this->getToken(), 'perPage' => 100, 'page' => $i ) );
				$campaigns = $Res->return->campaigns->campaign;
			} catch (Exception $e) {
				$this->error[] = "getExportableCampaigns ".$e->getMessage();
				return false;
			}
		}
		
		foreach ($campaigns as $campaign) {
			if ($campaign->type != 'TRIGGER')
				continue;

			$FID = substr($campaign->name, 0, 3);
			$ct = false;
			if (strpos($campaign->name, 'CT') || strpos($campaign->name, 'ct')) {
				$ct = true;
			}

			foreach ($segments as $key => $segment) {
				$FID_seg = substr($segment->name, 0, 3);
				if ($FID == 'liv' || $FID == 'ldv') {
					if ($campaign->name == $segment->name) {
						if (!array_key_exists($segment->segmentId, $mailingLists)) {
							$mailingLists[$segment->segmentId] = array('segmentId' => $segment->segmentId,
								'name' => $segment->name,
								'campaign' => $campaign->name,
								'campaignId' => $campaign->campaignId,
								'sendDate' => $campaign->sendDate,
								'triggerId' => $campaign->triggerId
							);
							$i++;
						}
					}
					continue;
				}
				if ($ct) {
					if ((strtolower($FID) == strtolower($FID_seg)) && (strpos($segment->name, 'CT') || strpos($segment->name, 'ct'))) {
						if (!array_key_exists($segment->segmentId, $mailingLists)) {
							$mailingLists[$segment->segmentId] = array(
								'segmentId' => $segment->segmentId,
								'name' => $segment->name,
								'campaign' => $campaign->name,
								'campaignId' => $campaign->campaignId,
								'sendDate' => $campaign->sendDate,
								'triggerId' => $campaign->triggerId
							);
						}
					}
				}
				else {
					if ((strtolower($FID) == strtolower($FID_seg)) && (!strpos($segment->name, 'CT') && !strpos($segment->name, 'ct'))) {
						if (!array_key_exists($segment->segmentId, $mailingLists)) {
							$mailingLists[$segment->segmentId] = array(
								'segmentId' => $segment->segmentId,
								'name' => $segment->name,
								'campaign' => $campaign->name,
								'campaignId' => $campaign->campaignId,
								'sendDate' => $campaign->sendDate,
								'triggerId' => $campaign->triggerId
							);
						}
					}
				}
			}
		}
		
		if (count($campaigns) == 0){
			$this->error[] = 'pas de campaign';
			return false;
		}
		else{
			foreach ($mailingLists as $segment) {
				$i++;
				$param = array(
					'token' => $this->getToken(),
					'mailinglistId' => $segment['segmentId'],
					'operationType' => 'QUARANTINED_MEMBERS',
					'fieldSelection' => 'EMAIL,DATEJOIN',
					'dedupFlag' => true,
					'dedupCriteria' => 'EMAIL',
					'keepFirst' => true,
					'fileFormat' => 'PIPE'
				);
				try{
					$Res = $this->getSoap()->createDownloadByMailinglist($param);
					$downloadId = $Res->return;
					$FID = substr($segment['name'], 0, 3);
					if (strpos($segment['name'], 'CT') || strpos($segment['name'], 'ct')) {
						$FID .= '_ct';
					}
					$downloadStatus = '';
					$stop			= time() + 600;
					do {
						usleep(100000);

						$Res = $this->getSoap()->getDownloadStatus(
								array(
									'token' => $this->getToken(),
									'id' => $downloadId
								)
						);
						$downloadStatus = $Res->return;


						if (time() > $stop)
							return false;
					}
					while ($downloadStatus == 'VALIDATED' || $downloadStatus == 'RUNNING');/**/
					
					$writeIn = $newExport.'/'.$FID.'_'.$segment['campaignId'].'_'.date("Y-m-d").'.csv';
					//$writeIn = $newExport.'/'.$segment['name'].'_'.$segment['campaignId'].'_'.date("Y-m-d").'.csv';
					$k = $this->getFile($downloadId,$writeIn,$segment['name']);
					if($k >= 0)
						$output .= '<div><u style="color:green">'.$segment['name'].'</u> file: ' . $FID.'_'.$campaign->campaignId.'_'.date("Y-m-d").'.csv' . ' created</div>';
					$details .= $segment['triggerId'].";".$segment['campaignId'].";".$segment['name'].";".date("Y-m-d", strtotime($segment['sendDate'])).";".date("Y-m-d").";".$k."\n";
					
					$output .= '<br />';
				}
				catch( Exception $e )
				{
					$output .= '<div><u style="color:red">'.$segment['name'].'</u> error: ' . $e->getMessage().'</div>';
					$this->error[] = $campaign->name.$e->getMessage();
					//$err = true;
				}
			}
			$fp = fopen($newExport.'/details.csv', 'w+');
			fwrite($fp, $details);
			fclose($fp);
		}
		$add = "<div><h2><center><u>Importation des utilisateurs QUARANTINED</u></center></h2></div>";
		$add .= "<div><u style=\"color:green\">porteur:</u> $porteur</div>";
		$add .= '<div><u style="color:green">nombre de campagnes importées: </u> ' . $i.' campagnes</div>';
		$output = $add.$output;
		if(!$err)
			return $output;
		else
			return false;
	}
	
	//NEW
	public function exportDataMarketingNew($porteur,$conf)
	{
		$this->perPage = $this->getPerPage('ALL');
		$err = false;
		$pages =  ceil($this->perPage/100);
		$campaigns = array();
		for($i=1;$i<=$pages;$i++){
			try {
				$Res = $this->getSoap()->getExportableCampaigns(
					array( 'token' => $this->getToken(), 'perPage' => 100, 'page' => $i )
				);
				$campaigns = $Res->return->campaigns->campaign;
			} catch (Exception $e) {
				$err = true;
				print_r($e->detail);die;
				$this->error[] = "getExportableCampaigns ".$e->getMessage();
			}
		}
		$i = 0;
		$return = [];
		
		foreach ($campaigns as $campaign) {
			if(!is_object($campaign) || $campaign->type != 'TRIGGER')
				continue;
			try {
				$param = array( 'token' => $conf[1], 'campaignId' => $campaign->campaignId );
				$Res = $conf[0]->getGlobalReportByCampaignId($param);
				$FID = substr($campaign->name, 0, 3);
				if (substr($campaign->name, 0, 6) == $FID . ' CT') {
					$FID .= '_CT';
				}
				elseif (substr($campaign->name, 0, 6) == $FID . ' ct') {
					$FID .= '_ct';
				}
				
				$F = substr($campaign->name, 0, 3);
				$ct = 0;
				if (substr($campaign->name, 0, 6) == $F . ' CT') {
					$ct = 1;
				} elseif (substr($campaign->name, 0, 6) == $F . ' ct') {
					$ct = 1;
				}
				
				$return[$i]['campagn_id'] = $campaign->campaignId;
				$return[$i]['name'] = $campaign->name;
				$return[$i]['trigger_id'] = $campaign->triggerId;
				$return[$i]['fid'] = $FID;
				$return[$i]['isCT'] = $ct;
				$return[$i]['import_date'] = date('Y-m-d');
				$return[$i]['send_date'] = explode('T',$Res->return->sendDate)[0];
				$return[$i]['opened'] = $Res->return->nbOpened;
				$return[$i]['click_detail'] = $Res->return->nbClickers;
				$return[$i]['hbounced'] = $Res->return->nbHardBounces;
				$return[$i]['unjoin'] = $Res->return->nbUnjoined;
				$return[$i]['sbounced'] = $Res->return->nbSoftBounces;
				$return[$i]['complaints'] = $Res->return->nbComplaints;
				$return[$i]['delivered'] = $Res->return->nbDelivered;
				$return[$i]['quarantined'] = 0;
				$i++;
			} catch (Exception $e) {
				$err = true;
				$this->error[] = "Campaign: ".$campaign->name.", Error: ".$e->getMessage();
			}
		}
		
		$txt = "<div><u style=\"color:green\">porteur:</u> $porteur</div>";
		$txt .= '<div><u style="color:green">nombre de campagnes importées: </u> ' . $i.' campagnes</div>';
		if(!$err)
			return array($return,'ok',$txt);
		else
			return array($return,'ko',$this->error);
	}
	
	/******************** Helpers ***************/
	public function getFile( $id, $writeIn = false, $campaign ){
		try
		{
			$Res = $this->getSoap()->getDownloadFile( array( 'token' => $this->getToken(), 'id' => $id ) );
		}
		catch( Exception $E )
		{
			$this->error[] = $E->detail;
			return false;
		}

		switch( $Res->return->fileStatus )
		{
			case 'OK' :
				if( !$writeIn )
					return $Res->return->fileContent;
				else
				{
					if( !$fp = fopen( $writeIn, 'w+' ) ){
						return false;
					}
					$k = 0;
					$exists = [];
					$lines = preg_split("/\r\n|\n|\r/", $Res->return->fileContent);
					for ($index = 0; $index < count($lines); $index++) {
						if ($index == 0) {
							$lines[0] = 'FID|'.$lines[0];
						}
						else{
							if($lines[$index] != ""){
								if(!in_array($lines[$index],$exists)){
									$k++;
									$exists[]=$lines[$index];
								}
								$lines[$index] = $campaign.'|'.$lines[$index];
							}
						}
					}
					$fileContent = implode("\r\n", $lines);
					
					fwrite( $fp, $fileContent );
					fclose( $fp );
					chmod($writeIn, 0777);

					return $k;
				}
				break;

			case 'NO_DATA' :
				return '<div style="color:red">fichier vide</div>';
				break;

			case 'NOT_YET_READY' :
				echo '<div style="color:red">RUNNING</div>';
				break;

			case 'ERROR' :
				echo '<div style="color:red">ERROR</div>';
				break;
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
	
	private function copy_directory( $source, $destination ) {
        if ( is_dir( $source ) ) {
        @mkdir( $destination );
        $directory = dir( $source );
        while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
            if ( $readdirectory == '.' || $readdirectory == '..' ) {
                continue;
            }
            $PathDir = $source . '/' . $readdirectory; 
            if ( is_dir( $PathDir ) ) {
                $this->copy_directory( $PathDir, $destination . '/' . $readdirectory );
                continue;
            }
            copy( $PathDir, $destination . '/' . $readdirectory );
        }

        $directory->close();
        }else {
        copy( $source, $destination );
        }
    }
	
	public function getPerPage($number){
		if($number=='ALL'){
			try {
				$Res = $this->getSoap()->getExportableCampaigns(
						array(
							'token' => $this->getToken(),
							'perPage' => 100,
							'page' => 1
						)
				);
				return $Res->return->nbTotalItems;
			} catch (Exception $e) {
				return 100;
			}
		}
		return 100;
	}
	
}

?>