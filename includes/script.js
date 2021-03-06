document.getElementById("submit").addEventListener("click", checkImage);
document.getElementById("image").addEventListener("change", checkImage);

const nameRegex = new RegExp("[^0-9A-Za-z_\\.' -]+");
const regex = new RegExp("[^a-zA-Z0-9]+");

function checkImage(e) {
	var imageSpan = document.getElementById("imageError");
	imageSpan.innerHTML = "";
	
	if(document.getElementById("image").files.length == 0) {
		document.getElementById("imageDisplay").innerHTML = "";
		imageSpan.innerHTML = "Please select a file.";
	} else {
		imageSpan.innerHTML = "";
	}
	
	var file = document.getElementById("image").files[0];
	var screenshot = file.name;
	var screenType = file.type;
	
	if(!screenType.includes("image")) {
		imageSpan.innerHTML = "File must be an image!";
	} else {
	  const reader = new FileReader();
	  
	  reader.addEventListener("load", (event) => {
		imagePreview.src = event.target.result;
		imagePreview.height = 200;
	  });
	  
	  reader.readAsDataURL(file);
	}

	if(e.target.id != "submit") {
		document.getElementById("imageDisplay").innerHTML = '<img id="imagePreview">';
		document.getElementById("imageValue").innerHTML = "";
	} else {
		document.getElementById("imageDisplay").innerHTML = "";
		document.getElementById("imageValue").innerHTML = '<img id="imagePreview">';
		
		isValid();
	}
}
				
function isValid() {
	var error = document.getElementsByClassName("error");
	
	for(let i = 0; i < error.length; i++) {
		if(document.getElementById(error[i].id).childNodes.length > 0) {
			document.getElementById(error[i].id).innerHTML = "";
		}
	}
	
	if(!document.getElementById("submission").checkValidity()) {
		document.getElementById("submission").reportValidity();
	} else {
		checkText();
	}
}

function checkText() {
	var skidInput = document.getElementById("skid");
	var nameInput = document.getElementById("name");
	var levelInput = document.getElementById("level");
	var skidResult = regex.test(skidInput.value);
	var nameResult = nameRegex.test(nameInput.value);
		
	if(skidResult == true) {
		document.getElementById("skidError").innerHTML = "Only letters and numbers allowed!";
		discombobulate();
	} else if(nameResult == true) {
		document.getElementById("nameError").innerHTML = "Only spaces and the following characters are allowed: A-Z, 0-9, ., ', -, _";
		discombobulate();
	} else if(skidInput.value.length < 28 || skidInput.value.length > 28) {
		document.getElementById("skidError").innerHTML = "Your SKID must be 28 characters!";
		discombobulate();
	} else if(levelInput.value > 100) {	//Change this as level increases
		document.getElementById("levelError").innerHTML = "Maximum level is 100!";
		discombobulate();
	}
	
	checkKdr();
}

function checkKdr() {
	var kills = document.getElementById("kills").value;
	var deaths = document.getElementById("deaths").value;
	var kdr = kills / deaths;
	var errorSpan = document.getElementsByClassName("kdr");
	kdr = kdr.toFixed(2);
	
	if(kdr > 99.99) {
		for(let i = 0; i < errorSpan.length; i++) {
			errorSpan[i].innerHTML = "&nbsp;Your KDR cannot be higher than 99.99!";
		}
		
		clearText("kills", "deaths");
	} else if(kdr < 0) {
		for(let i = 0; i < errorSpan.length; i++) {
			errorSpan[i].innerHTML = "$nbsp;Your KDR cannot be lower than zero!";
		}
		
		clearText("kills", "deaths");
	}
	else {
		getVariables(kdr);
		
		for(let i = 0; i < errorSpan.length; i++) {
			errorSpan[i].innerHTML = "";
		}
	}
}

function clearText(...id) {
	for(let i = 0; i < id.length; i++) {
		document.getElementById(id[i]).value = "";
	}
}

function getVariables(k) {
	var formInput = document.getElementsByClassName("formInput");
	var formValue = [];
	
	for(let i = 0; i < formInput.length; i++) {
		if(formInput[i].id != "image") {
			formValue.push(formInput[i].id + "Value");
			if(formInput[i].id == "skid") {
				var str = formInput[i].value;
				console.log("Skidstr " + str);
				var output = str.replace(regex, "");
				output = str.replaceAll(" " , "");
				console.log("Skid output " + output);
				
				document.getElementById("skidValue").innerHTML = output;
			} else if(formInput[i].id == "name") {
				var str = formInput[i].value;
				console.log("Namestr " + str);
				
				var output = str.replace(nameRegex, "");
				console.log("Name output " + output);
				
				document.getElementById("nameValue").innerHTML = output;
			} else {
				const numberRegex = new RegExp("[\D]");
				var str = formInput[i].value;
				console.log("Allstr " + str);
				
				var output = str.replace(numberRegex, "");
				output = str.replaceAll(" " , "");

				document.getElementById(formInput[i].id + "Value").innerHTML = output;
			}
		}
	}
	
	document.getElementById("kdrValue").innerHTML = k;
	
	showHide('start', 'verify');
}
		
function showHide() {
	for(let i = 0; i < arguments.length; i++) {
		console.log(arguments[i]);
		document.getElementById(arguments[i]).classList.toggle("hidden");
	}
}