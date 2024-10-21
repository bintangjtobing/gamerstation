(function ($) {
    "user strict";

    // preloader
    $(window).on('load', function () {
        $('.preloader').fadeOut(1000);
        var img = $('.bg_img');
        img.css('background-image', function () {
            var bg = ('url(' + $(this).data('background') + ')');
            return bg;
        });
    });

    //Create Background Image
    (function background() {
        let img = $('.bg_img');
        img.css('background-image', function () {
            var bg = ('url(' + $(this).data('background') + ')');
            return bg;
        });
    })();

    // nice-select
    $(".nice-select").niceSelect()

    // header-fixed
    var fixed_top = $(".header-section");
    $(window).on("scroll", function () {
        if ($(window).scrollTop() > 100) {
            fixed_top.addClass("animated fadeInDown header-fixed");
        }
        else {
            fixed_top.removeClass("animated fadeInDown header-fixed");
        }
    });

    // navbar-click
    $(".navbar li a").on("click", function () {
        var element = $(this).parent("li");
        if (element.hasClass("show")) {
            element.removeClass("show");
            element.children("ul").slideUp(500);
        }
        else {
            element.siblings("li").removeClass('show');
            element.addClass("show");
            element.siblings("li").find("ul").slideUp(500);
            element.children('ul').slideDown(500);
        }
    });

    //Odometer
    if ($(".statistics-item").length) {
        $(".statistics-item").each(function () {
            $(this).isInViewport(function (status) {
                if (status === "entered") {
                    for (var i = 0; i < document.querySelectorAll(".odometer").length; i++) {
                        var el = document.querySelectorAll('.odometer')[i];
                        el.innerHTML = el.getAttribute("data-odometer-final");
                    }
                }
            });
        });
    }

    // scroll-to-top
    var ScrollTop = $(".scrollToTop");
    $(window).on('scroll', function () {
        if ($(this).scrollTop() < 100) {
            ScrollTop.removeClass("active");
        } else {
            ScrollTop.addClass("active");
        }
    });

    // faq
    $('.faq-wrapper .faq-title').on('click', function (e) {
        var element = $(this).parent('.faq-item');
        if (element.hasClass('open')) {
            element.removeClass('open');
            element.find('.faq-content').removeClass('open');
            element.find('.faq-content').slideUp(300, "swing");
        } else {
            element.addClass('open');
            element.children('.faq-content').slideDown(300, "swing");
            element.siblings('.faq-item').children('.faq-content').slideUp(300, "swing");
            element.siblings('.faq-item').removeClass('open');
            element.siblings('.faq-item').find('.faq-title').removeClass('open');
            element.siblings('.taq-item').find('.faq-content').slideUp(300, "swing");
        }
    });

    // slider
    var swiper = new Swiper('.testimonial-slider', {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        centeredSlides: true,
        navigation: {
            nextEl: '.slider-next',
            prevEl: '.slider-prev',
        },
        autoplay: {
            speeds: 1000,
            delay: 2000,
        },
        speed: 1000,
        breakpoints: {
            991: {
                slidesPerView: 2,
            },
            767: {
                slidesPerView: 2,
            },
            575: {
                slidesPerView: 1,
            },
        }
    });

    // banner-sidebar
    var swiper = new Swiper(".banner-slider", {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,

        speed: 1000,
        navigation: {
            nextEl: '.slider-next',
            prevEl: '.slider-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });

    //sidebar Menu
    $(document).on('click', '.sidebar-collapse-icon', function () {
        $('.page-container').toggleClass('show');
    });

    // sidebar sub
    $(".has-sub > a").on("click", function () {
        var element = $(this).parent("li");
        if (element.hasClass("active")) {
            element.removeClass("active");
            element.children("ul").slideUp(500);
        }
        else {
            element.siblings("li").removeClass('active');
            element.addClass("active");
            element.siblings("li").find("ul").slideUp(500);
            element.children('ul').slideDown(500);
        }
    });

    // Mobile Menu
    $('.sidebar-mobile-menu').on('click', function () {
        $('.sidebar-main-menu').slideToggle();
    });

    //Profile Upload
    function proPicURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var preview = $(input).parents('.preview-thumb').find('.profilePicPreview');
                $(preview).css('background-image', 'url(' + e.target.result + ')');
                $(preview).addClass('has-image');
                $(preview).hide();
                $(preview).fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".profilePicUpload").on('change', function () {
        proPicURL(this);
    });

    $(".remove-image").on('click', function () {
        $(".profilePicPreview").css('background-image', 'none');
        $(".profilePicPreview").removeClass('has-image');
    });

    $(document).ready(function () {
        var AFFIX_TOP_LIMIT = 300;
        var AFFIX_OFFSET = 110;

        var $menu = $("#menu"),
            $btn = $("#menu-toggle");

        $("#menu-toggle").on("click", function () {
            $menu.toggleClass("open");
            return false;
        });


        $(".docs-nav").each(function () {
            var $affixNav = $(this),
                $container = $affixNav.parent(),
                affixNavfixed = false,
                originalClassName = this.className,
                current = null,
                $links = $affixNav.find("a");

            function getClosestHeader(top) {
                var last = $links.first();

                if (top < AFFIX_TOP_LIMIT) {
                    return last;
                }

                for (var i = 0; i < $links.length; i++) {
                    var $link = $links.eq(i),
                        href = $link.attr("href");

                    if (href.charAt(0) === "#" && href.length > 1) {
                        var $anchor = $(href).first();

                        if ($anchor.length > 0) {
                            var offset = $anchor.offset();

                            if (top < offset.top - AFFIX_OFFSET) {
                                return last;
                            }

                            last = $link;
                        }
                    }
                }
                return last;
            }


            $(window).on("scroll", function (evt) {
                var top = window.scrollY,
                    height = $affixNav.outerHeight(),
                    max_bottom = $container.offset().top + $container.outerHeight(),
                    bottom = top + height + AFFIX_OFFSET;

                if (affixNavfixed) {
                    if (top <= AFFIX_TOP_LIMIT) {
                        $affixNav.removeClass("fixed");
                        $affixNav.css("top", 0);
                        affixNavfixed = false;
                    } else if (bottom > max_bottom) {
                        $affixNav.css("top", (max_bottom - height) - top);
                    } else {
                        $affixNav.css("top", AFFIX_OFFSET);
                    }
                } else if (top > AFFIX_TOP_LIMIT) {
                    $affixNav.addClass("fixed");
                    affixNavfixed = true;
                }

                var $current = getClosestHeader(top);

                if (current !== $current) {
                    $affixNav.find(".active").removeClass("active");
                    $current.addClass("active");
                    current = $current;
                }
            });
        });
    });


    $(".logo-btn").click(function () {
        $(".main-side-menu").toggleClass("show");
        $('.body-overlay').addClass('active');
    });
    $(".main-side-menu-cross").click(function () {
        $(".main-side-menu").removeClass("show");
        $('.body-overlay').removeClass('active');
    });
    $('#body-overlay').on('click', function (e) {
        e.preventDefault();
        $('.main-side-menu').removeClass('show');
        $('.body-overlay').removeClass('active');
    });

    //account-toggle
    $('.account-area-btn').on('click', function () {
        $('.account-section').addClass('active');
    });
    $('.account-close, .account-bg').on('click', function () {
        $('.account-section').addClass('duration');
        setTimeout(signupRemoveClass, 200);
        setTimeout(signupRemoveClass2, 200);
    });
    function signupRemoveClass() {
        $('.account-section').removeClass("active");
    }
    function signupRemoveClass2() {
        $('.account-section').removeClass("duration");
    }
    $('.account-control-btn').on('click', function () {
        $('.account-area').toggleClass('change-form');
    })

    // notification
    $(".notify-btn-area").click(function () {
        $(".notification-wrapper").slideToggle();
    });



    $('.header-mobile-search-btn').on('click', function (e) {
        e.preventDefault();
        if ($('.header-mobile-search-form-area').hasClass('active')) {
            $('.header-mobile-search-form-area').removeClass('active');
            $('.body-overlay').removeClass('active');
        } else {
            $('.header-mobile-search-form-area').addClass('active');
            $('.body-overlay').addClass('active');
            $('.header-section').addClass('active');
        }
    });
    $('#body-overlay').on('click', function (e) {
        e.preventDefault();
        $('.header-mobile-search-form-area').removeClass('active');
        $('.body-overlay').removeClass('active');
    });

    // active menu JS
    function splitSlash(data) {
        return data.split('/').pop();
    }
    function splitQuestion(data) {
        return data.split('?').shift().trim();
    }
    var pageNavLis = $('.sidebar-menu a');
    var dividePath = splitSlash(window.location.href);
    var divideGetData = splitQuestion(dividePath);
    var currentPageUrl = divideGetData;

    // find current sidevar element
    $.each(pageNavLis, function (index, item) {
        var anchoreTag = $(item);
        var anchoreTagHref = $(item).attr('href');
        var index = anchoreTagHref.indexOf('/');
        var getUri = "";
        if (index != -1) {
            // split with /
            getUri = splitSlash(anchoreTagHref);
            getUri = splitQuestion(getUri);
        } else {
            getUri = splitQuestion(anchoreTagHref);
        }
        if (getUri == currentPageUrl) {
            var thisElementParent = anchoreTag.parents('.sidebar-menu-item');
            (anchoreTag.hasClass('nav-link') == true) ? anchoreTag.addClass('active') : thisElementParent.addClass('active');
            (anchoreTag.parents('.sidebar-dropdown')) ? anchoreTag.parents('.sidebar-dropdown').addClass('active') : '';
            (thisElementParent.find('.sidebar-submenu')) ? thisElementParent.find('.sidebar-submenu').slideDown("slow") : '';
            return false;
        }
    });

    // sidebar
    $(".sidebar-menu-item > a").on("click", function () {
        var element = $(this).parent("li");
        if (element.hasClass("active")) {
            element.removeClass("active");
            element.children("ul").slideUp(500);
        }
        else {
            element.siblings("li").removeClass('active');
            element.addClass("active");
            element.siblings("li").find("ul").slideUp(500);
            element.children('ul').slideDown(500);
        }
    });

    //sidebar Menu
    $('.sidebar-menu-bar').on('click', function (e) {
        e.preventDefault();
        if ($('.sidebar, .navbar-wrapper, .body-wrapper').hasClass('active')) {
            $('.sidebar, .navbar-wrapper, .body-wrapper').removeClass('active');
            $('.body-overlay').removeClass('active');
        } else {
            $('.sidebar, .navbar-wrapper, .body-wrapper').addClass('active');
            $('.body-overlay').addClass('active');
        }
    });
    $('#body-overlay').on('click', function (e) {
        e.preventDefault();
        $('.sidebar, .navbar-wrapper, .body-wrapper').removeClass('active');
        $('.body-overlay').removeClass('active');
    });

    // dashboard-list
    $('.dashboard-list-item').on('click', function (e) {
        var element = $(this).parent('.dashboard-list-item-wrapper');
        if (element.hasClass('show')) {
            element.removeClass('show');
            element.find('.preview-list-wrapper').removeClass('show');
            element.find('.preview-list-wrapper').slideUp(300, "swing");
        } else {
            element.addClass('show');
            element.children('.preview-list-wrapper').slideDown(300, "swing");
            element.siblings('.dashboard-list-item-wrapper').children('.preview-list-wrapper').slideUp(300, "swing");
            element.siblings('.dashboard-list-item-wrapper').removeClass('show');
            element.siblings('.dashboard-list-item-wrapper').find('.dashboard-list-item').removeClass('show');
            element.siblings('.dashboard-list-item-wrapper').find('.preview-list-wrapper').slideUp(300, "swing");
        }
    });

    //sidebar Menu
    $(document).on('click', '.push-icon', function () {
        $('.push-wrapper').toggleClass('active');
    });


    //info-btn
    $(document).on('click', '.info-btn', function () {
        $('.support-profile-wrapper').addClass('active');
    });
    $(document).on('click', '.chat-cross-btn', function () {
        $('.support-profile-wrapper').removeClass('active');
    });

    $('.cart-btn').on('click', function (e) {
        e.preventDefault();
        if ($('.cart').hasClass('active')) {
            $('.cart').removeClass('active');
            $('.body-overlay').removeClass('active');
        } else {
            $('.cart').addClass('active');
            $('.body-overlay').addClass('active');
            $('.body-overlay').addClass('bg');
        }
    });
    $('#body-overlay, .cart-wrapper .cross-btn').on('click', function (e) {
        e.preventDefault();
        $('.cart').removeClass('active');
        $('.body-overlay').removeClass('active');
    });

    $(document).on('click', '.cart-item .remove-btn', function (e) {
        e.preventDefault();
        $(this).parent().hide(300);
    });

    // lightcase
    $(window).on('load', function () {
        $("a[data-rel^=lightcase]").lightcase();
    })

    $(".confirm-withdraw-method-item.proceed").click(function () {
        $(".confirm-withdraw-form").slideToggle();
        $(this).toggleClass("active");
    });

    // input toggle
    $("#visa").click(function () {
        $(".checkout-hedden-form").addClass('active');
    });
    $("#paypal").click(function () {
        $(".checkout-hedden-form").removeClass('active');
    });
    $("#skrill").click(function () {
        $(".checkout-hedden-form").removeClass('active');
    });

    // select-2 init
    $('.select2-basic').select2();
    $('.select2-multi-select').select2();
    $(".select2-auto-tokenize").select2({
        tags: true,
        tokenSeparators: [',']
    });

    $(".header-search-area .top-up-search").click(function () {
        $(".header-search .header-search-result").addClass("active");
        $('.body-overlay').addClass('active');
    });
    $(".header-mobile-search-area .header-mobile-search-form-area").click(function () {
        $(".header-search .header-search-result").addClass("active");
        $('.body-overlay').addClass('active');
    });
    $('#body-overlay').on('click', function (e) {
        e.preventDefault();
        $('.header-search .header-search-result').removeClass('active');
        $('.body-overlay').removeClass('active');
    });

    $(".sidebar-search-input").click(function () {
        $(this).parents('.dashboard-title-part .header-search-wrapper').find('.sidebar-search-result').addClass("active");
        $('.body-overlay').addClass('active');
    });
    $(".body-overlay").click(function () {
        $('.sidebar-search-result').removeClass("active");
        $('.body-overlay').removeClass('active');
    });

})(jQuery);

