<?PHP
include_once "mysql.class.php";

class Fechas {

	var $id;
	var $nombre;
	var $idTorneoCat;
	var $fechaIni;
	var $fechaFin;
	var $fotoPreview;
	var $fotoGrande;
		
	function Fechas($id="") {
		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->nombre = $valores[0]["nombre"];			
			$this->idTorneoCat = $valores[0]["idTorneoCat"];
			$this->fechaIni = cambiaf_a_mysql($valores[0]["fechaIni"]); 
			$this->fechaFin = cambiaf_a_mysql($valores[0]["fechaFin"]);
		}
	}

	
	function set($valores){
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];			
		$this->idTorneoCat = $valores["idTorneoCat"];
		$this->fechaIni = cambiaf_a_mysql($valores["fechaIni"]); 
		$this->fechaFin = cambiaf_a_mysql($valores["fechaFin"]);
	}
	
	function _setById($id) {	
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		

	function insertar($files) {
		$db = new Db();
		$query = "insert into ga_fechas(
				idTorneoCat,nombre,fechaIni,fechaFin
				) values (".
				"'".$this->idTorneoCat."',".
				"'".$this->nombre."',".				
				"'".$this->fechaIni."',".
				"'".$this->fechaFin."')";
		$this->id = $db->query($query); 
		$db->close();
	}


	function eliminar() {
		$db = new Db();		
		$query = "delete from ga_fechas where id = ".$this->id ;	  
		$db->query($query); 		
		$query = "delete from ga_fechas_horas where id_fecha = ".$this->id ;		
		$db->query($query); 		
		$db->close();	
	}
	
	function actualizar($files) {
		$db = new Db();
		$query = "update ga_fechas set 
		          nombre = '". $this->nombre."',		
		          idTorneoCat = '". $this->idTorneoCat."',
		          fechaIni = '". $this->fechaIni."',
		          fechaFin = '". $this->fechaFin."'
				  where id = ".$this->id ;				  
		$db->query($query); 	
		$db->close();	
	}
	
	function get($id="") {
		$db = new Db();		
		$query = "Select e.*, tc.id_torneo, tc.	id_categoria, tc.id as idTorneoCat,t.nombre as torneo, c.nombrePagina  as categoria
				  from ga_fechas e, ga_torneos t, ga_torneos_categorias tc, ga_categorias c
				  where e.idTorneoCat = tc.id and tc.id_torneo = t.id and tc.id_categoria = c.id " ;
		if ($id != "") {	
			$query .= " and e.id = '$id' ";
		}	
		$query .= " order by e.nombre";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();		
		return $res;	
	}

	function getIdTorneoCat($id="",$orden="") {
		$db = new Db();	
		$query = "Select e.*, tc.id_torneo, tc.	id_categoria, tc.id as idTorneoCat,t.nombre as torneo, c.nombrePagina  as categoria
				  from ga_fechas e, ga_torneos t, ga_torneos_categorias tc, ga_categorias c
				  where e.idTorneoCat = tc.id and tc.id_torneo = t.id and tc.id_categoria = c.id " ;
		if ($id != "") {	
			$query .= " and e.idTorneoCat = '$id' ";
		}	
		if ( $orden != "") {
			$order = " order by e.".$orden;	
		} else {
			$order = " order by e.nombre";
		}
		$query .= $order;	
		$res = $db->getResults($query, ARRAY_A); 	
		$db->close();		
		return $res;	
	}
	
	function getFechaActual($id="",$orden="") {
		$aFechas = $this -> getIdTorneoCat($id,$orden);
		return count($aFechas);
	}

	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  e.*, t.nombre as torneo, c.nombrePagina as categoria
		          from ga_fechas e, ga_torneos t, ga_torneos_categorias tc, ga_categorias c
				  where e.idTorneoCat = tc.id and tc.id_torneo = t.id and tc.id_categoria = c.id ";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and e.nombre like '%".strtoupper($filtros["fnombre"])."%'";		  

		if (trim($filtros["ftorneo"]) != "")		 
			$query.= " and  t.nombre  like '%".strtoupper($filtros["ftorneo"])."%'";		  

		if (trim($filtros["fcategoria"]) != "")		 
			$query.= " and  c.nombrePagina like '%".strtoupper($filtros["fcategoria"])."%'";		  
		$query.= " order by e.fechaIni DESC, e.fechaFin DESC LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 		
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 	
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}

	
	function getHorasCancha($id="") {
		$db = new Db();		
		$query = "Select f.*, c.descripcion from ga_fechas_horas f, ga_horas_cancha c where f.id_fecha = $id and f.id_horas_cancha = c.id" ;		
		$datos = $db->getResults($query, ARRAY_A); 
		$db->close();		
		return $datos;
	}
	
	function getFechaActiva($id="") {
		$db = new Db();		
		$today = date("Y-m-d");		
		$query = "Select * from ga_fechas f where idTorneoCat = $id and fechaIni <= '$today' and fechaFin >= '$today' limit 1";
		$datos = $db->getResults($query, ARRAY_A); 	
		$db->close();	
		if ($datos[0]!=NULL) {
			$datos[0]['puedeReservar'] = $this->puedeReservar($datos[0]['fechaIni']);
		}		
		return $datos[0];
	}
	
	function setHorasCancha($id_fecha="",$id_horas_cancha="") {	
		$db = new Db();	
		$query = "Insert into ga_fechas_horas(id_fecha, id_horas_cancha) values($id_fecha,$id_horas_cancha)";
		$db->query($query); 	
		$db->close();	
	}
	
	function deleteHorasCancha($id_fecha="") {	
		$db = new Db();	
		$query = "delete from ga_fechas_horas where id_fecha = $id_fecha";	
		$db->query($query); 	
		$db->close();	
	}
	
	function puedeReservar($fechaIni) {
		$today = date('Y-m-d');
		$dias = (strtotime($today)-strtotime($fechaIni));
		$dias = $dias/(60*60*24);
		if ($dias > 2) {
			return false;
		}
		return true;	
	}
	
}

?>