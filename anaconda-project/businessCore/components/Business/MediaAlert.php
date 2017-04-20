<?php



namespace Business;



/**
 * Description of Alert
 *
 * @author YacineR 
 * @package Business.Alert
 */

class MediaAlert extends \MediaAlert

{
	public $file;

	static public function joinMedia($filename, $type, $urlfile, $comment_id)
	{

		$Path = $urlfile;
		$files = \CUploadedFile::getInstancesByName('Files');

    	//	if ((!file_exists(realpath(__DIR__ . '/../../..').$Path))) {
  //  			mkdir((realpath(__DIR__ . '/../../..').$Path), 0777, true);
   // 		}
   
    		 print_r($files);
    		
    		
    		foreach($files as $f => $file){
    			$cussus = new \MediaAlert();
    			$cussus->name=$file->name;
    			$cussus->extention=$file->getExtensionName();
    			$cussus->url=$file->getTempName();
    			$cussus->idComment=$comment_id;
           //     $cussus->save();
                	
    			
    			if ((!file_exists(realpath( '.').$Path.'/'.$file->name))) {
    				if (!$file->saveAs(realpath('.').$Path.'/'.$file->name)) {
    					echo "Probléme lors de l'enregistrement du ficher";exit;
    				}
    			}else{
    				continue;
    			}
    
    		}
	

}
}



?>