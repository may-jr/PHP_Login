<!-- here, the sessions in the authenticate.php will be destroyed  -->
<?php
session_start();
session_destroy();
//redirect to the login page:
header("Loxation: index.html");
?>