<style>
.bar {
	margin:5px;
    height: 12px;
    background: green;
}
</style>
<br>
<hr>
Upload DWG/DXF Files:
<br>

<input id="fileupload" type="file" name="files[]" data-url="../upload/UploadHandler.php" multiple>
<div id="progress">
    <div class="bar" style="width: 0%;"></div>
</div>
<script src="resources/js/vendor/jquery.ui.widget.js"></script>
<script src="resources/js/jquery.iframe-transport.js"></script>
<script src="resources/js/jquery.fileupload.js"></script>
<script>
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            console.log('done');
            console.log(data.result);
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo('#progress');
                if(typeof file.error != 'undefined')
                {
                    $('<p/>').text(file.error).appendTo('#progress');
                }
                
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        },
        fail: function(e,data){
             $('<p/>').text("Error uploading file: "+data.errorThrown).appendTo('#progress');;
        }
    });
});
</script>
