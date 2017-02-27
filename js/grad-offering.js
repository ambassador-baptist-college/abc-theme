(function($){
    $(document).ready(function(){
        // GradOffering
        $('.amount-raised').text('0');
        setTimeout(function() {
            var total = gradOfferingGoal,
                strokeOffset = 835,
                duration = 2000,
                current = gradOfferingProgress,
                $circle = $('#GO-progress #meter'),
                $amountText = $('.amount-raised');

            if (isNaN(current)) {
                current = total;
            } else {
                var r = $circle.attr('r'),
                    c = Math.PI * (r * 2),
                    val = current / total;

                if (val < 0) {
                    val = 0;
                }
                if (val > 1) {
                    val = 1;
                }
                var rotation = (1 - val) * c;

                $circle.css({
                    strokeDashoffset: rotation
                });
                $({
                    countNum: 0
                }).animate({
                    countNum: current
                }, {
                    duration: duration,
                    step: function () {
                        $amountText.text(Math.floor(this.countNum).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
                    },
                    complete: function () {
                        $amountText.text(current.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
                    }
                });
            }
        }, 1500);
    });
})(jQuery);
