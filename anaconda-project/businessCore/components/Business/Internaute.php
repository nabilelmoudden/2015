<?php
namespace Business;
/**
 * This is the model class for table "internaute".
 *
 * @author JalaL
 * @package Business.Internaute
 */
class Internaute extends \Internaute
{
	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(

			'Invoice' => array(self::HAS_MANY, '\Business\Invoice', array( 'IDInternaute' => 'ID' ) ),
		);
	}

	/**
	 * Retourne le nom complet de l'utilisateur
	 * @return string	Nom complet
	 */
	public function name()
	{
		return ucfirst($this->Firstname).' '.strtoupper($this->Lastname);
	}

	/**
	 * Retourne un objet DateTime representant la date de naissance
	 * @return \DateTime
	 */
	public function getBirthday( $format = false )
	{
		if( $this->birthday == '0000-00-00 00:00:00' )
			return false;
		if( $this->Birthday == '')
	        $this->Birthday = '1985-02-02 15:59:29';
		
		$Date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $this->Birthday );
		
		return ( $format == false ) ? $Date : $Date->format($format);
	}


	/**
	 * Retourne le numero du signe astrologique
	 * @return int
	 */
	public function getNumberSignAstro()
	{
		\Yii::import( 'ext.DateHelper' );
		return \DateHelper::getAstroSign( $this->getBirthday( 'Y-m-d' ) );
	}

	/**
	 * Retourne le nom du signe astrologique
	 * @return string
	 */
	public function getSignAstro()
	{
		\Yii::import( 'ext.DateHelper' );
		$tab = \DateHelper::getAstroSignByNumber( $this->getNumberSignAstro() );
		return ( isset($tab[1]) ) ? $tab[1] : false;
	}

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function search( $order = false, $pageSize = 20 )
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
	 * @return \Business\User
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 * Recupere un User par son adresse mail
	 * @param type $mail
	 * @return \Business\User
	 */
	static public function loadByEmail( $mail )
	{
		
		return self::model()->findByAttributes(array( 'Email' => $mail ));
	}
}