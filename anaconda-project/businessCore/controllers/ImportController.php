<?php
/**
 * Description of ImportController
 *
 * @author YSF
 */

class ImportController extends AdminController
{
	
	public $layout	= '//product/menu';

	
	/**
	 * @YSF
	 * actionVariablesPersoImport()
	 * 
	 */
	
	public function actionVariablesPersoImport(){
		
		    $Url = Yii::App()->createAbsoluteUrl( "Import/VariablesPersoImport");
		    $UrlAjax = Yii::App()->createAbsoluteUrl( "Import/AjaxVariablesPersoImport");
		    $baseUrl = Yii::app()->baseUrl;

			 if(isset($_POST['perso'])) {			 	
				 for ($i = 0; $i < count($_POST['perso']); $i++) {
						$VPE	= new \Business\Variablesperso();
						$VPE->nom    = $_POST['perso'][$i][2] ;
						$VPE->valueM = $_POST['perso'][$i][3] ;
						$VPE->valueF = $_POST['perso'][$i][4] ;
						if($VPE->validate()){	
							if( $VPE->save() ){
								
							}else{
								
							}
						}							
					}				
		      }
		      ###@Test###
		   	  
		      
			  #######
		    
  	    $this->renderPartial('//product/variablesPersoImport',array('url'=>$Url, 'baseurl'=>$baseUrl, 'urlajax'=>$UrlAjax));
		      		
	}
	
	/**
	 * @YSF
	 * actionAjaxVariablesPersoImport()
	 *
	 */
	
	public function actionAjaxVariablesPersoImport(){

		/**
		 * @var $Start
		 * @var $End
		 * @var $datasources
		 */
	     $datasources = '';
	     $Start       = '{"data":[';
		 $End         = ']}';
		 $results     = array();
		 $sql         = "SELECT * FROM  V2_variablesperso";
		
		 $map         = new CMap(array());
		 $nameArray   = array();
		
		 
				
		 #### Les persos du porteur actuel ########
			 $currentPerso = Yii::app()->db->createCommand($sql)->queryAll();			  
			 foreach ($currentPerso as $value) {
			 	$nameArray[] = $value['nom'];
			 }
		 ####
		
			 
	     
	     
		 foreach ($GLOBALS['listPorteur'] as $key =>$value) {
		 		 	
		 	if(strtolower(Yii::app()->params->lang) == strtolower(explode('-',$key)[1])){
		 		@include(SERVER_ROOT.$GLOBALS['listPorteur'][$key]['folder'].'/confi.php');
		 		if(isset($bdd_server)){
		 			if(!mysql_connect($bdd_server, $bdd_login, $bdd_mdp)){
		 				$error[] = 'error: ' . mysql_error();
		 			}
		 		}
		 		if(isset($bdd_database)){
		 			if(!mysql_select_db($bdd_database)){
		 				$error[] = 'error DB: ' . mysql_error();
		 				continue;
		 			}
		 		}
		 		mysql_query("set names 'utf8'");
		 		$result = mysql_query($sql);
		 		while($row = mysql_fetch_assoc($result)){
				  	   $results[] = $row;
				}
				$map->mergeWith($results);
		 	}
		 	unset($row, $results);
		 } 

         //Préparer le format JSON
		foreach ($map as $value) {
			if(in_array($value['nom'], $nameArray, true)){
				continue;				
			}else{					
				$datasources .=' ["'.$value['id'].'","'.$value['id'].'","'.htmlentities($value['nom']).'","'.htmlentities($value['valueM']).'","'.htmlentities($value['valueF']).'"],';
			}			
		}
		
		$datasources = substr($datasources,0,strlen($datasources)-1);
		$datasources = $Start.$datasources.$End;
		

		
		echo($datasources);
	
		Yii::app()->end();
		
		
	}
	
	/**
	 * @YSF
	 * action12SignesImport()
	 *
	 */
	
	public function action12SignesImport(){
		
		$map = new CMap(array());
		$Porteurs = array();
		$Url      = Yii::App()->createAbsoluteUrl( "Import/12SignesImport");
		$UrlAjax = Yii::App()->createAbsoluteUrl( "Import/12SignesUpdateProduct/");
		
				
		# Recupération des Porteurs de la meme langue que celui connecté
		foreach ($GLOBALS['listPorteur'] as $key =>$value) {
			if(strtolower(Yii::app()->params->lang) == strtolower(explode('-',$key)[1])){
				$Porteurs[]=$GLOBALS['listPorteur'][$key]['port-name'].'*'.$key;				
			}
 			
		}		

		 ####### POST #########
	    	$Sub = \Business\SubCampaign::load( Yii::app()->request->getParam('idSub'));			
			if(Yii::app()->request->getParam('FIDRef') != NULL){	
				
				$NomPorteur = strtolower(Yii::app()->request->getParam('PorteurName'));
				$RefProduct = strtolower(trim(Yii::app()->request->getParam('FIDRef')));
																
				$sqlSignes =   "SELECT x.id, x.name, x.idproduct,Number
								FROM   V2_12signes x
								INNER  JOIN  V2_product y ON y.id = x.idproduct
								WHERE  y.ref ='".$RefProduct."'"; # Recupération référence de la FID Porteur $_POST['FIDRef']

				###########
				@include(SERVER_ROOT.$GLOBALS['listPorteur'][$NomPorteur]['folder'].'/confi.php');
				if(isset($bdd_server)){
					if(!mysql_connect($bdd_server, $bdd_login, $bdd_mdp)){
						$error[] = 'error: ' . mysql_error();
					}
				}
				if(isset($bdd_database)){
					if(!mysql_select_db($bdd_database)){
						$error[] = 'error DB: ' . mysql_error();
					}
				}
				mysql_query("set names 'utf8'");
				$result = mysql_query($sqlSignes);
				
				if (mysql_num_rows($result) == 0) {
				
				}else{
					while($row = mysql_fetch_assoc($result)){
						$results[] = $row;
					}
					$map->mergeWith($results);
				}

				unset($row, $results);

				##########
					
				 foreach ($map as $Signe){
						$Signes12	= new \Business\Signes();
						$Signes12->name      =   $Signe['name'] ;
						$Signes12->idProduct =   Yii::app()->request->getParam('idSub');
						$Signes12->Number    =   $Signe['Number'];
					    $Signes12->save();
					    $SigneNum = $Signes12->getPrimaryKey();
						$sqlVarSignes =  "SELECT  x.name, x.value
										  FROM   V2_12signes_variables x
										  INNER  JOIN  V2_12signes y ON x.id_signe = y.id
										  WHERE  x.id_signe ='".$Signe['id']."'"; # Recupération référence de la FID Porteur $_POST['FIDRef']
	
						mysql_query("set names 'utf8'");
						$result = mysql_query($sqlVarSignes);
						
						while($VarSigne = mysql_fetch_assoc($result)){
								$VarSignes12	= new \Business\SignesVariables();
								$VarSignes12->id_signe =    $SigneNum ;
								$VarSignes12->name     =    $VarSigne['name'];
								$VarSignes12->value    =    $VarSigne['value'];
								$VarSignes12->save();
						}							
				}
			
			 	echo(json_encode('oc'));
			 	Yii::app()->end();
			}

		$this->renderPartial('//product/SignesImport',array('url'=>$Url,'urlajax'=>$UrlAjax, 'ListPorteurs' => $Porteurs,'lang'=>Yii::app()->params->lang,'idSub'=>$Sub->Product->id));
	
	}


	
	
	public function action12SignesUpdateProduct(){
		
		@include(SERVER_ROOT.$GLOBALS['listPorteur'][Yii::app()->request->getParam('porteur')]['folder'].'/confi.php');
		if(isset($bdd_server)){
			if(!mysql_connect($bdd_server, $bdd_login, $bdd_mdp)){
				$error[] = 'error: ' . mysql_error();
			}
		}
		if(isset($bdd_database)){
			if(!mysql_select_db($bdd_database)){
				$error[] = 'error DB: ' . mysql_error();
			}
		}
		$sqlProduct="SELECT  DISTINCT(x.ref)
					 FROM    V2_product  x
					 INNER  JOIN  V2_12signes y ON x.id = y.idproduct";
		mysql_query("set names 'utf8'");
		$result = mysql_query($sqlProduct);
		while($row = mysql_fetch_assoc($result)){
			$results[] = $row;
		}
		echo(json_encode($results));		
		Yii::app()->end();

	}
}