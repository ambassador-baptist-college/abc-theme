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
            bestHeight = _bestFrameSize(videoRes, height, width).toString(),
            videoContainerClass = 'home-video-background',
            $videoContainer = container.children('.'+videoContainerClass),
            videoSrc = '<div class="'+videoContainerClass+'" data-current-size="'+videoRes[bestHeight].height.toString()+'"><video autoplay="true" loop="true" src="'+videoRes[bestHeight].url+'" style="dispay: none;"></video></div>';

        console.info('computed size: '+height.toString()+'×'+width.toString()+'; source with nearest height: '+bestHeight.toString()+'; URL: '+videoRes[bestHeight].url.toString());

        if ($videoContainer.length === 0) {
            container.prepend(videoSrc)
            $videoContainer.fadeIn('slow');
        } else if ($videoContainer.length > 0 && $videoContainer.data('current-size').toString() !== videoRes[bestHeight].height) {
            $videoContainer.replaceWith(videoSrc);
        }
    }

    /**
     * Get best framesize given height and width
     * @private
     * @param   {object}  object object of available framesizes
     * @param   {integer} height container height
     * @param   {integer} width  container width
     * @returns {integer} key of best framesize
     */
    function _bestFrameSize(object, height, width){
        // get the highest number in case it matches nothing
        var closest = Object.keys(object).reduce(function(a, b){ return object[a] > object[b] ? a : b }),
            maxWidth = 1600;

        // return largest framesize if super-wide
        if (width >= maxWidth) {
            return closest;
        }

        // loop over all framesizes
        for (var i in object) {
            if (object.hasOwnProperty(i)) {
                if (Number(i) >= Number(height) && Number(i) < Number(closest)) {
                    closest = i;
                }
            }
        }
        return closest;
    }
})(jQuery);
