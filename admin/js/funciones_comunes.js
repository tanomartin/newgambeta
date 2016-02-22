function limpiar(idForm) {
	var form = document.getElementById(idForm);
	for (var i = 0; i < form.elements.length; i++) {
		switch (form.elements[i].type) {
		case "text":
			form.elements[i].value = "";
			break;
		case "checkbox":
			form.elements[i].checked = false;
			break;

		case "select-one":
			form.elements[i].selectedIndex = -1;
			break;
		}
	}
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

function procesando(target) {
	document.getElementById(target).innerHTML = "<img style='margin-left:20px' src='images/spinner.gif' alt='procesando' title='procesando' /> Procesando...";
}

function CreateXmlHttpObj() {
	try {
		XmlHttpObj = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			XmlHttpObj = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (oc) {
			XmlHttpObj = null;
		}
	}
	if (!XmlHttpObj && typeof XMLHttpRequest != "undefined") {
		XmlHttpObj = new XMLHttpRequest();
	}
}
/* Carga combo dependiente de otro */

function listOnChange(lista1, lista2, sublista, proveedor, idstatus, idsubLista) {
	id_status = idstatus;
	var to = document.getElementById(id_status);
	to.innerHTML = "<img src='images/loading.gif' align='absmiddle'>";
	var selectedIndex1 = document.getElementById(lista1).selectedIndex;
	var selectedId1 = document.getElementById(lista1).options[selectedIndex1].value;
	var selectedIndex2 = "";
	var selectedId2 = "";
	if (lista2 != "") {
		selectedIndex2 = document.getElementById(lista2).selectedIndex;
		selectedId2 = document.getElementById(lista2).options[selectedIndex2].value;
	}
	var requestUrl;
	requestUrl = proveedor + "?id=" + encodeURIComponent(selectedId1) + "&id2="
			+ encodeURIComponent(selectedId2) + "&id_status="
			+ encodeURIComponent(id_status) + "&id_sublista="
			+ encodeURIComponent(idsubLista);
	id_sublista = sublista;
	CreateXmlHttpObj();
	if (XmlHttpObj) {
		XmlHttpObj.onreadystatechange = StateChangeHandler;
		XmlHttpObj.open("POST", requestUrl, true);
		XmlHttpObj.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		XmlHttpObj.send('');
	}
}

function StateChangeHandler() {
	if (XmlHttpObj.readyState == 4) {
		if (XmlHttpObj.status == 200) {
			PopulatesubList(XmlHttpObj.responseText, id_sublista);
		} else {
			alert("Código de error: " + XmlHttpObj.status);
		}
	}
}

function PopulatesubList(lista, sublista) {
	var subList = document.getElementById(sublista);
	subList.innerHTML = lista;
	var to = document.getElementById(id_status);
	to.innerHTML = "";
}

function clearCategoria(id) {
	document.getElementById(id).options.length = 0;
	variable = new Option("Seleccione antes un Torneo...", "0");
	document.getElementById(id).options[0] = variable;
	document.getElementById(id).selectedIndex = 0;
}

function clearEquipo(id) {
	clearFecha(id);
}

function clearFecha(id) {
	document.getElementById(id).options.length = 0;
	variable = new Option("Seleccione antes una Categoria...", "0");
	document.getElementById(id).options[0] = variable;
	document.getElementById(id).selectedIndex = 0;
}

function clearEquipo1(id) {
	document.getElementById(id).options.length = 0;
	variable = new Option("Seleccione antes una Fecha...", "0");
	document.getElementById(id).options[0] = variable;
	document.getElementById(id).selectedIndex = 0;
}

function clearEquipo2(id) {
	document.getElementById(id).options.length = 0;
	variable = new Option("Seleccione antes un Equipo #1...", "0");
	document.getElementById(id).options[0] = variable;
	document.getElementById(id).selectedIndex = 0;
}

function clearJugadoras(id) {
	document.getElementById(id).options.length = 0;
	variable = new Option("Seleccione antes un Equipo...", "0");
	document.getElementById(id).options[0] = variable;
	document.getElementById(id).selectedIndex = 0;
}
