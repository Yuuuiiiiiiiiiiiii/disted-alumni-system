<?php
session_start();
session_unset(); 
session_destroy(); 

header("Location: Alumni Management Page.html"); 
exit();
?>