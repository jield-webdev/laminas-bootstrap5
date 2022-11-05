$(function () {

    $('.form-check-search > input[type="checkbox"]').on('click', function () {
        $('#search').submit();
    });
    $('.form-multi-slider').on('change', function () {
        $('#search').submit();
    });
    $('.form-check-search').on('click', function () {
        $('#search').submit(); //yesno/andor
    });

    $('#searchButton').on('click', function () {
        $('#search').submit();
    });
    $('#resetButton').on('click', function () {
        $('.form-check-search > input[type="checkbox"]').each(function () {
            this.removeAttribute('checked');
        });
        $(".form-check-search").each(function () {
            this.removeAttribute("checked");
        });
        $('.form-check-search > input[type="radio"]').each(function () {
            this.removeAttribute('checked');
        });
        $('input[name="query"]').val('');
        $('#search').submit();
    });

    $('.simple-load-more').simpleLoadMore({
        item: '.form-check',
        count: 10,
        itemsToLoad: 25
    });
});