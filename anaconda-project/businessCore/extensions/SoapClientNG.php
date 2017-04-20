<?php
/**
 * Classe Anaconda pour définir les traitements relatifs au comportement d'un lead Anaconda. 
 *
 * @author SaadH
 */

class SoapClientNG extends SoapClient{

	public function __doRequest($req, $location, $action, $version, $one_way = NULL){

		try{
			$xml = explode("\r\n", parent::__doRequest($req, $location, $action, $version));
			$response = preg_replace( '/^(\x00\x00\xFE\xFF|\xFF\xFE\x00\x00|\xFE\xFF|\xFF\xFE|\xEF\xBB\xBF)/', "", $xml[6] );
			return $response;
		}
		catch(Exception $e){
			var_dump($e); 
		}
	}
}