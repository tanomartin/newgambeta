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
	
	var $base;

	function Jugadoras($id="") {
		$this->base = new Db();
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
		$db = $this->base;
		$query = "insert into ga_jugadoras(nombre,dni,email,fechaNac,telefono) values (".
				"'".$this->nombre."',".
				"'".$this->dni."',".
				"'".$this->email."',".
				"'".$this->fechaNac."',".
				"'".$this->telefono."')";
		$this->id = $db->query($query);
		
	}

	function eliminar() {
		$db = $this->base;
		$query = "delete from ga_jugadoras_equipo where idJugadora = ".$this->id ;
		$db->query($query);
		$query = "delete from ga_jugadoras where id = ".$this->id ;
		$db->query($query);
		
	}

	function actualizar() {
		$db = $this->base;
		$query = "update ga_jugadoras set
		          nombre = '". $this->nombre."',
		          dni = '". $this->dni."',
		          email = '". $this->email."',
		          telefono = '". $this->telefono."',
				  fechaNac = '". $this->fechaNac."'
				  where id = ".$this->id ;
		$db->query($query);
		
	}

	function get($id="") {
		$db = $this->base;
		$query = "Select j.* from ga_jugadoras j where 1=1 " ;
		if ($id != "") {
			$query .= " and j.id = '$id' ";
		}
		$query .= " order by j.nombre";
		$res = $db->getResults($query, ARRAY_A);
		
		return $res;
	}
	
	function getByDocumento($documento="") {
		$db = $this->base;
		$query = "Select j.* from ga_jugadoras j where dni= ".$documento;
		$query .= " order by j.nombre";
		$res = $db->getResults($query, ARRAY_A);
		
		return $res;
	}
	
	function getByApellido($apellido="", $nombre="") {
		$db = $this->base;
		$query = "Select j.* from ga_jugadoras j where nombre like '%".$apellido."%' and nombre like '%".$nombre."%'";
		$query .= " order by j.nombre";
		$res = $db->getResults($query, ARRAY_A);
		
		return $res;
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = $this->base;
		$query = "Select SQL_CALC_FOUND_ROWS j.* from ga_jugadoras j where 1=1";
		if (trim($filtros["fnombre"]) != "")
			$query.= " and j.nombre like '%".strtoupper($filtros["fnombre"])."%'";
		if (trim($filtros["fdni"]) != "")
			$query.= " and j.dni  like '%".strtoupper($filtros["fdni"])."%'";
		$query.= " order by j.nombre LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A);
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A);
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		
		return $datos;
	}
	
	function getEquiposById($id="") {
		$db = $this->base;
		$query = "Select je.id as idJugadoraEquipo,	 
						 j.nombre as nombreJugadora,
      					 e.nombre as nombreEquipo,
       					 t.nombre as torneo,
       					 c.nombrePagina as categoria,
      					 je.activa,
      					 je.numero
		        from ga_jugadoras j, 
		        	 ga_jugadoras_equipo je, 
		        	 ga_equipos_torneos et, 
		        	 ga_equipos e,
		        	 ga_torneos_categorias tc,
		        	 ga_torneos t,
		        	 ga_categorias c
				where j.id = $id and 
					  j.id = je.idJugadora and
		              je.idEquipoTorneo = et.id and 
				      et.idEquipo = e.id and
					  et.idTorneoCat = tc.id and
					  tc.id_torneo = t.id and
					  tc.id_categoria = c.id" ;
		$res = $db->getResults($query, ARRAY_A);
		
		return $res;
	}
	
	function getJugadoraEquipo($idJugadoraEquipo="") {
		$db = $this->base;
		$query = "Select je.id as idJugadoraEquipo,
						 tc.id as idTorneoCat,
						 e.nombre,
						 j.id as idJugadora,
						 e.id as idEquipo,
						 t.id as idTorneo,
						 c.id as idCategoria,
						 je.activa,
						 je.numero
				  from  ga_jugadoras j,
						ga_jugadoras_equipo je,
						ga_equipos_torneos et,
						ga_equipos e,
						ga_torneos_categorias tc,
						ga_torneos t,
						ga_categorias c
				where 	je.id = $idJugadoraEquipo and
						je.idJugadora = j.id and
						je.idEquipoTorneo = et.id and
						et.idEquipo = e.id and
						et.idTorneoCat = tc.id and
						tc.id_torneo = t.id and
						tc.id_categoria = c.id";
		$res = $db->getResults($query, ARRAY_A);
		
		return $res;
	}
	
	function insertarequipo($datos){
		$db = $this->base;
		if (isset($datos['activo'])) {
			$activo = $datos['activo'];
		} else {
			$activo = 0;
		}
		if (isset($datos['email'])) {
			$envioMail = $datos['email'];
		} else {
			$envioMail = 0;
		}
		$query = "insert into ga_jugadoras_equipo values ('DEFAULT',".
				"'".$datos['id']."',".
				"'".$datos['idEquipoTorneo']."',".
				"'".$datos['numero']."',".
				"'".$activo."',".
				"'".$envioMail."')";
		$db->query($query);
		
	}
	
	function actualizarequipo($datos){
		$db = $this->base;
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
		$query = "update ga_jugadoras_equipo set 
					idEquipoTorneo = ".$datos['idEquipoTorneo'].",
					numero = ".$datos['numero'].", 
					activa = ".$activo.", 
					envioMail = ".$envioMail." 
				  where id = ".$datos['idJugadoraEquipo']." and idJugadora = ".$datos['id'];
		$db->query($query);
		
	}
	
	function getByEquipoTorneo($idEquipo="", $idTorneoCat="") {
		$db = $this->base;
		$query = "Select 
					j.*, 
					e.nombre as equipo, 
					je.id as idJugadoraEquipo, 
					je.numero as numero, 
					je.activa as activa,
					je.envioMail as envioMail
				  From 
				  	ga_equipos_torneos et,
					ga_jugadoras_equipo je, 
					ga_jugadoras j,
					ga_equipos e
			      Where 
					et.idEquipo = $idEquipo and et.idTorneoCat = $idTorneoCat and
					et.id = je.idEquipoTorneo and
					je.idJugadora = j.id and 
					et.idEquipo = e.id";
		$res = $db->getResults($query, ARRAY_A);
		return $res;
	}
	
	
	function getByEquipoTorneoActiva($idEquipo="", $idTorneoCat="") {
		$db = $this->base;
		$query = "Select
		j.*,
		e.nombre as equipo,
		je.id as idJugadoraEquipo,
		je.numero as numero,
		je.activa as activa,
		je.envioMail as envioMail
		From
		ga_equipos_torneos et,
		ga_jugadoras_equipo je,
		ga_jugadoras j,
		ga_equipos e
		Where
		et.idEquipo = $idEquipo and et.idTorneoCat = $idTorneoCat and
		et.id = je.idEquipoTorneo and
		je.idJugadora = j.id and
		et.idEquipo = e.id and je.activa = 1";
		$res = $db->getResults($query, ARRAY_A);
		return $res;
	}
	
	function getCantidadActivaByEquipoTorneo($idEquipo="", $idTorneoCat="") {
		$db = $this->base;
		$query = "Select
		count(*) as cantidad
		From
		ga_equipos_torneos et,
		ga_jugadoras_equipo je,
		ga_jugadoras j
		Where
		et.idEquipo = $idEquipo and et.idTorneoCat = $idTorneoCat and
		et.id = je.idEquipoTorneo and 
		je.activa = 1 and
		je.idJugadora = j.id";
		$res = $db->getResults($query, ARRAY_A);
		
		return $res[0]['cantidad'];
	}
	
	function getByIdEquipoTorneo($idEquipoTorneo="") {
		$db = $this->base;
		$query = "Select
					j.*,
					je.id as idJugadoraEquipo,
					je.numero as numero,
					je.activa as activa,
					je.envioMail as envio
				  From
					ga_jugadoras_equipo je,
					ga_jugadoras j
				  Where
					je.idEquipoTorneo = $idEquipoTorneo and
					je.idJugadora = j.id" ;
		$res = $db->getResults($query, ARRAY_A);
		
		return $res;
	}
	
	function getActivasByIdEquipoTorneo($idEquipoTorneo="") {
		$db = $this->base;
		$query = "Select count(*) as cantidad From ga_jugadoras_equipo je Where
					je.idEquipoTorneo = $idEquipoTorneo and je.activa = 1";
		$res = $db->getResults($query, ARRAY_A);
		
		return $res[0]['cantidad'];
	}
	
	function getReferentesByIdEquipoTorneo($idEquipoTorneo="") {
		$db = $this->base;
		$query = "Select
					j.*,
					je.id as idJugadoraEquipo,
					je.numero as numero,
					je.activa as activa
				  From
					ga_jugadoras_equipo je,
					ga_jugadoras j
				  Where
					je.idEquipoTorneo = $idEquipoTorneo and je.envioMail = 1 and
					je.idJugadora = j.id" ;
		$res = $db->getResults($query, ARRAY_A);
		
		return $res;
	}
	
	function cambiarActiva($idJugadorasEquipos, $activo) {
		$db = $this->base;
		if ($activo == 0) {
			$query = "update ga_jugadoras_equipo set activa = ".$activo.", envioMail = ".$activo." where id = ".$idJugadorasEquipos;
		} else {
			$query = "update ga_jugadoras_equipo set activa = ".$activo." where id = ".$idJugadorasEquipos;
		}
		$db->query($query);
		
	}
	
	function borrarEquipo($idJugadorasEquipos) {
		$db = $this->base;
		$query = "delete from ga_jugadoras_equipo where id = ".$idJugadorasEquipos;
		$db->query($query);
		
	}

	function getByFixture($idFixture,$idEquipoTorneo) {
		$db = $this->base;
		$query = "Select je.*, r.*, j.nombre
				  from ga_jugadoras_equipo je left join ga_resultados r on je.id = r.idJugadoraEquipo, ga_jugadoras j
				  where (idFixture = ". $idFixture. " or idFixture is null) 
				  and je.idEquipoTorneo = ".$idEquipoTorneo." and je.idJugadora = j.id";
		print($query);echo"<br><br>";
		$res = $db->getResults($query, ARRAY_A);
		
		return $res;
	}
	
	function getEstadisticas($idJugadorasEquipos) {
		$db = $this->base;
		$query = "Select 
					sum(goles) as goles, 
					sum(tarjeta_amarilla) as amarillas,
					sum(tarjeta_roja) as rojas 
				  from 
					ga_resultados 
			      where idJugadoraEquipo = ".$idJugadorasEquipos;
		$res = $db->getResults($query, ARRAY_A);
		
		return $res;
	}
	
	function updateTarjetasGoles($idJugadora="", $idEquipoTorneo="") {
		$db = $this->base;
		$query = "update ga_jugadoras_equipo set
		          amarillas = amarillas + '". $this->amarillas."',
		          rojas = rojas + '". $this->rojas."',
		          observaciones = '". $this->observaciones."'
				  where idJugadora = ".$idJugadora." and idEquipoTorneo = ".$idEquipoTorneo;
		print($query);
		
	}
	
	function activarEnvioMail($idJugadorasEquipos, $envio) {
		$db = $this->base;
		$query = "update ga_jugadoras_equipo set envioMail = ".$envio." where id = ".$idJugadorasEquipos;
		$db->query($query);
		
	}
	
	function cargarNumero($idJugadorasEquipos, $numero="") {
		$db = $this->base;
		$query = "update ga_jugadoras_equipo set numero = '".$numero."' where id = ".$idJugadorasEquipos;
		$db->query($query);
	}
	
	
	function jugadoraEnEquipo($idJugadora="", $idEquipoTorneo="") {
		$db = $this->base;
		$query = "Select count(*) as cantidad from ga_jugadoras_equipo where idJugadora = '$idJugadora' and idEquipoTorneo = '$idEquipoTorneo'";
		$res = $db->getRow($query);
		
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function existePorDni($dni="") {
		$db = $this->base;
		$query = "Select count(*) as cantidad from ga_jugadoras where dni = '$dni'";
		$res = $db->getRow($query);
	
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	}
}

?>