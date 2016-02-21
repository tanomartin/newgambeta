<?PHP
include_once "mysql.class.php";
include_once "../include/PHPMailer/class.phpmailer.php";
include_once "../include/PHPMailer/class.smtp.php";



class Correos {
	
	private $correoSalida = "XXXXXXXXXXXXXXXXXXXXXXXXX";
	private $nombreSalida = "XXXXXXXXXXXXXXXXXXXXXXXXX";
	private $pass = "XXXXXXXXXXXXXXXXXXXXXXXXX";

	var $correo;
	var $cuerpo;
	var $equipoId;
	var $equipoNombre;
		
	function Correos($valores="") {
		$this->correo = $valores["correo"]; 
		$this->cuerpo = $valores["cuerpo"]; 
		$this->equipoId = $valores["equipoId"]; 
		$this->equipoNombre = $valores["equipoNombre"]; 
		$this->asunto = $valores["asunto"]; 
	}
	
	function enviar() {
		$mail = new PHPMailer(); 
		$mail->IsSMTP(); 
		//$mail->SMTPDebug = 1;
		$mail->SMTPAuth = true; 
		$mail->SMTPSecure = "ssl"; 
		$mail->Host = "smtp.gmail.com"; 
		$mail->Port = 465; 
		$mail->IsHTML(true); 
		
		$mail->Username = $this->correoSalida;
		$mail->Password = $this->pass;
		
		$mail->SetFrom($this->correoSalida);
		$mail->FromName = $this->nombreSalida; 
		$mail->Subject = $this->asunto;
		$mail->Body = $this->equipoNombre.": <br><br>".$this->cuerpo;
		$mail->AltBody = $this->cuerpo;
		$mail->AddAddress($this->correo, $this->equipoNombre); 
		
		if(!$mail->Send()) { 
			echo "Error: " . $mail->ErrorInfo. "<br>"; 
			return false;
		} else { 
			return true;
		}
	}
	
	function cargarCorreo($idEquipo= "", $idFecha="", $tabla="") {
		$db = new Db();
		$today = date('Y-m-d');
		if ($tabla == "r") {
			$query = "Insert into ga_correo_reservas(id_equipo,id_fecha,fecha_envio) value ($idEquipo,$idFecha,'$today')";
		}
		if ($tabla == "c") {
			$query = "Insert into ga_correo_confirmacion(id_equipo,id_fecha,fecha_envio) value ($idEquipo,$idFecha,'$today')";
		}
		$db->query($query);
		$db->close();
	}
}

?>