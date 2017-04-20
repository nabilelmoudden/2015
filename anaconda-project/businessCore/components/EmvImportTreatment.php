<?php

/**
 * Description of HardSoftBounce
 *
 * @author JulienL
 */
class EmvImportTreatment extends \EMVMemberAPI
{
	private $configEMV;

	public function __construct( $confEMV )
	{
		parent::__construct( $confEMV['wdsl'], $confEMV['login'], $confEMV['pwd'], $confEMV['key'] );

		set_time_limit( 900 );
		\Yii::import( 'ext.CSVHelper' );

		if( !class_exists('Emv', false) )
		{
			require \Yii::getPathOfAlias('application.components.EMV').'/Emv.php';
			\Yii::registerAutoloader(array('Emv', 'autoload'), true);
		}

		$this->configEMV = $confEMV;
	}

	/**
	 * Recupere les clients dans notre DB sql pour les mettre a jour dans EMV
	 */
	public function updateClientInEMV()
	{
		try
		{
			$sep	= ';';
			$DB		= \Yii::app()->db;
			$nbMaj	= 0;

			// Si API EMV :
			if( !empty($this->configEMV['pwd']) && !empty($this->configEMV['login']) && !empty($this->configEMV['wdsl_batchMember']) && !empty($this->configEMV['wdsl']) )
			{
				$r		= "SELECT email as EMAIL, IF( SUM( p.isCT ) >0, 4, 3 ) as EMVADMIN54, DATE( MAX( inv.CreationDate ) ) as EMVADMIN53 FROM invoice AS inv INNER JOIN internaute AS i ON i.ID = inv.IDInternaute INNER JOIN product AS p ON p.ref = inv.refproduct WHERE inv.unitprice > 0 and inv.RefPricingGrid <> '5' GROUP BY i.ID;";
				$Res	= $DB->createCommand($r)->query();
				if( $Res->count() > 0 )
				{
					$nbMaj	= $Res->getRowCount();

					if( !($fileName = \CSVHelper::createFromCDbDataReader( $Res, \Yii::app()->params['porteur'].'_updateClient.csv', TMP_DIR.'/importUpdateDB/', $sep )) )
						return false;

					$wsdl	= array(
						'BatchMember' => $this->configEMV['wdsl_batchMember'],
						'Export' => $this->configEMV['wdsl']
					);

					$login	= $this->configEMV['login'];
					$pwd	= $this->configEMV['pwd'];
					$key	= $this->configEMV['key'];
					$EMV	= new \Emv( $wsdl, $login, $pwd, $key );
					$nb		= 1;

					$param = array(
						'fileName' => $fileName,
						'fileEncoding' => 'UTF-8',
						'separator' => $sep,
						'skipFirstLine' => true,
						'dateFormat' => 'YYYY-mm-dd',
						'autoMapping' => false,
						'mapping' => array(
							array(
								'colNum' => $nb++,
								'fieldName' => 'EMAIL',
								'toReplace' => false
							),
							array(
								'colNum' => $nb++,
								'fieldName' => 'EMVADMIN54',
								'toReplace' => true
							),
							array(
								'colNum' => $nb++,
								'fieldName' => 'EMVADMIN53',
								'toReplace' => true
							)
						),
						'dedup' => array(
							'criteria' => 'EMAIL',
							'order' => \Emv::ORDER_FIRST,
							'skipUnsubAndHBQ' => false
						)
					);

					$Upload		= \EMV_DataMassUpdate_UploadFileMerge::createFromArray( $param, true );
					$uploadID	= $EMV->createUpload( $Upload );
					$stop		= time() + self::TIME_TO_WAIT_EXPORT;

					do
					{
						sleep( 10 );

						$status = $EMV->getUploadStatus( $uploadID );
						$status	= ( is_object($status) ) ? $status->status : false;

						if( time() > $stop )
							break;
					}
					while( $status == \EMV::IMPORT_QUEUED || $status == \EMV::IMPORT_IMPORTING );

					return ( $uploadID > 0 );
				}
			}
			// Sinon WebForm :
			else if( isset($this->configEMV['wf_updateClient']) && !empty($this->configEMV['wf_updateClient']) )
			{
				$WF		= $WebForm = new WebForm( $this->configEMV['wf_updateClient'] ); // la variable est utilisée
				$Date	= new DateTime();
				$Date->sub( new DateInterval('P3D') );

				// Recupere toutes les commandes passées depuis hier
				$r		= 'SELECT email as EMAIL, IF( SUM( p.isCT ) >0, 4, 3 ) as EMVADMIN54, DATE_FORMAT( MAX( inv.CreationDate ), "%Y/%m/%d" ) as EMVADMIN53 FROM invoice AS inv INNER JOIN internaute AS i ON i.ID = inv.IDInternaute INNER JOIN product AS p ON p.ref = inv.refproduct WHERE inv.unitprice > 0 and inv.RefPricingGrid <> "5" AND inv.creationDate >= "'.$Date->format('Y-m-d').'" GROUP BY i.ID;';
				$Res	= $DB->createCommand($r)->query();
				if( $Res->count() > 0 )
				{
					foreach( $Res as $Row )
					{
						// Pour chaque commande, recupere la precedente commande
						// commenter par Othmane le 24/02/2016
						
						
						

						// Si le statut a changer alors on met a jour :
						
						
							$WF->setToken( array(
								'm'				=> $Row['EMAIL'],
								'EMVADMIN53'	=> $Row['EMVADMIN53'],
								'EMVADMIN54'	=> $Row['EMVADMIN54'],
							) );

							$WF->execute( true );

							$nbMaj++;
						//}
						// END commenter par Othmane le 24/02/2016
					}
				}

				return $nbMaj;
			}

			return false;
		}
		catch( CDbException $e )
		{
			$this->error[] = $e->getMessage();
			return false;
		}
		catch( EMV_Exception $e )
		{
			$this->error[]	= $e->getCustomMessage();
			return false;
		}
	}
	public function updateClientInEMVV2()
	{
		try
		{
			$sep	= ';';
			$DB		= \Yii::app()->db;
			$nbMaj	= 0;
	
			// Si API EMV :
			if( !empty($this->configEMV['pwd']) && !empty($this->configEMV['login']) && !empty($this->configEMV['wdsl_batchMember']) && !empty($this->configEMV['wdsl']) )
			{
				$r		= "SELECT inv.emailUser as EMAIL, IF( SUM( p.isCT ) >0, 4, 3 ) as EMVADMIN54, DATE( MAX( inv.CreationDate ) ) as EMVADMIN53 FROM V2_invoice AS inv INNER JOIN V2_recordinvoice AS rinv ON rinv.idInvoice=inv.id INNER JOIN V2_product AS p ON p.ref = rinv.refProduct WHERE rinv.priceATI > 0 AND inv.invoiceStatus=2 AND inv.refPricingGrid <> '5' GROUP BY inv.ID;";
				$Res	= $DB->createCommand($r)->query();
				if( $Res->count() > 0 )
				{
					$nbMaj	= $Res->getRowCount();
	
					if( !($fileName = \CSVHelper::createFromCDbDataReader( $Res, \Yii::app()->params['porteur'].'_updateClient.csv', TMP_DIR.'/importUpdateDB/', $sep )) )
						return false;
	
						$wsdl	= array(
								'BatchMember' => $this->configEMV['wdsl_batchMember'],
								'Export' => $this->configEMV['wdsl']
						);
	
						$login	= $this->configEMV['login'];
						$pwd	= $this->configEMV['pwd'];
						$key	= $this->configEMV['key'];
						$EMV	= new \Emv( $wsdl, $login, $pwd, $key );
						$nb		= 1;
	
						$param = array(
								'fileName' => $fileName,
								'fileEncoding' => 'UTF-8',
								'separator' => $sep,
								'skipFirstLine' => true,
								'dateFormat' => 'YYYY-mm-dd',
								'autoMapping' => false,
								'mapping' => array(
										array(
												'colNum' => $nb++,
												'fieldName' => 'EMAIL',
												'toReplace' => false
										),
										array(
												'colNum' => $nb++,
												'fieldName' => 'EMVADMIN54',
												'toReplace' => true
										),
										array(
												'colNum' => $nb++,
												'fieldName' => 'EMVADMIN53',
												'toReplace' => true
										)
								),
								'dedup' => array(
										'criteria' => 'EMAIL',
										'order' => \Emv::ORDER_FIRST,
										'skipUnsubAndHBQ' => false
								)
						);
	
						$Upload		= \EMV_DataMassUpdate_UploadFileMerge::createFromArray( $param, true );
						$uploadID	= $EMV->createUpload( $Upload );
						$stop		= time() + self::TIME_TO_WAIT_EXPORT;
	
						do
						{
							sleep( 10 );
	
							$status = $EMV->getUploadStatus( $uploadID );
							$status	= ( is_object($status) ) ? $status->status : false;
	
							if( time() > $stop )
								break;
						}
						while( $status == \EMV::IMPORT_QUEUED || $status == \EMV::IMPORT_IMPORTING );
	
						return ( $uploadID > 0 );
				}
			}
			// Sinon WebForm :
			else if( isset($this->configEMV['wf_updateClient']) && !empty($this->configEMV['wf_updateClient']) )
			{
				$WF		= $WebForm = new WebForm( $this->configEMV['wf_updateClient'] ); // la variable est utilisée
				$Date	= new DateTime();
				$Date->sub( new DateInterval('P3D') );
	
				// Recupere toutes les commandes passées depuis hier
				$r		= 'SELECT inv.emailUser as EMAIL, IF( SUM( p.isCT ) >0, 4, 3 ) as EMVADMIN54, DATE_FORMAT( MAX( inv.CreationDate ), "%Y/%m/%d" ) as EMVADMIN53 FROM V2_invoice AS inv INNER JOIN V2_recordinvoice AS rinv ON rinv.idInvoice=inv.id INNER JOIN V2_product AS p ON p.ref = rinv.refProduct WHERE rinv.priceATI > 0 AND inv.invoiceStatus=2 AND inv.creationDate >= "'.$Date->format('Y-m-d').'" AND inv.refPricingGrid <> "5"	 GROUP BY inv.ID;';
				$Res	= $DB->createCommand($r)->query();
				if( $Res->count() > 0 )
				{
					foreach( $Res as $Row )
					{
						// Pour chaque commande, recupere la precedente commande
						// commenter par Othmane le 24/02/2016
						
						
						
	
						// Si le statut a changer alors on met a jour :
						
						
							$WF->setToken( array(
									'm'				=> $Row['EMAIL'],
									'EMVADMIN53'	=> $Row['EMVADMIN53'],
									'EMVADMIN54'	=> $Row['EMVADMIN54'],
							) );
	
							$WF->execute( true );
	
							$nbMaj++;
						
						// END commenter par Othmane le 24/02/2016
					}
				}
	
				return $nbMaj;
			}
	
			return false;
		}
		catch( CDbException $e )
		{
			$this->error[] = $e->getMessage();
			return false;
		}
		catch( EMV_Exception $e )
		{
			$this->error[]	= $e->getCustomMessage();
			return false;
		}
	}
}

?>