var camera = document.getElementsByClassName("screenshot");

for(let i = 0; i < camera.length; i++) {
	camera[i].addEventListener("click", toggleImage);
}

document.getElementById("lightbox").addEventListener("click", toggleImage);

function toggleImage() {
	var path = this.id;
	
	document.getElementById("lightbox").innerHTML = 'Click image to close<br><img src="' + path + '">';
	document.getElementById("lightbox").style.display = "block";
	console.log(document.getElementById("lightbox").className);
	
	//If already shown, hide it
	if(document.getElementById("lightbox").className == "lightbox-show") {
		document.getElementById("lightbox").classList.remove("lightbox-show");
		document.getElementById("lightbox").classList.add("lightbox-hide");
		
		setTimeout(hideImage, 400);
	} else {
	//Otherwise, show it
		document.getElementById("lightbox").classList.remove("lightbox-hide");
		document.getElementById("lightbox").classList.add("lightbox-show");
	}
}

function hideImage() {
	document.getElementById("lightbox").style.display = "none";
}