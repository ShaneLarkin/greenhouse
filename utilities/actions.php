<?php
// actions.php - central place for all Device php actions
// for utilities called by AJAX on client

// Note: all stuff needs changing for this to work
// www-data to be in sudo group (visud) with 
// NOPASSWORD:/sbin/ping  set for www-data
// 

session_start();

require_once("functions.php");
require_once("constants.php");

// print what's in the post
foreach($_POST as $key => $value){ 
	writeToDebug($key,DEBUG_FILE);
}


/* first section of code decides which functions to call
 and calls them */

if(isset($_POST['pingDevice'])) {
writeToDebug("pingDevice in Debug",DEBUG_FILE);
	echo pingDevice();
}

// for maybe mistaken safety reasons, water  on and off are seperate functions 
// when they may have been one with a flag. Maybe that will be true later.
if(isset($_POST['waterOn'])) {
writeToDebug("Water On in Debug",DEBUG_FILE);
	echo waterOn();
}

if(isset($_POST['waterOff'])) {
writeToDebug("Water Off in Debug",DEBUG_FILE);
	echo waterOff();
}

if(isset($_POST['readTemperature'])) {
writeToDebug("read temepature in Debug",DEBUG_FILE);
	echo readTemperature();
}

/* still first section but "parent device" centered i.e. things that happen to the Pi */

if(isset($_POST['setParentDevice'])) {
writeToDebug("setParentDevice in Debug",DEBUG_FILE);
writeToDebug("setParent - waterOnSliderValMins = ". $_POST['waterOnSliderValMins'] ,DEBUG_FILE);
writeToDebug("setParent - moistureCheckSliderVal = ". $_POST['moistureCheckSliderVal'] ,DEBUG_FILE);
writeToDebug("setParent - moistureThresholdMins = ". $_POST['moistureThresholdMins'] ,DEBUG_FILE);
	// shane - the stuff is here
	//echo setParentDevice();
	echo setParentDevice($_POST['waterOnSliderValMins'],$_POST['moistureCheckSliderVal'],$_POST['moistureThresholdMins']);
}

if(isset($_POST['resetParentDevice'])) {
writeToDebug("restParentDevice in Debug",DEBUG_FILE);
	echo resetParentDevice();
}

if(isset($_POST['getDeviceSettings'])) {
writeToDebug("getDeviceSettings in Debug",DEBUG_FILE);
	echo getDeviceSettings();
}



/* -------- second section - the actual device functions ------- */

// check if device is alive 
function pingDevice() {
	$ip = $_SESSION["deviceIP"];
	$res = exec("sudo -u www-data ping -c 1 -t 1 $ip 2>&1",$output,$status);
	// worked
	if($status == 0) {
		return json_encode(array('output' => 'Device Alive','success' => 1));
	}
	return json_encode(array('output' => 'Device Missing','success' => 0));
} 

function waterOn() {
	$prefix = "http://";	
	$ip = $_SESSION["deviceIP"];
	$port = $_SESSION["listeningPort"];
	$action = "/WATER=ON";
	$command = $prefix . $ip . ":" . $port . $action;
	$res = exec("sudo -u www-data /usr/bin/curl $command 2>&1",$output,$status);
	// worked
	if($status == 0) {
		// TO DO: parse return to get result ststus out
		return json_encode(array('output' => 'Water On OK','success' => 1));
	}

    return json_encode(array('output' => 'Water On failed','success' => 0));
}

function waterOff() {
	$prefix = "http://";	
	$ip = $_SESSION["deviceIP"];
	$port = $_SESSION["listeningPort"];
	$action = "/WATER=OFF";
	$command = $prefix . $ip . ":" . $port . $action;
	$res = exec("sudo -u www-data /usr/bin/curl $command 2>&1",$output,$status);
	// worked
	if($status == 0) {
		// TO DO: parse return to get result ststus out
		return json_encode(array('output' => 'Water Off OK','success' => 1));
	}
    return json_encode(array('output' => 'Water Off failed','success' => 0));
}

function readTemperature() {
	$prefix = "http://";	
	$ip = $_SESSION["deviceIP"];
	$port = $_SESSION["listeningPort"];
	$action = "/READ_TEMPERATURE";
	$command = $prefix . $ip . ":" . $port . $action;
	$res = exec("sudo -u www-data /usr/bin/curl $command 2>&1",$output,$status);
// this works
//writeToDebug("Testing debug",DEBUG_FILE);
	// worked
	if($status == 0) {
		// parse the output from curl to get the temperature
		$temperature = extractRemainderAfterMatch($output[OFFSET_TO_TEMPERATURE], 'Centigrade');
		return json_encode(array('output' => $output,'success' => 1, 'temperature' => $temperature));
	}
	// failed
    return json_encode(array('output' => 'Temp Read failed','success' => 0));
}

function setParentDevice($waterOnSliderValMins,$moistureCheckSliderValMins,$moistureThresholdMins) {
//writeToDebug("test thing is ".$testThing ,DEBUG_FILE);
	setParentState('SET','Shed2',$waterOnSliderValMins,$moistureCheckSliderValMins,$moistureThresholdMins,1,2);
    return json_encode(array('output' => 'Set Parent Device','success' => 1));
}

function resetParentDevice() {
//writeToDebug("resetParentDeviceCalled()",DEBUG_FILE);
	setParentState('RESET','Shed2',0,0,0,0,0);
    return json_encode(array('output' => 'Reset Parent Device','success' => 1));
}

// return the current device settings 
function getDeviceSettings() {
	$currentValues = readDeviceSettings();

    return json_encode($currentValues);
	
}
?>
