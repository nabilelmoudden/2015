<?php
/**
 * @desc ce controlleur à pour but de remplir la table V2_reflationuser lors d'envoi des emails depuis Smart Focus.
 *
 * @author Yacine Rrami / Soufiane Balkaid
 * @package Controllers
 */
class ReflationController extends AdminController {
	
	
	
	public function actionReflationUser() {
			
			$ch = new \Business\Reflationuser ();
			$ch->email=$_GET['emailUser'];
			$ch->linkmailName=$_GET['emailName'];
			$ch->shootDate = date ( \Yii::app ()->params ['dbDateTime'] );
			$ch->save();
	}
	public function actionAddReflationData() {
		 
		$ref = new \Business\Reflationuser();
		$refs       = $ref->search();
		var_dump($refs->data);
		
		die();
	}
	
}

?>