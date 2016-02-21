<?PHP
include_once "mysql.class.php";
include_once "include/fechas.php";

class Jugadoras {

	var $id;
	var $nombre;
	var $dni;
	var $email;
	var $foto;
	var $fechaNac;
	var $telefono;

	function Jugadoras($id="") {
		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"];
			$this->nombre = $valores[0]["nombre"];
			$this->dni = $valores[0]["dni"];
			$this->email = $valores[0]["email"];
			$this->fechaNac = ($valores[0]["fechaNac"])?cambiaf_a_mysql($valores[0]["fechaNac"]):'1980-01-01';
			$this->foto = $valores[0]["foto"];
			$this->telefono = $valores[0]["telefono"];
		}
	}


	function set($valores){
		$this->id = $valores["id"];
		$this->nombre = $valores["nombre"];
		$this->dni = $valores["dni"];
		$this->email = $valores["email"];
		$this->fechaNac = ($valores["fechaNac"])?cambiaf_a_mysql($valores["fechaNac"]):'1980-01-01';
		$this->foto = $valores["foto"];
		$this->telefono =  $valores["telefono"];
	}

	function _setById($id) {
		$aValores = $this->getById($id, ARRAY_A);
		$this->set($aValores);
	}

	function insertar() {
		$db = new Db();
		$query = "insert into ga_jugadoras(nombre,dni,email,fechaNac,telefono) values (".
				"'".$this->nombre."',".
				"'".$this->dni."',".
				"'".$this->email."',".
				"'".$this->fechaNac."',".
				"'".$this->telefono."')";
		$this->id = $db->query($query);
		$db->close();
	}

	function eliminar() {
		$db = new Db();
		$query = "delete from ga_jugadoras_equipo where idJugadora = ".$this->id ;
		$db->query($query);
		$query = "delete from ga_jugadoras where id = ".$this->id ;
		$db->query($query);
		$db->close();
	}

	function actualizar() {
		$db = new Db();
		$query = "update ga_jugadoras set
		          nombre = '". $this->nombre."',
		          dni = '". $this->dni."',
		          email = '". $this->email."',
		          telefono = '". $this->telefono."',
				  fechaNac = '". $this->fechaNac."'
				  where id = ".$this->id ;
		$db->query($query);
		$db->close();
	}

	function get($id="") {
		$db = new Db();
		$query = "Select j.* from ga_jugadoras j where 1=1 " ;
		if ($id != "") {
			$query .= " and j.id = '$id' ";
		}
		$query .= " order by j.nombre";
		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}
	
	function getByDocumento($documento="") {
		$db = new Db();
		$query = "Select j.* from ga_jugadoras j where dni= ".$documento;
		$query .= " order by j.nombre";
		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}
	
	function getByApellido($apellido="", $nombre="") {
		$db = new Db();
		$query = "Select j.* from ga_jugadoras j where nombre like '%".$apellido."%' and nombre like '%".$nombre."%'";
		$query .= " order by j.nombre";
		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS j.* from ga_jugadoras j where 1=1";
		if (trim($filtros["fnombre"]) != "")
			$query.= " and j.nombre like '%".strtoupper($filtros["fnombre"])."%'";
		if (trim($filtros["fdni"]) != "")
			$query.= " and j.dni  like '%".strtoupper($filtros["fdni"])."%'";
		$query.= " order by j.nombre LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A);
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A);
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;
	}
	
	function getEquiposById($id="") {
		$db = new Db();
		$query = "Select je.id as idJugadoraEquipo,	 
						 j.nombre as nombreJugadora,
      					 e.nombre as nombreEquipo,
       					 t.nombre as torneo,
       					 c.nombrePagina as categoria,
      					 p.nombre as posicion,
      					 je.activa
		        from ga_jugadoras j, 
		        	 ga_jugadoras_equipo je, 
		        	 ga_equipos_torneos et, 
		        	 ga_equipos e, 
		        	 ga_posiciones p, 
		        	 ga_torneos_categorias tc,
		        	 ga_torneos t,
		        	 ga_categorias c
				where j.id = $id and 
					  j.id = je.idJugadora and 
					  je.idPosicion = p.id and 
		              je.idEquipoTorneo = et.id and 
				      et.idEquipo = e.id and
					  et.idTorneoCat = tc.id and
					  tc.id_torneo = t.id and
					  tc.id_categoria = c.id" ;
		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}
	
	function getJugadoraEquipo($idJugadoraEquipo="") {
		$db = new Db();
		$query = "Select je.id as idJugadoraEquipo,
						 tc.id as idTorneoCat,
						 j.id as idJugadora,
						 e.id as idEquipo,
						 t.id as idTorneo,
						 c.id as idCategoria,
						 p.id as idPosicion,
						 je.activa
				  from  ga_jugadoras j,
						ga_jugadoras_equipo je,
						ga_equipos_torneos et,
						ga_equipos e,
						ga_posiciones p,
						ga_torneos_categorias tc,
						ga_torneos t,
						ga_categorias c
				where 	je.id = $idJugadoraEquipo and
						je.idJugadora = j.id and
						je.idPosicion = p.id and
						je.idEquipoTorneo = et.id and
						et.idEquipo = e.id and
						et.idTorneoCat = tc.id and
						tc.id_torneo = t.id and
						tc.id_categoria = c.id";
		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}
	
	function insertarequipo($datos){
		$db = new Db();
		if (isset($datos['activo'])) {
			$activo = 1;
		} else {
			$activo = 0;
		}
		if (isset($datos['email'])) {
			$envioMail = 1;
		} else {
			$envioMail = 0;
		}
		$query = "insert into ga_jugadoras_equipo values ('DEFAULT',".
				"'".$datos['id']."',".
				"'".$datos['idEquipoTorneo']."',".
				"'".$datos['idPosicion']."',".
				"'".$activo."',".
				"'".$envioMail."')";
		$db->query($query);
		$db->close();
	}
	
	function actualizarequipo($datos){
		$db = new Db();
		if (isset($datos['activo'])) {
			$activo = 1;
		} else {
			$activo = 0;
		}
		if (isset($datos['email'])) {
			$envioMail = 1;
		} else {
			$envioMail = 0;
		}
		$query = "update ga_jugadoras_equipo 
					set idEquipoTorneo = ".$datos['idEquipoTorneo'].", idPosicion = ".$datos['idPosicion'].", activa = ".$activo.", envioMail = ".$envioMail." 
							where id = ".$datos['idJugadoraEquipo']." and idJugadora = ".$datos['id'];
		$db->query($query);
		$db->close();
	}
	
	function getByEquipoTorneo($idEquipo="", $idTorneoCat="") {
		$db = new Db();
		$query = "Select 
					j.*, 
					e.nombre as equipo, 
					je.id as idJugadoraEquipo, 
					je.activa as activa,
					je.envioMail as envioMail,
					p.nombre as posicion
				  From 
				  	ga_equipos_torneos et,
					ga_jugadoras_equipo je, 
					ga_posiciones p,
					ga_jugadoras j,
					ga_equipos e
			      Where 
					et.idEquipo = $idEquipo and et.idTorneoCat = $idTorneoCat and
					et.id = je.idEquipoTorneo and
					je.idJugadora = j.id and 
					et.idEquipo = e.id and
					je.idPosicion = p.id";
		$query .= " order by je.idPosicion";
		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}
	
	function getByIdEquipoTorneo($idEquipoTorneo="") {
		$db = new Db();
		$query = "Select
					j.*,
					je.id as idJugadoraEquipo,
					je.activa as activa
				  From
					ga_jugadoras_equipo je,
					ga_jugadoras j
				  Where
					je.idEquipoTorneo = $idEquipoTorneo and
					je.idJugadora = j.id" ;
		$query .= " order by je.idPosicion";
		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}
	
	function cambiarActiva($idJugadorasEquipos, $activo) {
		$db = new Db();
		if ($activo == 0) {
			$query = "update ga_jugadoras_equipo set activa = ".$activo.", envioMail = ".$activo." where id = ".$idJugadorasEquipos;
		} else {
			$query = "update ga_jugadoras_equipo set activa = ".$activo." where id = ".$idJugadorasEquipos;
		}
		$db->query($query);
		$db->close();
	}
	
	function borrarEquipo($idJugadorasEquipos) {
		$db = new Db();
		$query = "delete from ga_jugadoras_equipo where id = ".$idJugadorasEquipos;
		$db->query($query);
		$db->close();
	}

	function getByFixture($idFixture,$idEquipoTorneo) {
		$db = new Db();
		$query = "Select je.*, r.*, j.nombre
				  from ga_jugadoras_equipo je left join ga_resultados r on je.id = r.idJugadoraEquipo, ga_jugadoras j
				  where (idFixture = ". $idFixture. " or idFixture is null) 
				  and je.idEquipoTorneo = ".$idEquipoTorneo." and je.idJugadora = j.id";
		$query .= " order by je.idPosicion";
		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}
	
	function getEstadisticas($idJugadorasEquipos) {
		$db = new Db();
		$query = "Select 
					sum(goles) as goles, 
					sum(tarjeta_amarilla) as amarillas,
					sum(tarjeta_roja) as rojas 
				  from 
					ga_resultados 
			      where idJugadoraEquipo = ".$idJugadorasEquipos;
		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}
	
	function updateTarjetasGoles($idJugadora="", $idEquipoTorneo="") {
		$db = new Db();
		$query = "update ga_jugadoras_equipo set
		          amarillas = amarillas + '". $this->amarillas."',
		          rojas = rojas + '". $this->rojas."',
		          observaciones = '". $this->observaciones."'
				  where idJugadora = ".$idJugadora." and idEquipoTorneo = ".$idEquipoTorneo;
		print($query);
		$db->close();
	}
	
	function activarEnvioMail($idJugadorasEquipos, $envio) {
		$db = new Db();
		$query = "update ga_jugadoras_equipo set envioMail = ".$envio." where id = ".$idJugadorasEquipos;
		$db->query($query);
		$db->close();
	}
	
	function jugadoraEnEquipo($idJugadora="", $idEquipoTorneo="") {
		$db = new Db();
		$query = "Select count(*) as cantidad from ga_jugadoras_equipo where idJugadora = '$idJugadora' and idEquipoTorneo = '$idEquipoTorneo'";
		$res = $db->getRow($query);
		$db->close();
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	}
}

?>