$(document).ready(function () {

    const dateRangePicker = $('.daterangepicker-element');
    const format = 'DD-MM-YYYY';

    dateRangePicker.daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: format,
            cancelLabel: 'Clear'
        }
    });

    dateRangePicker.on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(format) + ' - ' + picker.endDate.format(format));
        $('#search-form').submit();
    });

    dateRangePicker.on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $('#search-form').submit();
    });
});