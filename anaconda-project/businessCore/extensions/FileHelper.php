<?php
/**
 * Description of FileHelper
 *
 * @author JulienL
 * @package Helper
 */
class FileHelper extends CFileHelper
{
	/**
	 * Supprime le contenu d'un repertoire ( Seulement les fichiers )
	 * @param string $dir Repertoire dans lequel supprimé les fichiers
	 * @param array $extAuth Tableau contenant les extensions a matcher
	 * @param array $exclude Tableau contenant les extensions OU nom de fichiers a exclure de la suppression
	 * @return boolean
	 */
	static public function rm( $dir, $extAuth = array(), $exclude = array() )
	{
		$docRoot = \Yii::app()->getbasePath();

		if( $dir[0] != '/' )
			$dir = $docRoot.'/'.$dir;

		if( strpos( $dir, $docRoot ) === false || !is_dir($dir) )
			return false;

		$Dir = Dir( $dir );
		while( ($read = $Dir->read()) )
		{
			if( $read[0] == '.' || !is_file($dir.'/'.$read) )
				continue;

			$ext = explode( '.', $read );
			$ext = end( $ext );

			if( is_array($extAuth) && count($extAuth) > 0 && !in_array( $ext, $extAuth ) )
				continue;

			if( in_array( $ext, $exclude ) || in_array( $read, $exclude ) )
				continue;

			if( !unlink( $dir.'/'.$read ) )
				return false;
		}

		return true;
	}

	/**
	 * Retourne le contenu d'un dossier sous forme d'un tableau multidimension
	 * @param	string	$dirName	Chemin vers le dossier
	 * @param	bool	$recursif	Recherche recursive
	 * @param	array	$extAuth	Tableau d'extension authorizée
	 * @param	array	$exclude	Tableau de dossier a exclure
	 * @return	bool	True / False
	 */
	static public function dirToArray( $dirName, $recursif = true, $extAuth = array(), $exclude = array() )
	{
		if( !is_dir($dirName) )
			return false;

		$Dir = dir( $dirName );
		$tab = array();

		$name = basename($dirName);

		while( ($read = $Dir->read()) )
		{
			if( $read[0] == '.' || in_array( $read, $exclude ) )
				continue;

				if( $recursif && is_dir($dirName.'/'.$read) )
				$tab[$read] = self::dirToArray( $dirName.'/'.$read, $recursif, $extAuth );
			else if( is_file($dirName.'/'.$read) )
			{
				$ext = self::getExtension( $dirName.'/'.$read );

				if( count($extAuth) == 0 || in_array( $ext, $extAuth) !== false )
					$tab[] = $read;
			}
		}

		return $tab;
	}

	/**
	 * Supprime tous les fichiers d'un repertoire
	 * @param string $directory Repertoire
	 * @param array $exclude Tableau de fichier a ne pas supprimer
	 * @return boolean
	 */
	static public function cleanDir( $directory, $recursive = true, $exclude = array() )
	{
		if( !is_dir($directory) )
			return false;

		$Dir = dir( $directory );
		while( ($read = $Dir->read()) != false )
		{
			if( $read[0] == '.' || in_array( $read, $exclude ) )
				continue;

			if( is_file($directory.'/'.$read) )
				unlink( $directory.'/'.$read );
			else if( $recursive && is_dir($directory.'/'.$read) )
			{
				if( self::cleanDir( $directory.'/'.$read ) )
					rmdir( $directory.'/'.$read );
			}
		}

		return true;
	}

	/**
	 * Fais une sauvegarde du fichier puis ecrase le contenu du fichier
	 * @param	string	$fileName	Chemin vers le fichier
	 * @param	string	$data		Donnée a enregistrer dans le fichier
	 * @return	bool	True / False
	 */
	static public function overwriteWithHistory( $fileName, $data )
	{
		if( is_file($fileName) )
		{
			$dirName			= dirname($fileName);
			$tmp				= explode( '.', basename($fileName) );
			$fileNameWithoutExt = $tmp[0];
			$extension			= $tmp[1];

			if( !copy( $fileName, $dirName.'/'.$fileNameWithoutExt.'-'.date( 'Ymd' ).'.'.$extension.'.BKP' ) )
				return false;
		}

		$fp	= fopen( $fileName, 'w+' );
		fwrite( $fp, $data );
		fclose( $fp );

		shell_exec( 'chmod 777 "'.$fileName.'"' );

		return true;
	}

	/**
	 * Retourne le contenu d'un template
	 * @param	string	$fileName		Chemin vers le fichier
	 * @param	bool	$replaceTwig	Doit on remplacer les variables Twig par leur valeur ?
	 * @return	string	Contenu du template
	 */
	public static function getTemplate( $fileName, $replaceTwig = true )
	{
		if( !is_file($fileName) )
			return false;

		$replace		= array( '{{ App.baseUrl() }}' );
		$replaceWith	= array( Yii::app()->baseUrl );
		$temp			= file_get_contents( $fileName );

		if( $replaceTwig )
			$temp = str_replace( $replace, $replaceWith, $temp );

		return $temp;
	}

	/**
	 * Sauvegarde le contenu d'un template
	 * @param	string	$fileName		Chemin vers le fichier
	 * @param	string	$date			Contenu du fichier
	 * @param	bool	$replaceTwig	Doit on remplacer les valeurs par leurs variables Twig ?
	 * @return	bool	True / False
	 */
	public static function saveTemplate( $fileName, $data, $replaceTwig = true )
	{
		$replace		= array( '{{ App.baseUrl() }}' );
		$replaceWith	= array( Yii::app()->baseUrl );

		if( $replaceTwig )
			$data = str_replace( $replaceWith, $replace, $data );

		return self::overwriteWithHistory( $fileName, $data );
	}

	/**
	 * Retourne un tableau de Sabberworm\CSS\RuleSet\DeclarationBlock contenant toutes les infos du CSS
	 * @param type $cssFile
	 * @return array	Array of Sabberworm\CSS\RuleSet\DeclarationBlock
	 */
	public static function parseCssFile( $cssFile )
	{
		if( !is_file($cssFile) )
			return false;

		$oCssParser		= new \Sabberworm\CSS\Parser( file_get_contents($cssFile) );
		$oCssDocument	= $oCssParser->parse();

		return $oCssDocument->getAllSelectors();
	}
}

?>
