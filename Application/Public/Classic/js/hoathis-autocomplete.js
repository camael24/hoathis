$(function () {
    $("#searchBar").autocomplete({
        source:function (request, response) {
            $.ajax({
                url:"/foo.html",
                data:request,
                dataType:"json",
                type:"POST",
                success:response
            });
        }
    });
});