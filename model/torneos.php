<?PHP
include_once "include/config.inc.php";
include_once "include/_funciones.php";
include_once "mysql.class.php";

class Torneos {

	var $id;
	var $nombre;
	var $fechaInicio;
	var $fechaFin;
	var $logoPrincipal;
	var $orden;
	var $activo;
	var $color;
		
	function Torneos($id="") {
		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->nombre = $valores[0]["nombre"];	
			$this->fechaInicio = $valores[0]["fechaInicio"]; 
			$this->fechaFin = $valores[0]["fechaFin"];
			$this->logoPrincipal = $valores[0]["logoPrincipal"]; 
			$this->orden = $valores[0]["orden"]; 
			$this->activo = ($valores[0]["activo"] == 'on')? 1: 0;
			$this->color = $valores[0]["color"]; 
		}
	}

	function set($valores){	
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];		
		$this->fechaInicio = $valores["fechaInicio"]; 
		$this->fechaFin = $valores["fechaFin"];
		$this->logoPrincipal = $valores["logoPrincipal"]; 
		$this->orden = $valores["orden"]; 
		$this->activo =  ($valores["activo"] == 'on')? 1: 0;
		$this->color = $valores["color"]; 
	}
	
	function _setById($id) {	
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		
	function insertar($files) {
		$db = new Db();
		$query = "select max( orden ) as orden from ga_torneos";
		$max = $db->getRow($query); 
		$max_orden = $max->orden + 1;
		$this->fechaInicio = eregi_replace("/","-",$this->mysql_fecha($this->fechaInicio));
		$this->fechaFin = eregi_replace("/","-",$this->mysql_fecha($this->fechaFin));
		$query = "insert into ga_torneos(
				nombre,fechaInicio,fechaFin,orden,color,activo
				) values (".
				"'".$this->nombre."',".			
				"'".$this->fechaInicio."',".
				"'".$this->fechaFin."',".
				"'".$max_orden."',".
				"'".$this->color."',".
				"'".$this->activo."')";	
		$this->id = $db->query($query); 
		if(is_uploaded_file($_FILES['logoPrincipal']['tmp_name'])) {
			$name = "pri_".$this->id."_".$files['logoPrincipal']['name'];
			$ruta= "../logos/".$name;	
			move_uploaded_file($_FILES['logoPrincipal']['tmp_name'], $ruta);
			$query = "update ga_torneos set  logoPrincipal = '". $name."'
					  where id = ".$this->id ;
			$db->query($query); 
		}
		$db->close();
	}

	function eliminar() {
		$db = new Db();
		$query = "select * from ga_torneos_categorias where id_torneo = ".$this->id." and id_padre != 0";
		$res = $db->getResults($query, ARRAY_A);
		if (sizeof($res) > 0) {
			foreach($res as $torneo) {
				$query = "select * from ga_equipos_torneos where idTorneoCat = ".$torneo['id'];
				$respass = $db->getResults($query, ARRAY_A);
				if (sizeof($respass) > 0) {
					foreach($respass as $equipotorneo) {
						$query = "delete from ga_equipos_password where id = ".$equipotorneo['id'] ;
						$db->query($query);
					}
				}	
				$query = "delete from ga_equipos_torneos where idTorneoCat = ".$torneo['id'] ;
				$db->query($query);
			}
		}
		$query = "delete from ga_torneos_categorias where id_torneo = ".$this->id;
		$db->query($query);
		$query = "delete from ga_torneos where id = ".$this->id ;
		$db->query($query);
		$db->close();
	}
	
	function actualizar($files) {
		$db = new Db();
		$this->fechaInicio = eregi_replace("/","-",$this->mysql_fecha($this->fechaInicio));
		$this->fechaFin = eregi_replace("/","-",$this->mysql_fecha($this->fechaFin));
		$query = "update ga_torneos set 
		          nombre = '". $this->nombre."',
				  fechaInicio = '". $this->fechaInicio."',
		          fechaFin = '". $this->fechaFin."',
		          color = '". $this->color."',				  
		          activo = '". $this->activo."'
				  where id = ".$this->id ;
				  
		$db->query($query); 
		if(is_uploaded_file($_FILES['logoPrincipal']['tmp_name'])) {
			$name = "pri_".$this->id."_".$_FILES['logoPrincipal']['name'];
			$ruta= "../logos/".$name;
			move_uploaded_file($_FILES['logoPrincipal']['tmp_name'], $ruta);
			$query = "update ga_torneos set  logoPrincipal = '". $name."'
					  where id = ".$this->id ;
			$db->query($query); 

		}
		$db->close();
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select * from ga_torneos" ;
		if ($id != "") {
			$query .= " where id = '$id' ";
		}
		$query .= " order by orden";  
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();	
		return $res;
	}

	function getPaginado($filtros) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  p.*
		          From ga_torneos p
				  where  1 = 1";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and p.nombre like '%".strtoupper($filtros["fnombre"])."%'";		  
		$query.= " order by orden";
		$datos = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $datos;	
	}

	function mysql_fecha($fech)	{
		$fech1= explode("/",$fech);	
		if(strlen(trim($fech1[1]))==1){
			$fech1[1]="0".$fech1[1];
		}
		if(strlen(trim($fech1[0]))==1)	{
			$fech1[0]="0".$fech1[0];
		}
		return trim($fech1[2])."/".trim($fech1[1])."/".trim($fech1[0]);
	}

	function cambiarActivo($id,$valor)	{
		$db = new Db();
		$query = "update ga_torneos set activo = '". $valor."'		  
					  where id = ".$id ;	  
		$db->query($query); 
		$db->close();
	}

	function cambiarOrden($pos,$orden)	{
		$db = new Db();
		$nueva_pos = $pos+$orden;
		$query = "update ga_torneos set orden = -100  where orden = ".$nueva_pos ;	  
		$db->query($query); 
		$query = "update ga_torneos set orden = ". $nueva_pos."  where orden = ".$pos ;	  
		$db->query($query); 
		$query = "update ga_torneos set orden = ". $pos."  where orden = -100" ;	  
		$db->query($query); 
		$db->close();
	}


	function getByCant($cant) {
		$db = new Db();
		$query = "Select * From ga_torneos  where activo = 1";
		$query .= " Order by orden LIMIT 0,$cant";
		$aTorneo = $db->getResults($query, ARRAY_A); 	
		$db->close();
		return $aTorneo;
	}		

	function getByTorneoCat($idTorneoCat) {	
		$db = new Db();
		$query = "Select * From ga_torneos  t, ga_torneos_categorias tc where tc.id_torneo = t.id and  tc.id = ".$idTorneoCat;
		$oTorneo = $db->getRow($query);	
		$db->close();
		return $oTorneo;
	}

}

?>