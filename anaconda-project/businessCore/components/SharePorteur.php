<?php
/**
 * Description of SharePorteur
 * Permet le partage de leads entre porteur
 *
 * @author JulienL
 */
class SharePorteur
{
	/**
	 * Constant time to wait for upload ( in seconds )
	 */
	const TIME_TO_WAIT_EXPORT			= 120;
	/**
	 * Defini la limite d'adresse a partage de la source vers la destination
	 * Arrete le traitement et envoi un mail si cet limite est depassé.
	 */
	const SHARE_LIMIT					= 30000;
	/**
	 * Map le nom des porteurs entre le framework et la DB commune
	 * @var array
	 */
	static private $porteurs = array(
		'fr_rmay'		=> 'fr_mlo',
		'fr_laetizia'	=> 'fr_lae',
	);
	/**
	 * Contient le nom du champs a utiliser pour la newsletter par porteur :
	 * @var array
	 */
	static private $champsNL = array(
		'fr_rmay'		=> 'EMVADMIN85',
		'fr_rucker'		=> 'EMVADMIN73',
		'fr_laetizia'	=> 'EMVADMIN89',
		'fr_rinalda'	=> 'EMVADMIN86',
	);
	/**
	 * Contient le nom du champs a utiliser pour le chainage fid par porteur :
	 * @var array
	 */
	static private $champsChainFid = array(
		'fr_rmay'		=> 'EMVADMIN43',
		'fr_rucker'		=> 'EMVADMIN5',
		'fr_laetizia'	=> 'EMVADMIN5',
		'fr_rinalda'	=> 'EMVADMIN5',
	);
	/**
	 * Contient le nom du champs a utiliser pour l'envoi du mail OPTOUT par porteur :
	 * @var array
	 */
	static private $champsOptout = array(
		'fr_laetizia'	=> 'EMVADMIN87',
		'fr_rmay'		=> 'EMVADMIN81',
	);

	/**
	 * Nombre de jour utilisé pour la recherche des leads a transferer
	 * @var int
	 */
	protected $delaiAfterCreation	= 30;
	/**
	 * Nombre de jour a ajouter a la date actuel pour le champs EMV5 ( Champs Chainage FID )
	 * @var int
	 */
	protected $nbJourEmv5			= 39;
	/**
	 * Nombre de jour a ajouter a la date actuel pour le champs EMV85 ( champs Newsletter )
	 * @var int
	 */
	protected $nbJourEmv85			= 3;

	/**
	 * Porteur source
	 * @var string
	 */
	private $portSrc	= false;
	/**
	 * Porteur destination
	 * @var string
	 */
	private $portDst	= false;
	/**
	 * Tableau d'erreur
	 * @var array
	 */
	private $error		= array();

	/**
	 * Config EMV
	 * @var array
	 */
	private $configEmvKey	= false;

	/**
	 * Constructeur necessitant le porteur source et dest
	 * @param string $portSrc
	 * @param string $portDst
	 */
	public function __construct( $portSrc, $portDst, $configEmvKeyDst = 'EMV_ACQ' )
	{
		if( !class_exists('Emv', false) )
		{
			require \Yii::getPathOfAlias('application.components.EMV').'/Emv.php';
			\Yii::registerAutoloader(array('Emv', 'autoload'), true);
		}

		if( !isset(self::$porteurs[ $portSrc ]) || !isset(self::$porteurs[ $portDst ]) )
			$this->error[] = 'La configuration d\'un ou des porteurs est introuvable';

		$this->portSrc		= $portSrc;
		$this->portDst		= $portDst;
		$this->configEmvKey	= $configEmvKeyDst;
	}

	/**
	 * Destructeur, supprime le fichier generé
	 */
	public function __destruct()
	{
		$fName	= TMP_DIR.'/sharePorteur/transfer_'.$this->portSrc.'_'.$this->portDst.'_'.date('dmY').'.csv';
		if( is_file($fName) && !IS_DEV_VERSION )
			unlink ( $fName );
	}

	/**
	 * Lance l'operation de transfert
	 * @return boolean
	 */
	public function transfer()
	{
		if( count($this->error) > 0 )
			return false;

		if( ($fName = $this->createFileToUpload()) )
			return $this->sendToPorteurDst( $fName );

		return false;
	}

	/**
	 * Envoi le fichier precedement generé a EMV pour import
	 * @param string $fileName
	 * @return boolean
	 */
	protected function sendToPorteurDst( $fileName )
	{
		try
		{
			\Controller::loadConfigForPorteur( $this->portDst );

			$wsdl	= array(
				'BatchMember' => \Yii::app()->params[ $this->configEmvKey ]['wdsl_batchMember'],
				'Export' => \Yii::app()->params[ $this->configEmvKey ]['wdsl']
			);

			$login	= \Yii::app()->params[ $this->configEmvKey ]['login'];
			$pwd	= \Yii::app()->params[ $this->configEmvKey ]['pwd'];
			$key	= \Yii::app()->params[ $this->configEmvKey ]['key'];
			$EMV	= new \Emv( $wsdl, $login, $pwd, $key );
			$nb		= 1;

			$param = array(
				'fileName' => $fileName,
				'fileEncoding' => 'UTF-8',
				'separator' => '|',
				'skipFirstLine' => true,
				'dateFormat' => 'YYYY-mm-dd',
				'autoMapping' => false,
				'mapping' => array(
					array(
						'colNum' => $nb++,
						'fieldName' => 'TITLE',
						'toReplace' => true
					),
					array(
						'colNum' => $nb++,
						'fieldName' => 'FIRSTNAME',
						'toReplace' => true
					),
					array(
						'colNum' => $nb++,
						'fieldName' => 'LASTNAME',
						'toReplace' => true
					),
					array(
						'colNum' => $nb++,
						'fieldName' => 'DATEOFBIRTH',
						'toReplace' => true
					),
					array(
						'colNum' => $nb++,
						'fieldName' => 'EMAIL',
						'toReplace' => false
					),
					array(
						'colNum' => $nb++,
						'fieldName' => 'OPTIN',
						'toReplace' => true
					),
					array(
						'colNum' => $nb++,
						'fieldName' => self::$champsChainFid[ $this->portDst ],
						'toReplace' => true
					),
					array(
						'colNum' => $nb++,
						'fieldName' => self::$champsNL[ $this->portDst ],
						'toReplace' => true
					),
					array(
						'colNum' => $nb++,
						'fieldName' => self::$champsOptout[ $this->portDst ],
						'toReplace' => true
					),
					array(
						'colNum' => $nb++,
						'fieldName' => 'SEGMENT',
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
		}
		catch( EMV_Exception $e )
		{
			$this->error[]	= $e->getCustomMessage();
			return false;
		}

		return ( $uploadID > 0 );
	}

	/**
	 * Créé le fichier a importer dans EMV
	 * Recupere les leads du porteur source et les insere dans un fichier avec les informations necessaires
	 * @return string Nom du fichier
	 */
	protected function createFileToUpload()
	{
		\Yii::import( 'ext.CSVHelper' );
		\Yii::import( 'ext.DateHelper' );

		$fName	= TMP_DIR.'/sharePorteur/transfer_'.$this->portSrc.'_'.$this->portDst.'_'.date('dmY').'.csv';
		$Fp		= new \CSVHelper( $fName, '|', 'w' );

		if( !($Res = $this->getClientToTransfer()) )
			return false;

		$total	= count( $Res );
		$ratio	= ceil( $total / 7 );
		$inc	= 0;

		$header = array(
			'TITLE',
			'FIRSTNAME',
			'LASTNAME',
			'DATEOFBIRTH',
			'EMAIL',
			'OPTIN',
			self::$champsChainFid[ $this->portDst ],
			self::$champsNL[ $this->portDst ],
			self::$champsOptout[ $this->portDst ],
			'SEGMENT'
		);

		$Fp->fputcsv( $header );

		foreach( $Res as $i => $row )
		{
			// Repartition des adresses sur 7 jours :
			if( $i%$ratio == 0 && $inc < 7 )
			{
				$inc++;

				$EMV5		= new \DateTime();
				$EMV5->add( new \DateInterval('P'.( $this->nbJourEmv5 + ( $inc - 1 ) ).'D') );

				$EMV85		= new \DateTime();
				$EMV85->add( new \DateInterval('P'.( $this->nbJourEmv85 + ( $inc - 1 ) ).'D') );

				$EMVoptout	= new \DateTime();
				$EMVoptout->add( new \DateInterval('P'.( $inc - 1 ).'D') );

				
			}

			$Birthday	= \DateTime::createFromFormat( 'Y-m-d H:i:s', $row['birthday'] );
			$civility	= ( $row['civility'] == 'm' ) ? 1 : ( $row['civility'] == 'md' ? 2 : 3 );

			$write = array(
				$civility,
				str_replace( '"', '', utf8_decode($row['firstName']) ),
				str_replace( '"', '', utf8_decode($row['lastName']) ),
				$Birthday->format( 'Y-m-d' ),
				$row['email'],
				$row['optin'],
				$EMV5->format( 'Y-m-d' ),
				\DateHelper::getJour( $EMV85->format( 'w' ) ),
				$EMVoptout->format( 'Y-m-d' ),
				'import_auto_'.$this->portSrc.'_'.date( 'Y-m-d' )
			);

			$Fp->fputcsv( $write );
		}

		return $fName;
	}

	/**
	 * Retourne les leads a transferer
	 * @return \CDbDataReader $Res Resultat de la requete
	 */
	protected function getClientToTransfer()
	{
		$DB		= \Yii::app()->commonDb;
		$Date	= new \DateTime();
		$Date->sub( new \DateInterval( 'P'.$this->delaiAfterCreation.'D' ) );

		$r		= 'SELECT u.* FROM user as u, ETLClient as e WHERE u.client_db = e.id AND e.guid = "'.self::$porteurs[ $this->portSrc ].'" AND DATE(u.creationDate) = "'.$Date->format('Y-m-d').'" AND u.email NOT IN ( SELECT u.email FROM user as u, ETLClient as e WHERE u.client_db = e.id AND e.guid = "'.self::$porteurs[ $this->portDst ].'" );';
		
		$Tmp	= $DB->createCommand( $r )->query();

		// Exclusion des adresses inactives ( hard, soft, desabo ) :
		$Res = array();
		foreach( $Tmp as $row )
		{
			if( empty($row['email']) )
				continue;

			// Pour exclure les adresse mails en Hard, soft ou desabo :
			if( !$this->isEmailActive( $row['email'], $this->portSrc ) )
				continue;

			$Res[] = $row;
		}

		if( count($Res) <= 0 )
		{
			$this->error[] = 'Rien a transferer';
			return false;
		}

		return $Res;
	}

	/**
	 * Test si une adresse email est active ( si l'adresse mail n'est pas reference comme Hard, soft, ou desabo )
	 * @param string $email
	 * @return boolean
	 */
	protected function isEmailActive( $email, $confToLoad )
	{
		\Controller::loadConfigForPorteur( $confToLoad );

		$DB	= \Yii::app()->db;
		$r	= 'SELECT EMAIL FROM `'.\Yii::app()->params['tablePrefix'].'EmvExport` WHERE EMAIL = "'.$email.'" LIMIT 1;';

		return !( $DB->createCommand( $r )->query()->count() > 0 );
	}

	/**
	 * Retourne les erreurs rencontrées
	 * @return array Errors
	 */
	public function getError()
	{
		return $this->error;
	}
}

?>