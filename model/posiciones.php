<?PHP
include_once "include/config.inc.php";
include_once "include/_funciones.php";
include_once "mysql.class.php";
class Posiciones {

	var $id;
	var $nombre;
	var $fechaInicio;
	var $fechaFin;
	var $logoPrincipal;
	var $logoMenu;
	var $orden;
	var $activo;
		
	function Posiciones($id="") {
		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->nombre = $valores[0]["nombre"];
		}
	}

	function set($valores){
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];
	}
	
	function _setById($id) {	
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		
	function insertar($files) {
		$db = new Db();
		$query = "insert into ga_posiciones(nombre) values ("."'".$this->nombre."')";
		$this->id = $db->query($query); 	
		$db->close();
	}


	function eliminar() {
		$db = new Db();	
		$query = "delete from ga_posiciones where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	
	}
	
	function actualizar($files) {
		$db = new Db();
		$query = "update ga_posiciones set nombre = '". $this->nombre."' where id = ".$this->id ;			  
		$db->query($query); 
		$db->close();
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select * from ga_posiciones" ;
		if ($id != "") {
			$query .= " where id = '$id' ";
		}
		$query .= " order by id";  
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getPaginado($filtros, $inicio, $cant, &$total) {
		$orden = ($filtros["filter_order"])?$filtros["filter_order"]:"p.nombre";
		$dir = ($filtros["filter_order_Dir"])?$filtros["filter_order_Dir"]:"asc"; 
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  p.*
		          From ga_posiciones p
				  where  1 = 1";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and p.nombre like '%".strtoupper($filtros["fnombre"])."%'";		  
		$query.= " order by orden, $orden $dir LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 	
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}

	function armarTabla($idTorneoCat){
		$oObj1 = new Fixture();
		$aFixture = $oObj1->getByidTorneoCat($idTorneoCat);
		
		$oObj2 = new Equipos();
		$aEquipos = $oObj2->getByCategoria($idTorneoCat);
				
		$valorGanado = 3;
		$valorEmpatado = 1;
		if (sizeof($aEquipos) > 0) {
			foreach($aEquipos as $key => $valor) {
				$aEquipoR[$valor['idEquipoTorneo']]['nombre'] = $valor['nombre'];
			}
			for ($i=0;$i<count($aFixture);$i++){
				$aEquipoR[$aFixture[$i][idEquipoTorneo1]]['goles_favor'] +=$aFixture[$i][golesEquipo1];
				$aEquipoR[$aFixture[$i][idEquipoTorneo1]]['goles_contra'] +=$aFixture[$i][golesEquipo2];
				$aEquipoR[$aFixture[$i][idEquipoTorneo2]]['goles_favor'] +=$aFixture[$i][golesEquipo2];
				$aEquipoR[$aFixture[$i][idEquipoTorneo2]]['goles_contra'] +=$aFixture[$i][golesEquipo1];			
				$gano1=0;$perdio1=0;$empato1=0;	
				$gano2=0;$perdio2=0;$empato2=0;						
				if ($aFixture[$i][golesEquipo1]>$aFixture[$i][golesEquipo2]) {
					$gano1=1;$perdio2=1;	
				} else {
					if ($aFixture[$i][golesEquipo1]<$aFixture[$i][golesEquipo2]) {
						$gano2=1;$perdio1=1;	
					} else {
						$empato1=1;$empato2=1;	
					}
			  	}
				$aEquipoR[$aFixture[$i][idEquipoTorneo1]]['par_ganados']  += $gano1;
				$aEquipoR[$aFixture[$i][idEquipoTorneo1]]['par_perdidos']  += $perdio1;
				$aEquipoR[$aFixture[$i][idEquipoTorneo1]]['par_empatados'] += $empato1;
				$aEquipoR[$aFixture[$i][idEquipoTorneo1]]['puntaje'] += ($gano1*$valorGanado)+($empato1*$valorEmpatado);
				$aEquipoR[$aFixture[$i][idEquipoTorneo2]]['par_ganados']  += $gano2;
				$aEquipoR[$aFixture[$i][idEquipoTorneo2]]['par_perdidos']  += $perdio2;
				$aEquipoR[$aFixture[$i][idEquipoTorneo2]]['par_empatados'] += $empato2;
				$aEquipoR[$aFixture[$i][idEquipoTorneo2]]['puntaje'] += ($gano2*$valorGanado)+($empato2*$valorEmpatado);
			}
			foreach ($aEquipoR as $key => $row){
				if (isset($row['nombre'])){ 
					$puntaje = (($row['puntaje'])?$row['puntaje']*1000:0) + ($row['goles_favor'] - $row['goles_contra']);
					$puntajes[$key]  = $puntaje ;//($row['puntaje'])?$row['puntaje']:0;
				}
			}		
			arsort($puntajes);		
			foreach ($puntajes as $key => $row) {
				$aTabla[]=	$aEquipoR[$key];
			}
		}
		return $aTabla;
	}
}

?>