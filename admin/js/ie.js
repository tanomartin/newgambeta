window.addEvent('domready', function() {
  if ($(document.html).get('lang') == 'de') {
    var text = '<strong>[!] Achtung!</strong> Sie verwenden einen veralteten Browser und <strong>können nicht alle Funktionen dieser Webseite nutzen</strong> (<a href="browser-aktualisierung.html">weitere Informationen</a>).';
  } else {
    var text = '<strong>[!] Attention!</strong> Your web browser is out of date and <strong>you cannot use all features of this website</strong> (<a href="browser-update.html">more information</a>).';
  }
  var div = new Element('div', {
    'id': 'browser-update',
    'html': text,
    'styles': {
      'background': '#ffc',
      'border-bottom': '1px solid #e4790f',
      'padding': '6px',
      'font-size': '11px'
    }
  });
  div.inject(document.body, 'top');
});