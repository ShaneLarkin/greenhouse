<?php
// mainMenu.php - main action page after a succeesful logon
require_once("utilities/functions.php");
require_once("utilities/constants.php");
session_start();
//
//header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");

// If no valid logon, go to index page
// comment out during dev to stop constant logons
if(!checkValidLogon()) {
	header("Location: index.php");
}

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
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
</head>

<!-- Any global Javascript values that are taken from config/config.ini -->
<?php
// show what's on the session
// var_dump($_SESSION);
// get the value in seconds for Water On to stop and pass to
// Javascript below in onload() 
// below is for the chop
$secsWaterTimeout = $_SESSION["defaultSecsForWaterToRun"] * 1000;
// the value to set the sliders if the device is not initialised
$defaultSliderValue = $_SESSION["defaultSliderValue"];
consoleLog($defaultSliderValue,true); 

// see if this device has been initialised. If not bang up warning alert
if(!deviceInitialised()) {
	echo ("<script type='text/javascript'>alert('WARNING: System is not initialised');</script>");
}

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
							<?php echo "<td>Welcome back<h2>" . $_SESSION["user"] . "</h2></td>"; ?>
						</tr>
						<tr>
							<td>
								<button type="button" style="color:#ff8c00" id = "logoutButton" name="logoutButton" 
								onclick="turnWaterOff(); window.location.replace('https://shanelarkin.hopto.org'); "<?php unsetUser(); ?> 
									>Log Off
								</button>
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
										id="pingButton">Ping Device
									</button>
								</form>
							</td>
							<td>
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
						<!-- settings button -->
						<tr>
							<td class="topPadded">
								<form name="showSettingsControlForm" id="showSettingsControlForm" method="POST">
									<button type="submit" name="temperatureButton" id="temperatureButton"
										style="color:#ff8c00"
										onclick="document.getElementById('settings').style.visibility='visible';
												$('#setParentControlForm').submit();"> 
										Settings</button>
								</form>
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
                                 				id = "waterOnMinsOP">
											</textarea>
											<?php
												if(!deviceInitialised()) {
													// do this more cleverly
												}
											?>
										</td>
										<td>
											<textarea rows="3" cols="3" readonly
                                 				id = "moistureThresholdOP" >
											</textarea>
										</td>
										<td>
											<textarea rows="3" cols="3" readonly
                                 				id = "moistureCheckMinsOP" >
											</textarea>
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
									<!-- save, cancel and reset for settings  -->
									<tr>
										<td>
											<form name="setParentControlForm" id="setParentControlForm" method="POST">
												<button type="button" name="settingsSubmitButton" id="settingsSubmitButton" 
													style="color:#ff8c00"
													onclick="document.getElementById('settings').style.visibility='hidden';
														readFromSliders();
														$('#setParentControlForm').submit();" 
													>Save 
												</button>
											</form>
										</td>
										<td>
											<button type="button" name="settingsCancelButton" id="settingsCancelButton"
												style="color:#ff8c00"
												onclick="document.getElementById('settings').style.visibility='hidden'">Cancel
											</button>
										</td>
										<td>
											<form name="resetParentControlForm" id="resetParentControlForm" method="POST">
												<button type="button" name="settingsResetButton" id="settingsResetButton"
													style="color:#ff0000"
													onclick="if(confirm('Reset device?')){
																console.log('Reset device confirmed');
																$('#resetParentControlForm').submit(); 
															} 
															else {
															};";
													>Reset
												</button>
											</form>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
</body>
</html>
