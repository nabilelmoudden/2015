<?php

/**
 * le composant CRUD.
 *
 * il contient les methodes permettant l'insertion et la mise à jour des données des deux tables  V2_ispreport et V2_ispcompaign .
 *
 *@package businessCore\components\EMV\IspExportReport
 */


use \Business\IspCompaign ;
use \Business\IspReport ;


class IspCrudBD  {

  /**
   * cette methode permet d'inserer les données d'une compagne dans la dase de données.
   * @return void 
   */
 
public function insertcompaign($idcompaign,$triggername,$idmessage,$messagename,$sendDate,$porteur,$site){
	
	  \Controller::loadConfigForPorteur('fr_rinalda');

		$senddate =str_replace('T',' ',explode("+",$sendDate)[0]);
	  	$data1=IspCompaign::model()->findAll(array(
                      
                      'condition' => 'idcompaign_sf = :idcompaign AND triggername = :triggername  AND senddate= :sendDate AND site = :site AND porteur= :porteur',
                      'params'    => array(':idcompaign' => $idcompaign ,':triggername' => $triggername ,':sendDate' => $senddate ,':site' => $site , ':porteur' => $porteur)
                  ));
	if($data1){
		
	}
	else{

		
		    $model=new IspCompaign;
			$model->idcompaign_sf=$idcompaign;
			$model->triggername=$triggername;
			$model->idmessage=$idmessage;
			$model->messagename=$messagename;
	        $model->senddate=$sendDate;
	        $model->porteur=$porteur;
	        $model->site=$site;
	       

			if ($model->validate()){
		    $model->save();
			} else {
			    print_r($model->errors);
			}

	
	}

	  
	
			
	


}

public function reupdatesite($porteur,$porteurDB,$triggername){

							  $lng=explode('_', $porteur);
						        $sitee=$lng[0];
						        $_GET['site']=$sitee;
						       \Controller::loadConfigForPorteur($porteur);

							$nombre=Yii::app()->params['isp_porteur_name'][$porteurDB]['nombre'];
							
		       			
							if($nombre == '1'){
								$site=Yii::app()->params['isp_porteur_name'][$porteurDB]['site'];
								
							}
							else {

								for($p=0;$p<$nombre;$p++){
									$siteporteur=Yii::app()->params['isp_porteur_name'][$porteurDB]['site'][$p];

									
									if (strstr($triggername, '['.$siteporteur.']') ||strstr($triggername, '['.strtolower ($siteporteur).']') ){

													 /* $explode1 = substr(strrchr($row->triggername, "["), 1);

									                  $site =strtoupper(strstr($explode1, ']', true));*/

									              
			 
                                                    $site = $siteporteur;

									              
									}	
								}

								if(empty($site)){
								/*	if(strstr($triggername, "ASH") && stripos($triggername, "ASH") == 0){
				                        
				                        $site="IN";
									

									}
									else{*/
										$site="0";
									/*}*/
								}

							}

									
						          
							

							return $site ;

	}


	 /**
   * cette methode permet de mettre à jour le site d'un porteur au niveau de la base de données.
   * @return void 
   */
	public function updatesite($porteur){

		/*$porteurfid=$porteur.'_fid';

		$msg="";
		$ancien="";
      	$crontwo=false;
      	$crontree=false;
      	$cronone=false;
		 \Controller::loadConfigForPorteur('fr_rinalda');

		 $dataReader=IspCompaign::model()->findAll(array(
			                      
                      'condition' => 'site = :site AND porteur IN (:porteur,:a)',
                      'params'    => array(':site' => '0' , ':porteur' => $porteur,':a'=>$porteurfid)
                  ));

		foreach ( $dataReader as $row){
		if($cronone==false){
			$ancien=$row->porteur ; $cronone=true;}

			
						   
                    \Controller::loadConfigForPorteur($porteur);
							$nombre=Yii::app()->params['isp_porteur_name'][$row->porteur]['nombre'];
							
		       			
							if($nombre == '1'){
								$site=Yii::app()->params['isp_porteur_name'][$row->porteur]['site'];
								
							}
							else {

									
						             if (strstr($row->triggername, '[')){

										*/			 /* $explode1 = substr(strrchr($row->triggername, "["), 1);

									                  $site =strtoupper(strstr($explode1, ']', true));*/

									           /*     $explode1 = explode("[",$row->triggername);
			 
                                                    $site =explode(']',$explode1[1])[0];

									              
									}	
									elseif(strstr($row->triggername, "ASH") && stripos($row->triggername, "ASH") == 0){
				                        
				                        $site="IN";
									

									}
									else{
										$site="0";
									}
							}
			if($site!=""){ 

				
					   
				
				 	if (!isset($GLOBALS['porteurMap']['fr_rinalda']) || !\Controller::loadConfigForPorteur('fr_rinalda')){
		                $msg .= '<div style="color:red"><u></u> : La configuration BD du porteur fr  est introuvable</div>';
						}
						else{   
							\Controller::loadConfigForPorteur('fr_rinalda');
							 $row->site=strtoupper($site);
						    $row->save(); 
						    if($crontwo==false  ){
						    	$msg.="DATE:".date('d/m/Y')." Insertion des sites effectuée avec succès pour le porteur ".$row->porteur."</br>";
						    	$crontwo=true;
						    }
						    elseif ($crontree==false && $row->porteur!=$ancien ) {
						    	$msg.="DATE:".date('d/m/Y')." Insertion des sites effectuée avec succès pour le porteur ".$row->porteur."</br>";
						    	$crontree=true;
						    }
						}
			}
			
				

			
			}
	return $msg ;
	    		
		*/
	}


	 /**
   * cette methode permet de faire la mise à jour des ids des messages de la base de données.
   * @return string contient les messages d'erreurs et de validation 
   */
	public function updateidmessage($porteur,$porteurDB){
		$lng=explode('_', $porteur);
        $sitee=$lng[0];       
        $site=strtoupper($sitee);

		$msg="";
		$ispAlimenterBD=new \IspAlimenterBD();
        $token_msg=$ispAlimenterBD->gettokencron($porteur);
        	

        for($tag=0;$tag<=1;$tag++){

        	if($token_msg[$tag]!=""){
        		switch ($tag) {
        			case 0:
        				$porteur_name=$porteurDB;$compte="acq";
        				break;
    				case 1:
    				$porteur_name=$porteurDB.'_fid';$compte="fid";
    				break;
        			
        		}
        		/*if($tag==0){$porteur_name=$porteurDB;$compte="acq";}elseif($tag==1){$porteur_name=$porteurDB.'_fid';$compte="fid";}*/

        \Controller::loadConfigForPorteur('fr_rinalda');
        $dataReader=IspCompaign::model()->findAll(array(
                      
                      'condition' => 'idmessage = :idmessage AND porteur= :porteur AND site= :site',
                      'params'    => array(':idmessage' => '0' , ':porteur' => $porteur_name ,':site' => $site )
                  ));
       	$cronone=false;
       	$crontwo=false;
			foreach ( $dataReader as $row){


		
				\Controller::loadConfigForPorteur($porteur);
			           $messageid_msg=$ispAlimenterBD->getmesageid($token_msg[$tag],$row->idcompaign_sf,$porteur,$compte);

			           	
					
					if($cronone==false){
						$msg.=$messageid_msg[2];
						$cronone=true;
					}	
					if($messageid_msg[$tag]!=""){ 
			
				
						if (!isset($GLOBALS['porteurMap']['fr_rinalda']) || !\Controller::loadConfigForPorteur('fr_rinalda')){
		                $msg .= '<div style="color:red"><u></u> : La configuration BD du porteur fr  est introuvable</div>';
						}
						else{   
							\Controller::loadConfigForPorteur('fr_rinalda');
							
							$row->idmessage=$messageid_msg[$tag];

						    $row->save(); 
						 
						   
						    if($crontwo==false){
						    	$msg.="DATE:".date('d/m/Y')."Insertion des ids messages effectuée avec succès pour le porteur ".$porteur_name." pour le compte ".$compte.'</br>';
						    	$crontwo=true;
						    }
						}
					}	

				
			}

		}
	  }

	  return $msg ;
		
	}

	/**
   * cette methode permet de faire la mise à jour des noms des messages de la base de données.
   * @return string contient les messages d'erreurs et de validation 
   */

	public function updatenamemessage($porteur,$porteurDB){
		$lng=explode('_', $porteur);
        $sitee=$lng[0];       
        $site=strtoupper($sitee);

		


      $msg="";
		$ispAlimenterBD=new \IspAlimenterBD();
        $token_msg=$ispAlimenterBD->gettokencron($porteur);
        	
	
        for($tag=0;$tag<=1;$tag++){

        	if($token_msg[$tag]!=""){

        		if($tag==0){$porteur_name=$porteurDB;$compte="acq";}elseif($tag==1){$porteur_name=$porteurDB.'_fid';$compte="fid";}
        \Controller::loadConfigForPorteur('fr_rinalda');
       $dataReader=IspCompaign::model()->findAll(array(
                      
                      'condition' => 'messagename = :messagename AND porteur= :porteur AND site= :site',
                      'params'    => array(':messagename' => 'null' ,':porteur' => $porteur_name ,':site' => $site  )
                  ));
       	$cronone=false;
       	$crontwo=false;
			foreach ( $dataReader as $row){

			
	
				\Controller::loadConfigForPorteur($porteur);
			     
			           $messagename_msg=$ispAlimenterBD->getmesagename($token_msg[$tag],$row->idmessage,$porteur,$compte);

					if($cronone==false){
						$msg.=$messagename_msg[2];
						$cronone=true;
					}	
					if($messagename_msg[$tag]!=""){ 
					   
					  
						if (!isset($GLOBALS['porteurMap']['fr_rinalda']) || !\Controller::loadConfigForPorteur('fr_rinalda')){
		                $msg .= '<div style="color:red"><u></u> : La configuration BD du porteur fr  est introuvable</div>';
						}
						else{   
							\Controller::loadConfigForPorteur('fr_rinalda');
							$row->messagename=$messagename_msg[$tag];
						    $row->save(); 
						    if($crontwo==false){
						    	$msg.="DATE:".date('d/m/Y')."Insertion des noms des messages effectuée avec succès pour le porteur ".$porteur_name." pour le compte ".$compte.'</br>';
						    	$crontwo=true;
						    }
						}
					}	

				
			}

		}
	  }

	  return $msg ;
   
	
	}

	/**
   * cette methode permet de retourner l'id d'une compagne suivant les informations passées en paramètres.
   * @return string représente l'id d'une compagne
   */
public function getcompaignid($triggername,$messagename,$senddate,$porteur,$site){

		\Controller::loadConfigForPorteur('fr_rinalda');

     $model=IspCompaign::model()->findAll(array(
                      'condition' => "triggername = :triggername AND messagename= :messagename AND senddate like '$senddate%' AND porteur= :porteur AND  site= :site",
                      'params'    => array(':triggername' => $triggername , ':messagename' => $messagename , ':porteur' => $porteur , ':site' => $site )
                  ));


		if($model){

		$idcompaign=	$model['0']->idcompaign_sf;
        $id=	$model['0']->id;
		return array($idcompaign,$id) ;

		}
		else{
			return "0";
		}
      
		
		return;
  }


/**
   * cette methode permet  d'inserer dans la table V2_ispreport les informations relatives à chaque rapport généré.
   * @return void 
   */
   public function insertreport($idcompaign,$idreport,$namereport,$creationdate,$personne,$chemin,$isdownloaded){
\Controller::loadConfigForPorteur('fr_rinalda');
   	    $model=new IspReport();
		$model->idispcompaign=$idcompaign;
		$model->idreport_sf=$idreport;
		$model->namereport=$namereport;
		$model->creationdate=$creationdate;
        $model->personne=$personne;
        $model->chemin=$chemin;
        $model->isdownloaded=$isdownloaded;
		if ($model->validate()){
	    $model->save();
		} else {
		    print_r($model->errors);
		}

   }

   /**
   * cette methode permet d'acquérir depuis la table V2_ispreport les informations relatives à un rapport généré.
   * @return array  indique les informations relatives à un rapport suivant les données passées en paramètres
   */

public function getreportid($namereport,$personne,$creationdate){
\Controller::loadConfigForPorteur('fr_rinalda');
		$model = Report::find()
			->where('namereport = :namereport', [':namereport' => $namereport])
			->andWhere('personne = :personne', [':personne' => $personne])
			->andWhere("creationdate LIKE '$creationdate%'")
			->all();

		if($model){

		
		return $model ;

		}
		else{
		
			return "0";
		}
      
	
		return;
  }


   /**
   * cette methode était utilisée dans l'ancienne version
   */
   public static function getsite($porteur_id) {
   	\Controller::loadConfigForPorteur('fr_rinalda');
        $data=Compaign::find()
       ->where('porteur = :porteur', [':porteur' => $porteur_id])
       ->select(['site as id','site as name'])->asArray()->all();
         
            return array_unique($data,SORT_REGULAR);
        }
   /**
   * cette methode était utilisée dans l'ancienne version
   */
   public static function gettrigger($porteur_id,$site) {
   	\Controller::loadConfigForPorteur('fr_rinalda');
        $data=Compaign::find()
       ->where('porteur = :porteur', [':porteur' => $porteur_id])
       ->andWhere('site = :site', [':site' => $site])
       ->select(['triggername as id','triggername as name'])->asArray()->all();
         
            return array_unique($data,SORT_REGULAR);
        }
   /**
   * cette methode était utilisée dans l'ancienne version
   */
    public static function getmessage($trigger_id) {
    	\Controller::loadConfigForPorteur('fr_rinalda');
        $data=Compaign::find()
       ->where('triggername = :triggername', [':triggername' => $trigger_id])
      
       ->select(['messagename as id','messagename as name'])->asArray()->all();
    
           return array_unique($data,SORT_REGULAR) ;
        }

 /**
   * cette methode permet d'inserer les informations relatives à des compagnes lancées dans le serveur Smartfocus par porteur.
   * @return string  indique les messages d'erreurs et de validation
   */
public function conloaddb1($porteur,$porteurDB){

       $msg="";
       $ispAlimenterBD=new \IspAlimenterBD();
       $ispCrudBD=new \IspCrudBD();

       $token_msg = $ispAlimenterBD->gettokencron($porteur);    
       $msg .=$token_msg[2];

 for($tag=0;$tag<=1;$tag++){
 	
 	    $i=1;
 	$data=array();
       if($token_msg[$tag]!=""){
       			if($tag==0){$porteur_name=$porteurDB;$compte="acq"; }elseif($tag==1){$porteur_name=$porteurDB.'_fid';$compte="fid"; }
       $one_msg=$ispAlimenterBD->getcompaigns($token_msg[$tag],1,$porteur,$compte);

       $msg .=$one_msg[1];
       $one=$one_msg[0];

    


       if($one!="" && $one['nbTotalItems']!=0){
       $xmlone=(object)$one;
       $nextPage=$xmlone->nextPage;
	   $xmlobject=$xmlone->campaigns;

         foreach($xmlobject->campaign as $node){
                        $data[] = $node;
                     
         }
   	 
          $i++;
          while ($nextPage) {
                     
                    $one2_msg=$ispAlimenterBD->getcompaigns($token_msg[$tag],$i,$porteur,$compte); 
                    
                    $msg .=$one2_msg[1];
       				$one2=$one2_msg[0];
                    if($one2!=""){
                    $xmlone2=(object)$one2;
                     
                    $xmlobject2=$xmlone2->campaigns;
                    $nextPage=$xmlone2->nextPage;
               
                    foreach($xmlobject2->campaign as $node){

                        $data[] = $node;

                    }
                    
                     $i++;

                 }

         }

         
       $nbTotalItems =$one['nbTotalItems'];

       $cronone=false;
		for($ligne=0;$ligne<$nbTotalItems ;$ligne++){

            
       
            if(count($data) == $nbTotalItems ){
		           
		             $tree=$data[$ligne];
					 $compaignid=(string)$tree->campaignId;
					 $triggername=(string)$tree->name;
					 $sendDate=(string)$tree->sendDate;
			}
			else{	
				  if($ligne<$nbTotalItems-1 )	{
					 $tree=$data[$ligne];
					 $compaignid=(string)$tree->campaignId;					
					 $triggername=(string)$tree->name;
					 $sendDate=(string)$tree->sendDate;
					}
					elseif($ligne==$nbTotalItems-1 ){
		             $compaignid=(string)$data[$nbTotalItems-1];
		             $triggername=(string)$data[$nbTotalItems];
		             $sendDate=(string)$data[$nbTotalItems+2];
					}
		}
			$site =strtoupper($ispCrudBD->reupdatesite($porteur,$porteurDB,$triggername));
			 if (!isset($GLOBALS['porteurMap']['fr_rinalda']) || !\Controller::loadConfigForPorteur('fr_rinalda')){
	                $msg .= '<div style="color:red"><u></u> : La configuration BD du porteur fr  est introuvable</div>';
			  }
			  else{
			  		  
		        	

		       /* 	echo $compaignid ;
		        	echo '</br>';
		        	echo $triggername ;
		        	 	echo '</br>';
		        	 	echo $sendDate ;
		        	 	echo '</br>';
		        	 	echo $porteur_name ;
		        	 	echo '</br>';
		        	 	echo $site ;
		        	 	echo '</br>';
		        	die();*/
		        	
		            $msg.=$ispCrudBD->insertcompaign($compaignid,$triggername,"0","null",$sendDate,$porteur_name,$site);

		           
		        	if($cronone==false){
		             $msg.="DATE:".date('d/m/Y').'insertion des compaigns effectuée avec succès du porteur '.$porteur.'  '.$compte.'</br>';
		             $cronone=true;
		         }
            
           }
         }


     }
     else{

     	$msg.="DATE:".date('d/m/Y').'Aucune compagne trouvée pour '.$porteur.'  '.$compte.'</br>';

     }
 }

 }

 return $msg;
}
}
?>