<?php

namespace Business;

/**
 * Description of EmvExport
 *
 * @author JulienL
 * @package Business.EmvExport
 */
class EmvExport extends \EmvExport
{
	const TYPE_HB		= 1;
	const TYPE_SB		= 2;
	const TYPE_DESABO	= 3;

	public $affiliatePlatform;
	public $affiliateCampaign;

	public function init()
	{
		parent::init();

		$this->tableSchema->columns['affiliatePlatform']	= NULL;
		$this->tableSchema->columns['affiliateCampaign']	= NULL;

		$this->onAfterFind	= array( $this, 'addColumn' );
	}

	protected function addColumn()
	{
		$source	= explode( '_', $this->SOURCE );

		if( $source[0] == 'pr' && isset($source[1]) && isset($source[2]) )
		{
			if( $source[1] > 0 && ($AP = \Business\AffiliatePlatform::load( $source[1] )) )
				$this->affiliatePlatform	= $AP->label;

			if( $source[2] > 0 && ($AC = \Business\AffiliateCampaign::load( $source[2] )) )
				$this->affiliateCampaign	= $AC->label;
		}
		else
		{
			$this->affiliatePlatform	= NULL;
			$this->affiliateCampaign	= NULL;
		}

		return true;
	}

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function search( $order = false, $pageSize = 100 )
	{
		if( $this->type == 'hard' )
			$this->type = self::TYPE_HB;
		else if( $this->type == 'soft' )
			$this->type = self::TYPE_SB;
		else if( $this->type == 'desabonne' )
			$this->type = self::TYPE_DESABO;

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
	 * @param string $email
	 * @return \Business\EmvExport
	 */
	static public function load( $email )
	{
		return self::model()->findByPk( $email );
	}
}

?>