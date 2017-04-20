<?php

/**
 * Description of MailHelper
 *
 * @author JulienL
 */
class MailHelper
{
	static public function sendMail( $to, $from, $subject, $message )
	{
		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
		if( is_array($to) )
			$headers .= 'To: '.implode( ', ', $to )."\r\n";
		else
			$headers .= 'To: '.$to."\r\n";
		$headers .= 'From: '.$from."\r\n";

		return mail( is_array($to) ? $to[0] : $to, $subject, $message, $headers );
	}
}

?>