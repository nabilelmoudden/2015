<?php

	class Global_Base
	{
		
		private $pdo;
		public function __construct($pdo)
		{
			$this->pdo=$pdo;
		}
		public function ajouter($table,$parametre)
		{
				try{	
			
					$req='insert into '. $table .' values(';
					for ($i = 0 ;$i < count ($parametre) ; $i++ ){
						$req = "$req?,";
					}
					$req[strlen($req)-1]=")";
					
					$re=$this->pdo->prepare("$req");
				
					$re->execute($parametre);
				
				}
				catch(Exception $e)
				{
					echo('erreur') ;
				}
		}
		


	}

?>