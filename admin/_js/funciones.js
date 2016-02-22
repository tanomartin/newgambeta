function validarFecha(dia,mes,anio)
{
var elMes = parseInt(mes);
var eldia = parseInt(dia);
var elanio = parseInt(anio);

if (isNaN(elMes) || isNaN(eldia) || isNaN(elanio))
	return 1;

if(elMes>12)
return 1;
// MES FEBRERO
if(elMes == 2){
if(esBisiesto(anio)){
if(parseInt(dia) > 29){
return 1;
}
else
return 0;
}
else{
if(parseInt(dia) > 28){
return 1;
}
else
return 0;
}
}
//RESTO DE MESES

if(elMes== 4 || elMes==6 || elMes==9 || elMes==11){
if(parseInt(dia) > 30){
return 1;
}
}

return 0;

}
//*****************************************************************************************
// esBisiesto(anio)
//
// Determina si el año pasado com parámetro es o no bisiesto
//*****************************************************************************************
function esBisiesto(anio)
{
var BISIESTO;
if(parseInt(anio)%4==0){
if(parseInt(anio)%100==0){
if(parseInt(anio)%400==0){
BISIESTO=true;
}
else{
BISIESTO=false;
}
}
else{
BISIESTO=true;
}
}
else
BISIESTO=false;

return BISIESTO;
} 

	function trim(str, chars) {
 	   return ltrim(rtrim(str, chars), chars);
	}

	function ltrim(str, chars) {
		chars = chars || "\\s";
		return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
	}
	
	function rtrim(str, chars) {
		chars = chars || "\\s";
		return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
	}
