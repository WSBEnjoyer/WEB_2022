<?php

include_once("parsing_util.php");
include("yamlToJsonParser.php");
include("jsonToYamlParser.php");

if ($_POST) {
    if ($_FILES["file"]["error"] === UPLOAD_ERR_OK && is_uploaded_file($_FILES["file"]["tmp_name"])) {
        if ($_POST["conversion-type"] === "yaml-to-json") {
            echo "<textarea rows='40' cols='60'>" . getJsonFromYaml(file_get_contents($_FILES["file"]["tmp_name"])) . "</textarea>";
        } else if ($_POST["conversion-type"] === "json-to-yaml") {
            echo "<textarea rows='40' cols='60'>" . getYamlFromJson(file_get_contents($_FILES["file"]["tmp_name"])) . "</textarea>";
        }
    } else if (!empty($_POST["file-text"])) {
        if ($_POST["conversion-type"] === "yaml-to-json") {
            echo "<textarea rows='40' cols='60'>" . getJsonFromYaml($_POST["file-text"]) . "</textarea>";
        } else if ($_POST["conversion-type"] === "json-to-yaml") {
            echo "<textarea rows='40' cols='60'>" . getYamlFromJson($_POST["file-text"]) . "</textarea>";
        }
    } else {
        // echo "Error. File and content not present";
    }
}

?>