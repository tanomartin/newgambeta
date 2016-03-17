<?PHP
include_once "include/config.inc.php";
include_once "mysql.class.php";

class Categorias {

	var $id;
	var $nombrePagina;	

	var $base;
	
	function Categorias($id="") {
		$this->base= new Db();
		if ($id != "") {
			$categoria = $this->get($id);
			$this->id = $categoria[0]["id"]; 
			$this->nombrePagina = $categoria[0]["nombrePagina"];
		}
	}

	
	function set($valores){
		
		$this->id = ($valores["idreg"])?$valores["idreg"]:$valores["id"]; 
		$this->nombrePagina = $valores["nombrePagina"];

	}
	
	function _setById($id) {
				
		$aCat = $this->getById($id, ARRAY_A);	
		$this->set($aCat);
	}
		

	function agregar() {
		$db = $this->base;
				
		$query = "insert into ga_categorias(
				nombrePagina		
				) values (".
				"'".$this->nombrePagina."'".		
				")" ;
		$db->query($query); 
		
			
			
	}


	function eliminar() {
	
		$db = $this->base;

		$query = "delete from ga_categorias where id = ".$this->id ;
				  
		$db->query($query); 
		
		$query = "delete from ga_torneos_categorias where id_categoria = ".$this->id ;
				  
		$db->query($query); 


	
	}
	
	function modificar() {

		$db = $this->base;
		$query = "update ga_categorias set 
		          nombrePagina 		= '". $this->nombrePagina."' 
				  where id = ".$this->id ;
				  
		$db->query($query); 
		
	
	}
	
	function get($id="") {
	
		$db = $this->base;
		$query = "Select c.* from ga_categorias c where 1=1 " ;
		
		if ($id != "") {
		
			$query .= " and c.id = '$id' ";
		}

		$res = $db->getResults($query, ARRAY_A); 
	
		
		
		return $res;
	
	}

	function getById($id, $output = OBJECT) {
		$db = $this->base;

		$query = "Select c.*
				  From ga_categorias c
				  WHERE  c.id = '".$id."'";

		$oCat = $db->getRow($query,"",$output); 

		
		return $oCat;

	}
	

	function getPaginado($filtros, $inicio, $cant, &$total) {

		$db = $this->base;
		$query = "Select SQL_CALC_FOUND_ROWS  c.* 
				   from ga_categorias c 
				  where 1=1 ";
	

		if (trim($filtros["fnombrePagina"]) != "")		 
			$query.= " and c.nombrePagina like '%".strtoupper($_REQUEST["fnombrePagina"])."%'";		    

		$query.= " LIMIT $inicio,$cant";

		$datos = $db->getResults($query, ARRAY_A); 
		
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
	
		$total = ceil( $cant_reg[0]["cant"] / $cant );

		

		return $datos;	

	}
	
	function getByCategoriasDisponibles($id, $output = OBJECT) {
		$db = $this->base;
		$query = "Select *
				  From ga_categorias
				  where id not in(
					Select 	id_categoria
				  	From ga_torneos_categorias								  
					Where id_torneo = '".$id." and id_padre = -1')";

		$aDatos = $db->getResults($query,ARRAY_A); 
		
		
		return $aDatos;

	}

	function getBySubCategoriasDisponibles($id, $id_categoria, $output = OBJECT) {
		$db = $this->base;
		$query = "Select *
				  From ga_categorias
				  where id not in(
					Select 	id_categoria
				  	From ga_torneos_categorias								  
					Where id_torneo = '".$id."' and id_padre = '".$id_categoria."')"; 
		$aDatos = $db->getResults($query,ARRAY_A); 
		return $aDatos;

	}

}

?>