<?php
	/* processLogon.php - if OK sets value 
	   in session. If not calls logon page again */
	session_start();
	$name = $_POST["userField"];
	$password = $_POST["passwordField"];

	// if username or password empty, call login page again
	if( (empty($name)) or (empty($password)) ) {
		header("Location: index.php");
	}

// get stored password from database and compare 
$db = new SQLite3("databases/greenhouse.db");
$statement = $db->prepare("SELECT password from Users where user = :name");
$statement->bindValue(':name',$name,SQLITE3_TEXT);
$results = $statement->execute();
$row = $results->fetchArray();

// user and password accepted so set user nam on session
// then parse the config file and add the vlaues to the session
if(password_verify($password,$row[0])) {
	$_SESSION["user"]=$name;
	// read config.ini and set vlaues in session
	$configArray = parse_ini_file("config/config.ini");
	foreach($configArray as $setting => $value) {
		$_SESSION[$setting]=$value;
	}
	// open menu page
	header("Location: mainMenu.php");
}
else {
	//session_destroy();
	//header("Location: index.php");
	header("Location: mainMenu.php");
}
?>
