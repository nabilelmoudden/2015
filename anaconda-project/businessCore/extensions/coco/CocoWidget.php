<?php
/**
	CocoWidget

	This File uploader Tool is a Yii implementation for Valums File Uploader
	(original source: http://github.com/valums/file-uploader)

	@author	Christian Salazar (christiansalazarh@gmail.com) bluyell, @yiienespanol
	@date oct-2012
	@license http://opensource.org/licenses/bsd-license.php
*/
class CocoWidget extends CWidget implements EYuiActionRunnable {

	public $id='cocowidget0';
	public $debug;
	public $htmlOptions;
	public $defaultControllerName='site';
	public $defaultActionName='coco';

	public $buttonText='Télécharger les images';
	public $dropFilesText='Glisser les images ici !';
	public $allowedExtensions=array();
	public $sizeLimit;
	public $uploadDir = 'assets/';
	public $onCompleted;
	public $onCancelled;
	public $onMessage;
	public $receptorClassName;
	public $methodName;
	public $userdata;
	public $multipleFileSelection=true; // true or false. allow the user to select multiple files at once.
	public $maxConnections=3; // max simultaneus connections (client-side)
	public $maxUploads=-1; // -1=unlimited. number of uploads allowed (client-side)
	public $maxUploadsReachMessage = ''; // a message when maxUploads is reach.

	private $_baseUrl;

	public static function t($text){
		return Yii::t("CocoWidget.coco",$text);
	}

	public function init(){
		parent::init();
		$this->registerCoreScripts();
		if($this->sizeLimit == null)
			$this->sizeLimit = 2 * 1024 * 1024;
		if($this->methodName == null)
			$this->methodName = 'coco_onFileUploaded';
	}
	public function run(){

		$id=$this->id;
		$upid = $id.'uploader';
		$logid = $id.'logger';
		$action = array($this->defaultControllerName.'/'.$this->defaultActionName);
		$action['action']='upload';
		$action['data']=""; // see later after $vars


		$htopts = '';
		if(empty($this->htmlOptions)){
			$htopts = "class='cocowidget'";
		}else{
			if(!isset($this->htmlOptions['class']))
				$this->htmlOptions['class'] = 'cocowidget';
			foreach($this->htmlOptions as $key=>$val)
				$htopts .= " {$key}='$val'";
		}

		if($this->onCompleted == null)
			$this->onCompleted = 'function(id,filename,jsoninfo){ }';
		if(!($this->onCompleted instanceof CJavaScriptExpression))
				$this->onCompleted = new CJavaScriptExpression($this->onCompleted);

		if($this->onCancelled == null)
			$this->onCancelled = 'function(id,filename){ }';
		if(!($this->onCancelled instanceof CJavaScriptExpression))
				$this->onCancelled = new CJavaScriptExpression($this->onCancelled);

		if($this->onMessage == null)
			$this->onMessage = 'function(messageText){ }';
		if(!($this->onMessage instanceof CJavaScriptExpression))
				$this->onMessage = new CJavaScriptExpression($this->onMessage);

		$vars = array(
			'allowedExtensions'=>$this->allowedExtensions,
			'sizeLimit'=>$this->sizeLimit,	// server-side size validation
			'uploadDir'=>$this->uploadDir,
			'receptorClassName'=>$this->receptorClassName,
			'methodName'=>$this->methodName,
			'userdata'=>$this->userdata,
		);

		$action['data'] = serialize($vars);

		$options = CJavaScript::encode(
			array(
				'id'=>$id,
				'loggerid'=>$logid,
				'action'=>CHtml::normalizeUrl($action),
				'onCompleted'=>$this->onCompleted,
				'onCancelled'=>$this->onCancelled,
				'onMessage'=>$this->onMessage,
				'buttonText'=>$this->buttonText,
				'dropFilesText'=>$this->dropFilesText,
				'uploaderContainer'=>$upid,
				'sizeLimit'=>$this->sizeLimit, // for client-side size validt.
				'multipleFileSelection'=>$this->multipleFileSelection,
				'maxConnections'=>$this->maxConnections,
				'maxUploads'=>$this->maxUploads,
				'maxUploadsReachMessage'=>$this->maxUploadsReachMessage,
				//'data'=>serialize($vars),
			)
		);

echo
"
	<!-- CocoWidget begins -->
	<div id='{$id}' {$htopts}>
		<div id='{$upid}' class='uploader'></div>
		<div id='{$logid}' class='logger'></div>
	</div>
	<!-- CocoWidget ends -->
";

		Yii::app()->getClientScript()->registerScript(
			$id,
"
	var _cocoUp = new CocoWidget({$options});
	_cocoUp.run();
",
			CClientScript::POS_LOAD
		);
	}

	public function registerCoreScripts(){

		$localAssetsDir = dirname(__FILE__) . '/assets';
		$this->_baseUrl = Yii::app()->getAssetManager()->publish($localAssetsDir);

        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

		if($this->debug)
			$this->_baseUrl = 'protected/extensions/coco/assets';

		foreach(scandir($localAssetsDir) as $f){
			$_f = strtolower($f);
			if(strstr($_f,".js"))
				$cs->registerScriptFile($this->_baseUrl."/".$_f);
			if(strstr($_f,".css"))
				$cs->registerCssFile($this->_baseUrl."/".$_f);
		}
	}

	private function getClassNameFromPhp($filename){
		$noext = trim(substr(strrev(trim($filename)),4,strlen(trim($filename))-4));
		$k=0;
		for($i=0;$i<strlen($noext);$i++){
			if(($noext[$i]=='\\') || ($noext[$i]=='/'))
				$k=$i;
			if($k > 0)
				break;
		}
		if($k==0)
			$k = strlen($noext);

		return strrev(substr($noext,0,$k));
	}


	// de: EYuiActionRunnable
	public function runAction($action,$data) {
		Yii::log('ACTION CALLED - action is: '.$action,'info');

		$vars = unserialize($data);

		$this->allowedExtensions = $vars['allowedExtensions'];
		$this->sizeLimit = (integer)$vars['sizeLimit'];
		$this->uploadDir = $vars['uploadDir'];
		$this->receptorClassName = $vars['receptorClassName'];
		$this->methodName = $vars['methodName'];
		$this->userdata = $vars['userdata'];

		if(($this->allowedExtensions == null) || ($this->allowedExtensions==''))
			$this->allowedExtensions = array();

		Yii::log('ACTION CALLED - data is: '.CJSON::encode($vars),'info');


		if($action == 'upload'){

			$uploader = new ValumsFileUploader($this->allowedExtensions, $this->sizeLimit);
			if($uploader->checkServerSettings() != null){
				Yii::log("CocoWidget. Please increase post_max_size and upload_max_filesize to ".$this->sizeLimit,"error");
				return;
			}

			// ensure directory
			$this->uploadDir = rtrim($this->uploadDir,'/').'/';
			@mkdir($this->uploadDir);

			$result = $uploader->handleUpload($this->uploadDir);
			if(isset($result['success'])){
				if($result['success']==true){
					Yii::log('ACTION CALLED - RESULT=SUCCESS','info');
					$fullpath = $result['fullpath'];
					$this->onFileUploaded($fullpath,$this->userdata);
				}
				else{
					Yii::log('ACTION CALLED - RESULT=ERROR1','info');
				}
			}else
			Yii::log('ACTION CALLED - RESULT=ERROR2','info');
			echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		}

	}

	private function onFileUploaded($filePath,$userdata){

		// will invoke method in a class defined when you setup the widget:
		// using: receptorClassName and methodName attributes.
		$this->_invokeMethod($filePath,$userdata);

	}

	private function _invokeMethod($upladedFilePath,$userdata){
		try{
			if(!empty($this->receptorClassName)){
				$phpFilepath = Yii::getPathOfAlias($this->receptorClassName).".php";
				$className = $this->getClassNameFromPhp($phpFilepath);
				Yii::log('receptorClassName is: '.$phpFilepath.', className='.$className,'info');

				if(!file_exists($phpFilepath))
				{
					Yii::log('the provided receptorClassName does not exist.'.$phpFilepath,'error');
					return false;
				}

				if(!class_exists($className,false))
					require($phpFilepath);

				if(!class_exists($className,false))
					return false;

				$inst = new $className();
				if($inst != null){
					if(method_exists($inst,$this->methodName)){
						$methodname = $this->methodName;
						try{
							$inst->$methodname($upladedFilePath,$userdata);
						}catch(Exception $e){
							Yii::log(__CLASS__.' an error occurs when invoke: '.$phpFilepath.' method: '.$methodname
								.', error is: '.$e
							,'error');
						}
						// method invoked
					}
					else{
						Yii::log('the defined receptorClassName has not a method named -'.$this->methodName.'-'
							,'error');
					}
				}else{
					Yii::log('the defined receptorClassName is an invalid class. cannot be instanciated.','error');
				}
			}
		}catch(Exception $e){
			Yii::log(__CLASS__.' an error occurs.','error');
		}
	}

}
