/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

jQuery(function($) {

    var config = $('html').data('config') || {};

    // Social buttons
    $('article[data-permalink]').socialButtons(config);

    // fit footer
    (function(main, meta, fn){

        if (!main.length) return;

        fn = function() {

            main.css('min-height','');

            meta = document.body.getBoundingClientRect();

            if (meta.height <= window.innerHeight) {
                main.css('min-height', (main.outerHeight() + (window.innerHeight - meta.height))+'px');
            }

            return fn;
        };

        UIkit.$win.on('load resize', fn());

    })($('#tm-middle'));

    $('ul.uk-thumbnav.uk-grid-width-medium-1-1.uk-grid-width-1-8 > li').each(function(){
        $(this).on('click', function(){
        $([document.documentElement, document.body]).animate({
        scrollTop: $(".tm-switcher-chester > .uk-width-medium-3-4").offset().top
    }, 1000);


});

    });



});
