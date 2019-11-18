(function() {
    // Grab elements, create settings, etc.
    var canvas = document.getElementById("canvas"),
        context = canvas.getContext("2d"),
		video = document.getElementById("video"),
		vendorUrl = window.URL || window.webkitURL,
		captureButton = document.getElementById('capture');
		videoObj = { "video": true, "audio": false },
		photo = document.getElementById('photo');
		
		navigator.getMedia = 	navigator.getUserMedia ||
							navigator.webkitGetUserMedia ||
							navigator.mozGetUserMedia ||
							navigator.msGetUserMedia ||
							navigator.oGetUserMedia;
        
    // Put video listeners into place
    if(navigator.getUserMedia) { // Standard
        navigator.getUserMedia(videoObj, handleVideo, videoError);
	}
	function handleVideo(stream) {
		video.srcObject = stream;
	}
	function videoError(error) {
		// An error occured
		// error.code
	}

	document.getElementById("snap").addEventListener("click", function() {
		context.drawImage(video, 0, 0, 400, 300);
		photo.setAttribute('src', canvas.toDataURL('image/png'));
	})

	document.getElementById("upload").addEventListener("click", function(){
		var dataURL = canvas.toDataURL("image/jpeg");
		//console.log("PHP request");
		var dataURL = canvas.toDataURL();
		//creation of form
		const form = document.createElement('form');
		form.action = 'image.php';
		form.method = 'post';
		//creation of image
		const myogimage = document.createElement('input');
		myogimage.value = dataURL;
		myogimage.name = 'baseimage';
		//add input to form
		form.appendChild(myogimage);
		//append form to document
		document.body.appendChild(form);
		//self submit such form
		form.submit();
	})
			
})();