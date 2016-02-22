
var XmlHttpObj;
var id_sublista;
var id_status;

var Utf8 = {

    //Convierte de UTF-8 a ISO
    decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while ( i < utftext.length ) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}

function CreateXmlHttpObj()
{
	try
	{
		XmlHttpObj = new ActiveXObject("Msxml2.XMLHTTP");
	
	}
	catch(e)
	{
		try
		{
			XmlHttpObj = new ActiveXObject("Microsoft.XMLHTTP");
		} 
		catch(oc)
		{
			XmlHttpObj = null;
		}
	}
		if(!XmlHttpObj && typeof XMLHttpRequest != "undefined") 
	{
		XmlHttpObj = new XMLHttpRequest();
	}
}

/* Carga combo dependiente de otro */

function listOnChange(lista1, lista2, sublista, proveedor, idstatus, idsubLista) {

	id_status = idstatus;

	var to=document.getElementById(id_status);
	to.innerHTML="<img src='images/loading.gif' align='absmiddle'>";
	
 	var selectedIndex1 = document.getElementById(lista1).selectedIndex;
    var selectedId1 = document.getElementById(lista1).options[selectedIndex1].value;

 	var selectedIndex2 = "";
    var selectedId2 = "";

	if (lista2 != "") {
 		selectedIndex2 = document.getElementById(lista2).selectedIndex;

    	selectedId2 = document.getElementById(lista2).options[selectedIndex2].value;

	}
	
	var requestUrl;

     requestUrl = proveedor + "?id=" + encodeURIComponent(selectedId1) + "&id2=" + encodeURIComponent(selectedId2) + "&id_status=" + encodeURIComponent(id_status)+ "&id_sublista=" + encodeURIComponent(idsubLista);

	id_sublista = sublista;
	
	CreateXmlHttpObj();
	
	if(XmlHttpObj)
	{
	
		XmlHttpObj.onreadystatechange = StateChangeHandler;
		XmlHttpObj.open( "POST", requestUrl, true );
		XmlHttpObj.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		XmlHttpObj.send('');		
	}
}


function StateChangeHandler()
{
	if(XmlHttpObj.readyState == 4)
	{
		if(XmlHttpObj.status == 200)
		{			
			PopulatesubList(XmlHttpObj.responseText, id_sublista);
		}
		else
		{
			alert("Código de error: "  + XmlHttpObj.status);
		}
	}
}

function PopulatesubList(lista, sublista)
{	
    var subList = document.getElementById(sublista);

	subList.innerHTML = lista;
		
	var to=document.getElementById(id_status);
	to.innerHTML="";
}




