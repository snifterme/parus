/*!
 * @copyright Copyright &copy; Roman Korolov, 2016
 * @version 1.0.0
 */

(function(e) {
    "use strict";
    
    var SideMenu = function() {
        this.$menu = $('#sidebar-menu'),
        this.$selectorSlide = 'sidebar-menu-slide',
        this.$selectorPop = 'sidebar-menu-pop'
    };
    
    SideMenu.prototype.establishMenuType = function(e) {
        if ($(window).width() < 768) {
            this.$menu.addClass(this.$selectorSlide);
        } else {
            this.$menu.addClass(this.$selectorPop);
        }
    }
    
    SideMenu.prototype.menuItemSlide = function(e) {
        if($(this).parent().hasClass("has-sub")) {
            e.preventDefault();
            var element = $(this).parents('li:last');
            if (!$('#wrapper').hasClass('enlarged')) {
                if (!element.hasClass('is-drop')) {
                    $('ul', $(this).parents('ul:first')).slideUp(350);
                    $('li', $(this).parents('ul:first')).removeClass('is-drop');

                    $(this).next('ul').slideDown(350);
                    element.addClass('is-drop');
                } else if (element.hasClass('is-drop')) {
                    element.removeClass('is-drop');
                    $(this).next('ul').slideUp(350);
                }
            }
        }
    }
    
    SideMenu.prototype.menuItemPop = function() {
        $(".sidebar-menu-pop .has-sub > .side-menu-item-link").each(function() {
            var $this = $(this);
            $this.popover({
                    html: true,
                    trigger: "manual",
                    content: function () {
                        return $(this).next('.side-menu-sub').clone(true).addClass('show');
                    },
                    container: 'body',
                    animation: false,
                    template: '<div class="popover side-menu-popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>',
            });
        }).on("mouseenter", function () {
            var _this = this;
            $(this).popover("show");
            $(".popover").on("mouseleave", function () {
                $(_this).popover('hide');
            });
        }).on("mouseleave", function () {
            var _this = this;
            setTimeout(function () {
                if (!$(".popover:hover").length) {
                    $(_this).popover("hide");
                }
            }, 0);
        });
    }
    
//    SideMenu.prototype.menuItemPopItem = function() {
//        $(".sidebar-menu-pop .has-sub > .side-menu-item-link").each(function() {
//            var $this = $(this);
//            $this.popover({
//                html: true,
//                placement: "right",
//                trigger: 'hover',
//                animation: false,
//                content: function () {
//                    return $(this).next('.side-menu-sub').clone(true).addClass('show pop');
//                },
//                template: '<div class="popover side-menu-popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>',
//                container: $this
//            })
//        });
//    }
    
    SideMenu.prototype.init = function(e) {
        var $this = this;
        $this.establishMenuType();
        var event = (navigator.userAgent.match(/iP/i)) ? "touchstart" : "click";
        $('.sidebar-menu-slide .side-menu-item-link').on(event, $this.menuItemSlide);
        $('#side-menu').slimScroll({height: '100%', color: '#dcdcdc', size: '5'});
        $this.menuItemPop();
    }
    
    $.SideMenu = new SideMenu
    
})(jQuery);

(function($){
    $.SideMenu.init();
})(jQuery);