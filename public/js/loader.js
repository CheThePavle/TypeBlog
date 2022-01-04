$(function() {
    window.onload = function () {
        $("body").attr('class', 'loaded_hiding');
        window.setTimeout(function () {
            $("body").attr('class', 'loaded');
            $("body").removeAttr('loaded_hiding');
        }, 500);
    }
});