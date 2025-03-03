<?php 
session_name("chuletaaaa");
session_start();
session_destroy();
header('Location: ../index.php');
exit;

?>