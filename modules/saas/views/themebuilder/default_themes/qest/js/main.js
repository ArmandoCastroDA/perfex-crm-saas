(function ($) {
	"use strict";
	// meanmenu
	$('#mobile-menu').meanmenu({
		meanMenuContainer: '.mobile-menu',
		meanScreenWidth: "1200",
		onePage: true
	});
	// scroll
	$(window).on('scroll', function () {
		var scroll = $(window).scrollTop();
		if (scroll < 50) {
			$("#header-sticky").removeClass("sticky");
		} else {
			$("#header-sticky").addClass("sticky");
		}
	});
	// mainSlider
	function mainSlider() {
		var BasicSlider = $('.slider-active');
		BasicSlider.on('init', function (e, slick) {
			var $firstAnimatingElements = $('.single-slider:first-child').find('[data-animation]');
			doAnimations($firstAnimatingElements);
		});
		BasicSlider.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
			var $animatingElements = $('.single-slider[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
			doAnimations($animatingElements);
		});
		BasicSlider.slick({
			autoplay: false,
			autoplaySpeed: 10000,
			dots: false,
			fade: true,
			arrows: false,
			responsive: [
				{ breakpoint: 767, settings: { dots: false, arrows: false } }
			]
		});
		function doAnimations(elements) {
			var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
			elements.each(function () {
				var $this = $(this);
				var $animationDelay = $this.data('delay');
				var $animationType = 'animated ' + $this.data('animation');
				$this.css({
					'animation-delay': $animationDelay,
					'-webkit-animation-delay': $animationDelay
				});
				$this.addClass($animationType).one(animationEndEvents, function () {
					$this.removeClass($animationType);
				});
			});
		}
	}
	mainSlider();
	// brand-active
	$('.brand-active').owlCarousel({
		loop:true,
		margin:50,
		items:5,
		nav:false,
		dots:false,
		autoplay:false,
		responsive:{
			0:{
				items:2,
				margin:30,
				nav: false,
			},
			530: {
				items: 3
			},
			767:{
				items:5
			},
			992:{
				items:4
			},
			1200:{
				items:5
			}
		}
	})
	// screenshot-active
	$('.screenshot-active').owlCarousel({
		loop:true,
		margin:30,
		items:3,
		center: true,
		navText: ["<i class='icofont-rounded-left'></i>","<i class='icofont-rounded-right'></i>"],
		nav:true,
		dots:false,
		autoplay:false,
		responsive:{
			0:{
				items:1,
				
			},
			767:{
				items:3,
				
			},
			992:{
				items:3
			},
			1200:{
				items:3
			}
		}
	})
	/* magnificPopup img view */
	$('.popup-image').magnificPopup({
		type: 'image',
		gallery: {
		  enabled: true
		}
	});
	/* magnificPopup video view */
	$('.popup-video').magnificPopup({
		type: 'iframe'
	});
	// data - background
	$("[data-background]").each(function () {
		$(this).css("background-image", "url(" + $(this).attr("data-background") + ")")
	})
	// scrollToTop
	$.scrollUp({
		scrollName: 'scrollUp', // Element ID
		topDistance: '300', // Distance from top before showing element (px)
		topSpeed: 300, // Speed back to top (ms)
		animation: 'fade', // Fade, slide, none
		animationInSpeed: 200, // Animation in speed (ms)
		animationOutSpeed: 200, // Animation out speed (ms)
		scrollText: '<i class="fas fa-chevron-up"></i>', // Text for element
		activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#000'
	});
	// WOW active
	new WOW().init();
	//counterUp Start
	$('.counter-value').counterUp({
		delay: 10,
		time: 1000
	});
	//counterUp End
	// map
	function basicmap() {
		// Basic options for a simple Google Map
		// For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
		var mapOptions = {
			// How zoomed in you want the map to start at (always required)
			zoom: 11,
			scrollwheel: false,
			// The latitude and longitude to center the map (always required)
			center: new google.maps.LatLng(32.941236, -97.134178), // New York
			// This is where you would paste any style found on Snazzy Maps.
			styles: [{ "featureType": "all", "elementType": "geometry.fill", "stylers": [{ "weight": "2.00" }] }, { "featureType": "all", "elementType": "geometry.stroke", "stylers": [{ "color": "#9c9c9c" }] }, { "featureType": "all", "elementType": "labels.text", "stylers": [{ "visibility": "on" }] }, { "featureType": "landscape", "elementType": "all", "stylers": [{ "color": "#f2f2f2" }] }, { "featureType": "landscape", "elementType": "geometry.fill", "stylers": [{ "color": "#ffffff" }] }, { "featureType": "landscape.man_made", "elementType": "geometry.fill", "stylers": [{ "color": "#ffffff" }] }, { "featureType": "poi", "elementType": "all", "stylers": [{ "visibility": "off" }] }, { "featureType": "road", "elementType": "all", "stylers": [{ "saturation": -100 }, { "lightness": 45 }] }, { "featureType": "road", "elementType": "geometry.fill", "stylers": [{ "color": "#eeeeee" }] }, { "featureType": "road", "elementType": "labels.text.fill", "stylers": [{ "color": "#7b7b7b" }] }, { "featureType": "road", "elementType": "labels.text.stroke", "stylers": [{ "color": "#ffffff" }] }, { "featureType": "road.highway", "elementType": "all", "stylers": [{ "visibility": "simplified" }] }, { "featureType": "road.arterial", "elementType": "labels.icon", "stylers": [{ "visibility": "off" }] }, { "featureType": "transit", "elementType": "all", "stylers": [{ "visibility": "off" }] }, { "featureType": "water", "elementType": "all", "stylers": [{ "color": "#46bcec" }, { "visibility": "on" }] }, { "featureType": "water", "elementType": "geometry.fill", "stylers": [{ "color": "#c8d7d4" }] }, { "featureType": "water", "elementType": "labels.text.fill", "stylers": [{ "color": "#070707" }] }, { "featureType": "water", "elementType": "labels.text.stroke", "stylers": [{ "color": "#ffffff" }] }]
		};
		// Get the HTML DOM element that will contain your map
		// We are using a div with id="map" seen below in the <body>
		var mapElement = document.getElementById('contact-map');

		// Create the Google Map using our element and options defined above
		var map = new google.maps.Map(mapElement, mapOptions);

		// Let's also add a marker while we're at it
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(32.941236, -97.134178),
			map: map,
			title: 'Cryptox'
		});
	}
	if ($('#contact-map').length != 0) {
		google.maps.event.addDomListener(window, 'load', basicmap);
	}

	})(jQuery);