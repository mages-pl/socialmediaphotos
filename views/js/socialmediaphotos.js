$(document).ready(function () {
    console.log('run');
    $("#submitMessage").click(function (event) {
        if (($("#mediaphotos_photo").get(0).files.length) > 4) {
            event.preventDefault();
            alert('Yo can upload max 4 files');
        } else {
        }
    });
});