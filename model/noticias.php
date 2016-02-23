<?php
include_once "include/_funciones.php";
include_once "mysql.class.php";
// include_once "mensaje.php";
// include_once "parametros.php";
// include_once "include/fechas.php";
class Noticias {
	var $id;
	var $titulo;
	var $desarrollo;
	var $fecha;
	var $posicion;
	var $idTorneoCat;
	var $base;
	function Noticias($id = "") {
		if ($id != "") {
			$valores = $this->get ( $id );
			$this->id = $valores [0] ["id"];
			$this->titulo = $valores [0] ["titulo"];
			$this->desarrollo = $valores [0] ["desarrollo"];
			$this->fecha = $valores [0] ["fecha"];
			$this->posicion = $valores [0] ["posicion"];
			$this->idTorneoCat = $valores [0] ["idTorneoCat"];
		}
		$this->base = new Db ();
	}
	
	/**
	 * Seteo usuario partiendo del objeto parametro
	 * 
	 * @param requestParam $oParametro        	
	 */
	function set($aParametro) {
		if ($aParametro) {
			
			$this->id = $aParametro ["id"];
			$this->titulo = $aParametro ["titulo"];
			$this->desarrollo = $aParametro ["desarrollo"];
			$this->fecha = $aParametro ["fecha"];
			$this->posicion = $aParametro ["posicion"];
			$this->idTorneoCat = $aParametro ["idTorneoCat"];
		}
	}
	function _setById($id) {
		$Novedad = $this->getById ( $id, ARRAY_A );
		$this->_setParametro ( $Novedad );
	}
	
	/**
	 * Agregar una Novedad
	 * 
	 * @return id insertado
	 */
	function agregar() {
		$db = $this->base;
		$this->fecha = eregi_replace ( "/", "-", $this->mysql_fecha ( $this->fecha ) );
		$query = "insert into ga_noticias (
							titulo,	desarrollo,
							fecha,posicion,idTorneoCat
						 ) values (" . "'" . $this->titulo . "', " . "'" . $this->desarrollo . "', " . "'" . $this->fecha . "', " . "'" . $this->posicion . "', " . "'" . $this->idTorneoCat . "' " . ")";
		
		$id_insertado = $db->query ( $query );
		$this->id = $id_insertado;
		
		return $id_insertado;
	}
	
	/**
	 * Actualizar una Novedad
	 */
	function modificar() {
		$db = $this->base;
		$this->fecha = eregi_replace ( "/", "-", $this->mysql_fecha ( $this->fecha ) );
		
		$query = "update ga_noticias set 
					titulo		    = '" . $this->titulo . "', 
					desarrollo	    = '" . $this->desarrollo . "', 
					fecha			= '" . $this->fecha . "',
					posicion		= '" . $this->posicion . "',					
					idTorneoCat		= '" . $this->idTorneoCat . "'					
				where id = '" . $this->id . "'";
		
		$db->query ( $query );
	}
	
	function eliminar() {
		$db = $this->base;
		$query = "delete from ga_noticias " . " where id = '" . $this->id . "'";
		$db->query ( $query );
		
	}
	
	function get($id = "", $output = ARRAY_A) {
		$db = $this->base;
		$query = "Select u.*, date_format(fecha,'%e/%c/%Y') as fecha
					  From ga_noticias u";
		if ($id != "")
			$query .= " Where u.id = '" . $id . "'";
		$query .= " Order by id desc ";
		$aNovedad = $db->getResults ( $query, $output );
		return $aNovedad;
	}
	
	function getByPos($pos = 1, $cant = 1) {
		$db = $this->base;
		$query = "Select u.*, date_format(fecha,'%e/%c/%Y') as fecha
					  From ga_noticias u where posicion = " . $pos;
		$query .= " Order by id desc LIMIT 0,$cant";
		$aNovedad = $db->getResults ( $query, ARRAY_A );	
		return $aNovedad;
	}
	
	function getByCant($cant, $idTorneoCat = 0) {
		$db = $this->base;
		$query = "Select u.*, date_format(fecha,'%e/%c/%Y') as fecha
					  From ga_noticias u where 	idTorneoCat = " . $idTorneoCat;
		$query .= " Order by id desc LIMIT 0,$cant";
		$aNovedad = $db->getResults ( $query, ARRAY_A );
		return $aNovedad;
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = $this->base;
		$query = "Select SQL_CALC_FOUND_ROWS u.*, date_format(fecha,'%e/%c/%Y') as fecha
				  From ga_noticias u
				  where  1 = 1";
		if (trim ( $filtros ["fnombre"] ) != "")
			$query .= " and u.titulo like '%" . $filtros ["fnombre"] . "%'";
		$query .= " order by u.fecha desc LIMIT $inicio,$cant";
		$datos = $db->getResults ( $query, ARRAY_A );
		$cant_reg = $db->getResults ( "SELECT FOUND_ROWS() cant", ARRAY_A );
		$total = ceil ( $cant_reg [0] ["cant"] / $cant );
		return $datos;
	}
	
	function mysql_fecha($fech) {
		$fech1 = explode ( "/", $fech );
		if (strlen ( trim ( $fech1 [1] ) ) == 1) {
			$fech1 [1] = "0" . $fech1 [1];
		}
		if (strlen ( trim ( $fech1 [0] ) ) == 1) {
			$fech1 [0] = "0" . $fech1 [0];
		}
		return trim ( $fech1 [2] ) . "/" . trim ( $fech1 [1] ) . "/" . trim ( $fech1 [0] );
	}
}
?>
