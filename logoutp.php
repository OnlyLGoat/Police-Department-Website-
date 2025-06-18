<?php
session_start();
session_unset(); 
session_destroy(); 
header("Location: logins/loginp.php");
exit;
