/*!
 * @package   yii2-bootstrap5-dropdown
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2022
 * @version   1.0.2
 *
 * Bootstrap 4 Dropdown Nested Submenu Script
 * 
 * For more JQuery plugins visit http://plugins.krajee.com
 * For more Yii related demos visit http://demos.krajee.com
 **/
(function ($) {
    "use strict"
    $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
        var $el = $(this), $parent = $el.offsetParent(".dropdown-menu"), $subMenu, $subMenuParent;

        $el.parents('.dropdown-menu').first().find('.dropdown-toggle.show').each(function () {
            if (!$(this).is($el)) {
                $(this).removeClass('show');
            }
        });

        $subMenu = $el.next('.dropdown-menu');
        $subMenuParent = $subMenu.closest('.dropdown');
        $subMenuParent.closest('.dropdown-menu').find('.dropdown').each(function () {
            var $el = $(this);
            if (!$el.is($subMenuParent)) {
                $el.removeClass('is-expanded show');
                $el.find('.dropdown-menu').removeClass('is-expanded show');
            }
        });
        $el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});

        // fix for correct styling of each dropdown menu container to fit each of the enclosed dropdown items
        setTimeout(function () {
            var $menu = $parent.find('.dropdown-menu:visible'), vInset = $menu.css('inset');
            if (!vInset) {
                return;
            }
            vInset = vInset.trim().split(/\s+/);
            if (vInset[2]) {
                vInset[2] = 'auto';
            }
            vInset = vInset.join(' ');
            $menu.css({inset: vInset}).data('inputProp', vInset);

        }, 100);
        // maintain the open dropdown menu container style after scroll
        $(window).off('scroll.kvdropdown').on('scroll.kvdropdown', function () {
            $('.dropdown-menu:visible').each(function () {
                var vInset = $(this).data('inputProp');
                if (vInset) {
                    $(this).css({inset: vInset});
                }
            });
        });
        return false;
    });
})(window.jQuery);