// actions.js - uses AJAX to call app php device functions
$(document).ready(function() {
//alert('Ready Called');
// Ping button pressed
    $('#pingControlForm').submit(function(e) {
		// clear status area
		document.getElementById("statusArea").value = "";
console.log('Ping submitted');
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '../utilities/actions.php',
            data: {"pingDevice" : "1"},

            success: function(response)
            {
                var jsonData = JSON.parse(response);

                if (jsonData.success == "1")
                {
					document.getElementById("statusArea").value = jsonData.output;
                }
                else
                {
					document.getElementById("statusArea").value = jsonData.output;
                    alert(jsonData.output);
                }
           }
       });
     });

// Water On button  pressed
    $('#waterOnControlForm').submit(function(e) {
		// clear status area
		document.getElementById("statusArea").value = "";
console.log('Water On submitted');
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '../utilities/actions.php',
            data: {"waterOn" : "1"},
            success: function(response)
            {
                var jsonData = JSON.parse(response);

                if (jsonData.success == "1")
                {
					document.getElementById("statusArea").value = jsonData.output;
					waterOffTimerHandle = setTimeout(turnWaterOff,waterOffTimerValSecs);
                }
                else
                {
					// Water On failed
					document.getElementById("statusArea").value = jsonData.output;
					//  Turn button off 
					changeButtonColour('waterOnButton','#0069ed');
					// Stop timer if it got set
				    clearTimeout(waterOffTimerHandle);
                    alert(jsonData.output);
                }
           }
       });
     });

// Water Off button pressed
    $('#waterOffControlForm').submit(function(e) {
		// clear status area
		document.getElementById("statusArea").value = "";
console.log('Water Off submitted');
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '../utilities/actions.php',
            data: {"waterOff" : "1"},
            success: function(response)
            {
                var jsonData = JSON.parse(response);
//console.log(jsonData);
                if (jsonData.success == "1")
                {	
					document.getElementById("statusArea").value = jsonData.output;
					clearTimeout(waterOffTimerHandle);
                }
                else
                {
					document.getElementById("statusArea").value = jsonData.output;
                    alert(jsonData.output);
                }
           }
       });
     });

// Read Temepature button pressed
    $('#temperatureControlForm').submit(function(e) {
		// clear status area
		document.getElementById("statusArea").value = "";
console.log('Read Temperature submitted');
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '../utilities/actions.php',
            data: {"readTemperature" : "1"},
            success: function(response)
            {
                var jsonData = JSON.parse(response);
//console.log(jsonData);
                if (jsonData.success == "1")
                {
					//document.getElementById("statusArea").value = jsonData.output;
					// curl OK but result doesn't contain temp data
					if( typeof(jsonData.temperature) == 'undefined') {
						document.getElementById("statusArea").value = "Centigrade data error";
					}
					else {
						document.getElementById("tempArea").value = jsonData.temperature;
						document.getElementById("statusArea").value = "Temp read running";
					}
                }
                else
                {
					// curl call failed
					document.getElementById("statusArea").value = "Temp read failed";
					alert(jsonData.output);
                }
           }
       });
     });

// Set device to initialised and store values set on sliders
    $('#setParentControlForm').submit(function(e) {
		// clear status area
		document.getElementById("statusArea").value = "";
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '../utilities/actions.php',
            data: {"setParentDevice" : "1"},
            success: function(response)
            {
//console.log(response);
                var jsonData = JSON.parse(response);

                if (jsonData.success == "1")
                {
					document.getElementById("statusArea").value = "Parent Device Set";
                }
                else
                {
					document.getElementById("statusArea").value = "Failed to Reset Parent Device";
                    alert('Failed to Set Parent Device');
                }
           }
       });
     });

	// Reset device to unitinialised
    $('#resetParentControlForm').submit(function(e) {
		// clear status area
		document.getElementById("statusArea").value = "";
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '../utilities/actions.php',
            data: {"resetParentDevice" : "1"},
            success: function(response)
            {
                var jsonData = JSON.parse(response);

                if (jsonData.success == "1")
                {
					document.getElementById("statusArea").value = "Parent Device Reset";
                }
                else
                {
					document.getElementById("statusArea").value = "Failed to Reset device"; 
                    alert('Failed to Reset Parent Device');
                }
           }
       });
     });

	// show the stored values for the Device Settings stored in the database
    $('#showSettingsControlForm').submit(function(e) {
		// clear status area
		document.getElementById("statusArea").value = "";
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '../utilities/actions.php',
            data: {"getDeviceSettings" : "1"},
            success: function(response)
            {
                var jsonData = JSON.parse(response);

                if (jsonData.success == "1")
                {
					document.getElementById("statusArea").value = "Got Device Settings";
                }
                else
                {
					document.getElementById("statusArea").value = "Failed to get Device Settings"; 
                    alert('Failed to get Device Settings');
                }
           }
       });
     });

// end of document.ready
});

