<?PHP
include_once "include/config.inc.php";
include_once "mysql.class.php";

class Arbitros {

	var $id;
	var $nombre;
	var $telefono;
	
	var $base;
	
	function Arbitros($id="") {
		if ($id != "") {
			$arbitro = $this->get($id);
			$this->id = $arbitro[0]["id"]; 
			$this->nombre = $arbitro[0]["nombre"];
			$this->telefono = $arbitro[0]["telefono"];
		}
		$this->base = new Db();
	}

	
	function set($valores){
		$this->id = ($valores["idreg"])?$valores["idreg"]:$valores["id"]; 
		$this->nombre = $valores["nombre"];
		$this->telefono = $valores["telefono"];
	}
	
	function _setById($id) {
		$aCat = $this->getById($id, ARRAY_A);	
		$this->set($aCat);
	}
		

	function agregar() {
		$db = $this->base;
		$query = "insert into ga_arbitros(nombre, telefono) values ("."'".$this->nombre."'".","."'".$this->telefono."'".")" ;
		$db->query($query); 
	}


	function eliminar() {
		$db = $this->base;
		$query = "delete from ga_arbitros where id = ".$this->id ; 
		$db->query($query); 
	}
	
	function modificar() {
		$db = $this->base;
		$query = "update ga_arbitros set nombre = '". $this->nombre."', telefono = '". $this->telefono."' where id = ".$this->id ;		  
		$db->query($query); 
	}
	
	function get($id="") {
		$db = $this->base;
		$query = "Select c.* from ga_arbitros c where 1=1 " ;
		if ($id != "") {
			$query .= " and c.id = '$id' ";
		}
		$res = $db->getResults($query, ARRAY_A); 
		return $res;
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = $this->base;
		$query = "Select SQL_CALC_FOUND_ROWS c.* from ga_arbitros c where 1=1 ";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and c.nombre like '%".strtoupper($_REQUEST["fnombre"])."%'";		  
		$query.= " LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		return $datos;	
	}
	
}

?>