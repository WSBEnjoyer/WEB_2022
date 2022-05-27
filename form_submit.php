<?php

if ($_POST) {
    if ($_FILES["file"]["error"] === UPLOAD_ERR_OK && is_uploaded_file($_FILES["file"]["tmp_name"])) {
        // File uploaded. Get its contents
        echo file_get_contents($_FILES["file"]["tmp_name"]);
    } else if (!empty($_POST["file-text"])) {
        // File not uploaded. Check pasted content
        echo $_POST["file-text"];
    } else {
        echo "Error. File and content not present";
    }
}

?>