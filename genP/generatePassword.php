<?php
$saltedHashedPassword = password_hash("PUT YOUR PASSWORD HERE",PASSWORD_DEFAULT);
echo "salted hashed password = $saltedHashedPassword" . "\n";

?>
