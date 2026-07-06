!function ($) {
  $.fn.maxlength = function() {
    $(this).each(function() {
      var max = $(this).attr('maxlength');

      if (max <= 0 || max === undefined) {
        throw new Error('maxlength attribute must be defined and greater than 0');
      }

      if (!$(this).parent().hasClass('nfor_maxlength_wrap')) {
        $(this).wrap("<label class=\"nfor_maxlength_wrap\"></label>");
      }
      $(this).after("<span class=\"nfor_maxlength_addon maxlength\"></span>");

      $(this).bind('input', function(e) {
        var max = $(this).attr('maxlength');
        var val = $(this).val();
        var cur = 0;

        if (val) {
          cur = val.length;
        }

        var left = cur;

        $(this).next(".maxlength").text(left.toString()+"/"+max);

        return this;
      }).trigger('input');
    });
    return this;
  };
}(window.jQuery);
