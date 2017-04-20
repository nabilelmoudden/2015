<?php
/**
 * Description of ActiveRecordBehavior
 *
 * @author JulienL
 * @package ActiveRecord
 */
class ActiveRecordBehavior extends CActiveRecordBehavior
{
	/**
	 * Contient les valeurs des attributs au moment de la recuperation
	 * @var array
	 */
	private $oldAttributes = array();

	public function afterFind( $Event )
	{
		$this->oldAttributes = $this->Owner->getAttributes();
	}

	/**
	 * Retourne les anciens attributs du model ( avant mise a jour )
	 * @return array	Ancien attribut du model
	 */
	public function getOldAttributes()
	{
		return $this->oldAttributes;
	}
}

?>