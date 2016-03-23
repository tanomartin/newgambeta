<?php

$casilla_destino = "info@gambetafemenina.com.ar";
$nombre = (isset($_POST["nombre"]))? $_POST["nombre"] : "";
$email = (isset($_POST["email"]))? $_POST["email"] : "";
$telefono = (isset($_POST["telefono"]))? $_POST["telefono"] : "";
$mensaje = (isset($_POST["mensaje"]))? $_POST["mensaje"] : "";
			
$asunto = "Consulta - Gambeta Femenina";
$cuerpo_mail = "Nombre y Apellido: ".$nombre."\r\n";
$cuerpo_mail .= "Email: ".$email."\r\n";
$cuerpo_mail .= "Telefono: ".$telefono."\r\n";
$cuerpo_mail .= "Mensaje: ".$mensaje."\r\n";
$cabecera = 'From: '.$email."\r\n".'Reply-To:'.$email."\r\n".'X-Mailer: PHP/'.phpversion();

if (mail($casilla_destino, $asunto, utf8_decode($cuerpo_mail), $cabecera)) {
	echo '1';
} else {
	echo '0';
}

?>