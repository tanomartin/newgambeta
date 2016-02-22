<?PHP
include_once "include/config.inc.php";
include_once "mysql.class.php";

class Colores {

	var $id;
	var $nombreColor;
	var $rgb;
		
	function Colores($id="") {
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
		$db = new Db();	
		$query = "insert into ga_colores(nombreColor,rgb) values ('".$this->nombreColor."','".$this->rgb."')";
		print($query);
		$db->query($query); 
		$db->close();	
	}


	function eliminar() {
		$db = new Db();
		$query = "delete from ga_colores where id = ".$this->id ; 
		$db->query($query); 
		$db->close();
	}
	
	function modificar() {
		$db = new Db();
		$query = "update ga_colores set nombreColor = '".$this->nombreColor."', rgb = '". $this->rgb."' where id = ".$this->id ;		  
		$db->query($query); 
		$db->close();
	
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select c.* from ga_colores c where 1=1 " ;
		if ($id != "") {
			$query .= " and c.id = '$id' ";
		}
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS c.* from ga_colores c where 1=1 ";
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