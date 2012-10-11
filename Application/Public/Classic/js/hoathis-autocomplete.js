$(function () {
    $("#searchBar").autocomplete({
        source:function (request, response) {
            $.ajax({
                url:"/a/search.html",
                data:request,
                dataType:"json",
                type:"POST",
                success:response
            });
        }
    });
    $("#searchButton").click(function () {
        var search = $('#searchBar').val();
        $.ajax({
            url:"/a/search.html",
            data:"search=" + search,
            dataType:"html",
            type:"POST",
            success:function (data) {
                $('#result').html(data);
            }
        });
    });
});