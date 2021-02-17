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
	$debugFile = "";
	// create timestamp
	$dateTime = date('d/m/y h:i:s');

	if(!file_exists($location)) {
		$debugFile = fopen($location, "w");
	} else {
		$debugFile = fopen($location, "a");
	}
	fwrite($debugFile, $dateTime . " - " . print_r($message, true) . "\n");

	fclose($debugFile);
}

// remove user variable from session 
function unsetUser() {
	unset($_SESSION["user"]);
} 

// write to Javascipt console from php
function consoleLog($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .  ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

 // Return remainder of a string after matching a word
function extractRemainderAfterMatch($string,$match) {
	$remainder = "";
	$index = strpos($string, $match) + strlen($match);
	$remainder = substr($string, $index);
 
	return $remainder;
}

function openDatabase($dbName) {
	try {
			$dbh = new SQLite3($dbName);
	} catch (Exception $e) {
		echo 'Caught exception" ' . $e->getMessage();
		echo(" in openDatabase()");
		exit();
	} 


	return $dbh;
}

function closeDatabase($dbh) {
	$dbh->close();
}

function executeDbCommand($dbh, $query) {
	try {
		$resultSet = $dbh->query($query);
	} catch (Exception $e) {
		echo 'Caught exception" ' . $e->getMessage();
		echo(" in executeDbCommand()");
		exit();
	}

	return $resultSet;
}

function processResultset($resultSet) {
	//return 1;
}

function deviceInitialised() {
	// conect to database
	$db =  openDatabase("databases/greenhouse.db");
	// check if initialised
	$query = "SELECT * from setupStatus";
	$results = executeDbCommand($db,$query);
	$row = $results->fetchArray();
	// close database
	closeDatabase($db);
	// return result
	if($row[1] == '0') {
		return false;
	}
	return true;
}
?>
