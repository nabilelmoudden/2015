<?php

\Yii::import( 'ext.FileHelper' );

/**
 * Description of CKEditorHelper
 *
 * @author JulienL
 * @package Helper
 */
class CKEditorHelper
{
	/**
	 * Retourne la config necessaire au ContentManager
	 * @param string $fileName	Nom du fichier de la vue
	 * @param string $css	Nom du fichier CSS
	 * @return string	Tableau encodé en JSON : array( 'view' => '...', 'css' => '...', 'class' => '...' )
	 */
	public static function getConfigForContentManager( $fileName, $css = false )
	{
		// Vue :
		$view	= \FileHelper::getTemplate( $fileName );

		// Css :
		if( is_file($css) )
		{
			$Parser	= FileHelper::parseCssFile( $css );
			$class	= array();
			foreach( $Parser as $Block )
			{
				foreach( $Block->getSelectors() as $Selector )
				{
					$className = $Selector->getSelector();
					if( $className[0] == '.' )
						$class[] = array( 'name' => $className, 'element' => 'span', 'attributes' => array( 'class' => substr( $className, 1 ) ) );
				}
			}
		}
		else
			$css = $class = false;

		return json_encode( array( 'view' => $view, 'css' => $css, 'class' => $class ) );
	}

	/**
	 * Sauvegarde une vue modifié par le contentManager
	 * @param type $fileName
	 * @param string Tableau encodé en JSON contenant le resultats de la fonction sous forme de flashes
	 */
	public static function saveView( $fileName, $data )
	{
		//die( 'To Update' );
		$root	= Yii::app()->getbasePath();

		// Ecrase le template :
		return FileHelper::saveTemplate( $fileName, $data );
		
	}
}

?>