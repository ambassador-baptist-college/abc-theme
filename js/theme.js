(function($){
    $(document).ready(function(){
        // chosen
        $('select.preachers, select[name="archive-dropdown"]').chosen();

        // hide collapse-form and add toggle button
        $('.collapse-form').slideUp().before('<p><button type="button" class="toggle-form">Expand Form <span class="dashicons dashicons-arrow-down-alt2"></span></button></p>');

        // handle collapse-form toggle buttons
        $(document).on('click', 'button.toggle-form', function(e){
            e.preventDefault();

            // expand form
            $(this).parent().next('.collapse-form').slideToggle();

            // handle button visual state
            $(this).find('span.dashicons').toggleClass('dashicons-arrow-up-alt2 dashicons-arrow-down-alt2')
        });

        // handle preset and custom donation/payment amounts
        $('.wpcf7-form input[name="amount-other"]').slideUp();
        $('.wpcf7-form select[name="preset-amount"]').on('change', function(){
            var presetAmount = $(this).val(),
                otherField = $(this).parents('p').find('input[name="amount-other"]');

            if (presetAmount == 'Other') {
                otherField.slideDown().focus();
            } else {
                otherField.slideUp();
            }
        });
    });
})(jQuery);
