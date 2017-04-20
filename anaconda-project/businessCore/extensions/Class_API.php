<?php
 
require_once("Global_Base.php");
	
	 
	
 class Class_API
{

	//lien + login + pwd + key se trouve dans le chemin  /dossier proteur/fv2_YII
	//lien de l'api
	private $clientAPI;
	
	//login
	private $login; 
	
	//mot de passe 
	private $pwd;
	
	//key
	private $keys;
	
	//client SOAP
	public $soap_client;
	
	//segement
	private $id_segement;
	
	public function __construct($clientAPI,$login,$pwd,$keys){
		$this->login=$login;
		$this->clientAPI=$clientAPI;
		$this->pwd=$pwd;
		$this->keys=$keys;
		
		
	}
	
	public function get_string_between($string, $start, $end){
                    $string = ' ' . $string;
                    $ini = strpos($string, $start);
                    if ($ini == 0) return '';
                    $ini += strlen($start);
                    $len = strpos($string, $end, $ini) - $ini;
                    return substr($string, $ini, $len);
                   }
	
	public function connexion() {
		try{
			$this->soap_client= new SoapClient($this->clientAPI);
			$this->soap_client->_Res=$this->soap_client->openApiConnection(array(
				'login'=>$this->login,
				'pwd'=>$this->pwd,
				'key'=>$this->keys));
		return $this->soap_client->_Res->return; 
		}
		catch(Exception $e){
			echo "Erreur de connexion à l'API";
		
		}
	}
	
	public function connexion2() { 
		//appel de la classe Soap Client NG
		Yii::import( 'ext.SoapClientNG', true );
		try{
			$this->soap_client=new SoapClientNG($this->clientAPI, array('trace' => 1));
			$this->soap_client->_Res=$this->soap_client->openApiConnection(array(
					'login'=>$this->login,
					'pwd'=>$this->pwd,
					'key'=>$this->keys));
			return $this->soap_client->_Res->return;
		}
		catch(Exception $e){
			echo "Erreur de connexion à l'API";
	
		}
	}
	
	public function closeConnection($token)
	{
		try{
			
			return $this->soap_client->closeApiConnection(array(
							'token'=>$token));
		}
		catch(Exception $e){
			echo "Erreur de fermeture de connection";
		
		}
	}
	
	public function createContenuDynamique($token,$name,$description,$content,$contentType){
	      if(!empty($token)){
		  try{
                     $id_contenu=$this->soap_client->createBanner(array(
							'token'=>$token,
							'name'=>$name,
							'description'=>$description,
							'content'=>$content,
							'contentType'=>$contentType))->return;
				
					}
					catch(Exception $e){
					var_dump($e);
					}
	
	return $id_contenu;
		  }
	}
	

	public function createMessageEmail($token,$name,$subject,$from,$body,$replyTo,$replyToEmail,$isBounceback,$description,$encoding,$hotmailUnsubFlg,$hotmailUnsubUrl,$type,$macroURL,$FAQURL)
	{if(!empty($token)){
		try{
		$sendermail=$this->soap_client->getDefaultSender(
								array(
									'token'=>$token						
								))->return;
				 
		}catch(Exception $e){
		var_dump($e);}


						try{
$id_message=$this->soap_client->createEmailMessage(
										array(
										'token'=>$token,
										'name'=>$name,
										'subject'=>$subject,
										'from'=>$from,
										'fromEmail'=>$sendermail,
										'to'=>'[EMV FIELD]FIRSTNAME[EMV /FIELD] [EMV FIELD]LASTNAME[EMV /FIELD]',
										'body'=>$body,
										'replyTo'=>$replyTo,
										'replyToEmail'=>$replyToEmail,
										'isBounceback'=>$isBounceback,
										'description'=>$description,
										'encoding'=>$encoding,
										'hotmailUnsubFlg'=>$hotmailUnsubFlg,
										'hotmailUnsubUrl'=>$hotmailUnsubUrl,
										'type'=>$type							
									))->return;

									$this->soap_client->createPersonalisedUrl(array(
										
										'token'=>$this->soap_client->_Res->return,
										'messageId'=>$id_message,
										'name'=>'Personalised',
										'nb'=>1,
										'url'=>$macroURL										
									));

$this->soap_client->createPersonalisedUrl(array(
										
										'token'=>$this->soap_client->_Res->return,
										'messageId'=>$id_message,
										'name'=>'Personalised',
										'nb'=>2,
										'url'=>'http://'										
									));
$this->soap_client->createPersonalisedUrl(array(
										'token'=>$this->soap_client->_Res->return,
										'messageId'=>$id_message,
										'name'=>'Personalised',
										'nb'=>3,
										'url'=>'http://'										
									));

$this->soap_client->createPersonalisedUrl(array(
										'token'=>$this->soap_client->_Res->return,
										'messageId'=>$id_message,
										'name'=>'Personalised',
										'nb'=>4,
										'url'=>$FAQURL										
									));
								
									
									
}
catch(Exception $e){
	var_dump($e);
}

	}	
	}
	
	
	
		public function getNomPorteur($token)
	{
		$id_contenu='';
		$nom_porteur='';
		if(!empty($token)){
		     try{
                     $id_contenu=$this->soap_client->getBannersByField(
					 array(
							'token'=>$token,
							'field'=>'name',
							'value'=>'nom_porteur',
							'limit'=>'1'
				))->return;
				}
					catch(Exception $e){
					var_dump($e);
				}
		
	
		     try{
                     $nom_porteur=$this->soap_client->getBanner(
					 array(
							'token'=>$token,
							'id'=>$id_contenu
							
				))->return;
							
				}
				catch(Exception $e){
					var_dump($e);
				}

            return $nom_porteur->content;
		}
		
	}
	
	
			public function getLangue($token)
	{
		$id_contenu='';
		$langue='';
		if(!empty($token)){
		     try{
                     $id_contenu=$this->soap_client->getBannersByField(
					 array(
							'token'=>$token,
							'field'=>'name',
							'value'=>'langue',
							'limit'=>'1'
				))->return;
				}
					catch(Exception $e){
					var_dump($e);
				}
		
	
		     try{
                     $langue=$this->soap_client->getBanner(
					 array(
							'token'=>$token,
							'id'=>$id_contenu
							
				))->return;
							
				}
				catch(Exception $e){
					var_dump($e);
				}

            return $langue->content;
	
		}
	}
	
	public function date_a_programmer($token)
	{
		$id_contenu='';
		$langue='';
		if(!empty($token)){
		     try{
                     $id_contenu=$this->soap_client->getBannersByField(
					 array(
							'token'=>$token,
							'field'=>'name',
							'value'=>'date_a_programmer',
							'limit'=>'1'
				))->return;
				}
					catch(Exception $e){
					var_dump($e);
				}
		
	
		     try{
                     $langue=$this->soap_client->getBanner(
					 array(
							'token'=>$token,
							'id'=>$id_contenu
							
				))->return;
							
				}
				catch(Exception $e){
					var_dump($e);
				}

            return $langue->content;
		}
		
	}
	
		public function mail_sender($token)
	{
		$id_mail='';
		$adresse_mail='';
		if(!empty($token)){
		     try{
                     $id_mail=$this->soap_client->getBannersByField(
					 array(
							'token'=>$token,
							'field'=>'name',
							'value'=>'mail_sender',
							'limit'=>'1'
				))->return;
				}
					catch(Exception $e){
					var_dump($e);
				}
		
	/* 
		     try{
                     $adresse_mail=$this->soap_client->getBanner(
					 array(
							'token'=>$token,
							'id'=>$id_mail
							
				))->return;
							
				}
				catch(Exception $e){
					var_dump($e);
				} */

            return $id_mail;
	
		}
	}
	/**
	 * @author Zakaria CHNIBER
	 * @desc Exporter un segment via son id et retourner son contenu.
	 * @return fileContent
	 */
	public function export($token,$idSegment){
		
		try{
			
		$idDownloadFile=$this->soap_client->createDownloadByMailinglist(
				
				array(
						'token'=>$token,
						'mailinglistId'=>$idSegment,
						'operationType'=>'ACTIVE_MEMBERS',
						'fileFormat'=>'PIPE',
						'fieldSelection'=>'EMAIL',
						'dedupFlag'=>'',
						'keepFirst'=>''
				)
				)->return;
		//cr�er le telechargement du segment dans SF
				$downloadStatus=$this->soap_client->getDownloadStatus(
		
						array(
								'token'=>$token,
								'id'=>$idDownloadFile
		
						)
						)->return;
						
						$fullTime=0;
						$timestart=microtime(true);
		//boucler jusqu'� r�cup�rer l'�tat success du telechargement dans SF
						while ($downloadStatus !='SUCCESS' && $fullTime<180){
		
							
							$downloadStatus=$this->soap_client->getDownloadStatus(
		
									array(
											'token'=>$token,
											'id'=>$idDownloadFile
		
									)
									)->return;
								$timeend=microtime(true);
								$fullTime=$timeend-$timestart;
		
						}
						$downloadFile=$this->soap_client->getDownloadFile(
									
								array(
										'token'=>$token,
										'id'=>$idDownloadFile
		
								)
								);
			if(isset($downloadFile->return->fileContent)){
				return	$downloadFile->return->fileContent;
			}else{
				return "";
			}
			
			
		}catch(Exception $e){
			var_dump($e);
		}
		
	}
	
	public function importFile($token, $file, $mapping){
		
		try{
			$id_job=$this->soap_client->uploadFileMerge(array("token"=>$token,'file'=>file_get_contents($file),
					'mergeUpload'=>array('fileName'=>basename($file),
							'fileEncoding'=>'UTF-8',
							'separator'=>'|', 
							'skipFirstLine'=>true,
							'dateFormat'=>'dd/mm/yyyy',
							'criteria'=>'LOWER(EMAIL)', 
							'mapping'=>$mapping
					)))->return;
			if($id_job)
				return true;
			else
				return false;
		}
		catch(Exception $e){
					var_dump($e);
				}

		
	}
	
	public function createSegment($name_segment){
	
		// Création des segments pour le client et VG et attribution des id de chaque segment
		try{
			$this->soap_client=new SoapClient($this->clientAPI);
				$this->id_segement=$this->soap_client->segmentationCreateSegment(array('token'=> $this->connexion(),
					'apiSegmentation'=>array('','id'=>'','name'=>$name_segment,'sampleType'=>"ALL",'sampleRate'=>'')));
	
			return $this->id_segement->return;
		}
		catch(Exception $e){
			echo("erreur creation segement");
		}
	 
	}
	
	public function critereString($column_name,$operator,$values,$groupName,$groupNumber){
		try{
			$this->soap_client->segmentationAddStringDemographicCriteriaByObj(
					array(
							'token'=>$this->connexion(),
							'stringDemographicCriteria'=>array(
									'groupName' => $groupName,
									'groupNumber'=>$groupNumber,
									'orderFrag'=>'',
									'id'=>$this->id_segement->return,
									'columnName'=>$column_name,
									'operator'=>$operator,
									'values'=>$values
							)
					)
					);
		}
		catch(Exception $e){
			echo"erreur d'ajout de critére";
		}
	}
	
	
	 
	public function critereNumeric($column_name,$operator,$first_value,$second_value,$groupName,$groupNumber){
		try{
			$this->soap_client->segmentationAddNumericDemographicCriteriaByObj(
					array(
							'token'=>$this->connexion(),
							'numericDemographicCriteria'=>array(
									'groupName' => $groupName,
									'groupNumber'=>$groupNumber,
									'orderFrag'=>'',
									'id'=>$this->id_segement->return,
									'columnName'=>$column_name,
									'operator'=>$operator,
									'firstValue'=>$first_value,
									'secondValue'=>$second_value
							)
					)
					);
		}
		catch(Exception $e){
			echo"erreur d'ajout de critére";
		}
	}
	
	
	public function critereDateStatic($column_name,$operator,$first_value,$second_value,$groupName,$groupNumber){
		try{
			$this->soap_client->segmentationAddRecencyCriteriaByObj(
					array('token'=>$this->connexion(),
							'recencyCriteria'=>array(
									'groupName' => $groupName,
									'groupNumber'=>$groupNumber,
									'orderFrag'=>'',
									'id'=>$this->id_segement->return,
									'firstStaticValue'=>$first_value,
									'secondStaticValue'=>$second_value,
									'columnName'=>$column_name,
									'operator'=>$operator,
									'periodDayBeginList'=>'',
									'periodDayEndList'=>''
							)
					)
					);
		}
		catch(Exception $e){
			echo"erreur d'ajout de critére";
		}
	}
	
	public function critereDateAbsolute($column_name,$operator,$first_value,$second_value,$groupName,$groupNumber){
		
			$this->soap_client->segmentationAddDateDemographicCriteriaByObj(
					array('token'=>$this->connexion(),
							'dateDemographicCriteria'=>array(
									'groupName' => $groupName,
									'groupNumber'=>$groupNumber,
									'orderFrag'=>'',
									'id'=>$this->id_segement->return,
									'firstAbsoluteDate'=>$first_value,
									'secondAbsoluteDate'=>$second_value,
									'columnName'=>$column_name,
									'operator'=>$operator,
									'absoluteDate'=>'1'
							)
					)
					);
	
	}
	
	public function critereDateAbsoluteSansInterval($column_name,$operator,$groupName,$groupNumber){
	
		$this->soap_client->segmentationAddDateDemographicCriteriaByObj(
				array('token'=>$this->connexion(),
						'dateDemographicCriteria'=>array(
								'groupName' => $groupName,
								'groupNumber'=>$groupNumber,
								'orderFrag'=>'',
								'id'=>$this->id_segement->return,
								'columnName'=>$column_name,
								'operator'=>$operator,
								'absoluteDate'=>'1'
						)
				)
				);
	
	}
	 
	public function critereDateAbsoluteDynamic($column_name,$groupName,$groupNumber){
		
		$this->soap_client->segmentationAddDateDemographicCriteriaByObj(
				array('token'=>$this->connexion(),
						'dateDemographicCriteria'=>array(
								'groupName' => $groupName,
								'groupNumber'=>$groupNumber,
								'orderFrag'=>'',
								'id'=>$this->id_segement->return,
								'columnName'=>$column_name,
								'operator'=>'RELATIVE_AFTER_OR_ON_AFTER',
								'absoluteDate'=>'false',
								'numberDaysBeforeOrAfter'=>0,
							'relativeColumnNameBeforeOrAfter'=>'sysDate'
						)
				)
				);
		 
	}
	
	public function critereDateAbsoluteSB($id_segment,$numberDaysBeforeOrAfter,$groupName,$groupNumber){
	
		$this->soap_client->segmentationAddDateDemographicCriteriaByObj(
				array(	'token'=>$this->connexion(),
						'dateDemographicCriteria'=>array(
								'groupName' => $groupName,
								'groupNumber'=>$groupNumber,
								'orderFrag'=>'',
								'id'=>$id_segment,
								'columnName'=>'DE_ANACONDA',
								'operator'=>'RELATIVE_ON_BEFORE',
								'absoluteDate'=>0,
								'numberDaysBeforeOrAfter'=>$numberDaysBeforeOrAfter,
								'relativeColumnNameBeforeOrAfter'=>'sysDate'
						)
				)
				);
			
	}
	
	public function critereSerieAction($id_segment,$id_trigger,$order,$groupName,$groupNumber){
	
		$this->soap_client->segmentationAddSerieActionCriteriaByObj(
				array(	'token'=>$this->connexion(),
          				'actionCriteria'=>array(
		                       'groupName'=>$groupName,
		                       'groupNumber'=>$groupNumber,
					           'id'=>$id_segment,
					           'orderFrag'=>'',
					           'campaignId'=>'',
					           'serieId'=>$id_trigger,
					           'operator'=>'SOFTBOUNCE_SERIE',
					           'messageOrder'=>$order
			           	)
         		)
				);
			
	}
	
	public function getIdMessage($name,$limit){
		try {
		return $idMessage=$this->soap_client->getEmailMessagesByField(
			  array(
			  		'token'=>$this->connexion(),
			  		'field'=>'NAME',
			  		'value'=>$name,
			  		'limit'=>$limit
			  )
			  );
		}
		
		catch(Exception $e){
			var_dump($e);
		}
		
			
	}
	
	public function CreateWebForm($name,$idMessage,$replyTo,$fields,$confirmationPageURL,$errorPageURL){
		try{
		
		return $idWebForm= $this->soap_client->createWebform(
					array(
							'token'=>$this->connexion(),
							'webform'=>array(
									'name'=>$name,
									'description'=>$name,
									'type'=>'SUBSCRIBE_OR_UPDATE',
									'controlWarningLanguage'=>'FRENCH',
									'expirationDate'=>'2040-08-02T07:15:00',
									'useDefaultExpirationLandingPage'=>true,
									'confirmationLandingPageUrl'=>$confirmationPageURL,
									'standardErrorLandingPageUrl'=>$errorPageURL,
									'fields'=>$fields,
									'bouncebackType'=>'USER',
									'replyTo'=>$replyTo,
									'overrideUnsubscribedUsersStatus'=>false,
									'allowMessageOverriding'=>false,
									'bouncebackMessageId'=>$idMessage)
					))->return;
						
		}
		catch(Exception $e){
			var_dump($e);
		}
	}
	
	public function getUrlWebForm($idWebForm){
		try {
			return $urlWebForm= $this->soap_client->getWebformIntegration(
			  array(
			  		'token'=>$this->connexion(),
			  		'webformId'=>$idWebForm
			  )
			  )->return->httpCallMethod;
		}
		
		catch(Exception $e){
			var_dump($e);
		}
		
	}
	public function deleteSegment($id){
		try {
			$this->soap_client->segmentationDeleteSegment(
			  array(
			  		'token'=>$this->connexion(),
			  		'difflistId'=>$id
			  )
			  )->return;
		}
		
		catch(Exception $e){
			var_dump($e);
		}
	}
	
	public  function getAllWebForm($names)
	{
		try {
		return	$Webforms = $this->soap_client->getWebformSummaryList(
					array(
							'token'=>$this->connexion(),
							'listOptions'=>array(
									'page'=>'1',
									'pageSize'=>'100',
									'search'=>array('name'=>$names),
									'sortOptions'=>array('sortOption'=>array('column'=>'webformId','order'=>'desc'))		 
							)			 
			  )
			  )->return->webformSummaryList;
		}catch(Exception $e){
			var_dump($e);
		}

	}
	
	public function updateWebForm($id){
		try{
			$fields = array (
					'field' => array (
							array (
									'fieldName' => 'EMAIL',
									'required' => true,
									'displayName' => 'email',
									'inputType' => 'TEXT',
									'validationType' => 'NONE',
									'textLength' => '255'
							),
							array (
									'fieldName' => 'CLIENTURN',
									'inputType' => 'HIDDEN',
									'displayName' => ''
							)
					)
			);
			$webform=$this->GetInformationWebForm($id);
			echo 'webformId===>'.$id . '  name ===>'.$webform->name .'<br>';
		$this->soap_client->updateWebform(
			 array(
			 		'token'=>$this->connexion(),
			 		'webform'=>array(
			 				'webformId'=>$id,
			 				'name'=>$webform->name,
			 				'type'=>$webform->type,
			 				'controlWarningLanguage'=>$webform->controlWarningLanguage,
			 				'expirationDate'=>$webform->expirationDate,
			 				'fields'=>$fields,
			 				'bouncebackType'=>$webform->bouncebackType,
			 				'replyTo'=>$webform->replyTo,
			 				'bouncebackMessageId'=>$webform->bouncebackMessageId,
			 				'useDefaultExpirationLandingPage'=>$webform->useDefaultExpirationLandingPage,
			 				'confirmationLandingPageUrl'=>$webform->confirmationLandingPageUrl,
			 				'standardErrorLandingPageUrl'=>$webform->standardErrorLandingPageUrl,
			 				'description'=>$webform->description,
			 				'overrideUnsubscribedUsersStatus'=>$webform->overrideUnsubscribedUsersStatus,
			 				'allowMessageOverriding'=>$webform->allowMessageOverriding,
					))
			 );
		}catch(Exception $e){
			var_dump($e);
		}
		
		
	}
	
	public function GetInformationWebForm($id)
	{
		return $webform = $this->soap_client->getWebform(
				array(
						'token'=>$this->connexion(),
						'webformId'=>$id,
		
						 
				)
				)->return;
	}

	public function exportQuarantaine($token,$idSegment){
	
		try{
				
			$idDownloadFile=$this->soap_client->createDownloadByMailinglist(
	
					array(
							'token'=>$token,
							'mailinglistId'=>$idSegment,
							'operationType'=>'QUARANTINED_MEMBERS',
							'fileFormat'=>'PIPE',
							'fieldSelection'=>'EMAIL',
							'dedupFlag'=>'',
							'keepFirst'=>''
					)
			)->return;
			//cr�er le telechargement du segment dans SF
			$downloadStatus=$this->soap_client->getDownloadStatus(
	
					array(
							'token'=>$token,
							'id'=>$idDownloadFile
	
					)
			)->return;
	
			$fullTime=0;
			$timestart=microtime(true);
			//boucler jusqu'� r�cup�rer l'�tat success du telechargement dans SF
			while ($downloadStatus !='SUCCESS' && $fullTime<180){
	
					
				$downloadStatus=$this->soap_client->getDownloadStatus(
	
						array(
								'token'=>$token,
								'id'=>$idDownloadFile
	
						)
				)->return;
				$timeend=microtime(true);
				$fullTime=$timeend-$timestart;
	
			}
			$downloadFile=$this->soap_client->getDownloadFile(
						
					array(
							'token'=>$token,
							'id'=>$idDownloadFile
	
					)
			);
			if(isset($downloadFile->return->fileContent)){
				return	$downloadFile->return->fileContent;
			}else{
				return "";
			}
				
				
		}catch(Exception $e){
			var_dump($e);
		}
	
	}

	 /**
	  * @author Fouad DANI
	  * @desc Retrieves a list of uploads and their details.
	  * @return	list of uploads and their details
	  * @param int token and array listOptions
	  */

	 public function getUploadSummaryList($token, $listOptions){

		 try{
			 $uploadSummaryList = $this->soap_client->getUploadSummaryList(
				 array(
					 "token"=>$token,
					 'listOptions' => $listOptions
				 )
			 )->return;
			 if($uploadSummaryList)
				 return $uploadSummaryList;
			 else
				 return false;
		 }
		 catch(Exception $e){
			 var_dump($e);
		 }

	 }
	/********************************* Update Segment ***************************************/ 
/*author Yacine RAMI
 * mettre � jour un segment par critere
 */
	public function update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag){
		 	try{
		 		return $this->soap_client->segmentationUpdateRecencyCriteriaByObj(
		 				array( 
		 						'token'=>$token,
		 						'recencyCriteria'=>array(
		 						'groupNumber'=>$groupNumber,
		 						'orderFrag'=>$orderFrag,
		 						'id'=>$id_segment,
		 						'firstStaticValue'=>$first_value,
		 						'secondStaticValue'=>$second_value,
		 						'columnName'=>$column_name,
		 						'operator'=>$operator,
	
		 						)
		 				)
		 				)->return;
		 	}
		 	catch(Exception $e){
		 		
		 		var_dump($e);
		 	}
		 	
	}
	
	/********************************* / Update Segment ***************************************/
	
}



?>