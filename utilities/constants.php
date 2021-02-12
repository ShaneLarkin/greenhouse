<?php
// constants.php - constant values
// Even numbers need to be quotes.
// There's a const keyword, but it's the same

// debug file - the directory must be writeable )as a group) by www-data
define("DEBUG_FILE","/var/www/html/debug/debug.txt");

// offset into the "output" array from curl that contains the temperature
define("OFFSET_TO_TEMPERATURE","4");

?>
