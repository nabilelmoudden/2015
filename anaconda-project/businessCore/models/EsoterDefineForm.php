<?php

/**
 * EsoterDefineForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 *
 * @package UserLogin
 */
class EsoterDefineForm extends CFormModel
{
	/**
	 * $GLOBALS['porteurMap']
	 */
	public  $porteurMap; //OK
	public  $porteurFolder; //OK
	
	/**
	 * Nom de domaine du porteur
	 * $GLOBALS['porteurDNS']
	 */
	public  $porteurDNS;
	
	/**
	 * $GLOBALS['porteurPathPhoto']
	 */
	public  $imageFolder; //OK
	
	/**
 	* Mail de porteur par defaut
 	* $GLOBALS['porteurMail']
 	*/
	public  $porteurMail; //OK
	
	/**
 	* Nom du/des compte(s) EMV associés au porteur
 	* $GLOBALS['porteurCompteEMVactif'] 
 	*/
	public  $compteEMVactif; //OK
	
	/**
 	 * Porteur multiCompte SF
 	 * $GLOBALS['SFAccountsMap']
 	 */
	public  $SFAccountsMap; //OK

	/**
 	 * Le site par défaut si le porteur est multisite
 	 * $GLOBALS['DefaultSite']
 	 */
	public  $DefaultSite; //OK
	
	/**
 	 * Le site par défaut si le porteur est multisite
 	 * $GLOBALS['porteurMultiSite']
 	 */
	public  $porteurMultiSite = false; //OK
	

	/**
	 * Porteurs ayants 2 comptes SF:
	 * $GLOBALS['porteurWithTwoSFAccounts']
 	 */
	public  $porteurWithTwoSFAccounts = false; //OK
	
	/**
 	 * la redirection d'un produit vers la version v1 
 	 * $GLOBALS['porteurRedirectV1']
 	 */
	public  $porteurRedirectV1 = false; //OK
	
	public  $porteurWebformUpdateClient;
	public  $porteurWebformInscrirClient;
	public  $porteurWebformDesinscrirClient;
	public  $UrlRefundDone;
	public  $UrlRefundReceived;
	public  $UrlResendProduct;
	public  $UrlResendProductV1;

	public  $SendMailSAV; //KO regex
	public  $SendMailSAVkey= array(); //KO regex

	/**
 	* $GLOBALS['listPorteur']
 	* $porteurAlias => alias-lang
 	* $porteurName  => Nom du porteur
 	*/
	public  $porteurAlias; //OK
	public  $porteurName; //OK
	
	/**
 	* Porteur source
 	* $GLOBALS['porteurPere'] 
 	*/
	public  $porteurPere;  
	
   /**
	* Mapping des files Isoter OTRS  Vers porteur 
	* $GLOBALS['isoter_file_mapping']
	*/		
	public  $isoterFileMapping = array(); //KO regex

	
	public  $site;     //KO regex
	public  $porteur;  //KO regex
	public  $compte;   //KO regex



	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{

 		return array(
                         array('site, compteEMVactif, SFAccountsMap, porteurFolder,porteurAlias, porteurMap, porteurDNS', 'required'),
                         array(
					           'compteEMVactif, 
					            SFAccountsMap,
					            porteurFolder,
					            porteurAlias, 
					            porteurMap, 
					            porteurDNS, 

					            porteurWebformUpdateClient,
					            
					            porteurWebformInscrirClient, 
					            porteurWebformDesinscrirClient,
					            UrlRefundDone, 
					            UrlRefundReceived, 
					            UrlResendProduct,
					            UrlResendProductV1, 
					            porteurMail, 
					            imageFolder',
					            
					            'match', 'pattern' => '/^[a-zA-Z0-9_\- .:\/]*$/',
					            'message' => 'Invalid characters.',
					        ),
					         array(
					           'site, 
					            porteurName,
					            DefaultSite,
					            porteur, 
					            compte,
					          	SFAccountsMap,'
					            ,
					            
					            'match', 'pattern' => '/^[a-zA-Z0-9_ .:\/]*$/',
					            'message' => 'Invalid characters.',
					        ),
					 );

	}


	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'   => 'Remember me next time',
			'porteurDNS'   => 'Nom de domaine',
			'porteurAlias' => 'Raccourci Porteur',
			'porteurName' =>'Nom du porteur',
			'isoterFileMappingPR' => 'File Prospet OTRS ',
			'isoterFileMappingCL' => 'File Client OTRS ',
			'porteurWithTwoSFAccounts'=>' Deux Comptes SF',
		);
	}

}
