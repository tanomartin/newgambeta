window.addEvent('domready', function()
{
  if (window.location.search.indexOf('iso=') != -1 && window.location.hash == '') {
    window.location += '#translations';
  }
  if (Browser.Engine.trident) {
    return;
  }
  $('mainmenu').getElements('ul.level_2').each(function(el) {
    el.set({'opacity':0});
  });
  $('mainmenu').getElements('span.submenu').each(function(el) {
    var ul = el.getParent().getElement('ul.level_2');
    el.addEvent('mouseenter', function() {
      new Fx.Morph(ul, {'duration':300}).start({'opacity':1});
    });
    el.addEvent('mouseout', function() {
      setTimeout(function() {
        if (ul.getStyle('left').toInt() < 0) {
          ul.set({'opacity':0});
        }
      }, 50);
    });
  });
});
window.addEvent('load', function()
{
  var el = $$('p.confirm');
  if ($defined(el[0])) {
    window.scrollTo(0, el[0].getPosition().y);
  }
  if ($defined($('username'))) {
    $('username').focus();
  }
});
