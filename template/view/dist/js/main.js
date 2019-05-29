/* ========================================================================

Pixaar: Main.js ( Main Theme JS file )

Theme Name: Pixaar - App Landing Page & Product Showcase
Version: 1.0
Author: Raventhemez
Author URI: https://themeforest.net/user/raventhemez
If you having trouble in editing js. please send a mail to raventhemez@gmail.com
 
=========================================================================
 */


"use strict";


/*======== Doucument Ready Function =========*/
jQuery(document).ready(function () {

    //CACHE JQUERY OBJECTS
    var $window = $(window);

    /*======= jQuery navbar on scroll =========*/


    $window.on('scroll', function () {

        if ($(".navbar-default").add(".navbar-inverse").offset().top > 50) {
            $(".reveal-menu-home").addClass("sticky-nav");
            $(".reveal-menu-blog").addClass("sticky-nav-white");
        } else {
            $(".reveal-menu-home").removeClass("sticky-nav");
            $(".reveal-menu-blog").removeClass("sticky-nav-white");
        }
    });

    /*======= End jQuery navbar on scroll =========*/

    /*======== One Page Scrolling ======= */

    $("#navigation").onePageNav({
        currentClass: "active",
        changeHash: true,
        scrollSpeed: 1000,
        scrollThreshold: 0.5,
        filter: "",
        easing: "swing",
        begin: function () {
            //I get fired when the animation is starting
        },
        end: function () {
            //I get fired when the animation is ending
        },
        scrollChange: function ($currentListItem) {
            //I get fired when you enter a section and I pass the list item of the section
        }
    });

    /*======== End One Page Scrolling ========*/

    /*========= Fun and Facts Script ======== */

    try {
        $(".fun-facts_wrapper").appear(function () {
            $(".timer").countTo();
        });
    } catch (err) {

        console.log(err.message);
    }

    /*========= End Fun and Facts Script ======== */

    /*======== Contact Form ========*/

    $('#submit-btn').on('click', function (event) {
        event.preventDefault();
        $.ajax({
            dataType: 'JSON',
            url: 'index.php?url=common/home/send',
            type: 'POST',
            data: $('#contact_form').serialize(),
            beforeSend: function (xhr) {
                $('.mt_load').show();
            },
            success: function (response) {
                if (response['error']) {
                    if (response['error']['name']) {
                        $('input[name=\'name\']').after('<div class="mt_error">' + response['error']['name'] + '</div>');
                    }

                    if (response['error']['email']) {
                        $('input[name=\'email\']').after('<div class="mt_error">' + response['error']['email'] + '</div>');
                    }

                    if (response['error']['message']) {
                        $('textarea[name=\'message\']').after('<div class="mt_error">' + response['error']['message'] + '</div>');
                    }
                }

                if (response['success']) {
                    toastr.success(response['success']);
                    $('#msg').hide();
                    $('input, textarea').val(function () {
                        return this.defaultValue;
                    });
                }
                // if (response) {
                //     console.log(response);
                //     if (response['signal'] == 'ok') {
                //         toastr.success(response['msg']);
                //         $('#msg').hide();
                //         $('input, textarea').val(function () {
                //             return this.defaultValue;
                //         });
                //     }
                //     else {
                //         $('#msg').show();
                //         $('#msg').html('<div class="mt_error">'+ response['msg'] +'</div>');
                //     }
                // }
            },
            error: function () {
                $('#msg').show();
                $('#msg').html('<div class="mt_error">Errors occur. Please try again later.</div>');
            },
            complete: function () {
                $('.mt_load').hide();
            }
        });
    });
    /*======== End Contact Form ========*/

    /*========= Masonry Grid Script ==========*/

    $(".grid-masonry").masonry({
        // options...
        itemSelector: ".grid-item",
        columnWidth: ".grid-item",

    });

    /*========== End Masonry Grid ==========*/


    /*======== ScreenShot Section =========*/

    $("#rt_screenshots .owl-carousel").owlCarousel({
        center: true,
        loop: true,
        margin: 0,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsiveBaseElement: window,
        responsiveClass: true,
        responsive: {
            0: {
                items: 2,
                nav: true
            },
            600: {
                items: 3,
                nav: false
            },
            1000: {
                items: 4,
                nav: true
            },
            1201: {
                items: 4,
                nav: true
            }
        }
    });

    /*======== End ScreenShot Section =========*/

    /*======== Drone ScreenShot Section =========*/

    $("#rt_drone_screenshots .owl-carousel").owlCarousel({
        center: true,
        loop: true,
        margin: 0,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsiveBaseElement: window,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: true
            },
            600: {
                items: 1,
                nav: false
            },
            1000: {
                items: 2,
                nav: true
            },
            1201: {
                items: 2,
                nav: true
            }
        }
    });

    /*======== End Drone ScreenShot Section =========*/



    /*======== Testimonial Section =========*/

    $("#rt_testimonial .owl-carousel").owlCarousel({
        loop: false,
        margin: 24,
        autoplay: false,
        autoplayHoverPause: true,
        autoplaySpeed: 1000,
        dot: true,
        smartSpeed: 850,
        responsive: {
            0: {
                items: 1,
                dots: true
            },
            600: {
                items: 1,
                dots: true
            },
            1000: {
                items: 2,
                dots: true
            },
            1201: {
                items: 2,
                dots: true
            }
        }
    });

    /*======== End Testimonial Section =========*/


    /*======== Team Section =========*/
    $("#rt_team .owl-carousel").owlCarousel({
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        autoplaySpeed: 1000,
        smartSpeed: 850,
        responsive: {
            0: {
                items: 1,
                dots: true
            },
            450: {
                items: 2,
                dots: true
            },
            500: {
                items: 2,
                dots: true
            },
            600: {
                items: 2,
                dots: true
            },
            1000: {
                items: 3,
                dots: true
            },
            1201: {
                items: 3,
                dots: true
            }
        }
    });

    /*======== End Team Section =========*/


    /*======== Youtube Background Init =========*/

    $("#bgndVideo").YTPlayer();

    /*======== End Youtube Background Init =========*/


    /*======== Fancy Box Init ========*/

    $(".various").fancybox({
        maxWidth: 800,
        maxHeight: 600,
        fitToView: false,
        width: "70%",
        height: "70%",
        autoSize: false,
        closeClick: true,
        openEffect: "elastic",
        closeEffect: "none"
    });

    /*======== End Fancy Box ========*/

});
/*======== End Doucument Ready Function =========*/

/*======== On Load Function =========*/

//CACHE JQUERY OBJECTS
var $window = $(window);

$window.on('load', function () {

    /*======== Preloader =========*/
    $(".loading-text").fadeOut();
    $(".loading").delay(350).fadeOut("slow");
    /* END of Preloader */

    /*========== Init Wow ==========*/
    new WOW().init();
    /*========== End Init Wow ==========*/
});

/*======== End On Load Function =========*/