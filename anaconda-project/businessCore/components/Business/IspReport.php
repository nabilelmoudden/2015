<?php
/**
 * classe du modèle de la table V2_ispreport
 *
 * @package component\Business
 * @var string $porteur
 * @var string $site
 * @var string $triggername
 * @var string $messagename
 * @var string $senddate
 * @var string $porteur_search
 */
namespace Business;


class IspReport extends \IspReport
{
	 public $porteur,$site,$triggername,$messagename,$senddate,$porteur_search;



  /**
   * cette methode retourne le modèle en se basant sur les conditions de recherche/filtre.
   * @return CActiveDataProvider retourne le modèle en se basant sur les conditions de recherche/filtre
   */
public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$Provider = parent::search();
	    $Provider->criteria->with=array('IspCompaign');
		$Provider->criteria->compare('isdownloaded',0);
		return $Provider;

}

  
  /**
   * cette methode retourne le modèle en se basant sur les conditions de recherche/filtre.
   * @return CActiveDataProvider retourne le modèle en se basant sur les conditions de recherche/filtre
   */
public function search1()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$Provider = parent::search();
	    $Provider->criteria->with=array('IspCompaign');
	    $Provider->criteria->compare('isdownloaded',1);
	  
	    $Provider->criteria->compare('IspCompaign.site',$this->site);
	    $Provider->criteria->compare('IspCompaign.triggername',$this->triggername,true);
	    $Provider->criteria->compare('IspCompaign.messagename',$this->messagename,true);
		$Provider->criteria->compare('IspCompaign.senddate',$this->senddate,true);
		$Provider->criteria->compare('IspCompaign.porteur',$this->porteur);

		
		return $Provider;

}

}
?>
