<?php

namespace Business;

/**
 * Description of Variablesperso
 *
 * @author OthmaneH
 * @package Business.Variablesperso
 */
class Variablesperso extends \Variablesperso 
{
	public function init()
	{
		parent::init();

	}


	public function search( $order = false, $pageSize = 0 )
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



	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }
}

?>
