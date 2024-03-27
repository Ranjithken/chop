(function ($) {
  $(document).ready(function() {
    $(".form-submit").on('mouseover focus', function() {
			if ($("a.cke_button__source").hasClass("cke_button_on")) {
	      $(".cke_button.cke_button_on").click();
			}
    });
  });
})(jQuery);