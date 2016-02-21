<?PHP
include_once "mysql.class.php";

class HorasCancha {

	var $id;
	var $descripcion;
		
	function HorasCancha($id="") {

		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->descripcion = $descripcion[0]["nombre"];			
		}
	}
	
	function get($id="") {
	
		$db = new Db();
		
		$query = "Select * from ga_horas_cancha where id = $id" ;

		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	
	}

	function getHorasDisponibles() {
		
		$db = new Db();
		
		$query = "Select * from ga_horas_cancha" ;

		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
		
	}
	
}

?>