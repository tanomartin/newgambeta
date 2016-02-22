<?   

	function left($cadena, $cant) {
		
		return substr($cadena, 0, $cant);
			
	}

	function right($cadena, $cant) {
		
		return substr($cadena,  -$cant);
			
	}

	function decodeUTF8($array) {
		
			if (count($array) != 0) {	
             foreach ($array as $k => $postTmp) {
                      if (is_array($postTmp)) {
                              $array[$k]= decodeUTF8($postTmp);
                      }else{
                              $array[$k] = utf8_decode($postTmp);
                      }
              }
			 } 
		 
		return $array;
      }
  
  
        function encodeUTF8($array) {

		if (count($array) != 0) {	
             foreach ($array as $k => $postTmp) {
                      if (is_array($postTmp)) {
                              $array[$k]= encodeUTF8($postTmp);
                      }else{
                              $array[$k] = utf8_encode($postTmp);
                      }
              }
		}
	 
              return $array;
      }

/* Convierte fecha de mysql a normal*/
/*
function cambiaf_a_normal($fecha){
    ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
    $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1];
    return $lafecha;
}

*/
/*Convierte fecha de normal a mysql */

/*
function cambiaf_a_mysql($fecha){
    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
    return $lafecha;
}
*/
function do_upload($upload_dir, $upload_url,$temp_name,$file_name) {

  $file_name = str_replace("\\","",$file_name);
  $file_name = str_replace("'","",$file_name);
	$file_path = $upload_dir.$file_name;

	//File Name Check
  if ( $file_name =="") { 
	$message = "Nombre de archivo inválido";
	return $message;
  }

  $result  =  move_uploaded_file($temp_name, $file_path);
  if (!chmod($file_path,0777))
	$message = "change permission to 777 failed.";
  else
	$message = ($result)?"$file_name uploaded successfully." :
			  "Somthing is wrong with uploading a file.";
  return $message;
}

function NotifyExpire($ref,$key)
{
	
}

function filtros($var) {
    return (substr($var,0,1) == "f");
}

function elimina_acentos($cadena){
		$tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
		$replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
		$cadena = strtr($cadena,$tofind,$replac);
		return $cadena;
	}