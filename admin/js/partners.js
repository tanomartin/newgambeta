window.addEvent('domready', function() {
  if ($defined($('f4'))) {
    var update = function() {
      if ($('opt_30_0').get('checked')) {
        $('partner-design').setStyle('display', 'block');
      } else {
        $('partner-design').setStyle('display', 'none');
      }
      if ($('opt_30_1').get('checked')) {
        $('partner-training').setStyle('display', 'block');
      } else {
        $('partner-training').setStyle('display', 'none');
      }
      if ($('opt_30_2').get('checked')) {
        $('partner-programming').setStyle('display', 'block');
      } else {
        $('partner-programming').setStyle('display', 'none');
      }
      if ($('opt_30_3').get('checked')) {
        $('partner-hosting').setStyle('display', 'block');
      } else {
        $('partner-hosting').setStyle('display', 'none');
      }
    };
    window.addEvent('load', update);
    $('opt_30_0').addEvent('change', update);
    $('opt_30_1').addEvent('change', update);
    $('opt_30_2').addEvent('change', update);
    $('opt_30_3').addEvent('change', update);
  }
  if ($defined($('f5'))) {
    var update = function() {
      if ($('opt_46_0').get('checked')) {
        $('partner-design').setStyle('display', 'block');
      } else {
        $('partner-design').setStyle('display', 'none');
      }
      if ($('opt_46_1').get('checked')) {
        $('partner-training').setStyle('display', 'block');
      } else {
        $('partner-training').setStyle('display', 'none');
      }
      if ($('opt_46_2').get('checked')) {
        $('partner-programming').setStyle('display', 'block');
      } else {
        $('partner-programming').setStyle('display', 'none');
      }
      if ($('opt_46_3').get('checked')) {
        $('partner-hosting').setStyle('display', 'block');
      } else {
        $('partner-hosting').setStyle('display', 'none');
      }
    };
    window.addEvent('load', update);
    $('opt_46_0').addEvent('change', update);
    $('opt_46_1').addEvent('change', update);
    $('opt_46_2').addEvent('change', update);
    $('opt_46_3').addEvent('change', update);
  }
});