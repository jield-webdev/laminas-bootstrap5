$(document).ready(function () {
    $('.daterangepicker-element').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        }
    });
    $('.daterangepicker-element').on('apply.daterangepicker', function (ev, picker) {
        //do something, like clearing an input
        $('#search-form').submit();
    });


});