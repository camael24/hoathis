$().ready(function () {

    var autocomplete;
    $.get('/api/autocomplete.html', function (data) {
        $('input[data-provide="typeahead"]').each(function () {
            console.log($(this).attr('id'));
            $(this).typeahead({
                source: data
            });
        });

    }, 'json');


});
