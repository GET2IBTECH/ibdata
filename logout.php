<?php
session_start();    // Starts the session
session_unset();    // Unsets all session variables
session_destroy();  // Destroys all data registered to the session
header("Location: login.html"); // Redirects the user
exit();             // Ensures no further code is executed
?>