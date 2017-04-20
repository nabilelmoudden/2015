<?php



namespace Business;



/**
 * Description of Alert
 *
 * @author YacineR 
 * @package Business.Alert
 */

class Alert extends \Alert

{

 /**
  * Retourne l'Alert correspondant
  * @param type $id
  * @return \Business\Alert
  */
 static public function load( $id )
 {
  return self::model()->findByPk( $id );
 }
 

}



?>