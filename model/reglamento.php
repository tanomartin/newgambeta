<?PHP
include_once "include/config.inc.php";
include_once "mysql.class.php";

class Reglamento {

	var $descripcion;

	function set($valores){
		
		$this->descripcion = $valores["descripcion"];

	}

	
	function modificar($id) {

		$db = new Db();
		
		$valor=htmlentities(addslashes($this->descripcion));
		
		$query = "update ga_reglamento set 
		          descripcion  = '". $valor."'
				  where id = ".$id ;
		  
		$db->query($query); 
		$db->close();
	
	}
	
	function getById($id, $output = OBJECT) {
		$db = new Db();

		$query = "Select c.*
				  From ga_reglamento c
				  WHERE  c.id = '".$id."'";

		$oReg = $db->getRow($query,"",$output); 

		$db->close();
		return $oReg;

	}
	
}

?>