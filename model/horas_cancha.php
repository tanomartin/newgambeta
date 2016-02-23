<?PHP
include_once "mysql.class.php";

class HorasCancha {

	var $id;
	var $descripcion;
	
	var $base;
		
	function HorasCancha($id="") {

		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->descripcion = $descripcion[0]["nombre"];			
		}
		$this->base = new Db();
	}
	
	function get($id="") {
	
		$db = $this->base;
		
		$query = "Select * from ga_horas_cancha where id = $id" ;

		$res = $db->getResults($query, ARRAY_A); 
	
		
		
		return $res;
	
	}

	function getHorasDisponibles() {
		
		$db = $this->base;
		
		$query = "Select * from ga_horas_cancha" ;

		$res = $db->getResults($query, ARRAY_A); 
	
		
		
		return $res;
		
	}
	
}

?>