<?PHP
include_once "include/config.inc.php";
include_once "mysql.class.php";

class Sedes {

	var $id;
	var $nombre;
	
	var $base;
	
	function Sedes($id="") {
		if ($id != "") {
			$sede = $this->get($id);
			$this->id = $sede[0]["id"]; 
			$this->nombre = $sede[0]["nombre"];

		}
		$this->base = new Db();
	}

	
	function set($valores){
		
		$this->id = ($valores["idreg"])?$valores["idreg"]:$valores["id"]; 
		$this->nombre = $valores["nombre"];

	}
	
	function _setById($id) {
				
		$aCat = $this->getById($id, ARRAY_A);	
		$this->set($aCat);
	}
		

	function agregar() {
		$db = $this->base;
				
		$query = "insert into ga_sedes(
				  nombre
				) values (".
		"'".$this->nombre."'".
		")" ;
		$db->query($query); 
		
			
			
	}


	function eliminar() {
	
		$db = $this->base;

		$query = "delete from ga_sedes where id = ".$this->id ;
				  
		$db->query($query); 
		
	
	}
	
	function modificar() {

		$db = $this->base;
		$query = "update ga_sedes set 
		          nombre  = '". $this->nombre."'
				  where id = ".$this->id ;
				  
		$db->query($query); 
		
	
	}
	
	function get($id="") {
	
		$db = $this->base;
		$query = "Select c.* from ga_sedes c where 1=1 " ;
		
		if ($id != "") {
		
			$query .= " and c.id = '$id' ";
		}

		$res = $db->getResults($query, ARRAY_A); 
	
		
		
		return $res;
	
	}

	function getById($id, $output = OBJECT) {
		$db = $this->base;

		$query = "Select c.*
				  From ga_sedes c
				  WHERE  c.id = '".$id."'";

		$oCat = $db->getRow($query,"",$output); 

		
		return $oCat;

	}
	

	function getPaginado($filtros, $inicio, $cant, &$total) {

		$db = $this->base;
		$query = "Select SQL_CALC_FOUND_ROWS  c.* 
				   from ga_sedes c 
				  where 1=1 ";
	

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