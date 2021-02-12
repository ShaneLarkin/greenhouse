<!DOCTYPE html>
<?php
	session_start();
?>
<html>
<head>
	<title>Shane's Place</title>
    <link rel="stylesheet" type="text/css" href="css/main.css?t=<?php echo time();?>">
	<script type="text/javascript" src="jscript/main.js?t=<?php echo time();?>"></script>
</head>
<body onload="focusUserLogin()">
	<table>
		<tr>
			<td>
				<div class = "header">
					<table>
						<tr>
							<td><h1>Shane's Place</h1></td>
						</tr>
						<tr>
							<td><img src = "graphics/ShaneBeardSmall.png" ></img></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="middle">
					<table>
						<tr>
							<td>
								<form name="loginForm" id="loginForm" action="processLogon.php" onsubmit="return validateLoginPage();"  method="POST" >
									<tr>
										<td><h3>User</h3></td>
										<td><input type="text" id = "userField" name="userField"></td>
									</tr>
									<tr>
										<td><h3>Password</h3></td>
										<td><input type="password" id ="passwordField" name="passwordField"
										autocomplete="off"></td>
									</tr>
									<tr>
										<td><button type = "submit" onclick="document.getElementById('loginForm').submit();">Submit</button></td> 
										<td><button type = "button" style="float:right" onclick="clearLogonPage();" >Clear</button></td>
									</tr>
								</form>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="footer">
				</div>
			</td>
		</tr>
	</table>
</body>
</html>
