<?php
// Define o nome da sessão antes de iniciar ou destruir
session_name("chulettaaa");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_destroy();
header('Location: ../index.php');
exit;
