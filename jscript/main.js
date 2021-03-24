// main.js - validation for the site

// global Javascript variables
// this kills the water off timeout if the water off button
// is pressed before the timer expires
var waterOffTimerHandle;

// this will be set by reading a config value stored on the session
var waterOffTimerValSecs;

// Flags for colour on button for water on and temerature
var waterOnDeviceState = false;
var temperatureOnDeviceState = false;

// make this better
// waterOnMinsSlide.oninput = function() {
//	document.getElementById("waterOnMinsOP").value = this.value; 
//alert(this.value);
//}


// focus on the user name on logon page
function focusUserLogin() {
	document.getElementById("userField").focus();
}

// make sure logon form has info on it
function validateLoginPage() {
	if(document.getElementById("userField").value == ""){
		alert("User is required");
		document.getElementById("userField").focus();
		return false;
	}
	if(document.getElementById("passwordField").value == ""){
		alert("Password is required");
		document.getElementById("passwordField").focus();
		return false;
	}

	return true;
}

function clearLogonPage() {
	document.getElementById("userField").value = "";
	document.getElementById("passwordField").value = "";
	document.getElementById("userField").focus();
}

// this is for toggling buttons
function activeButtonClick(btnID,colour,deviceState) {
	var property = document.getElementById(btnID);
	if (deviceState == false) {
		property.style.backgroundColor = "#0069ed";
		 deviceState = true;        
	}
	else {
		property.style.backgroundColor = "#ffff66";
		deviceState = false;
	}
}

// this is just when we want to change the button colour
function changeButtonColour(btnID,newColour) {
console.log('change colour');
	var property = document.getElementById(btnID);
    property.style.backgroundColor = newColour; 
}

// set value for waterOffTimeValSecs from values stored on session
// and read from config/config.ini
function setWaterOffTimerSecs(seconds) {
	// read from session and multiply by 1000 to turn to seconds
	waterOffTimerValSecs = seconds;
console.log('water off times value in seconds - ' + waterOffTimerValSecs);
}

// called from action.js when timer triggers to switch off water
// from waterOnControlForm submit
function turnWaterOff() {
console.log('In turnWaterOff()');
	changeButtonColour('waterOnButton','#0069ed');
	// do below - not document.getElementById("waterOffControlForm").submit();
	$('#waterOffControlForm').submit();
}

// section for sliders
function showValueInSlider(sliderVal, outputDisplayID) {
	document.getElementById(outputDisplayID).value = sliderVal;
	//document.getElementById("waterOnMinsOP").value = sliderVal;
}

// get slider values from json and populate
// return an array containing 
function populateSliders($jsonData) {
	console.log($jsonData);
	document.getElementById('waterOnMinsOP').value = $jsonData.defaultSecsForWaterToRun;
	document.getElementById('waterOnMinsSlide').value = $jsonData.defaultSecsForWaterToRun;

	// = jsonData.temperatureReadRefreshSecs;

	document.getElementById('moistureCheckMinsOP').value = $jsonData.moistureCheckIntervalMins;
	document.getElementById('moistureCheckMinsSlide').value = $jsonData.moistureCheckIntervalMins;


	document.getElementById('moistureThresholdOP').value = $jsonData.drySoilWateringThreshold;
	document.getElementById('moistureThresholdSlide').value = $jsonData.drySoilWateringThreshold;

	// = jsonData.heightTriggerrCms;

}
// return a "map" array of current slider values
function readFromSliders() {
	//collect the slider readings
	//alert(document.getElementById('waterOnMinsOP').value);
	//alert(document.getElementById('moistureCheckMinsOP').value);
	//alert(document.getElementById('moistureThresholdOP').value);
	// Javascript doesn't have associative arrays, so we'll use a map (could use an object but I shun them :-) )
	var sliderValArr = new Map( [
		['waterOnSliderValMins',document.getElementById('waterOnMinsOP').value],
		['moistureCheckSliderVal',document.getElementById('moistureCheckMinsOP').value],
		['moistureThresholdMins',document.getElementById('moistureThresholdOP').value],

	]);
	
	return sliderValArr;
}

