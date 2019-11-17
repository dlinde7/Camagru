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

	document.getElementById("upload").addEventListener("click", function() {
		var myImg = document.getElementById("photo").src;
		$.ajax (
			{
				type: "POST",
				url: "image.php",
				data:  myImg,
				}
		)
	})
			
})();