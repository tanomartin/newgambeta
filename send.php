<?php
error_reporting(0);

//$casilla_destino = "info@gambetafemenina.com.ar";
$casilla_destino = "mzuccotti93@gmail.com";
$nombre = (isset($_POST["nombre"]))? $_POST["nombre"] : "";
$email = (isset($_POST["email"]))? $_POST["email"] : "";
$telefono = (isset($_POST["telefono"]))? $_POST["telefono"] : "";
$mensaje = (isset($_POST["mensaje"]))? $_POST["mensaje"] : "";
	
	
$asunto = "Consulta - Gambeta Femenina";
$cuerpo_mail .= "Nombre y Apellido: ".$nombre."\r\n";
$cuerpo_mail .= "Email: ".$email."\r\n";
$cuerpo_mail .= "Telefono: ".$telefono."\r\n";
$cuerpo_mail .= "Mensaje: ".$mensaje."\r\n";
	
	
@mail($casilla_destino, $asunto, $cuerpo_mail);

echo 'mensaje enviado correctamente';

?>