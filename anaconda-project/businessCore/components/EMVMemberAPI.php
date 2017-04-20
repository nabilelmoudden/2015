<?php
/**
 * Description of EMVConnection
 * Classe de communication avec l'API d'EMV.
 *
 * @author JulienL
 * @package	EMV.API
 */
class EMVMemberAPI
{
	/**
	 * Constant time to wait for export ( in seconds )
	 */
	const TIME_TO_WAIT_EXPORT			= 600;
	/**
	 * Constant separator
	 */
	const SEPARATOR						= 'PIPE';
	/**
	 * Constant operationType
	 */
	const ACTIVE_MEMBERS				= 'ACTIVE_MEMBERS';
	const QUARANTINED_OR_UNJOIN_MEMBERS	= 'QUARANTINED_OR_UNJOIN_MEMBERS';
	const QUARANTINED_MEMBERS			= 'QUARANTINED_MEMBERS';
	const UNJOIN_MEMBERS				= 'UNJOIN_MEMBERS';
	const ALL_MEMBERS					= 'ALL_MEMBERS';

	/**
	 * Constante status
	 */
	const EXPORT_VALIDATED				= 'VALIDATED';
	const EXPORT_RUNNING				= 'RUNNING';
	const EXPORT_SUCCESS				= 'SUCCESS';
	const EXPORT_ERROR					= 'ERROR';
	const EXPORT_DELETED				= 'DELETED';

	/**
	 * Url wdsl
	 * @var string
	 */
	private $wdsl;
	/**
	 * Login for the connection API
	 * @var string
	 */
	private $login;
	/**
	 * Password for the connection API
	 * @var string
	 */
	private $pwd;
	/**
	 * Key for the connection API
	 * @var string
	 */
	private $key;
	/**
	 * SoapClient Object
	 * @var SoapClient
	 */
	private $Soap;
	/**
	 * Token return when call "openApiConnection"
	 * @var string
	 */
	private $token;

	/**
	 * Error tab
	 * @var array
	 */
	protected $error	= array();

	/**
	 * Constructeur
	 * Lance la connection a EMV
	 */
	public function __construct( $wdsl, $login, $pwd, $key )
	{
		$this->wdsl		= $wdsl;
		$this->login	= $login;
		$this->pwd		= $pwd;
		$this->key		= $key;

		if( $this->login != '' && $this->pwd != '' && $this->key != '' )
			$this->connect();
	}

	public function __destruct()
	{
		try
		{
			if( is_object($this->Soap) && !empty($this->token) )
				$this->Soap->closeApiConnection( array(
						'token' => $this->token
					) );
		}
		catch( Exception $E )
		{
			$this->error[] = $E->detail;
			return false;
		}
	}
	
	public function getSoap(){
		return $this->Soap;
	}
	
	public function getToken(){
		return $this->token;
	}

	/**
	 * Connection a EMV
	 * @return boolean
	 */
	protected function connect()
	{
		try
		{
			$this->Soap	= new SoapClient( $this->wdsl );
			$Res		= $this->Soap->openApiConnection( array(
											'login' => $this->login,
											'pwd' => $this->pwd,
											'key' => $this->key ) );
		}
		catch( Exception $E )
		{
			$this->error[] = $E->detail;
			return false;
		}

		$this->token = $Res->return;
		return true;
	}

	/**
	 * Demande de creation d'un export
	 * @param int $segmentID	ID du segment a exporter
	 * @param string $operationType	Type d'export ( MEMBRE ACTIF, MEMBRE EN QUARANTAINE, ... )
	 * @param string $fields	Champs a exporter
	 * @param string $dateFormat	Date d'export, ne fonctionne pas ??
	 * @param bool $dedupEmail	Dedup sur les mails
	 * @return boolean
	 */
	public function createExport( $segmentID, $operationType = self::QUARANTINED_OR_UNJOIN_MEMBERS, $fields = 'EMAIL,SOURCE,HBQ_REASON,DATEUNJOIN,DATEJOIN', $dateFormat = '', $dedupEmail = true )
	{
		if( !is_object($this->Soap) )
			return false;

		$param = array(
			'token'				=> $this->token,
			'mailinglistId'		=> $segmentID,
			'operationType'		=> $operationType,
			'fieldSelection'	=> $fields,
			'dedupFlag'			=> $dedupEmail,
			'dedupCriteria'		=> 'EMAIL',
			'keepFirst'			=> true,
			'fileFormat'		=> self::SEPARATOR,
			'dateFormat'		=> $dateFormat
		);

		try
		{
			$Res = $this->Soap->createDownloadByMailinglist( $param );
		}
		catch( Exception $E )
		{
			$this->error[] = $E->detail;
			return false;
		}

		return $Res->return;
	}

	/**
	 * Retourne un tableau d'etat des derniers exports demandés
	 * @return array	Status
	 */
	public function getStatusLastExport()
	{
		try
		{
			$Res = $this->Soap->getLastDownloads( array( 'token' => $this->token ) );
		}
		catch( Exception $E )
		{
			$this->error[] = $E->detail;
			return false;
		}

		return $Res->return;
	}

	/**
	 * Retourne l'etat d'un export en particulier
	 * @param int $id ID de l'export
	 * @return string Etat de l'export
	 */
	public function getStatusExport( $id )
	{
		try
		{
			$Res = $this->Soap->getDownloadStatus( array(
				'token' => $this->token,
				'id' => $id
			) );
		}
		catch( Exception $E )
		{
			$this->error[] = $E->detail;
			return false;
		}

		return $Res->return;
	}

	/**
	 * Retourne OU ecrit dans un fichier le resultat de l'export
	 * @param int $id	ID de l'export
	 * @param string $writeIn	Fichier dans lequel ecrire le resultat, OU False pour qu'il soit retourné
	 * @return boolean|string
	 */
	public function getExport( $id, $writeIn = false )
	{
		$porteur = \Yii::app()->params['porteur'];

		if( !is_dir(TMP_DIR.'/HBSB/'.$porteur) )
			mkdir( TMP_DIR.'/HBSB/'.$porteur, 0777 );

		try
		{
			$Res = $this->Soap->getDownloadFile( array( 'token' => $this->token, 'id' => $id ) );
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
					if( !$fp = fopen( TMP_DIR.'/HBSB/'.$porteur.'/'.$writeIn, 'w+' ) )
						die( 'Impossible de creer le fichier : "'.$writeIn.'"' );

					fwrite( $fp, $Res->return->fileContent );
					fclose( $fp );

					return true;
				}
				break;

			case 'NO_DATA' :
				return NULL;
				break;

			case 'NOT_YET_READY' :
				return self::EXPORT_RUNNING;
				break;

			case 'ERROR' :
				return self::EXPORT_ERROR;
				break;
		}
	}

	/**
	 * Demande la creation d'un export, et patiente 30 sec qu'il soit crée, puis retourne ou ecrit dans un fichier le resultat
	 * @param int $segmentID	ID du segment a exporter
	 * @param string $writeIn	Fichier dans lequel ecrire le resultat, OU False pour qu'il soit retourné
	 * @param string $operationType	Type d'export ( MEMBRE ACTIF, MEMBRE EN QUARANTAINE, ... )
	 * @param string $fields	Champs a exporter
	 * @param string $dateFormat	Date d'export, ne fonctionne pas ??
	 * @param bool $dedupEmail	Dedup sur les mails
	 * @return boolean|string
	 */
	public function createAndWaitExport( $segmentID, $writeIn = false, $operationType = self::QUARANTINED_OR_UNJOIN_MEMBERS, $fields = 'EMAIL,SOURCE,HBQ_REASON,DATEUNJOIN,DATEJOIN', $dateFormat = '', $dedupEmail = true )
	{
		$this->error	= array();
		$id				= $this->createExport( $segmentID, $operationType, $fields, $dateFormat, $dedupEmail );
		$stop			= time() + self::TIME_TO_WAIT_EXPORT;

		if( count($this->error) > 0 )
			return false;

		do
		{
			sleep( 1 );

			$status = $this->getStatusExport( $id );

			/*echo $status.'<br/>';
			flush();
			//ob_flush();*/

			if( time() > $stop || count($this->error) > 0 )
				break;
		}
		while( $status == self::EXPORT_VALIDATED || $status == self::EXPORT_RUNNING );

		if( $status == self::EXPORT_ERROR )
			return false;

		if( $status == self::EXPORT_VALIDATED || $status == self::EXPORT_RUNNING )
			die( 'RUNNING' );

		return $this->getExport( $id, $writeIn );
	}

	/**
	 * Retourne les erreurs rencontrées
	 * @return string
	 */
	public function getError()
	{
		$return = NULL;
		foreach( $this->error as $error )
		{
			if( isset($error->ConnectionServiceException->description) )
				$return .= $error->ConnectionServiceException->description.', ';
			else if( is_string($error) )
				$return .= $error.', ';
			else
				$return .= 'unknow error, ';
		}

		return substr( $return, 0, -1 );
	}
}

?>