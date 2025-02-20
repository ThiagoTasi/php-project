<?php 
session_name("chuletaaaa");
session_start();
session_destroy();
header('Locations: ../index.php');
exit;

?>