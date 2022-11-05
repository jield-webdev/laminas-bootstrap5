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
    $('input[name^="facet"]').on('click', function () {
        $('#search-form').submit();
    });
    $('input[name^="filter"]').on('click', function () {
        $('#search-form').submit();
    });
    $('.search-toggle').change(function () {
        $('#search-form').submit();
    });
    $('.submitButton').on('click', function () {
        $('#search-form').submit();
    });
    $('.resetButton').on('click', function () {
        $('input[name^="facet"]').each(function () {
            this.removeAttribute('checked');
        });
        $('input[name^="filter"]').each(function () {
            this.removeAttribute('checked');
        });
        $('input[name="query"]').val('');
        $('#search-form').submit();
    });
});