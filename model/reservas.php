<?PHP
include_once "include/config.inc.php";
include_once "include/_funciones.php";
include_once "mysql.class.php";

class Reservas {

	var $id;
	var $id_fecha;
	var $id_equipo;
	var $fecha_libre;
	var $observacion;

	function Reservas($id="") {

		if ($id != "") {
			$valores = $this->getReservaById($id);
			$this->id = $valores[0]["id"]; 
			$this->id_fecha = $valores[0]["id_fecha"];			
			$this->id_equipo = $valores[0]["id_equipo"];
			$this->fecha_libre = $valores[0]["fecha_libre"]; 
			$this->observacion = $valores[0]["observacion"];
		}
	}

	function getReservaById($id="") {
		$db = new Db();
		
		$query = "Select * from ga_reservas r where r.id = '$id'";

		$res = $db->getRow($query); 
	
		$db->close();
		
		return $res;
	}
	
	function getReservaByIdFecha($id_fecha="") {
		$db = new Db();
		
		$query = "Select r.id as id_reserva, e.id as id_equipo, e.nombre, r.fecha_libre, r.observacion from ga_reservas r, ga_equipos e where r.id_fecha = '$id_fecha' and r.id_equipo = e.id";

		$datos = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $datos;
	}
	
	function getReservaByIdFechaIdEquipo($id_fecha="", $id_equipo="") {
		$db = new Db();
	
		$query = "Select 
					r.id as id_reserva, e.id as id_equipo, e.nombre, r.fecha_libre, r.observacion
				  From 
					ga_reservas r, ga_equipos e
				  Where r.id_fecha = '$id_fecha' and r.id_equipo = '$id_equipo' and r.id_equipo = e.id";
		
		$datos = $db->getResults($query, ARRAY_A);
	
		$db->close();
	
		return $datos;
	}
	
	function getReservaLibresByIdFecha($id_fecha="") {
		$db = new Db();
		
		$query = "Select r.id as id_reserva, e.id as id_equipo, e.nombre, r.fecha_libre, r.observacion from ga_reservas r, ga_equipos e where r.id_fecha = '$id_fecha' and r.fecha_libre != 0 and r.id_equipo = e.id";

		$datos = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $datos;
	}

	
	function getDetalleReservaById($id="") {
		$db = new Db();
		
		$query = "Select * from ga_detalle_reservas d, ga_horas_cancha c where d.id_reserva = '$id' and d.id_horas_cancha = c.id;";

		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	}
	
	function set($valores) { 
		$this->id_fecha = $valores["id_fecha"];			
		$this->id_equipo = $valores["id_equipo"];
		$this->fecha_libre = $valores["fecha_libre"]; 
		$this->observacion = $valores["observacion"];
	}
	
	function insertarDetalleReserva($valores) {
		$db = new Db();
		
		$query = "insert into ga_detalle_reservas(id_reserva,id_horas_cancha) values (".
				"'".$valores["id_reserva"]."',".
				"'".$valores["id_horas_cancha"]."')";
		
		$db->query($query);
		
		$db->close(); 
	}
	
	function insertar() {
		$db = new Db();

		$query = "insert into ga_reservas(id_fecha,id_equipo,fecha_libre,observacion) values (".
				"'".$this->id_fecha."',".
				"'".$this->id_equipo."',".				
				"'".$this->fecha_libre."',".
				"'".$this->observacion."')";
	
		$id = $db->query($query);
		
		$this->id = $id;
		
		$db->close(); 
	}
	
	function eliminar($id_reserva) {
		$db = new Db();
		
		$query = "delete from ga_detalle_reservas where id_reserva = $id_reserva";
		
		$db->query($query);
		
		$query = "delete from ga_reservas where id = $id_reserva";

		$db->query($query);
		
		$db->close(); 
	
	}
	
}

?>