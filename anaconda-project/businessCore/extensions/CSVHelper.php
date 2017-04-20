<?php

/**
 * Description of CSVHelper
 *
 * @author JulienL
 */
class CSVHelper extends \SplFileObject
{
	public function __construct( $fileName, $separator = '|', $mode = 'r' )
	{
		parent::__construct( $fileName, $mode );

		$this->setFlags( SplFileObject::READ_CSV );
		$this->setCsvControl( $separator );
	}

	// ********************* STATIC ************************* //
	/**
	 * Créé un fichier CSV a partir d'un objet Model representant une DB
	 * @param Object $Obj		Objet Model
	 * @param string $name		Nom du fichier
	 * @param string $dir		Dossier dans lequel enregistrer le fichier ( dossier /tmp/ par defaut )
	 * @param string $separator	Separateur du CSV
	 */
	public static function createWithModel( $Obj, $name, $dir = NULL, $separator = ';' )
	{
		$fileName = ( $dir == NULL ) ? '/tmp/'.$name : $dir.'/'.$name;

		if( !($fp = fopen( $fileName, 'w+' )) )
			return false;

		// Header :
		$header = NULL;
		foreach( $Obj->tableSchema->columns as $col => $info )
			$header .= $col.$separator;
		$header = substr( $header, 0, strlen($header) - strlen($separator) );

		if( !fwrite( $fp, $header.PHP_EOL ) )
			return false;

		// Content :
		foreach( $Obj->search( false, false )->getData() as $Data )
		{
			$content = NULL;
			foreach( $Obj->tableSchema->columns as $col => $info )
			{
				$content .= utf8_decode($Data->$col).$separator;
			}
			$content = substr( $content, 0, strlen($content) - strlen($separator) );

			if( !fwrite( $fp, $content.PHP_EOL ) )
				return false;
		}

		fclose( $fp );

		// Download :
		if( $dir == NULL )
		{
			header( 'Content-type: text/csv' );
			header( 'Content-Disposition: attachment; filename="'.$name.'"' );
			echo file_get_contents( $fileName );
			exit();
		}

		return $fileName;
	}

	/**
	 * Créé un fichier CSV a partir d'un objet Model representant une DB
	 * @param \CDbDataReader 	$data		Object CDbDataReader
	 * @param string			$name		Nom du fichier
	 * @param string			$dir		Dossier dans lequel enregistrer le fichier ( dossier /tmp/ par defaut )
	 * @param string			$separator	Separateur du CSV
	 */
	public static function createFromCDbDataReader( $data, $name, $dir = NULL, $separator = ';' )
	{
		$fileName = ( $dir == NULL ) ? '/tmp/'.$name : $dir.'/'.$name;

		if( !($fp = fopen( $fileName, 'w+' )) )
			return false;

		foreach( $data as $i => $Row )
		{
			// Header
			if( $i === 0 )
			{
				if( !fwrite( $fp, implode( $separator, array_keys( $Row ) ).PHP_EOL ) )
					return false;
			}

			// Content :
			if( !fwrite( $fp, implode( $separator, $Row ).PHP_EOL ) )
				return false;
		}

		fclose( $fp );

		// Download :
		if( $dir == NULL )
		{
			header( 'Content-type: text/csv' );
			header( 'Content-Disposition: attachment; filename="'.$name.'"' );
			echo file_get_contents( $fileName );
			exit();
		}

		return $fileName;
	}
}

?>