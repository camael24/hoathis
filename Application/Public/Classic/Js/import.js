function parserComposerFile(json) {
    $('#name').val(json.name);
    $('#description').val(json.description);
    $('#home').val(json.homepage);
    $('#release').val(json.support.source);
}
$().ready(function () {
    var holder = document.getElementById('holder');

    holder.ondragover = function () {
        this.className = 'hover';
        return false;
    };
    holder.ondragend = function () {
        this.className = '';
        return false;
    };
    holder.ondrop = function (e) {
        this.className = '';
        e.preventDefault();

        var file = e.dataTransfer.files[0];
        var reader = new FileReader();
        reader.onload = function (event) {

            var j = event.target.result;
            var json = jQuery.parseJSON(j);
            parserComposerFile(json);

        };
        if (file.name == 'composer.json') {
            reader.readAsText(file);
            $('#holder').text('Thank you !');
            $('#holder').css('background-color', 'green');
        }
        else {
            $('#holder').text('Its not a composer.json file !');
            $('#holder').css('background-color', '#FF0000');
        }

        return false;
    };
});