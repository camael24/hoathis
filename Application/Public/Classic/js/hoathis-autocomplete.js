$(function () {
    $("#searchBar").autocomplete({
        source:function (request, response) {
            $.ajax({
                url:"/search.html",
                data:request,
                dataType:"json",
                type:"POST",
                success:response
            });
        }
    });
    $('#searchBar').keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            $('#searchButton').trigger('click');
            e.preventDefault();
        }


    });

    $("#searchButton").click(function () {
        var search = $('#searchBar').val();
        $.ajax({
            url:"/search.html",
            data:"search=" + search,
            dataType:"html",
            type:"POST",
            success:function (data) {
                $('#result').html(data);
            }
        });
    });
});