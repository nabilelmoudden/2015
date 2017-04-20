<?php


namespace Business;

/**
 * This is the model class for table "campaign".
 *
 * The followings are the available columns in table 'campaign':
 * @property integer $id
 * @property string $label
 * @property integer $type
 *
 * The followings are the available model relations:
 * @property Subcampaign[] $SubCampaign
 *
 * @package Models.Campaign
 */
class Achat extends \Achat
{
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Campaign the static model class
	 */


	/**
	 * @return array relational rules.
	 */
	
	
	public function rules()
	{
		return(parent::rules());
	}
	
	
	public function relations()
	{
		return array(
				'LigneAchat' => array(self::HAS_MANY, '\Business\LigneAchat', 'idAchat')
		);
	}


	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($order = false, $pageSize = '25')
	{
		$Provider = parent::search();
		
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;
		if( $order != false )
			$Provider->criteria->order = $order; 
		
		return $Provider;
	}
	
	
	// *********************** STATIC *********************** //
	
	
	static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 *
	 * @param type $id
	 * @return \Business\Product_V1
	 */
	
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	static public function getListeByservice($service)
	{
		$Achat	= new \Business\Achat( 'search' );
		
		$Achat->getDbCriteria()->addCondition(" service_demandeur = '$service'");
	
		return $Achat;
	
	}
	

	
	// ********************************* Business Methodes ********************************* //
	


	
	
}
