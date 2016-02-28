<?PHP
include_once "include/config.inc.php";
include_once "include/_funciones.php";
include_once "mysql.class.php";

class Equipos {

	var $id;
	var $nombre;
	var $dt;
	var $foto;
	var $descripcion;
	
	var $base;
	
	function Equipos($id="") {
		$this->base = new Db();
		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->nombre = $valores[0]["nombre"];		
			$this->dt = $valores[0]["dt"];
			$this->foto = $valores[0]["foto"]; 
			$this->descripcion = $valores[0]["descripcion"];
		}
	}

	function set($valores){
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];		
		$this->dt = $valores["dt"];
		$this->descripcion = $valores["descripcion"];
		$this->foto = $valores["foto"]; 	
	}
	
	function _setById($id) {		
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		
	function insertar($files) {
		$db = $this->base;
		$query = "insert into ga_equipos(nombre,dt,descripcion) values (".
				"'".$this->nombre."',".
				"'".$this->dt."',".
				"'".$this->descripcion."')";
		$this->id = $db->query($query); 
		if(is_uploaded_file($_FILES['foto']['tmp_name'])) {
			$path_parts = pathinfo($_FILES["foto"]["name"]);
			$extension = $path_parts['extension'];
			$name = "pre_".$this->id."_".time().".".$extension;
			$ruta= "../fotos_equipos/".$name;
			$query = "update ga_equipos set foto = '". $name."' where id = ".$this->id ;
			$db->query($query); 

		}
	}


	function eliminar() {
		$db = $this->base;
		$query = "delete from ga_equipos where id = ".$this->id;
		$db->query($query); 	
		$query = "delete from ga_equipos_torneos where idEquipo = ".$this->id;	
		$db->query($query);		
		$query = "delete from ga_equipos_password where idEquipo = ".$this->id;		
		$db->query($query);
	}
	
	function actualizar($files) {
		$db = $this->base;
		$query = "update ga_equipos set 
		          nombre = '". $this->nombre."',
		          dt = '". $this->dt."',
		          descripcion = '". $this->descripcion."'
				  where id = ".$this->id ;				  
		$db->query($query); 
		if(is_uploaded_file($_FILES['foto']['tmp_name'])) {
			$path_parts = pathinfo($_FILES["foto"]["name"]);
			$extension = $path_parts['extension'];
			$name = "pre_".$this->id."_".time().".".$extension;
			$ruta= "../fotos_equipos/".$name;	
			if ( move_uploaded_file($_FILES['foto']['tmp_name'], $ruta)) {
				$query = "update ga_equipos set  foto = '". $name."'
					  where id = ".$this->id ;
				$db->query($query); 
			}
		}
	}
	
	function get($id="") {
		$db = $this->base;
		$query = "Select e.* from ga_equipos e where 1=1" ;
		if ($id != "") {		
			$query .= " and e.id = '$id' ";
		}		
		$query .= " order by e.nombre";		
		$res = $db->getResults($query, ARRAY_A); 		
		return $res;	
	}

	function getByCategoria($id="") {
		$db = $this->base;
		$query = "Select e.*, t.id as idEquipoTorneo from ga_equipos e, ga_equipos_torneos t where e.id = t.idEquipo and t.idTorneoCat = ". $id;
		$query .= " order by e.nombre";
		$res = $db->getResults($query, ARRAY_A); 
		return $res;
	}
	
	function getByCategoriaEquipos($id="",$idEquipo="") {
		$db = $this->base;
		$query = "Select e.*, t.id as idEquipoTorneo 
					from ga_equipos e, ga_equipos_torneos t 
					where e.id = t.idEquipo and t.idEquipo = ".$idEquipo." and t.idTorneoCat = ". $id;
		$query .= " order by e.nombre";
		$res = $db->getResults($query, ARRAY_A);
		return $res;
	}
	
	function getByIdEquipoTorneo($idEquipoTorneo="") {
		$db = $this->base;
		$query = "Select e.*, t.id as idEquipoTorneo
					from ga_equipos e, ga_equipos_torneos t
					where t.id = ".$idEquipoTorneo." and e.id = t.idEquipo";
		$query .= " order by e.nombre";
		$res = $db->getResults($query, ARRAY_A);
		return $res;
	}
	
	function getTorneos($id="") {		
		$db = $this->base;
		$query = "Select et.*, t.nombre as torneo, c.nombrePagina as categoria
				  from ga_equipos e, ga_equipos_torneos et, ga_torneos_categorias tc, ga_torneos t, ga_categorias c
				  where e.id = ". $id ." and e.id = et.idEquipo and et.idTorneoCat = tc.id and tc.id_torneo = t.id and tc.id_categoria = c.id";		
		$query .= " order by e.nombre";		
		$res = $db->getResults($query, ARRAY_A);				
		return $res;		
	}
	
	function getEquipoTorneo($idEquipo="",$idTorneoCat="") {		
		$db = $this->base;
		$query = "Select et.*, e.nombre 
				  from ga_equipos_torneos et, ga_equipos e
				  where et.idEquipo = ". $idEquipo ." and et.idTorneoCat = ".$idTorneoCat." and et.idEquipo = e.id";		
		$res = $db->getResults($query, ARRAY_A);				
		return $res;	
	}
	
	
	function getRelacionTorneo($id="") {
		$db = $this->base;
		$query = "Select et.*, e.nombre, tc.id_torneo, tc.id_categoria, t.nombre as torneo, c.nombrePagina as categoria
					from ga_equipos_torneos et, ga_torneos_categorias tc, ga_equipos e, ga_torneos t, ga_categorias c
					where et.id = $id and et.idTorneoCat = tc.id and et.idEquipo = e.id and tc.id_torneo = t.id and tc.id_categoria = c.id";		
		$res = $db->getResults($query, ARRAY_A);	
		return $res;
	}
	
	function eliminarRelacionTorneo($id="") {	
		$db = $this->base;
		$query = "Delete from ga_jugadoras_equipo where idEquipoTorneo = $id";
		$db->query($query);
		$query = "Delete from ga_equipos_password where id = $id";
		$db->query($query);
		$query = "Delete from ga_equipos_torneos where id = $id";	
		$db->query($query);		
	}
		
	function guardarRelacionTorneo($datos="") {
		$db = $this->base;
		$query = "INSERT INTO ga_equipos_torneos VALUE (DEFAULT,'".$datos['id']."','".$datos['idTorneoCat']."','".$datos['descuento_puntos']."')";	
		$db->query($query);	
	}
	
	function actualizarRelacionTorneo($datos="") {	
		$db = $this->base;
		$query = "UPDATE ga_equipos_torneos 
					SET idEquipo = '".$datos['id']."', 
						idTorneoCat = '".$datos['idTorneoCat']."', 
						descuento_puntos = '".$datos['descuento_puntos']."'
					WHERE id = ".$datos['idTorneoEquipo'];
		$db->query($query);
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$orden = ($filtros["filter_order"])?$filtros["filter_order"]:"e.id";
		$dir = ($filtros["filter_order_Dir"])?$filtros["filter_order_Dir"]:"asc"; 
		$db = $this->base;
		$query = "Select SQL_CALC_FOUND_ROWS  e.*
		          from ga_equipos e
				  where 1=1 ";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and e.nombre like '%".strtoupper($filtros["fnombre"])."%'";		  
		if (trim($filtros["femail"]) != "")		 
			$query.= " and e.email  like '%".strtoupper($filtros["femail"])."%'";		   
		$query.= " order by  $orden $dir LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 		
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 	
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		return $datos;	
	}


	function getTorneoCat($id="") {
		$db = $this->base;
		$query = "Select e.*, t.id as idEquipoTorneo
				  from ga_equipos e, ga_equipos_torneos t
				  where e.id = t.idEquipo and t.idTorneoCat =  '$id'" ;		
		$query .= " order by e.nombre";	
		$res = $db->getResults($query, ARRAY_A); 
		return $res;
	}
	
	function getEquiposSinTorneo() {
		$db = $this->base;
		$query = "SELECT g.* 
				FROM ga_equipos g 
				where id not in(select idEquipo from ga_equipos_torneos) 
				order by g.nombre";	
		$res = $db->getResults($query, ARRAY_A);			
		return $res;
	}

	function getByEquipoTorneo($idEquipoTorneo="") {	
		$db = $this->base;
		$queryEquipo = "Select * from ga_equipos_torneos where id = ".$idEquipoTorneo;
		$resEquipos = $db->getResults($queryEquipo, ARRAY_A);
		$id = $resEquipos[0]['idEquipo'];
		$query = "Select e.*, et.id as idEquipoTorneo
				  from ga_equipos e, ga_equipos_torneos et
				  where e.id <> '$id' and e.id = et.idEquipo and
				  et.idTorneoCat = (select idTorneoCat from ga_equipos_torneos where id = '$idEquipoTorneo')
				  order by e.nombre";	
		$res = $db->getResults($query, ARRAY_A); 
		return $res;
	}

	function getById($id="") {
		$db = $this->base;
		$query = "Select e.* from ga_equipos e where e.id =  '$id'";	
		$res = $db->getRow($query); 		
		return $res;	
	}
	
	function accesoCorrecto($id="", $idTorneo="", $pass="") {
		$db = $this->base;
		$query = "Select count(*) as cantidad from ga_equipos_password e where id = '$idTorneo' and idEquipo = '$id' and password = '".$pass."'";
		$res = $db->getRow($query); 
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;	
		}
	}

	function tieneFechaLibre($idTorneo="", $idEquipo= "") {
		$db = $this->base;
		$query = "Select count(*) as cantidad 
					from ga_reservas e, ga_fechas f 
					where f.idTorneoCat = '$idTorneo' and f.id = e.id_fecha and e.id_equipo = '$idEquipo' and e.fecha_libre = 1";
		$res = $db->getRow($query); 
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	}

	function tieneReserva($idFecha="", $idEquipo= "") {
		$db = $this->base;
		$query = "Select e.id as id from ga_reservas e where id_Fecha = '$idFecha' and id_equipo = '$idEquipo'";	
		$res = $db->getRow($query); 	
		if($res->id == 0) {
			return 0;
		} else {
			return $res->id;
		}
	}
	
	function getPassword($idTorneoEquipo= "") {
		$db = $this->base;
		$query = "Select * from ga_equipos_password where id = $idTorneoEquipo";
		$res = $db->getResults($query, ARRAY_A); 
		return $res;
	}
	
	function setPassword($idTorneoEquipo= "",$idEquipo= "" , $pass="") {
		$db = $this->base;
		$query = "Delete from ga_equipos_password where id = $idTorneoEquipo";	
		$db->query($query);	
		$query = "Insert into ga_equipos_password value ($idTorneoEquipo,$idEquipo,'".$pass."')";
		$db->query($query);
	}
	
	function seEnvioCorreo($idEquipo= "", $idFecha="", $tabla="") {
		$db = $this->base;
		if ($tabla == "r") {
			$query = "Select count(*) as cantidad from ga_correo_reservas where id_equipo = '$idEquipo' and id_fecha = '$idFecha'";
		}
		if ($tabla == "c") {
			$query = "Select count(*) as cantidad from ga_correo_confirmacion where id_equipo = '$idEquipo' and id_fecha = '$idFecha'";
		}
		$res = $db->getRow($query); 
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function eliminarCorreo($idEquipo= "", $idFecha="", $tabla="") {
		$db = $this->base;
		$today = date('Y-m-d');
		if ($tabla == "r") {
			$query = "delete from ga_correo_reservas where id_equipo = $idEquipo and id_fecha = $idFecha";
		}
		if ($tabla == "c") {
			$query = "delete from ga_correo_confirmacion where id_equipo = $idEquipo and id_fecha = $idFecha";
		}
		$db->query($query);
	}
	
	function getCorreos($idEquipoTorneo) {
		$db = $this->base;
		$query = "select j.email from ga_jugadoras_equipo je, ga_jugadoras j
					where je.idEquipoTorneo = $idEquipoTorneo and je.envioMail = 1 and je.idJugadora = j.id and j.email != ''";
		$res = $db->getResults($query, ARRAY_A);
		return $res;
	}

}

?>