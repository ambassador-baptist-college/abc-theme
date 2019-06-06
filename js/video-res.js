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
     *
     * @see {@link https://github.com/ambassador-baptist-college/abc-theme#homepage-video more information about framesize and quality}}
     *
     * @param {object} container jQuery object to udpate with best source URL
     */
    function getBestVideo(container) {
        var width = container.width(),
            height = container.height(),
            bestHeight = _bestFrameSize(videoRes, height, width).toString(),
            motionQuery = matchMedia('(prefers-reduced-motion)'),
            videoUrl = (motionQuery.matches ? videoRes[bestHeight].url.replace('video', 'video/low-motion') : videoRes[bestHeight].url),
            videoContainerClass = 'home-video-background',
            $videoContainer = container.children('.'+videoContainerClass),
			videoSrc = '<div class="'+videoContainerClass+'" data-current-size="'+videoUrl.toString()+'"><video id="home-video-background" muted autoplay="true" loop="true" src="'+videoUrl+'" style="display: none;"></video></div>',
			promise;

        console.info('computed size: '+height.toString()+'×'+width.toString()+'; source with nearest height: '+bestHeight.toString()+'; URL: '+videoUrl.toString());

        if ($videoContainer.length === 0) {
            container.prepend(videoSrc)
            $('#'+videoContainerClass).fadeIn();
			setMaxLoops($('#home-video-background'));
			promise = $('#home-video-background').get(0).play();

			// Fade out if video didn’t play.
			if ('undefined' === typeof promise) {
				promise.catch(function() {
					$('#'+videoContainerClass).fadeOut();
				});
			}
        } else if ($videoContainer.length > 0 && $videoContainer.data('current-size').toString() !== videoRes[bestHeight].url) {
            $('#'+videoContainerClass).fadeOut();
            $videoContainer.replaceWith(videoSrc);
            $('#'+videoContainerClass).fadeIn();
            setMaxLoops($('#home-video-background'));
        }
    }

    /**
     * Get best framesize given height and width
     * @private
     *
     * @see {@link https://github.com/ambassador-baptist-college/abc-theme#homepage-video more information about framesize and quality}}
     *
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

    /**
     * Set max number of times to loop video
     * @param {object} videoElement jQuery <video> object
     */
    function setMaxLoops(videoElement) {
        var loopIterations = 0,
            totalLoopIterations = 5;

        videoElement.on('timeupdate', function() {
            // count how many times video has looped
            if (videoElement.get(0).currentTime == 0) {
                loopIterations++;
            }

            // pause video when it reaches the total count
            if (loopIterations == totalLoopIterations) {
                videoElement.get(0).pause();
                videoElement.fadeOut();
            }
        });
    }
})(jQuery);
