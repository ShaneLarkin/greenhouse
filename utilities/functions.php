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

function openDatabase($dbName) {
    try {
        $dbh = new PDO('sqlite:databases/greenhouse.db');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }   
    catch(PDOException $e) {
        consoleLog("Could not open database");
        exit(1);
    }

	return $dbh;
}

function closeDatabase($dbh) {
	$dbh = NULL;;
}

function executeDbCommand($dbh, $query,$returnStuff) {
	$resultSet = NULL;
	$sth = $dbh->prepare($query);
//$dbh->beginTransaction();
	$sth->execute();
	if($returnStuff == true) {
		$resultSet = $sth->fetchAll();
	}
//$dbh->commit();
	
	return $resultSet;
}

function deviceInitialised() {
	// conect to database
	$db =  openDatabase("databases/greenhouse.db");
	// check if initialised
	$query = "SELECT * from setupStatus";

	$results = executeDbCommand($db,$query,true);
	// close database
	closeDatabase($db);
	// [0][1] is 'yes' if initialised
	if($results[0][1] == 'yes') {
		return true;
	}
	return false;
}

function setParentState($action) {

	$action = strtoupper($action);

consoleLog('In setParentState',false);
	$db =  openDatabase("databases/greenhouse.db");


	switch ($action) {
		case "SET" :	$desiredState = 'yes';
						break;

		case "RESET" :	$desiredState = 'no';
						break;

		default:		echo("Invalid case in setParentState");
						exit(1);
	}

consoleLog('action = ' . $action,false);

	$statement = "update setupStatus set state = '$desiredState', timestamp = datetime('now','localtime') where stage = 'initialised'";
//$db->beginTransaction();
	$results = executeDbCommand($db,$statement,true);
//$db->commit();

	//closeDatabase($db);
consoleLog('got to end of setParentState with db ploop',false);

}
?>
