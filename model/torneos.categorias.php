<?PHP
	include_once "include/_funciones.php";
	include_once "mysql.class.php";

class TorneoCat {

	var $id;  	
	var $id_torneo;
	var $id_categoria; 	
	var $id_padre; 	
		
	function TorneoCat($id="") {
		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id_torneo_categoria"]; 
			$this->id_torneo = $valores[0]["id_torneo"]; 
			$this->id_categoria = $valores[0]["id_categoria"]; 
			$this->id_padre = -1;
		}
	}

	function set($valores){
			$this->id = $valores["id_torneo_categoria"]; 
			$this->id_torneo = $valores["id_torneo"]; 
			$this->id_categoria = $valores["id_categoria"]; 
			$this->id_padre = -1;
	}
	
	function _setById($id) {		
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		

	function insertar() {
		$db = new Db();	
		$query = "insert into ga_torneos_categorias(
				id_torneo, id_categoria,id_padre
				) values (".
				"'".$this->id_torneo."',".
				"'".$this->id_categoria."',".
				"'".$this->id_padre."'".				
				")" ;
		$id_insertado = $db->query($query); 
		$db->close();
		return $id_insertado;
	}

	function actualizarPadre() {
		$db = new Db();	
		$query = "update ga_torneos_categorias set id_padre = 0 where id_torneo = ". $this->id_torneo ." and id_categoria = ".$this->id_padre;
		$db->query($query); 	
		$db->close();
		return $id_insertado;
	}

	function eliminar() {
		$db = new Db();	
		$query = "delete from ga_torneos_categorias where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	
	function get($id="") {
		$db = new Db();
		$query = "Select * from ga_torneos_categorias" ;
		if ($id != "") {
			$query .= " where id = '$id' ";
		}
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getById($id, $output = OBJECT) {
		$db = new Db();
		$query = "Select * From ga_torneos_categorias u Where id = '".$id."'";
		$oDatos = $db->getRow($query,"",$output); 
		$db->close();
		return $oDatos;
	}
	
	function getByTorneo($id,$order ="") {
		$db = new Db();
		$query = "Select tc.*, c.nombrePagina
				  From ga_torneos_categorias tc, ga_categorias c
				  Where tc.id_categoria = c.id and id_torneo = '".$id."' and id_padre < 1";
		if ($order != "")
			$query.= " order by $order";
		else
			$query.= " order by id_categoria ";	
		$aDatos = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $aDatos;
	}
	
	function getByTorneoSub($id) {
		$db = new Db();
		$query = "Select tc.*,c.nombrePagina, c1.nombrePagina as nombreCatPagina, IFNULL(c1.id,c.id) as idCat
				  From ga_torneos_categorias tc 
				  left join ga_categorias c on tc.id_categoria = c.id
				  left join ga_categorias c1 on tc.id_padre = c1.id				  
				  Where  id_torneo = '".$id."' and id_padre <> 0 ";
		$query.= " order by idCat,id ";
		$aDatos = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $aDatos;
	}

	function getByIdCompleto($id) {
		$db = new Db();
		$query = "Select tc.*,c.nombrePagina,c1.nombrePagina as nombreCatPagina, IFNULL(c1.id,c.id) as idCat, t.nombre as torneo
				  From ga_torneos_categorias tc 
				  left join ga_categorias c on tc.id_categoria = c.id
				  left join ga_categorias c1 on tc.id_padre = c1.id		
				  left join ga_torneos t on tc.id_torneo = t.id						  
				  Where  tc.id = '".$id."' and id_padre <> 0 ";
		$query.= " order by idCat,tc.id ";
		$oDatos = $db->getRow($query); 
		$db->close();
		return $oDatos;
	}

	function getByTorneoFechas($id,$order ="") {
		$db = new Db();
		$query = "Select tc.*, c.nombrePagina,c1.nombrePagina as nombreCatPagina
				  From ga_torneos_categorias tc left join ga_categorias c
				  on tc.id_categoria = c.id 
				  left join ga_categorias c1
				  on tc.id_padre = c1.id 				  
				  where id_torneo = '".$id."' and id_padre <> 0";
		if ($order != "")
			$query.= " order by $order";
		else
			$query.= " order by id_categoria ";	
		$aDatos = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $aDatos;
	}

	function getBySubCategorias($id,$idCategoria) {
		$db = new Db();
		$query = "Select tc.*,c.nombrePagina
				  From ga_torneos_categorias tc, ga_categorias c
				  Where tc.id_categoria = c.id and id_torneo = '".$id."' and id_padre = ".$idCategoria;
		if ($order != "")
			$query.= " order by $order";
		else
			$query.= " order by id_categoria ";	
		$aDatos = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $aDatos;
	}
	
	function obtenerIdCat($id,$id_torneo,&$idCatPadre) {
		$db = new Db();
		$query = "Select tc.*
				  From ga_torneos_categorias tc
				  Where id = '".$id."' and id_torneo = ".$id_torneo;	
		$idCatPadre = $id;
		$oDatos = $db->getRow($query); 
		if ($oDatos -> id_padre == 0) {
			$query = "Select tc.*
				  From ga_torneos_categorias tc
				  Where id_padre = '".$oDatos -> id_categoria."' order by id ";
			$oDatos1 = $db->getRow($query); 
			$id = $oDatos1 -> id;
		}
		if ( $oDatos -> id_padre > 0) {
			$query = "Select tc.*
				  From ga_torneos_categorias tc
				  Where id_categoria = '". $oDatos->id_padre ."'  and id_torneo = ".$id_torneo." order by id";
			$oDatos = $db->getRow($query); 
			$idCatPadre = $oDatos->id;
		}
		$db->close();	
		return $id;
	}

	function getCategoriasCompletas($id="", $filtros="") {
		$db = new Db();
		$query = "Select tc.*, c.nombrePagina,c1.nombrePagina as nombreCatPagina, t.nombre as nombreTorneo
				  From ga_torneos_categorias tc left join ga_categorias c
				  on tc.id_categoria = c.id 
				  left join ga_categorias c1
				  on tc.id_padre = c1.id
				  left join ga_torneos t
				  on tc.id_torneo = t.id 
				  where id_padre <> 0";
		if ($id != "")	
			$query.= " and  tc.id = ". $id;	
		if (trim($filtros["ftorneo"]) != "" ) 
			$query.= " and t.nombre like '%".strtoupper($filtros["ftorneo"])."%'";	
		$query.= " order by   t.nombre, c.nombrePagina ";	
		$aDatos = $db->getResults($query, ARRAY_A); 
		$db->close();	
		return $aDatos;
	}
}

?>