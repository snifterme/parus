/*!
 * @copyright Copyright &copy; Roman Korolov, 2016
 * @version 1.0.0
 */

App = (function ($) {
    var pub = {
        
        clickableDataMehodSelector: '.js-data-post',
        clickableLanguageSwitherSelector: '.js-change-lang',
        
        init: function () {
            initDataMehod(this.clickableDataMehodSelector);
            initLanguageSwither();
        },

        showAlert: function(alert) {
            $('#system-messages').html(alert).stop().fadeIn().animate({opacity: 1.0}, 4000).fadeOut('slow');
        },
        
        confirm: function(message) {
            return message == undefined || confirm (message);
        },
        
        getCsrfToken: function() {
            return $('meta[name="csrf-token"]').attr("content");
        },
        
        reloadPjax: function(container) {
            if (container) {
                $.pjax.reload({container: '#' + container});
            }
        },
        
        getEventType: function() {
            return (navigator.userAgent.match(/iP/i)) ? "touchstart" : "click";
        },
        
        initEventDataMehod: function(selector) {
            initDataMehod(selector);
        },
        
        notify: function(type, message) {
            $.notify({message:message},{type:type, mouse_over:'pause'});
        },
        
        notifyAll: function(messages) {
            $.each(messages, function(type, message){
                pub.notify(type, message);
            });
        },
        
        generateSlug: function (selectorTo, valueFrom, event) {
            if (valueFrom.length) {
                var url = $(event).attr('href') + '?value=' + valueFrom;
                $.post(url, function(data) {
                    $(selectorTo).val(data)
                });
            }
        },
        
        changeLang: function (link) {
            lang = $(link).data('lang');
            $.post(document.href, {lang: lang}, function() {
                location.reload();
                return true;
            });
            return false;
        }
    }
    
    function initDataMehod(selector) {
        $(selector).on(pub.getEventType(), function(e) {
            e.preventDefault();
            var $this = $(this);
                message = $this.data('message');
                container = $this.data('container');
                url = $this.attr('href');
                csrfToken = pub.getCsrfToken();
            
            if (pub.confirm(message)) {
                $.post(url, {_csrf : csrfToken}, function (data) {
                    pub.reloadPjax(container);
                    pub.notifyAll(data.messages);
                }, 'json');
                return false;
            } else {
                return false;
            }
        });
    };
    
    function initLanguageSwither() {
        $(pub.clickableLanguageSwitherSelector).on(pub.getEventType(), function(e) {
            e.preventDefault();
            pub.changeLang(this);
        });
    }
    
    return pub;
})(jQuery);

$(document).on('ready pjax:end', function() {
    App.init();
});


Responsive = (function($) {
    "use strict";
    
    var pub = {
        
        clickableOpenSideBarSelector: '.js-open-sidebar',
        
        init: function() {
            $(document).ready(onDocReady());
            initOpenSideBar(this.clickableOpenSideBarSelector);
            
        },
        
        initEventOpenSidebar: function(selector) {
            initOpenSideBar(selector);
        }
    }
    
    function onDocReady () {
        $(window).resize(debounce(changeptype, 100));
        $("body").trigger("resize");
    }
    
    function debounce (func, wait, immediate) {
        var timeout, result;
        return function () {
        var context = this, args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate)
                result = func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) result = func.apply(context, args);
            return result;
        };
    }
        
    function changeptype () {
        var w, h, dw, dh;
        w = $(window).width();
        h = $(window).height();
        dw = $(document).width();
        dh = $(document).height();

        var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        
        if (isMobile === true) {
            $("body").addClass("mobile").removeClass("fixed-left");
        }

        if (!$("#wrapper").hasClass("forced")) {
            if (w > 990) {
                $("body").removeClass("smallscreen").addClass("widescreen");
                $("#wrapper").removeClass("enlarged");
            } else {
                $("body").removeClass("widescreen").addClass("smallscreen");
                $("#wrapper").addClass("enlarged");
            }
            if ($("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left")) {
                $("body").removeClass("fixed-left").addClass("fixed-left-void");
            } else if (!$("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left-void")) {
                $("body").removeClass("fixed-left-void").addClass("fixed-left");
            }
        }
    }
    
    function initOpenSideBar(selector) {
        $(selector).on(App.getEventType(), function(e) {
            e.stopPropagation();
            
            $("#wrapper").toggleClass("enlarged");
            $("#wrapper").addClass("forced");

            if($("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left")) {
                $("body").removeClass("fixed-left").addClass("fixed-left-void");
            } else if(!$("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left-void")) {
                $("body").removeClass("fixed-left-void").addClass("fixed-left");
            }
            $("body").trigger("resize");
        })
    }
        
    return pub;
    
})(jQuery);

(function($){
    Responsive.init()
})(jQuery);

$(document).on('ready pjax:end', function() {
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = this.href.split('#');
        $('.nav a').filter('[href="#'+target[1]+'"]').tab('show');
    })
});