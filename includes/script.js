document.getElementById("submit").addEventListener("click", isValid);
				
function isValid() {
	if(!document.getElementById("submission").checkValidity()) {
		document.getElementById("submission").reportValidity();
	} else {
		checkImage();
	}
}

function checkImage() {
	var screenshot = document.getElementById("image").files[0].name;
	var screenType = document.getElementById("image").files[0].type;
	var imageSpan = document.getElementById("imageError");
	
	console.log(screenType);
	
	if(!screenType.includes("image")) {
		imageSpan.innerHTML = "File must be .jpg or .png format!";
	} else {
		screenshot = screenshot.substring(screenshot.lastIndexOf("\\") + 1, screenshot.length);
		document.getElementById("imageValue").innerHTML = screenshot;
		
		checkKdr();
	}
}

function checkKdr() {
	var kills = document.getElementById("kills").value;
	var deaths = document.getElementById("deaths").value;
	var kdr = kills / deaths;
	var errorSpan = document.getElementsByClassName("error");
	kdr = kdr.toFixed(2);
	
	if(kdr > 99.99) {
		for(let i = 0; i < errorSpan.length; i++) {
			errorSpan[i].innerHTML = "&nbsp;Your KDR cannot be higher than 99.99%!";
		}
		
		clearText();
	} else if(kdr < 0) {
		for(let i = 0; i < errorSpan.length; i++) {
			errorSpan[i].innerHTML = "$nbsp;Your KDR cannot be lower than zero!";
		}
		
		clearText();
	}
	else {
		getVariables(kdr);
		
		for(let i = 0; i < errorSpan.length; i++) {
			errorSpan[i].innerHTML = "";
		}
	}
}

function clearText() {
	document.getElementById("kills").value = "";
	document.getElementById("deaths").value = "";
}

function getVariables(k) {
	var formInput = document.getElementsByClassName("formInput");
	var formValue = [];
	
	for(let i = 0; i < formInput.length; i++) {
		if(formInput[i].id != "image") {
			formValue.push(formInput[i].id + "Value");
			
			document.getElementById(formValue[i]).innerHTML = formInput[i].value;
		}
	}
	
	document.getElementById("kdrValue").innerHTML = k;
	
	showHide('start', 'verify');
}
		
function showHide(currId, nextId) {
	var cId = currId;
	var nId = nextId;
	
	if(document.getElementById(cId).style.display == "block") {
		document.getElementById(cId).style.display = "none";
	} else if(document.getElementById(cId).style.display == "none") {
		document.getElementById(cId).style.display = "block";
	}
	
	if(document.getElementById(nId).style.display == "block") {
		document.getElementById(nId).style.display = "none";
	} else if(document.getElementById(nId).style.display == "none") {
		document.getElementById(nId).style.display = "block";
	}
}