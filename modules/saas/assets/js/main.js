(function ($) {
    "use strict";
// meanmenu
    $('#mobile-menu').meanmenu({
        meanMenuContainer: '.zekio-mobile-menu',
        meanScreenWidth: "1200",
        onePage: true
    });

// Menu Nav
    function smoothSctollTop() {
        $('.main-menu ul li > a').on('click', function (event) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 1000);
            }
        });
    }

    smoothSctollTop();
    var top_offset = $('.header-area').height() - 50;
    $('.main-menu nav ul').onePageNav({
        currentClass: 'active',
        scrollOffset: top_offset,
    });

// menu active class
    $('.main-menu ul li:first-child > a').addClass('active');

//for menu active class
    $('.main-menu ul li > a').on('click', function () {
        $('.main-menu ul li > a').removeClass("active");
        $(this).addClass("active");
    });

// scroll
    $(window).on('scroll', function () {
        var scroll = $(window).scrollTop();
        if (scroll < 50) {
            $("#header-sticky").removeClass("zekio-sticky");
        } else {
            $("#header-sticky").addClass("zekio-sticky");
        }
    });


// owlCarousel
    $('.brand-active').owlCarousel({
        loop: true,
        margin: 160,
        items: 6,
        navText: ["<img src='img/arrow/02.png'>", "<img src='img/arrow/01.png'>"],
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 2,
                margin: 30,
                nav: false,
            },
            530: {
                items: 3
            },
            767: {
                items: 4
            },
            992: {
                items: 5
            },
            1200: {
                items: 6
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

// isotop
    $('.grid').imagesLoaded(function () {
        // init Isotope
        var $grid = $('.grid').isotope({
            itemSelector: '.grid-item',
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: 1
            }
        });

        // filter items on button click
        $('.button-group').on('click', 'button', function () {
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({filter: filterValue});
        });

    });

//for menu active class
    $('.button-group > button').on('click', function (event) {
        $(this).siblings('.active').removeClass('active');
        $(this).addClass('active');
        event.preventDefault();
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
        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
    });

// WOW active
    new WOW().init();

//counterUp Start
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });
//counterUp End
    // circle-progress
    if (typeof ($.fn.knob) != 'undefined') {
        $('.knob').each(function () {
            var $this = $(this),
                knobVal = $this.attr('data-rel');

            $this.knob({
                'draw': function () {
                    $(this.i).val(this.cv + '%')
                }
            });

            $this.appear(function () {
                $({
                    value: 0
                }).animate({
                    value: knobVal
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function () {
                        $this.val(Math.ceil(this.value)).trigger('change');
                    }
                });
            }, {accX: 0, accY: -150});
        });
    }


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
            styles: [{
                "featureType": "all",
                "elementType": "geometry.fill",
                "stylers": [{"weight": "2.00"}]
            }, {
                "featureType": "all",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#9c9c9c"}]
            }, {
                "featureType": "all",
                "elementType": "labels.text",
                "stylers": [{"visibility": "on"}]
            }, {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [{"color": "#f2f2f2"}]
            }, {
                "featureType": "landscape",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#ffffff"}]
            }, {
                "featureType": "landscape.man_made",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#ffffff"}]
            }, {"featureType": "poi", "elementType": "all", "stylers": [{"visibility": "off"}]}, {
                "featureType": "road",
                "elementType": "all",
                "stylers": [{"saturation": -100}, {"lightness": 45}]
            }, {
                "featureType": "road",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#eeeeee"}]
            }, {
                "featureType": "road",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#7b7b7b"}]
            }, {
                "featureType": "road",
                "elementType": "labels.text.stroke",
                "stylers": [{"color": "#ffffff"}]
            }, {
                "featureType": "road.highway",
                "elementType": "all",
                "stylers": [{"visibility": "simplified"}]
            }, {
                "featureType": "road.arterial",
                "elementType": "labels.icon",
                "stylers": [{"visibility": "off"}]
            }, {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [{"visibility": "off"}]
            }, {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{"color": "#46bcec"}, {"visibility": "on"}]
            }, {
                "featureType": "water",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#c8d7d4"}]
            }, {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#070707"}]
            }, {"featureType": "water", "elementType": "labels.text.stroke", "stylers": [{"color": "#ffffff"}]}]
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

    // side-bar
    $('.menu-bar').on("click", function () {
        $('.btn-menu-main').addClass('btn-menu-main-right');
    });
    $('.crose').on("click", function () {
        $('.btn-menu-main').removeClass('btn-menu-main-right');
    });


})(jQuery);