/**
 * ABC theme functions.
 */

/* global countUpTimers, jQuery */
'use strict';
(function($) {

	/**
	 * Show/hide transcript delivery method fields.
	 *
	 * @returns {void} Modifies DOM.
	 */
	function updateDeliveryMethod() {
		if ($('select[name="delivery-type"]').length > 0) {
			var deliveryMethod = $('select[name="delivery-type"]').val().toLowerCase().replace(' ', '-');

			$('.delivery').hide();
			$('.' + deliveryMethod).show();

			if ('physical-mail' === deliveryMethod) {
				$('.physical-mail input').attr('required', true);
				$('.physical-mail label').addClass('required');
			} else {
				$('.physical-mail input').attr('required', false);
				$('.physical-mail label').removeClass('required');
			}
		}
	}

	/**
	 * Handle debouncing.
	 *
	 * @param  {Function} func  Function to run.
	 * @param  {int} wait       Number of milliseconds to wait.
	 * @param  {bool} immediate Whether to run immediately or not.
	 *
	 * @return {Function}       Function to run.
	 */
	function debounce(func, wait, immediate) {
		var timeout;

		return function() {

			var context = this,
				args = arguments,
				later = function() {
					timeout = null;
					if (! immediate) func.apply(context, args);
				},
				callNow = immediate && ! timeout;

			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) {
				func.apply(context, args);
			}
		};
	}

	/**
	 * Detect whether element is completely in view.
	 *
	 * @param  {Object}  elem DOM element
	 *
	 * @returns {boolean}      Whether elem is completely in view or not.
	 */
	function isScrolledIntoView(elem) {
		if (null === elem) {
			return false;
		}

		var docViewTop = $(window).scrollTop(),
			docViewBottom = docViewTop + $(window).height(),
			elemTop = $(elem).offset().top,
			elemBottom = elemTop + $(elem).height();

		return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
	}

	$(document).ready(function() {

		// live streaming banner
		if ($('.wp-youtube-live').hasClass('live')) {
			$('.streaming.off-air').removeClass('off-air').addClass('on-air');
		}
		window.addEventListener('wpYouTubeLiveStarted', function() {
			if ($('.wp-youtube-live').hasClass('live')) {
				$('.streaming.off-air').removeClass('off-air').addClass('on-air');
			}
		});


		// live streaming popup
		$('.streaming-frame, .streaming-frame-shade').hide();
		$('#streaming-popup').on('click', function(e) {
			e.preventDefault();
			$('.streaming-frame, .streaming-frame-shade').toggle();
		});
		$('.streaming-frame .close, .streaming-frame-shade').on('click', function(e) {
			e.preventDefault();
			$('.streaming-frame, .streaming-frame-shade').hide();
		});

		// chosen
		$('select.sermon-search, select[name="archive-dropdown"]').chosen();

		// hide collapse-form and add toggle button
		var buttonText;

		if ($('.collapse-form').hasClass('payment')) {
			buttonText = 'Pay Now';
		} else {
			buttonText = 'Give Now';
		}
		$('.collapse-form').slideUp().before('<p><button type="button" class="toggle-form">' + buttonText + ' <span class="dashicons dashicons-arrow-down-alt2"></span></button></p>');

		// handle collapse-form toggle buttons
		$(document).on('click', 'button.toggle-form', function(e) {
			e.preventDefault();

			// expand form
			$(this).parent().next('.collapse-form').slideToggle();

			// handle button visual state
			$(this).find('span.dashicons').toggleClass('dashicons-arrow-up-alt2 dashicons-arrow-down-alt2');
		});

		// handle preset and custom donation/payment amounts
		$('.wpcf7-form input[name="amount-other"]').slideUp();
		$('.wpcf7-form select[name="preset-amount"]').on('change', function() {
			var presetAmount = $(this).val(),
				otherField = $(this).parents('p').find('input[name="amount-other"]');

			if ('Other' === presetAmount) {
				otherField.slideDown().focus();
			} else {
				otherField.slideUp();
			}
		});

		// handle transcript delivery method
		$('select[name="delivery-type"]').on('change', updateDeliveryMethod);
		updateDeliveryMethod();

		/**
		 * Start visible timers.
		 *
		 * @param  {function} anon The function.
		 * @param  {int}      100  How many ms to wait before running the function.
		 *
		 * @return {void}     Starts visible timers.
		 */
		var runTimers = debounce(function() {
			$.each(countUpTimers, function() {
				if (isScrolledIntoView(this.d)) {
					this.start(function() {
						if (this.d.dataset.value !== this.d.dataset.finish) {
							this.d.innerHTML = this.d.dataset.finish;
						}
					});
				}
			});
		}, 100);

		if ('undefined' !== typeof countUpTimers) {
			window.addEventListener('load', runTimers);
			window.addEventListener('resize', runTimers);
			window.addEventListener('scroll', runTimers);
		}
	});

}(jQuery));
