<?php
//UPDATE `invoice` as i LEFT JOIN internaute as it ON it.id = i.idInternaute INNER JOIN debugSiteInstit as d ON d.EMAIL = it.Email SET i.site = 'mx'
namespace Business;

/**
 * Description of RouterEMV
 *
 * @author JulienL
 * @package Business.Campaign
 */
class Signes extends \Signes
{

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Product' => array(self::BELONGS_TO, '\Business\Product', 'idProduct'),
		);
	}


	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\RouterEMV
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	static public function loadByIdProductBySigneNumber( $idproduct,$number )
	{

		
		return self::model()->findByAttributes(array( 'idProduct' => $idproduct,'Number' => $number ));
	}
}

?>