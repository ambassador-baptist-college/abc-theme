(function($){
    $(document).ready(function() {
        /**
         * Handle missed services fields
         */
        $('input[name="services[]"]').on('change', function(){
            var totalServiceCount = $('input[name="services[]"]').length,
            attendedServiceCount = $('input[name="services[]"]:checked').length;

            if (totalServiceCount === attendedServiceCount) {
                $('.missed-services').hide();
            } else {
                $('.missed-services').show();
            }
        });
    });
})(jQuery);
