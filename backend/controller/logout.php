<!-- The PHP code initiates a session, destroys it using `session_destroy()`, and then redirects the user to the "/jwd" location. 
This script effectively ends the user's session and redirects them to a specific location, likely a homepage or a login page, indicated by the "/jwd" URL. 
This is a common pattern used for logging out users by destroying their session data and redirecting them to another page. -->

<?php
session_start();
session_destroy();
header('Location: /jwd');
?>