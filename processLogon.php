<?php
/* processLogon.php - if OK sets value 
   in session. If not calls logon page again */
require_once("utilities/functions.php");
require_once("utilities/constants.php");
session_start();
$name = $_POST["userField"];
$password = $_POST["passwordField"];

// if username or password empty, call login page again
if( (empty($name)) or (empty($password)) ) {
	header("Location: index.php");
}

// get stored password from database and compare 
$db =  openDatabase("databases/greenhouse.db");
$query = "SELECT password from Users where user = '$name'";
$results = executeDbCommand($db,$query,true);
closeDatabase($db);

// user and password accepted so set user name on session
// then parse the config file and add the values to the sessiona

if(password_verify($password,$results[0][0])) {
	$_SESSION["user"] = $name;
	// read config.ini and set values in session
	$configArray = parse_ini_file("config/config.ini");
	foreach($configArray as $setting => $value) {
		$_SESSION[$setting]=$value;
	}
	// open menu page
	header("Location: mainMenu.php");
}
else {
	// below just during dev to stop constant logging on
	//session_destroy();
	header("Location: index.php");
	//header("Location: mainMenu.php");
}
?>
