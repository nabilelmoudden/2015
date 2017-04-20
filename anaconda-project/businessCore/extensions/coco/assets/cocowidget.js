var CocoWidget = function(options) {

	this.nocache = function(){
		var dateObject = new Date();
		var uuid = dateObject.getTime();
		return "&nocache="+uuid;
	}

	this.onComplete = function(id, fileName, responseJSON){
		//$('#'+options.loggerid).append('<p>Completed:'+fileName+'</p>');
		if(options.onCompleted != null)
			options.onCompleted(id, fileName, responseJSON);

	}

	this.onCancel = function(id, fileName){
		//$('#'+options.loggerid).append('<p>Cancelled: '+fileName+'</p>');
		if(options.onCancelled != null)
			options.onCancelled(id, fileName);
	}

	this.showMessage = function(messageText){
		//$('#'+options.loggerid).append('<p>'+messageText+'</p>');
		if(options.onMessage != null)
			options.onMessage(messageText);

	}

	this.onSubmit = function(id, filename){
		if(options.maxUploads == -1){
			return true;
		}else
			if(id >= options.maxUploads){
				if(options.onMessage != null)
					if(options.maxUploadsReachMessage != '')
						options.onMessage(options.maxUploadsReachMessage);
				return false;
			}
			else
				return true;
	}

	this.run = function(){
		var _this = this;
		var _uploader = new qq.FileUploader({
			maxConnections: options.maxConnections, // max simultaneous
			multiple: options.multipleFileSelection, // user file selection
			sizeLimit: options.sizeLimit,	// client side size validation
			buttonText: options.buttonText,
			dropFilesText: options.dropFilesText,
			element: document.getElementById(options.uploaderContainer),
			action: options.action,
			onComplete: _this.onComplete,
			onCancel: _this.onCancel,
			onSubmit: _this.onSubmit,// helps controlling the number of uploads
			showMessage: _this.showMessage
		});
	}

}
