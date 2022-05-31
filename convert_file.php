<?php

include_once("parsing_util.php");
include("yamlToJsonParser.php");
include("jsonToYamlParser.php");

$source = "";
$result = "";

if ($_POST) {
    if ($_FILES["file"]["error"] === UPLOAD_ERR_OK && is_uploaded_file($_FILES["file"]["tmp_name"])) {
        $source = file_get_contents($_FILES["file"]["tmp_name"]);
    } else if (!empty($_POST["file-text"])) {
        $source = $_POST["file-text"];
    } else {
        // echo "Error. File and content not present";
    }

    // Create replacement options array

    $replacementOptions = array();

    for ($i = 1; ; $i++) {
        if (!isset($_POST["repl-option-first-" . $i])) {
            break;
        }

        $replacementOptions[$_POST["repl-option-first-" . $i]] = array($_POST["repl-option-type-" . $i], $_POST["repl-option-second-" . $i]);
    }

    if ($_POST["conversion-type"] === "yaml-to-json") {
        $result = getJsonFromYaml($source);
    } else if ($_POST["conversion-type"] === "json-to-yaml") {
        $result = getYamlFromJson($source);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Резултат от конвертиране</title>
    <link rel="stylesheet" href="css/convert_result.css">
</head>
<body>
    <main>
        <h1>Резултат от конвертиране</h1>
        <section class="files">
            <section class="file-container">
                <h3>Изходен файл</h3>
                <section class="file-text"><?= $source ?></section>
            </section>
            <section class="file-container">
                <h3>Резултат</h3>
                <section class="file-text"><?= $result ?></section>
            </section>
        </section>
    </main>
</body>
</html>