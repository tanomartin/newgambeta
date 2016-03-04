<?php
session_start(); 
unset($_SESSION['equipo']);
unset($_SESSION['equipoTorneo']);
$acceso = "";
include_once "reservas.php";
?>