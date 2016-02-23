<?
// //////////////////////////////////////////////////
// Convierte fecha de mysql a normal
// //////////////////////////////////////////////////
function cambiaf_a_normal($fecha) {
	$lafecha = $fecha;
	if (strpos ( $fecha, "-" ) > 0) {
		$mifecha = explode ( "-", $fecha );
		$lafecha = trim ( $mifecha [2] ) . "/" . trim ( $mifecha [1] ) . "/" . trim ( $mifecha [0] );
	}
	return $lafecha;
}

// //////////////////////////////////////////////////
// Convierte fecha de normal a mysql
// //////////////////////////////////////////////////
function cambiaf_a_mysql($fecha) {
	$lafecha = $fecha;
	if (strpos ( $fecha, "/" ) > 0) {
		$mifecha = explode ( "/", $fecha );
		$lafecha = trim ( $mifecha [2] ) . "-" . trim ( $mifecha [1] ) . "-" . trim ( $mifecha [0] );
	}
	return $lafecha;
}

// Una función que compara dos fechas devolviendo un valor positivo, negativo o nulo si la primera fecha es respectivamente mayor, menor o igual que la segunda.
// Para complicar las cosas un poco, la función usa expresiones regulares para que admita fechas tanto en formato "dd-mm-aaaa" como con formato "dd/mm/aaaa", dotando a la función de algo más de inteligencia.
function compara_fechas($fecha1, $fecha2) {
	if (preg_match ( "/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/", $fecha1 ))
		list ( $dia1, $mes1, $año1 ) = split ( "/", $fecha1 );
	if (preg_match ( "/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/", $fecha1 ))
		list ( $dia1, $mes1, $año1 ) = split ( "-", $fecha1 );
	if (preg_match ( "/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/", $fecha2 ))
		list ( $dia2, $mes2, $año2 ) = split ( "/", $fecha2 );
	if (preg_match ( "/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/", $fecha2 ))
		list ( $dia2, $mes2, $año2 ) = split ( "-", $fecha2 );
	$dif = mktime ( 0, 0, 0, $mes1, $dia1, $año1 ) - mktime ( 0, 0, 0, $mes2, $dia2, $año2 );
	return ($dif);
}

?>