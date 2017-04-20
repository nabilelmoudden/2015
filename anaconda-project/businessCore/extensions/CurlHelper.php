<?php

/**
 * Description of CurlHelper
 *
 * @author JulienL
 * @package Helper
 */
class CurlHelper
{
	/**
	 * Ressource curl
	 * @var ressource
	 */
	private $Curl = false;

	/**
	 * Constructeur
	 */
	public function __construct()
	{
		$this->Curl		= curl_init();

		$this->setOption( CURLOPT_VERBOSE, false );
		$this->setOption( CURLOPT_SSL_VERIFYPEER, false );
		$this->setOption( CURLOPT_FOLLOWLOCATION, true );
		$this->setOption( CURLOPT_RETURNTRANSFER, true );
		$this->setOption( CURLOPT_USERAGENT, 'Test Webbot' );
	}

	/**
	 * Configure une option sur la requete cUrl
	 * @param string $name nom de l'option
	 * @param mixed $value valeur
	 * @return boolean
	 */
	public function setOption( $name, $value )
	{
		if( $this->Curl === false )
			return false;

		return curl_setopt( $this->Curl, $name, $value );
	}
	/**
	 * Confiure un timeout sur la requete cUrl
	 * @param int $time Timeout
	 * @return boolean
	 */
	public function setTimeout( $time )
	{
		return ( $this->setOption( CURLOPT_CONNECTTIMEOUT, $time ) && $this->setOption( CURLOPT_TIMEOUT, $time ) );
	}
	/**
	 * Retourne un tableau d'information sur la requete
	 * @return array Infos
	 */
	public function getInfo()
	{
		return curl_getinfo( $this->Curl );
	}
	/**
	 * Retourne les erreurs sur la requete
	 * @return array Infos
	 */
	public function getError()
	{
		return curl_error( $this->Curl );
	}
	/**
	 * Ajoute des POST a la requete cUrl
	 * @param array $data Tableau de post
	 * @return boolean
	 */
	public function setPost( $data )
	{
		if( !$this->setOption( CURLOPT_POST, true ) )
			return false;

		if( !$this->setOption( CURLOPT_POSTFIELDS, $data ) )
			return false;
	}
	/**
	 * Execute la requete et retourne le resultat
	 * @param string $url	Url
	 * @return string	Resultat de la requete
	 */
	public function sendRequest( $url )
	{
		if( !$this->setOption( CURLOPT_URL, $url ) )
			return false;

		return curl_exec( $this->Curl );
	}

	// ***************************** STATIC ***************************** //
	/**
	 * Execute la requete de facon asynchrone ( Ne retourne pas le resultat )
	 * @param string $url	URL to call
	 * @return bool
	 */
	static public function sendRequestAsync( $url )
	{
		$cmd = \Yii::app()->basePath.'/yiic cron curlSendRequest --url="'.urlencode( $url ).'" > /dev/null &';
		return ( ($ret = shell_exec( $cmd )) === NULL ) ? true : $ret;
	}
}

?>