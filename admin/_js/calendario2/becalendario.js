//<!-- #include "java.lang.Integer" -->

calDateFormat    = "DD/MM/YYYY";
topBackground    = "white";
bottomBackground = "white";
tableBGColor     = "#f9f9ee";
cellColor        = "#f9f9ee";
headingCellColor = "#f9f9ee";
headingTextColor = "003366";
dateColor        = "black";
focusColor       = "#ff0000";
hoverColor       = "darkred";
headingFontStyle = "bold 8pt verdana, arial";
bottomBorder  = false;
tableBorder   = 0;
var isNav = false;
var isIE  = false;
var calDateField2 = '';

if (navigator.appName == "Netscape") {
	isNav = true;
	fontStyle        = "8pt verdana, arial";
} else {
	isIE = true;
	fontStyle        = "7pt verdana, arial";
}
selectedLanguage = navigator.language;
buildCalParts();

function setDateField(dateField) {
	calDateField = dateField;
		  
	//DATEFORMAT
	var toto = "";
	var totos;
	toto=dateField.value;
	totos=toto.split("/");
	inDate = 'dd/mm/yyyy';
	anio = getYear4Digitos(totos[2]);
	if (anio != -1){
		//inDate=totos[0] + '/' + totos[1] + '/' + anio;
		inDate=anio + '/' + totos[1] + '/' + totos[0];
	}
	//FIN DATEFORMAT
	setInitialDate();
	calDocTop    = buildTopCalFrame();
	calDocBottom = buildBottomCalFrame();
}

function setDateField2(dateField2) {
	calDateField2 = dateField2;    
}

function setInitialDate() {
	calDate = new Date(inDate);
	if (isNaN(calDate)) {
	  calDate = new Date();
	}
	calDay  = calDate.getDate();
	calMonth = calDate.getMonth();
	calDate.setDate(1);
}

function showCalendar(dateField) {
	setDateField(dateField);
	calDocFrameset = 
	  "<HTML><HEAD><TITLE>Calendario ...</TITLE></HEAD>\n" +
	  "<FRAMESET ROWS='30,*' FRAMEBORDER='no' border=0 framespacing=0>\n" +
	  "  <FRAME NAME='topCalFrame' SRC='javascript:parent.opener.calDocTop' SCROLLING='no' frameborder='no' marginwidth=0 marginheight=0>\n" +
	  "  <FRAME NAME='bottomCalFrame' SRC='javascript:parent.opener.calDocBottom' SCROLLING='no' frameborder='no' marginwidth=0 marginheight=0>\n" +
	  "</FRAMESET>\n";
	top.newWin = window.open("javascript:parent.opener.calDocFrameset", "calWin", winPrefs);
	top.newWin.focus();
}

function buildTopCalFrame() {
	var calDoc =
	  "<HTML>" +
	  "<HEAD>" +
	  "</HEAD>" +
	  "<BODY BGCOLOR='" + topBackground + "'leftmargin=0 topmargin=2 marginwidth=2 marginheight=2>" +
	  "<FORM NAME='calControl' onSubmit='return false;'>" +
	  "<CENTER>" +
	  "<table border=0 cellspacing=0 cellpadding=1 align=center width=190 bgcolor=eee8aa>" +
		"<tr><td>" +
	  "<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 align=center width=100% bgcolor=#f9f9ee>" +
	  "<TR><TD align=center>";        

	// NAVIGATOR NEEDS BUTTONS TO WORK PROPERLY
	calDoc +=         
		"<a href='javascript:;' onClick='parent.opener.setPreviousMonth()' NAME='previousMonth'>" +
		"<img src='cal_flecha_izq.gif' border=0 alt='Mes anterior'></a>&nbsp;";

	calDoc += 
	getMonthSelect() +
	"<INPUT NAME='year' VALUE='" + calDate.getFullYear() + "'TYPE=TEXT SIZE=4 MAXLENGTH=4 " +
	"onFocus=\"top.bottomCalFrame.layerBottom.style.visibility='visible';\" " +
	"onChange='parent.opener.setYear();top.bottomCalFrame.layerBottom.style.visibility=\"hidden\";' " +
//No funciona !!!	"onBlur='top.bottomCalFrame.layerBottom.style.visibility=\"hidden\";'" +
	">";

	calDoc +=		
		"&nbsp;<a href='javascript:;' NAME='nextMonth' onClick='parent.opener.setNextMonth()'>" +
		"<img src='cal_flecha_der.gif' border=0 alt='Mes siguiente'></a>";
	calDoc += 
	  "</TD>" +
	  "</TR>" +
	  "</TABLE>" +
	  "</TD>" +
	  "</TR>" +
	  "</TABLE>" +
	  "</CENTER>" +
	  "</FORM>" +
	  "</BODY>" +
	  "</HTML>";	

	return calDoc;
}

function buildBottomCalFrame() {       
	var calDoc = calendarBegin;
	month   = calDate.getMonth();
	year    = getYear4Digitos(calDate.getYear());
	day     = calDay;
	var i   = 0;
	var days = getDaysInMonth();
	if (day > days) {
	  day = days;
	}
	var firstOfMonth = new Date (year, month, 1);
	var startingPos  = firstOfMonth.getDay();
	days += startingPos;
	var columnCount = 0;
	for (i = 0; i < startingPos; i++) {
	  calDoc += blankCell;
		columnCount++;
	}
	var currentDay = 0;
	var currentMonth = 0;
	var dayType    = "weekday";
	for (i = startingPos; i < days; i++) {
		var paddingChar = "&nbsp;";
	  if (i-startingPos+1 < 10) {
	    padding = "&nbsp;&nbsp;";
	  } else {
	    padding = "&nbsp;";
	  }
	  currentDay = i-startingPos+1; 
	  if ((currentDay == day) && (month == calMonth)) {
	    dayType = "focusDay";
	  } else {
	    dayType = "weekDay";
	  }

	  calDoc += "<TD class='heading2' align=center>" +
	            "<a class='" + dayType + "' href='javascript:parent.opener.returnDate(" + 
	            currentDay + ")'>" + padding + currentDay + paddingChar + "</a></TD>";
	  columnCount++;
	  if (columnCount % 7 == 0) {
	    calDoc += "</TR><TR>";
	  }
	}
	for (i=days; i<42; i++)  {
	  calDoc += blankCell;
		columnCount++;
	  if (columnCount % 7 == 0) {
	    calDoc += "</TR>";
	    if (i<41) {
	      calDoc += "<TR>";
	    }
	  }
	}
	calDoc += calendarEnd;

	//Layer transparente para inhabilitar los links y el boton "cerrar" cuando se modifica el año.
	calDoc += '<div onclick="parent.opener.writeCalendar();top.topCalFrame.focus();" id="layerBottom" style="HEIGHT: 130px; LEFT: 0px; POSITION: absolute; TOP: 0px; VISIBILITY: hidden; WIDTH: 200px; Z-INDEX: 1">' +
						'<table width="100" border="0" height="200" cellspacing="0">' +
						'<tr><td valign="top">&nbsp;</td></tr>' +
						'</table>' +
						'</div>';

	return calDoc;
}

function writeCalendar() {
	calDocBottom = buildBottomCalFrame();
	top.newWin.frames['bottomCalFrame'].document.open();
	top.newWin.frames['bottomCalFrame'].document.write(calDocBottom);
	top.newWin.frames['bottomCalFrame'].document.close();
}

function setToday() {
	calDate = new Date();
	var month = calDate.getMonth();
	var year  = getYear4Digitos(calDate.getYear());
	top.newWin.frames['topCalFrame'].document.calControl.month.selectedIndex = month;
	top.newWin.frames['topCalFrame'].document.calControl.year.value = year;
	writeCalendar();
}

function setYear() {
	var year  = top.newWin.frames['topCalFrame'].document.calControl.year.value;
	var year  = getYear4Digitos(year);

//Año	if (isFourDigitYear(year)) {
	if(year != -1){
		calDate.setFullYear(year);
		top.newWin.frames['topCalFrame'].document.calControl.year.value = year;
	  writeCalendar();
	}	else {
	  top.newWin.frames['topCalFrame'].document.calControl.year.focus();
	  top.newWin.frames['topCalFrame'].document.calControl.year.select();
	}
}

function setCurrentMonth() {
	var month = top.newWin.frames['topCalFrame'].document.calControl.month.selectedIndex;
	calDate.setMonth(month);
	writeCalendar();
}

function setCurrentYear() {
	var year = top.newWin.frames['topCalFrame'].document.calControl.year.id;
	calDate.setFullYear(year);
	writeCalendar();
}

function setPreviousYear() {
	var year  = top.newWin.frames['topCalFrame'].document.calControl.year.value;
		
	if (isFourDigitYear(year) && year > 1000) {
	  year--;
	  calDate.setFullYear(year);
	  top.newWin.frames['topCalFrame'].document.calControl.year.value = year;
	  writeCalendar();
	}
}

function setPreviousMonth() {
	var year  = top.newWin.frames['topCalFrame'].document.calControl.year.value;
	if (isFourDigitYear(year)) {
	  var month = top.newWin.frames['topCalFrame'].document.calControl.month.selectedIndex;
	  if (month == 0) {
	    month = 11;
	    if (year > 1000) {
	      year--;
	      calDate.setFullYear(year);
	      top.newWin.frames['topCalFrame'].document.calControl.year.value = year;
	    }
	  } else {
	    month--;
	  }
	  calDate.setMonth(month);
	  top.newWin.frames['topCalFrame'].document.calControl.month.selectedIndex = month;
	  writeCalendar();
	}
}

function setNextMonth() {
	var year = top.newWin.frames['topCalFrame'].document.calControl.year.value;
		
	if (isFourDigitYear(year)) {
	  var month = top.newWin.frames['topCalFrame'].document.calControl.month.selectedIndex;
	  if (month == 11) {
	    month = 0;
	    year++;
	    calDate.setFullYear(year);
	    top.newWin.frames['topCalFrame'].document.calControl.year.value = year;
	  } else {
	    month++;
	  }
	  calDate.setMonth(month);
	  top.newWin.frames['topCalFrame'].document.calControl.month.selectedIndex = month;
	  writeCalendar();
	}
}

function setNextYear() {
	var year  = top.newWin.frames['topCalFrame'].document.calControl.year.value;
		
	if (isFourDigitYear(year)) {
	  year++;
	  calDate.setFullYear(year);
	  top.newWin.frames['topCalFrame'].document.calControl.year.value = year;
	  writeCalendar();
	}
}

function getDaysInMonth()  {
	var days;
	var month = calDate.getMonth()+1;
	var year  = getYear4Digitos(calDate.getYear());
	if (month==1 || month==3 || month==5 || month==7 || month==8 || month==10 || month==12)  {
	  days=31;
	} else if (month==4 || month==6 || month==9 || month==11) {
	  days=30;
	} else if (month==2)  {
	  if (isLeapYear(year)) {
	    days=29;
	  } else {
	    days=28;
	  }
	}

	return (days);
}

function isLeapYear (Year) {
	if (((Year % 4)==0) && ((Year % 100)!=0) || ((Year % 400)==0)) {
	  return (true);
	} else {
	  return (false);
	}
}

function isFourDigitYear(year) {
	if (year.length != 4) {
	  top.newWin.frames['topCalFrame'].document.calControl.year.value = getYear4Digitos(calDate.getYear());
	  top.newWin.frames['topCalFrame'].document.calControl.year.select();
	  top.newWin.frames['topCalFrame'].document.calControl.year.focus();
	} else {
	  return true;
	}
}

function getMonthSelect() {
	monthArray = new Array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
	                       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	var activeMonth = calDate.getMonth();
	monthSelect = "<SELECT NAME='month' onChange='parent.opener.setCurrentMonth()'>";
	for (i in monthArray) {
	  if (i == activeMonth) {
	    monthSelect += "<OPTION SELECTED>" + monthArray[i];
	  } else {
	    monthSelect += "<OPTION>" + monthArray[i];
	  }
	}
	monthSelect += "</SELECT>";

	return monthSelect;
}

function getYearSelect() {
	var activeYear = calDate.getYear();
	yearSelect = "<SELECT NAME='year' onChange='parent.opener.setCurrentYear()'>";

	for (var i=1900; i <= 3000; i++){
	  if (i == activeYear) {
	    yearSelect += "<OPTION SELECTED id=" + i + ">" + i;
	  } else {
	    yearSelect += "<OPTION id=" + i + ">" + i;
	  }
	}
	yearSelect += "</SELECT>";

	return yearSelect;
}

function createWeekdayList() {
	weekdayList  = new Array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
	weekdayArray = new Array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa');

	var weekdays = "<TR BGCOLOR='" + headingCellColor + "'>";
	for (i in weekdayArray) {
	  weekdays += "<TD class='heading' align=center>" + weekdayArray[i] + "</TD>";
	}
	weekdays += "</TR>";
	return weekdays;
}

function buildCalParts() {
	weekdays = createWeekdayList();
	blankCell = "<TD align=center>&nbsp;&nbsp;&nbsp;</TD>";
	calendarBegin =
	  "<HTML>" +
	  "<HEAD>" +
	  "<STYLE type='text/css'>" +
	  "<!--" +
	  "TD.heading2 { text-decoration: none; color:" + dateColor + "; font: " + fontStyle + "; }" +
	  "TD.heading { text-decoration: none; color:" + headingTextColor + "; font: " + headingFontStyle + "; }" +
	  "A.focusDay:link { color: " + focusColor + "; text-decoration: none; font: " + fontStyle + "; }" +
	  "A.focusDay:hover { color: " + focusColor + "; text-decoration: none; font: " + fontStyle + "; }" +
	  "A.focusDay:visited { color: " + focusColor + "; text-decoration: none; font: " + fontStyle + "; }" +
	  "A.weekday:link { color: " + dateColor + "; text-decoration: none; font: " + fontStyle + "; }" +
	  "A.weekday:hover { color: " + hoverColor + "; font: " + fontStyle + "; }" +
	  "A.weekday:visited { color: " + dateColor + "; text-decoration: none; font: " + fontStyle + "; }" +
	  "-->" +
	  "</STYLE>" +
	  "</HEAD>" +
	  "<BODY BGCOLOR='" + bottomBackground + "' leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>" +
	  "<CENTER>" +
	  "<table border=0 cellspacing=0 cellpadding=1 align=center width=190 bgcolor=eee8aa>" +
		"<tr><td>";        
	// NAVIGATOR NEEDS A TABLE CONTAINER TO DISPLAY THE TABLE OUTLINES PROPERLY
	if (isNav) {
	  calendarBegin += 
	    "<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=" + tableBorder + " ALIGN=CENTER width=100% BGCOLOR='" + tableBGColor + "'><TR><TD>";
	}
	calendarBegin +=
	  "<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=" + tableBorder + " ALIGN=CENTER width=100% BGCOLOR='" + tableBGColor + "'>" +
	  weekdays +
	  "<TR>";
	calendarEnd = "";
	if (bottomBorder) {
	  calendarEnd += "<TR></TR>";
	}
	// NAVIGATOR NEEDS A TABLE CONTAINER TO DISPLAY THE BORDERS PROPERLY
	if (isNav) {
	  calendarEnd += "</TD></TR></TABLE>";
	}
	calendarEnd +=
		"<table border=0 cellspacing=0 cellpadding=0 align=center width=100% bgcolor='" + tableBGColor + "'>" +
		"<TR><TD>" +
		"<img src='trans.gif' width=1 height=1>" +
		"</TD></TR>" +
		"<TR><TD ALIGN=CENTER>" +
		"<a href='javascript:window.top.close()'>" + 
		"<img src='cal_cerrar.gif' border=0></a>" +
		"</TD></TR>" +
		"<TR><TD>" +
		"<img src='dummie.gif' width=5 height=5>" +
		"</TD></TR>" +			
		"</TABLE>" +
		"</TD></TR>" +			
		"</TABLE>" +
		"</TD></TR>" +
		"</TABLE>" +			
	  "</CENTER>" +
	  "</BODY>" +
	  "</HTML>";
}

function jsReplace(inString, find, replace) {
	var outString = "";
	if (!inString) {
	  return "";
	}
	if (inString.indexOf(find) != -1) {
	  t = inString.split(find);
	  return (t.join(replace));
	} else {
	  return inString;
	}
}

function doNothing() {
}

function makeTwoDigit(inValue) {
	var numVal = parseInt(inValue, 10);
	if (numVal < 10) {
	  return("0" + numVal);
	} else {
	  return numVal;
	}
}

function returnDate(inDay){
	calDate.setDate(inDay);
	var day           = calDate.getDate();
	var month         = calDate.getMonth()+1;
	var year          = getYear4Digitos(calDate.getYear());
	var monthString   = monthArray[calDate.getMonth()];
	var monthAbbrev   = monthString.substring(0,3);
	var weekday       = weekdayList[calDate.getDay()];
	var weekdayAbbrev = weekday.substring(0,3);
	outDate = calDateFormat;
	if (calDateFormat.indexOf("DD") != -1) {
	  day = makeTwoDigit(day);
	  outDate = jsReplace(outDate, "DD", day);
	} else if (calDateFormat.indexOf("dd") != -1) {
	  outDate = jsReplace(outDate, "dd", day);
	}
	if (calDateFormat.indexOf("MM") != -1) {
	  month = makeTwoDigit(month);
	  outDate = jsReplace(outDate, "MM", month);
	} else if (calDateFormat.indexOf("mm") != -1) {
	  outDate = jsReplace(outDate, "mm", month);
	}
	if (calDateFormat.indexOf("YYYY") != -1) {
	  outDate = jsReplace(outDate, "YYYY", year);
	} else if (calDateFormat.indexOf("yyyy") != -1) {
	  outDate = jsReplace(outDate, "yyyy", year);
	} else if (calDateFormat.indexOf("yy") != -1) {
	  var yearString = "" + year;
	  var yearString = yearString.substring(2,4);
	  outDate = jsReplace(outDate, "yy", yearString);
	} else if (calDateFormat.indexOf("YY") != -1) {
	  outDate = jsReplace(outDate, "YY", year);
	}
	if (calDateFormat.indexOf("Month") != -1) {
	  outDate = jsReplace(outDate, "Month", monthString);
	} else if (calDateFormat.indexOf("month") != -1) {
	  outDate = jsReplace(outDate, "month", monthString.toLowerCase());
	} else if (calDateFormat.indexOf("MONTH") != -1) {
	  outDate = jsReplace(outDate, "MONTH", monthString.toUpperCase());
	}
	if (calDateFormat.indexOf("Mon") != -1) {
	  outDate = jsReplace(outDate, "Mon", monthAbbrev);
	} else if (calDateFormat.indexOf("mon") != -1) {
	  outDate = jsReplace(outDate, "mon", monthAbbrev.toLowerCase());
	} else if (calDateFormat.indexOf("MON") != -1) {
	  outDate = jsReplace(outDate, "MON", monthAbbrev.toUpperCase());
	}
	if (calDateFormat.indexOf("Weekday") != -1) {
	  outDate = jsReplace(outDate, "Weekday", weekday);
	} else if (calDateFormat.indexOf("weekday") != -1) {
	  outDate = jsReplace(outDate, "weekday", weekday.toLowerCase());
	} else if (calDateFormat.indexOf("WEEKDAY") != -1) {
	  outDate = jsReplace(outDate, "WEEKDAY", weekday.toUpperCase());
	}
	if (calDateFormat.indexOf("Wkdy") != -1) {
	  outDate = jsReplace(outDate, "Wkdy", weekdayAbbrev);
	} else if (calDateFormat.indexOf("wkdy") != -1) {
	  outDate = jsReplace(outDate, "wkdy", weekdayAbbrev.toLowerCase());
	} else if (calDateFormat.indexOf("WKDY") != -1) {
	  outDate = jsReplace(outDate, "WKDY", weekdayAbbrev.toUpperCase());
	}
	calDateField.value = outDate;
	if (calDateField2) {
	  calDateField2.value = outDate;
	}
	calDateField.focus();
	top.newWin.close();
}

//Funcion de llamada al Calendario.
//Parametros:
//           - inputFechaRetorno: String que indica el campo en donde debe retornarse la fecha elegida.
//           - posicionTop: Entero que indica el top de la ventana de calendario.
//           - posicionLeft: Entero que indica el left de la ventana de calendario.
function llamarCalendario(inputFechaRetorno, posicionTop, posicionLeft){
	link = "<a onclick=\"setDateField(" + inputFechaRetorno + ");" +
		   "top.newWin = window.open('/modulos/calendario.htm','cal','WIDTH=200,HEIGHT=160,TOP=" + posicionTop + 
		   ",LEFT=" + posicionLeft + ",resizable=no');" +
		   "top.newWin.focus();\" " +
		   "onMouseOver=\"javascript: window.status = 'Abrir calendario'; return true;\" " +
		   "onMouseOut=\"window.status=' '; return true;\">" +
		   "<img src='calendario.gif' width='26' height='22' border='0' alt='Abrir calendario'>" +
		   "</a>";
	document.write(link);
}

//Funcion de windowing para los años de 2 digitos (1930-2029).
//El parametro 'fecha' es un nro que representa un año.
function getYear4Digitos(anioFecha){

	anioFechaInt = parseInt(anioFecha);
	
	if (anioFechaInt >= 0 && anioFechaInt <= 99){
		//Año valido. Convierto a 4 digitos.
		return ((((anioFechaInt + 70) % 100) + 1930).toString());
	}else{
		if (anioFechaInt > 999 && anioFechaInt < 3000){
			//Año valido de 4 digitos. No convierto.
			return (anioFecha);
		}else{
			//Año invalido. Retorno -1 (valor invalido).
			return (-1);
		}
	}
}
