<?PHP
include_once "include/config.inc.php";
include_once "mysql.class.php";

class Arbitros {

	var $id;
	var $nombre;
	var $telefono;
		
	function Arbitros($id="") {
		if ($id != "") {
			$arbitro = $this->get($id);
			$this->id = $arbitro[0]["id"]; 
			$this->nombre = $arbitro[0]["nombre"];
			$this->telefono = $arbitro[0]["telefono"];
		}
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
		$db = new Db();	
		$query = "insert into ga_arbitros(nombre, telefono) values ("."'".$this->nombre."'".","."'".$this->telefono."'".")" ;
		$db->query($query); 
		$db->close();	
	}


	function eliminar() {
		$db = new Db();
		$query = "delete from ga_arbitros where id = ".$this->id ; 
		$db->query($query); 
		$db->close();
	}
	
	function modificar() {
		$db = new Db();
		$query = "update ga_arbitros set nombre = '". $this->nombre."', telefono = '". $this->telefono."' where id = ".$this->id ;		  
		$db->query($query); 
		$db->close();
	
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select c.* from ga_arbitros c where 1=1 " ;
		if ($id != "") {
			$query .= " and c.id = '$id' ";
		}
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS c.* from ga_arbitros c where 1=1 ";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and c.nombre like '%".strtoupper($_REQUEST["fnombre"])."%'";		  
		$query.= " LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}
	
}

?>