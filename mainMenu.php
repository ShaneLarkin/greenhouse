<?php
// mainMenu.php - main action page after a succeesful logon
require_once("utilities/functions.php");
require_once("utilities/constants.php");
session_start();

// If no valid logon, go to index page
//if(!checkValidLogon()) {
//	header("Location: index.php");
//}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Main Menu</title>
	<!-- take these out later. Stopping caching during development -->
	<link rel="stylesheet" type="text/css" href="css/main.css?t=<?php echo time(); ?>" />
	<script type="text/javascript" src="jscript/jquery.js" ></script>
	<script type="text/javascript" src="jscript/actions.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="jscript/main.js?t=<?php echo time(); ?>">" ></script>
</head>

<!-- Any global Javascript values that are taken from config/config.ini -->
<?php
// show what's on the session
// var_dump($_SESSION);
// get the value in seconds for Water On to stop and pass to
// Javascript below in onload() 
$secsWaterTimeout = $_SESSION["defaultSecsForWaterToRun"] * 1000;

?>
<body onload="setWaterOffTimerSecs(<?php echo $secsWaterTimeout ?>)">
	<table>
		<tr>
			<td>
				<div class = "header">
					<table>
						<tr>
							<td><h1>Main Menu</h1></td>
						</tr>
						<tr>
							<?php echo "<td>Welcome back<h2>" . $_SESSION["user"] . "</h2>"; ?>
						</tr>
						<tr>
							<td>
								<button type="button" style="color:#ff8c00" id = "logoutButton" name="logoutButton" 
								onclick="turnWaterOff(); window.location.replace('https://shanelarkin.hopto.org'); "<?php unsetUser(); ?> 
									>Log Off</button>
							</td>
						</tr>
					</table>
				</div>
				<div class = "startHidden" name = "temperatureDisplay" id="temperatureDisplay">
					<table>
						<tr>
							<td><textarea rows="2" cols="4" readonly placeholder="" 
								id = "tempArea" name = "tempArea"></textarea>
							</td>
						</tr>
						<tr>
							<td><b>Probe Temperature</b></td>
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
								<form name="pingControlForm" id="pingControlForm" method="POST">
									<button type="submit" name="pingButton" 
										id="pingButton">Ping Device</button>
								</form>
							</td>
							<td>
								</form>
								<form name="waterOnControlForm" id="waterOnControlForm" method="POST">
									<button type="submit" name="waterOnButton"
										onclick="waterOffTimerHandle = setTimeout(turnWaterOff,waterOffTimerValSecs); 
											changeButtonColour('waterOnButton','#cccc00');" 
										id="waterOnButton">Water On</button>
								</form>
							</td>
							<td>
								<form name="waterOffControlForm" id="waterOffControlForm" method="POST">
									<button type="submit" name="waterOffButton" onclick="changeButtonColour('waterOnButton','#0069ed')" 
										id="waterOffButton">Water Off</button>
								</form>
							</td>
							<td>
								<form name="temperatureControlForm" id="temperatureControlForm" method="POST">
									<button type="submit" name="temperatureButton" id="temperatureButton"
										onclick="document.getElementById('temperatureDisplay').style.visibility='visible'">Temp Show</button>
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
					<table>
						<tr>
							<td><b>status</b></td>
						</tr>
						<tr>
							<td><textarea rows="6" cols="18" readonly placeholder="" 
								id = "statusArea" name = "statusArea"></textarea>
							</td>
						</tr>
						<!--Let's try a "settings" button -->
						<tr>
							<td class="topPadded">
								<button type="submit" name="temperatureButton" id="temperatureButton"
									style="color:#ff8c00"
									onclick="document.getElementById('settings').style.visibility='visible'">
									Settings</button>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="topPadded">
				<div class="startHidden" name="settings" id="settings">
					<table>
						<tr>
							<td>
							<!-- table for setting displays and savings  -->
								<table>
									<tr>
										<td> <!-- Displays for sliders  -->
											<textarea rows="3" cols="3" readonly
                                 				id = "waterOnMinsOP"></textarea>
										</td>
										<td>
											<textarea rows="3" cols="3" readonly
                                 				id = "moistureThresholdOP" ></textarea>
										</td>
										<td>
											<textarea rows="3" cols="3" readonly
                                 				id = "moistureCheckMinsOP" ></textarea>
										</td>
									</tr>
									<tr>
										<td> <!-- sliders -->
											<div class="slidecontainer">
  												<input type="range" min="1" max="100" value="50" 
													class="slider" id="waterOnMinsSlide" onchange="showValueInSlider(this.value,'waterOnMinsOP')">
											</div>
										</td>
										<td>
											<div class="slidecontainer">
  												<input type="range" min="1" max="100" value="50" 
													class="slider" id="moistureThresholdSlide" onchange="showValueInSlider(this.value,'moistureThresholdOP')">
											</div>
										</td>
										<td>
											<div class="slidecontainer">
  												<input type="range" min="1" max="100" value="50" 
													class="slider" id="moistureCheckMinsSlide" onchange="showValueInSlider(this.value,'moistureCheckMinsOP')">
											</div>
										</td>
									</tr>
									<tr> <!-- slider labels -->
										<td>Water On Mins</td>
										<td>Moisture Threshold</td>
										<td>Moisture Check Mins</td>
									</tr>
						</tr>
						<!-- save, cancel and reset for settings  -->
						<tr>
							<td>
								<button type="submit" name="settingsSubmitButton" id="settingsSubmitButton"
									style="color:#ff8c00"
									onclick="">Save
								</button>
							</td>
							<td>
								<button type="submit" name="settingsCancelButton" id="settingsCancelButton"
									style="color:#ff8c00"
									onclick="">Cancel
								</button>
							</td>
							<td>
								<button type="submit" name="settingsResetButton" id="settingsResetButton"
									style="color:#ff8c00"
									onclick="">Reset
								</button>
							</td>
						</tr>
						</table>
					</table>
				</div>
			</td>
		</tr>
	</table>
</body>
</html>
