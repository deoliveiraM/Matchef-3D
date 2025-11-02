<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: usuario.php");
    exit;
}
echo "Bem-vindo, " . $_SESSION['usuario']." -- ".$_SESSION['email'];
?>
