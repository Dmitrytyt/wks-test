<h1>Upload users</h1>
<div class="upload-file">
    <form enctype="multipart/form-data" action="/users" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
        Upload file: <input name="file" type="file" />
        <input type="submit" value="send" />
    </form>
</div>
<br/>