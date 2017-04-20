<?php

\Yii::import( 'ext.MailHelper' );

/**
 * Description of EsoterException
 *
 * @author JulienL
 * @package Exceptions
 */
class EsoterException extends \CHttpException
{
	/**
	 * Constructeurm permet de lancer une Exception
	 * @param int $errorNumber	Numero de l'erreur
	 * @param string $message	Message d'erreur
	 * @param int $status		Statut HTTP
	 */
	public function __construct( $errorNumber, $message = NULL, $status = 500 )
	{
		if( $message == NULL )
			$message = \Yii::t ( 'error', $errorNumber );

		$dbErr = ActiveRecord::getAllErrors();
		if( count($dbErr) > 0 )
		{
			$message .= '<br />';
			foreach( $dbErr as $field => $err )
				foreach( $err as $errMsg )
					$message .= $field.' : '.$errMsg.'<br />';
		}

		//if( SENDMAIL_ON_ERROR ) //&& !IS_DEV_VERSION )
		//	\MailHelper::sendMail( $GLOBALS['mailError'], 'FrameWork error', 'Exception catched : '.( IS_DEV_VERSION ? 'version DEV' : 'version PROD' ), $message );

		parent::__construct( $status, $message, $errorNumber );
	}
}

?>
