<?PHP
include_once "include/config.inc.php";
include_once "mysql.class.php";

class Colores {

	var $id;
	var $nombreColor;
	var $rgb;
	
	var $base;
		
	function Colores($id="") {
		$this->base = new Db();
		if ($id != "") {
			$colores = $this->get($id);
			$this->id = $colores[0]["id"]; 
			$this->nombreColor = $colores[0]["nombreColor"];
			$this->rgb = $colores[0]["rgb"];
		}
	}

	
	function set($valores){
		$this->id = ($valores["idreg"])?$valores["idreg"]:$valores["id"]; 
		$this->nombreColor = $valores["nombreColor"];
		$this->rgb = $valores["rgb"];
	}
	
	function _setById($id) {
		$aCat = $this->getById($id, ARRAY_A);	
		$this->set($aCat);
	}
		

	function agregar() {
		$db = $this->base;
		$query = "insert into ga_colores(nombreColor,rgb) values ('".$this->nombreColor."','".$this->rgb."')";
		print($query);
		$db->query($query); 
	}


	function eliminar() {
		$db = $this->base;
		$query = "delete from ga_colores where id = ".$this->id ; 
		$db->query($query); 
	}
	
	function modificar() {
		$db = $this->base;
		$query = "update ga_colores set nombreColor = '".$this->nombreColor."', rgb = '". $this->rgb."' where id = ".$this->id ;		  
		$db->query($query); 
	}
	
	function get($id="") {
		$db = $this->base;
		$query = "Select c.* from ga_colores c where 1=1 " ;
		if ($id != "") {
			$query .= " and c.id = '$id' ";
		}
		$res = $db->getResults($query, ARRAY_A); 
		return $res;
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = $this->base;
		$query = "Select SQL_CALC_FOUND_ROWS c.* from ga_colores c where 1=1 ";
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