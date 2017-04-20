<?php
/*
	Cette librairie g�re le load balancing entre processeur de paiement.
*/

class LBElement {
	var $pourcent = 100;
	var $element;
	
	function LBElement($_pourcent, $_element) {
		$this->pourcent = $_pourcent;
		$this->element = $_element;
	}
	
}

	function lb_paymentprocessor($tabLBElement) {
		
		$sumPourcent = 0;
		$aleatoire = 0; 
		$outElement;
		
		$outElement = $tabLBElement[0]->element;
		
		foreach($tabLBElement as $lbElement) {
			$sumPourcent += $lbElement->pourcent;
		}
		
		$aleatoire = mt_rand(0, $sumPourcent -1);
		foreach($tabLBElement as $lbElement) {
			if ($aleatoire < $lbElement->pourcent) {
				$outElement = $lbElement->element;
				break;
			}
			$aleatoire -= $lbElement->pourcent;
		}
		return $outElement;
	}
	
///-------------------------------
/// Cette fonction choisi al�atoirement et de mani�re �quir�partie un processeur parmis les PP du param�tre tabLBEelement
///-------------------------------

/*
$tab= array();
$tab[]= new LBElement(50, "PACNET");
$tab[]= new LBElement(50, "G2S"); 
$order_storeName = lb_paymentprocessor($tab);
echo $order_storeName;
*/

?>