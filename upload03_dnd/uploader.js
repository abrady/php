var Uploader = function(){
}

/*
 * Define an exception for this package
 */
Uploader.InvalidField = function(message) {
    this.message = message;
    this.name    = "Uploader.InvalidField";
};

/*
 * Inherit the built-in Error object so that our error can output the file
 * and line where it was thrown. Also the stack-trace.
 */
Uploader.InvalidField.prototype = new Error;

Uploader = {    

    generateBoundary : function() {
        return "AJAX---------------------------" + (new Date).getTime();
    },

    /**
     * @param datatransfer_files : event.DataTransfer.files
	 * @param action : where to send it, e.g. uploader.php
     * @param uploaded_cb : called when file is done uploading and server responds
     */
    send_files : function(datatransfer_files, action, uploaded_cb) {
		var CRLF = "\r\n";
		var xhr = new XMLHttpRequest;
		var boundary    = this.generateBoundary();
		var contentType = "multipart/form-data; boundary=" + boundary;
		
		xhr.open("POST", action, true);
		xhr.onreadystatechange = uploaded_cb;		
		xhr.setRequestHeader("Content-Type", contentType);
		var req_data = "";
		
		for(var i = 0; i < datatransfer_files.length; i++){
			var filename = datatransfer_files[i].name;
			
			// start of this part 
			req_data += "--" + boundary + CRLF; 
			
			// Content-Disposition header contains name of the field used
			// to upload the file and also the name of the file as it was
			// on the user's computer.
			req_data += 'Content-Disposition: form-data; ';
			req_data += 'name=file' + i + '; ';
			req_data += 'filename="'+ filename + '"' + CRLF;
			
			// Content-Type header contains the mime-type of the file to
			// send. Although we could build a map of mime-types that match
			// certain file extensions, we'll take the easy approach and
			// send a general binary header: application/octet-stream.
			req_data += "Content-Type: application/octet-stream" + CRLF + CRLF;
			
			// File contents read as binary data, obviously
			req_data += datatransfer_files[i].getAsBinary() + CRLF;
		}
		
		// terminate the request
		req_data += "--" + boundary + "--" + CRLF;
		
		// finally send the request as binary data
		xhr.sendAsBinary(req_data);
	}
};
