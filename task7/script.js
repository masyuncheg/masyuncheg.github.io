/*jslint browser: true*/ /*global  $*/
$(document).ready(function() {
    $(".slider").slick({
        dots: true,
        infinite: true,
        responsive: [
            {
            breakpoint: 1024,
            settings: {
            slidesToShow: 1
            }
            }
        ],
        slidesToShow: 3
    });
});