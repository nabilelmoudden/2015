<?php
//UPDATE `invoice` as i LEFT JOIN internaute as it ON it.id = i.idInternaute INNER JOIN debugSiteInstit as d ON d.EMAIL = it.Email SET i.site = 'mx'
namespace Business;

/**
 * Description of RouterEMV
 *
 * @author JulienL
 * @package Business.Campaign
 */
class SignesVariables extends \SignesVariables
{

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Signes' => array(self::BELONGS_TO, '\Business\Signes', 'id_signe'),
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
	
static public function loadByIdSigne( $idsigne )
	{

		
		return self::model()->findAllByAttributes(array( 'id_signe' => $idsigne));
	}
}

?>