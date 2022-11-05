$(document).ready(function () {
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
        $('input[name="dateInterval"]').val('');
        $('#search-form').submit();
    });
});