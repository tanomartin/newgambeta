<?PHP
include_once "include/config.inc.php";
include_once "include/_funciones.php";
include_once "mysql.class.php";

class Resultados {

	var $idFixture;
	var $idJugadoraEquipo;
	var $goles;
	var $tarjeta_amarilla;
	var $tarjeta_roja;
	var $mejor_jugadora;
	
	function Resultados($idFixture="") {
		if ($idFixture != "") {
			$valores = $this->get($idFixture);
			$this->idFixture = $valores[0]["idFixture"]; 
			$this->idJugadoraEquipo = $valores[0]["$idJugadoraEquipo"];			
			$this->goles = ($valores[0]["goles"])?$valores[0]["goles"]:0;
			$this->tarjeta_amarilla = ($valores[0]["tarjeta_amarilla"])?$valores[0]["tarjeta_amarilla"]:0; 
			$this->tarjeta_roja = ($valores[0]["tarjeta_roja"])?$valores[0]["tarjeta_roja"]:0;
			$this->mejor_jugadora = $valores[0]["mejor_jugadora"];
		}
	}

	function set($valores){
		$this->idFixture = $valores["idFixture"]; 
		$this->idJugadoraEquipo = $valores["idJugadoraEquipo"];			
		$this->goles = ($valores["goles"])?$valores["goles"]:0;
		$this->tarjeta_amarilla = ($valores["tarjeta_amarilla"])?$valores["tarjeta_amarilla"]:0; 
		$this->tarjeta_roja = ($valores["tarjeta_roja"])?$valores["tarjeta_roja"]:0;
   	    $this->mejor_jugadora = $valores["mejor_jugadora"];
	}
	
	function _setById($idFixture) {		
		$aValores = $this->getById($idFixture, ARRAY_A);	
		$this->set($aValores);
	}
		
	function insertar() {
		$db = new Db();
		if ( ($this->goles != 0) || ($this->tarjeta_amarilla != 0) || ($this->tarjeta_roja != 0) || ($this->mejor_jugadora != 'N') ){
			$query = "insert into ga_resultados(idFixture,idJugadoraEquipo,
				goles,tarjeta_amarilla,tarjeta_roja,mejor_jugadora
				) values (".
				"'".$this->idFixture."',".
				"'".$this->idJugadoraEquipo."',".				
				"'".$this->goles."',".
				"'".$this->tarjeta_amarilla."',".
				"'".$this->tarjeta_roja."',".				
				"'".$this->mejor_jugadora."')";
			$db->query($query); 
		}
		$db->close();
	}

	function borrarByIdFixture($idFixture) {
		$db = new Db();	
		$query = "delete from ga_resultados where idFixture = ".$idFixture ;
		$db->query($query); 
		$db->close();
	}
	
	function borrarByIdJugadoraEquipo($idJugadoraEquipo) {
		$db = new Db();
		$query = "delete from ga_resultados where idJugadoraEquipo = ".$idJugadoraEquipo;
		$db->query($query);
		$db->close();
	}
	
	function actualizar() {
		$db = new Db();
		if (($this->goles != 0) || ($this->tarjeta_amarilla != 0) || ($this->tarjeta_roja != 0 || ($this->mejor_jugadora != 'N')) ){
			$query = "update ga_resultados set 
		          idJugadoraEquipo = '". $this->idJugadoraEquipo."',		
		          goles = '". $this->goles."',
		          tarjeta_amarilla = '". $this->tarjeta_amarilla."',
		          tarjeta_roja = '". $this->tarjeta_roja."'
				  where idFixture = ".$this->idFixture."
				  and idJugadora = ".$this->idJugadora ;	  
			$db->query($query); 
		}	
		$db->close();
	}
	
	function get($idFixture="",$idJugadoraEquipo="") {
		$db = new Db();	
		$query = "Select e.* from ga_resultados e where 1 = 1 " ;
		if ($idFixture != "") {
			$query .= " and e.idFixture = '$idFixture' ";
		}
		if ($idJugadora != "") {
			$query .= " and e.idJugadoraEquipo = '$idJugadoraEquipo' ";
		}
		$query .= " order by e.idJugadoraEquipo";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();	
		return $res;
	}

	//TODO VER
	function goleadoras($idTorneoCat) {
		$db = new Db();
		$query = "select r.idJugadoraEquipo, j.nombre, e.nombre as nombreEquipo, SUM(r.goles) as goles, count(*) as partidos
					from ga_resultados r
					inner join ga_fixture fx on r.idFixture = fx.id
					inner join ga_fechas f on fx.idFecha = f.id
					inner join ga_jugadoras_equipo je on r.idJugadoraEquipo = je.id
					inner join ga_jugadoras j on je.idJugadora = j.id
					inner join ga_equipos_torneos et on je.idEquipoTorneo = et.id
					inner join ga_equipos e on et.idEquipo = e.id
					inner join ga_torneos_categorias tc on f.idTorneoCat = tc.id
					where f.idTorneoCat = ".$idTorneoCat."
					GROUP BY idJugadora, j.nombre order by 4 desc LIMIT 0,15";	  
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;	
	}
	
	function getTarjetasByIdJugadoraEquipo($idJugadoraEquipo="") {
		$db = new Db();
		$query = "Select 
					j.nombre, sum(tarjeta_amarilla) amarillas, sum(tarjeta_roja) rojas
				  from 
				  	ga_resultados r, ga_jugadoras_equipo je, ga_jugadoras j
				  where 
				  	je.id = '$idJugadoraEquipo' and je.id = r.idJugadoraEquipo and je.idJugadora = j.id";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
}

?>