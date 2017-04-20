<?php  
namespace Business;

/**
 * Description of Product_V1
 *
 * @author J
 * @package Business.Campaign
 *
 */

class PariteInvoice extends \PariteInvoice  {

//implements Interface_Camp

	public function init(){
		parent::init(); 	
	}

	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.

		return array(

		);

	}



	/**

	 * Recherche

	 * @param string $order Ordre

	 * @param int $pageSize	Nb de result par page

	 * @return CActiveDataProvider	CActiveDataProvider 

	 */

	public function search( $order = false, $pageSize = false){
		
	
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
	
	/**
	 *
	 * @param string code
	 * @return \Business\Site
	 */
	 
	static public function loadByDevise( $devise )
	{
		return self::model()->findByAttributes( array( 'devise' => $devise ) );
	}


}
?>