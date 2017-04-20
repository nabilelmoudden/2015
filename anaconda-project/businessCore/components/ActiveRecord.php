<?php
/**
 * Description of ActiveRecord
 *
 * @author JulienL
 * @package ActiveRecord
 */
abstract class ActiveRecord extends CActiveRecord
{
	/**
	 * Represente la connection courante a la DB
	 * @var CDbConnection	Connection a la DB
	 */
	static private $currentDB	= false;

	/**
	 * Contient les erreurs de validation, tous modele confondu
	 * @var array	tableau d'erreurs
	 */
	static private $errors		= array();

	public function init()
	{
		parent::init();

		$this->onAfterValidate	= array( $this, 'saveError' );
	}

	/**
	 * Stocke les erreurs de tous les models
	 * @return boolean
	 */
	public function saveError()
	{
		self::$errors = array_merge( self::$errors, $this->getErrors() );
		return true;
	}

	
	public function getDbConnection() {
        self::$currentDB = Yii::app()->db;
        if (self::$currentDB instanceof CDbConnection) {
            self::$currentDB->setActive(true);
			self::$currentDB->enableProfiling	= true;
            return self::$currentDB;
        }
        else
            throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));
    }

	/**
	 * Retourne le nom de la table avec son prefix
	 * @return string
	 */
	public function tableName()
	{
		return $this->tableNamePrefix().$this->rawTableName();
	}

	/**
	 * Retourne le prefix des tables
	 * @return string
	 */
	protected function tableNamePrefix()
	{
		return ( isset(Yii::app()->params['tablePrefix']) ) ? Yii::app()->params['tablePrefix'] : NULL;
	}

	/**
	 * Define the name of the table
	 */
	abstract protected function rawTableName();

	/**
	 *
	 * @return array
	 */
	public static function getAllErrors()
	{
		return self::$errors;
	}

	public function save( $runValidation = true, $attributes = NULL )
	{
		try
		{
			return parent::save( $runValidation, $attributes );
		}
		catch( CDbException $E )
		{
			self::$errors[] = $E->getMessage();
		}
	}

	// ************************ STATIC ************************ //

	/**
	 * Permet de changer de DB
	 * @param string $dbName	Nom de la conf representant la DB
	 * @throws CDbException
	 */
	static public function setDB( $dbName )
	{
		if( isset(Yii::app()->$dbName) && Yii::app()->$dbName instanceof CDbConnection )
		{
			if( is_object(self::$currentDB) )
				self::$currentDB->setActive( false );

			self::$currentDB = Yii::app()->$dbName;
			self::$currentDB->setActive( true );
		}
		else
			throw new CDbException( $dbName.' n\'est pas une connection valide' );
	}
}

?>