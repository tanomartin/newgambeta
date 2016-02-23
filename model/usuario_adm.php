<?php
include_once "mysql.class.php";
class Usuario_adm {
	var $id_usuario;
	var $usuario;
	var $clave;
	var $nombre;
	var $apellido;
	var $esAdmin;
	var $base;
	
	function Usuario_adm() {
		$this->base = new Db();
	}
	
	/**
	 * Seteo usuario partiendo del objeto parametro
	 * 
	 * @param requestParam $oParametro        	
	 */
	function _setParametro($aParametro) {
		if ($aParametro) {
			$this->id_usuario = $aParametro ["id_usuario"];
			$this->usuario = $aParametro ["usuario"];
			$this->clave = $aParametro ["clave"];
			$this->nombre = $aParametro ["nombre"];
			$this->apellido = $aParametro ["apellido"];
			$this->esAdmin = $aParametro ["esAdmin"];
		}
	}
	function getByUsrPass($usuario, $clave) {
		$db = $this->base;
		
		$query = "Select u.* 
					From ga_usuarios_adm u
					 Where u.usuario = '" . $usuario . "' 
					And   u.clave = '" . md5 ( $clave ) . "'";
		
		$oUsuario = $db->getRow ( $query );
		
		return $oUsuario;
	}
	
	/**
	 * Agregar usuario
	 * 
	 * @return id insertado
	 */
	function insertar() {
		$db = $this->base;
		
		$query = "insert into ga_usuarios_adm (
							usuario 		,
							clave 	,
							nombre,
							apellido,
							esAdmin ) values (" . "'" . $this->usuario . "'," . "'" . md5 ( $this->clave ) . "'," . "'" . $this->nombre . "'," . "'" . $this->apellido . "'," . "'" . $this->esAdmin . "'" . ")";
		
		$id_insertado = $db->query ( $query );
		
		$this->id_usuario = $id_insertado;
		
		return $id_insertado;
	}
	
	/**
	 * Actualizar usuario
	 */
	function actualizar() {
		$db = $this->base;
		
		$query = "update ga_usuarios_adm set " . " usuario 		    = '" . $this->usuario . "'," . " clave 	        = '" . md5 ( $this->clave ) . "'," . " nombre        = '" . $this->nombre . "'," . " nombre        = '" . $this->apellido . "'," . " esAdmin        = '" . $this->esAdmin . "'," . " where id_usuario = '" . $this->id_usuario . "'";
		
		$db->query ( $query );
	}
	function eliminar() {
		$db = $this->base;
		
		$query = "delete from ga_usuarios_adm " . " where id_usuario = '" . $this->id_usuario . "'";
		
		$db->query ( $query );
	}
	
	/**
	 * Cambiar clave al usuario
	 */
	function cambiarClave($clave_actual, $clave_nueva = "") {
		if ($clave_nueva == "") {
			$clave_nueva = GeneratePassword ();
		}
		
		$db = $this->base;
		
		$query = "update ga_usuarios_adm set " . " clave	        = '" . md5 ( $clave_nueva ) . "' " . " where usuario = '" . $this->usuario . "'
						  and   clave   = '" . md5 ( $clave_actual ) . "'";
		
		$db->query ( $query );
	}
	
	/**
	 * Obtiene usuarios
	 * 
	 * @return aUsuario
	 */
	function getById($id, $output = OBJECT) {
		$db = $this->base;
		
		$query = "Select u.* 
					  From ga_usuarios_adm u
					  Where u.id_usuario = '" . $id . "'";
		
		$oUsuario = $db->getRow ( $query, "", $output );
		
		return $oUsuario;
	}
	
	/**
	 * Obtiene un usuario por el mail
	 * 
	 * @return aUsuario
	 */
	function getByUsuario($usr, $output = OBJECT) {
		$db = $this->base;
		
		$query = "Select u.* 
					  From ga_usuarios_adm u
					  Where u.usuario = '" . $usr . "'";
		
		$oUsuario = $db->getRow ( $query, "", $output );
		
		return $oUsuario;
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = $this->base;
		
		$query = "Select SQL_CALC_FOUND_ROWS  u.*	
		          From ga_usuarios_adm u 
				  where 1 = 1";
		
		if (trim ( $filtros ["fusuario"] ) != "")
			$query .= " and u.usuario like '%" . $_REQUEST ["fusuario"] . "%'";
		
		$query .= " order by usuario LIMIT $inicio,$cant";
		
		$datos = $db->getResults ( $query, ARRAY_A );
		
		$cant_reg = $db->getResults ( "SELECT FOUND_ROWS() cant", ARRAY_A );
		
		$total = ceil ( $cant_reg [0] ["cant"] / $cant );
		
		return $datos;
	}
}

?>
