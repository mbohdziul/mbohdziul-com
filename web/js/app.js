$(document).foundation();

$(document).ready(function () {
    $("img.lazy").lazyload();

    //load pictures in tabs after click
    $(".tabs-title").on('click', 'a', function () {
        setTimeout(function () {
            $(window).trigger("scroll")
        }, 100);
    });

    $('.tabs').on('click', '.tabs-title:not(.is-active) a', function (e) {
        e.preventDefault();
        $('li.is-active').removeClass('is-active').find('a').attr('aria-selected', false);
        $(this).parent().addClass('is-active').find('a').attr('aria-selected', true);
        $('.tabs-content .tabs-panel').removeClass('is-active').eq($(this).parent().index()).addClass('is-active');
    });

    $('.tabs-title.is-active').on('click', 'a', function (e) {
        e.preventDefault();
    });
});

