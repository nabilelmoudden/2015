Coco! Multi file Uploader Widget
================================

by:
Christian Salazar. christiansalazarh@gmail.com	@yiienespanol, oct. 2012.

![Coco Screen Capture](https://bitbucket.org/christiansalazarh/coco/downloads/coco--valums-fileuploader--wrapped-as-a-yii-widget.png "Coco Screen Capture")

[Ver la Documentación en Español](http://trucosdeprogramacionmovil.blogspot.com/2012/10/file-uploads-en-yii-framework-con-drag.html "")

[http://opensource.org/licenses/bsd-license.php](http://opensource.org/licenses/bsd-license.php "http://opensource.org/licenses/bsd-license.php")

[Go to Coco Repository at Bit Bucket !](https://bitbucket.org/christiansalazarh/coco/ "Go to Coco Repository at Bit Bucket !")
------------------------------------------------------------------------------------------------------------------------------

Requirement: Yii  1.1.11
if you are using a lower yii version then you will receive an error about "CJavaScriptExpression", please refer to issues about how to solve this issue.


1.	Single & Multi File Uploads via Ajax-jQuery
1.	Drag & Drop.

'Coco' is a widget for yii framework designed to handle File Uploads (Single and Multiple). Is designed using Ajax-jQuery and a well formed Architecture based on MVC (and UML).  Using 'coco' is very simple, at first place you setup a fixed action in any controller, this action serves for all every coco-widgets in your application. At second place you insert the widget in your form, all uploaded files will be stored in the path provided by 'uploadDir' widget attribute. Very simple and usefull. Please enjoy it.

Coco takes its functionality from a very nice PHP library located at [valums](https://github.com/valums/file-uploader "valums") repository in github, all licences are explicit in coco related to Valums work.
[Go to the official valums google group](https://groups.google.com/forum/#!forum/fineuploader "Go to the official Valums Google Group").

INSTALL
---------------------

[Ver la Documentación en Español](http://trucosdeprogramacionmovil.blogspot.com/2012/10/file-uploads-en-yii-framework-con-drag.html "")

## 1) GIT Cloning

		cd /home/blabla/myapp/protected
		mkdir extensions
		cd extensions
		git clone git@bitbucket.org:christiansalazarh/coco.git

		If you dont use GIT please copy the entire 'coco' folder into your extensions folder

### ABOUT DIRECT DOWNLOAD !
Please download the provided zip, and uncompress it into /protected/extensions/coco.


## 2) Setup 'config/main'

		'import'=>array(
			'application.models.*',
			'application.components.*',
			'application.extensions.coco.*',			// <------
		),

## 3) Action Setup

Connect the widget with your current application using a fixed Action in siteController (or a distinct controller if you prefer).

By default, using:

myapp/protected/controllers/SiteController.php

IMPORTANT:
	This action is required only one time for all above project !!

~~~
[php]
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			'page'=>array(
				'class'=>'CViewAction',
			),
			'coco'=>array(
				'class'=>'CocoAction',
			),
		);
	}

~~~

## 4) Insert and configure the Widget / Configura el Widget

~~~
[php]
<?php
	$this->widget('ext.coco.CocoWidget'
		,array(
			'id'=>'cocowidget1',
			'onCompleted'=>'function(id,filename,jsoninfo){  }',
			'onCancelled'=>'function(id,filename){ alert("cancelled"); }',
			'onMessage'=>'function(m){ alert(m); }',
			'allowedExtensions'=>array('jpeg','jpg','gif','png'), // server-side mime-type validated
			'sizeLimit'=>2000000, // limit in server-side and in client-side
			'uploadDir' => 'assets/', // coco will @mkdir it
			// this arguments are used to send a notification
			// on a specific class when a new file is uploaded,
			'receptorClassName'=>'application.models.MyModel',
			'methodName'=>'onFileUploaded',
			'userdata'=>$model->primaryKey,
			// controls how many files must be uploaded
			'maxUploads'=>3, // defaults to -1 (unlimited)
    		'maxUploadsReachMessage'=>'No more files allowed', // if empty, no message is shown
			// controls how many files the can select (not upload, for uploads see also: maxUploads)
			'multipleFileSelection'=>true, // true or false, defaults: true
		));
	?>
~~~

## 5) How to receive the uploaded file.

Coco will invoke an specific method name (methodName) in an specific class provided in widget config (receptorClassName). When a new file arrives from upload the Coco will invoke this method passing to you the 'userdata' argument and the full file path.

By example, suppose you have /protected/models/MyModel.php and you need the uploaded file arrives in this class, so the widget config will be:

	'receptorClassName'=>'application.models.MyModel',
	'methodName'=>'myFileReceptor',
	'userdata'=>$model->primaryKey,

Your class and method in MyModel would be:

~~~
[php]
class MyModel {
	public function myFileReceptor($fullFileName,$userdata) {
		// userdata is the same passed via widget config.
	}
}
~~~


## 6)  If your File is not received, or it says: "Failed" everytime..

  1. Check your php.ini for post_max_uploads variable, and please be consistent with 'sizeLimit'=>2000000.

  2. Check log for errors. Coco will write an error log when something goes wrong.

## 7) Extra Options

~~~
[php]
'buttonText'=>'Find & Upload',
'dropFilesText'=>'Drop Files Here !',
'htmlOptions'=>array('style'=>'width: 300px;'),
'defaultControllerName'=>'site',
'defaultActionName'=>'coco',
~~~

[Ver la Documentación en Español](http://trucosdeprogramacionmovil.blogspot.com/2012/10/file-uploads-en-yii-framework-con-drag.html "")
