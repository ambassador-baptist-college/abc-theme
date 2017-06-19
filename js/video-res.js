(function($){
    $(document).ready(function(){
        var resizeTimer,
            container = $('.site-header');

        // run on pageload
        getBestVideo(container);

        // run when window is resized
        $(window).on('resize', function(e) {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                getBestVideo(container);
            }, 250);
        });
    });

    /**
     * Get best video source based on computed height and width
     * @param {object} container jQuery object to udpate with best source URL
     */
    function getBestVideo(container) {
        var width = container.width(),
            height = container.height(),
            bestHeight = closest(videoRes, height),
            posterUrl = themeUrl+'/video/poster.jpg',
            videoContainerClass = 'home-video-background',
            $videoContainer = container.children('.'+videoContainerClass),
            videoSrc = '<video class="'+videoContainerClass+'" autoplay="true" loop="true" poster="'+posterUrl+'" src="'+videoRes[bestHeight].url+'" style="dispay: none;"></video>';

        console.log('computed height:');console.log(height);
        console.log('closest available source:');console.log(bestHeight);
        console.log('best URL:');console.log(videoRes[bestHeight]);

        if ($videoContainer.length == 0) {
            container.prepend(videoSrc)
            $videoContainer.fadeIn('slow');
        } else {
            $videoContainer.html(videoSrc);
        }
    }

    /**
     * Get object key closest to input
     * @param   {object}  object input object
     * @param   {integer} target number to check
     * @returns {integer} key of nearest object item
     */
    function closest(object, target){
        // Get the highest number in case it matches nothing
        var closest = Object.keys(object).reduce(function(a, b){ return object[a] > object[b] ? a : b });
        for (var i in object) {
            if (object.hasOwnProperty(i)) {
                if (i >= target && i < closest) {
                    closest = i;
                }
            }
        }
        return closest;
    }
})(jQuery);
