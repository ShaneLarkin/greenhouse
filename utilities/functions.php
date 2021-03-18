<?php
	// functions.php - common functions
	// THINK: If these are called async - AJAX "promisefy"
// make sure user is looged on the session
function  checkValidLogon() {
	if(!isset($_SESSION["user"])) {
		return false;
	}
	return true;
}
// create (if required) and write to a PHP debug file
// location is path and file name - use a constant
function writeToDebug($message, $location) {
	$dbfh;
$location = "/var/www/html/debug/debug.txt"; 
	// create timestamp
	$dateTime = date('d/m/y h:i:s');

	if(!file_exists($location)) {
		$dbfh = fopen($location, "w");
	} else {
		$dbfh = fopen($location, "a");
	}
	fwrite($dbfh, $dateTime . " - " . print_r($message, true) . "\n");

	fclose($dbfh);
}

// remove user variable from session 
function unsetUser() {
	unset($_SESSION["user"]);
} 

// write to Javascipt console from php
function consoleLog($output, $addTags = true) {
	$jsCode = 'console.log(\'' . $output . '\' );';
    if ($addTags) {
        $jsCode = '<script>' . $jsCode . '</script>';
	}
    echo $jsCode;
}

 // Return remainder of a string after matching a word
function extractRemainderAfterMatch($string,$match) {
	$remainder = "";
	$index = strpos($string, $match) + strlen($match);
	$remainder = substr($string, $index);
 
	return $remainder;
}

// shane - needs to work with relative paths
function openDatabase($dbName) {

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
    try {
        //$dbh = new PDO("sqlite:".$dbName,$options);
        $dbh = new PDO("sqlite:".$dbName);

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        //$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_COLUMN);
/*
        $dbh->setAttribute(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
							PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
						);
*/
    }   
    catch(PDOException $e) {
        consoleLog("Could not open database",false);
// shane - needs something here that stops everything - exit(1) doesn't
        exit(1);
    }

	return $dbh;
}

function closeDatabase($dbh) {
	$dbh = NULL;;
}

function executeDbCommand($dbh, $query,$returnStuff) {
	$resultSet = NULL;
	try {
		$sth = $dbh->prepare($query);
		$sth->execute();

		if($returnStuff == true) {
			$resultSet = $sth->fetch();
		}
	}
	catch(PDOExceoption $e) {
		//$e->getMessage();
        consoleLog("Execute Command failed",false);

	}
	
	return $resultSet;
}

function deviceInitialised() {
	// conect to database
	$db =  openDatabase("/var/www/html/databases/greenhouse.db");
	// check if initialised
	$query = "SELECT * from setupStatus";

	$results = executeDbCommand($db,$query,true);
	// close database
	closeDatabase($db);

	//writeToDebug('stage',DEBUG_FILE);
	//writeToDebug($results['stage'],DEBUG_FILE);

	//writeToDebug('state',"thing");
	//writeToDebug($results['state'],DEBUG_FILE);

	// 'yes' if initialised
	if($results['state'] == 'yes') {
		return true;
	}
	return false;
}

function setParentState($action, $deviceName,$defaultSecsForWaterToRun,$temperatureReadRefreshSecs,
						$moistureCheckIntervalMins,$drySoilWateringThreshold,
						$heightTriggerCms) {

	$action = strtoupper($action);

	$db =  openDatabase("/var/www/html/databases/greenhouse.db");

	switch ($action) {
		case "SET" :	$desiredState = 'yes';
						break;

		case "RESET" :	$desiredState = 'no';
						break;

	}
	// set Parent device status
	$statement = "update setupStatus set state = '$desiredState', timestamp = datetime('now','localtime') where stage = 'initialised'";
	$results = executeDbCommand($db,$statement,false);

	// set values for timmed actions
	$statement = "update deviceValues set defaultSecsForWaterToRun = $defaultSecsForWaterToRun,
										temperatureReadRefreshSecs = $temperatureReadRefreshSecs,
										moistureCheckIntervalMins = $moistureCheckIntervalMins,
										drySoilWateringThreshold = $drySoilWateringThreshold,
										heightTriggerCms = $heightTriggerCms
				where deviceName = '$deviceName'";

	$results = executeDbCommand($db,$statement,false);
	
	closeDatabase($db);
}

//function readDeviceSettings($deviceName) {
function readDeviceSettings() {
	// no struct or typedef! Are you kidding?
	$currentDeviceValues = array(
						"success" => 1,
						"defaultSecsForWaterToRun" => 10,
						"temperatureReadRefreshSecs" => 20,
						"moistureCheckIntervalMins" => 30,
						"drySoilWaterThreshold" => 40,
						"heightTriggerCms" => 50
						);

	$db =  openDatabase("/var/www/html/databases/greenhouse.db");
	$statement = "select * from deviceValues";
	$results = executeDbCommand($db,$statement,true);
	closeDatabase($db);

writeToDebug('about to print results',DEBUG_FILE);

writeToDebug($results['defaultSecsForWaterToRun'],DEBUG_FILE);
writeToDebug($results['temperatureReadRefreshSecs'],DEBUG_FILE);
writeToDebug($results['moistureCheckIntervalMins'],DEBUG_FILE);
writeToDebug($results['drySoilWateringThreshold'],DEBUG_FILE);
writeToDebug($results['heightTriggerCms'],DEBUG_FILE);

writeToDebug('about to print device',DEBUG_FILE);

foreach($currentDeviceValues as $item => $itemValue) {
	writeToDebug($item  . ' '  . $itemValue,DEBUG_FILE);
}


	return $currentDeviceValues;
}


?>
