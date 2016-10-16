
(function($){
    "use strict";
    
    var LanguageSwither = function() {
        this.$curentLanguage = translatable_default_language,
        this.$selectorSwitch = '.language-switcher-sub-link'
    };
    
    LanguageSwither.prototype.hideLanguages = function(id) {
        $('.translatable-field').hide();
        $('.translatable-field.lang-' + id).show();
        $('.language-switcher-sub').show();
        $('.language-switcher-sub-' + id).hide();
    }
    
    LanguageSwither.prototype.switchLanguage = function(e, self) {
        e.preventDefault();
        this.hideLanguages($(self).data('lang'));
        $('.language-switcher-title').text($(self).text());
        $('.language-switcher-title').val($(self).text());
    }
    //Исправить путаницу var $this = this;
    LanguageSwither.prototype.init = function(e) {
        var $this = this;
        this.hideLanguages(this.$curentLanguage);
        var event = (navigator.userAgent.match(/iP/i)) ? "touchstart" : "click";
        $($this.$selectorSwitch).on(event, function(e) {
            $this.switchLanguage(e, this);
        });
    }
    
    $.LanguageSwither = new LanguageSwither
    
})(jQuery);

$(document).on('ready pjax:success', function() {
    $.LanguageSwither.init();
});
