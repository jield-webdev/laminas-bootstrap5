$(document).ready(function () {
    $('input[type=date]').datepicker({
        addSliderAccess: true,
        regional: 'nl',
        firstDay: 1,
        sliderAccessArgs: {touchonly: false},
        dateFormat: 'yy-mm-dd'
    });
    $('input[type=datetime]').datetimepicker({
        addSliderAccess: true,
        firstDay: 1,
        regional: 'nl',
        sliderAccessArgs: {touchonly: false},
        timeFormat: 'HH:mm',
        dateFormat: 'yy-mm-dd'
    });
});