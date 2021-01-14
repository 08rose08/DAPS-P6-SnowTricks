/*$('.custom-file-input').on('change', function(event) {
    var inputFile = event.currentTarget;
    $(inputFile).parent()
        .find('.custom-file-label')
        .html(inputFile.files[0].name);
});*/

$(document).on("change", ".custom-file-input", function () {
    let fileName = $(this).val().replace(/\\/g, "/").replace(/.*\//, "");
    $(this).parent(".custom-file").find(".custom-file-label").text(fileName);
});