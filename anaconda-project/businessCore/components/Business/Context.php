<?php

namespace Business;

/**
 * Description of Context
 *
 * @author JulienL
 * @package Business.Context
 */
abstract class Context
{
	// Types de contextes :
    const TYPE_GET			= 1;
    const TYPE_POST			= 2;
    const TYPE_SESSION		= 3;
    const TYPE_COOKIE		= 4; 
    const TYPE_HTTP_BOTH	= 5; // GET + POST

	/**
     * Abstraction de la récupération des variables PHP
     * @param	array	$nameParam	Nom du parametre
     * @param	integer $context
     * @return	mixed	Valeur de la variable ou celle par defaut si inexistante
     */
    protected function getContextVar( $nameParam, $context = self::TYPE_GET )
    {
        switch( $context )
		{
            default:
            case self::TYPE_GET:
                return \Yii::app()->request->getQuery( $nameParam, false );
                break;
            case self::TYPE_POST:
               return \Yii::app()->request->getPost( $nameParam, false );
                break;
            case self::TYPE_SESSION:
               return isset(\Yii::app()->session[ $nameParam ]) ? \Yii::app()->session[ $nameParam ] : false;
                break;
            case self::TYPE_COOKIE:
               return \Yii::app()->request->getCookies()->itemAt( $nameParam );
                break;
            case self::TYPE_HTTP_BOTH:
               return \Yii::app()->request->getParam( $nameParam, false );
                break;
        }

		return false;
    }

	public function getQueryString()
	{
		return \Yii::app()->request->getQueryString();
	}
}

?>