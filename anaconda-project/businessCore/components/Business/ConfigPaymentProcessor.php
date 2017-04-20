<?php

namespace Business;

/**
 * Description of ConfigPaymentProcessor
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class ConfigPaymentProcessor
{
	private $url				= false;
	private $param				= array();
	private $transformGetToPost	= false;

	/**
	 * Constructeur de l'objet de configuration du processeur de paiement
	 * @param string $url	Url vers laquel redirigé
	 * @param array $param	Tableau contenant les parametres a envoyé au processeur
	 * @param bool $transformGetToPost True pour envoyer les parametres en POST, False en GET
	 */
	public function __construct( $url, $param, $transformGetToPost = false )
	{
		$this->url					= $url;
		$this->param				= $param;
		$this->transformGetToPost	= $transformGetToPost;
	}

	/**
	 * Retourne l'URL vers le processeur
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}
	/**
	 * Retourne le tableau de parametre du processeur
	 * @return array
	 */
	public function getParam()
	{
		return $this->param;
	}
	/**
	 * Retourne TRUE s'il faut envoyé les parametres en POST, sinon FALSE en GET
	 * @return bool
	 */
	public function getTransformGetToPost()
	{
		return $this->transformGetToPost;
	}
	/**
	 * Retourne les parametres sous forme '&key=value'
	 * @return string
	 */
	public function getParamURL()
	{
		$paramGet = NULL;
		foreach( $this->getParam() as $k => $v )
			$paramGet .= '&'.$k.'='.$v;

		return $paramGet;
	}
}

?>