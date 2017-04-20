<?php

namespace Business;

/**
 * Description of AnacondaSegment
 *
 * @author MeskiM
 * @package Business.AnacondaSegment
 */
class AnacondaSegment extends \AnacondaSegment 

{
	
	/**
	 * creer un enregistrement dans la table anacondaSegment
	 *
	 * @author soufiane balkaid
	 * @param $id de
	 *        	segment au niveau de smartFocus
	 * @param $nameSegment le
	 *        	nom du segment
	 * @param $type de
	 *        	segment (shoot,livraison)
	 * @param
	 *        	idSubCampaign id de subCamapign
	 */
	public function createSegment($id, $nameSegment, $type, $idSubCampaign,$compte) {
		$anacondaSegment = new AnacondaSegment();
		$anacondaSegment->idSegment = $id;
		$anacondaSegment->nameSegment = $nameSegment;
		$anacondaSegment->dateCreation = date ( 'Y-m-d H:i:s' );
		$anacondaSegment->typeSegment = $type;
		$anacondaSegment->idSubCampaign = $idSubCampaign;
		$anacondaSegment->compteEMV=$compte;
		$anacondaSegment->save ();
	}
	static public function loadByIdProduct($idProduct) {
		return self::model ()->findAllByAttributes ( array (
				'idSubCampaign' => \Business\SubCampaign::loadByProduct ( $idProduct )->id 
		) );
	}
	public function deleteSegment($idSegment) {
		self::model ()->deleteAll ( 'idSegment = :idSegments', array (
				'idSegments' => $idSegment 
		) );
	}
    static public function loadByNameSegmentAndIdSubCampaign($name,$idsubcampaign) {
		return self::model ()->findAllByAttributes ( array (
				'nameSegment' => $name,
				'idSubCampaign'=>$idsubcampaign,
		) );
	}
	static public function updateIdSegmentById($id,$idSegment){
		self::model()->updateByPk($id,array('idSegment'=>$idSegment));
	}
	static public function loadById($ID) {
		return self::model ()->findAllByAttributes ( array (
				'id' => $ID
		) );
	}
	static public function loadSbSegments($type){
		return self::model ()->findAllByAttributes ( array (
				'typeSegment' => $type
		) );
	}
	
	
}

?>