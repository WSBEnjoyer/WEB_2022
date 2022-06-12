<?php

include("util/auth.php");
include("util/conversion_util.php");
include("util/user_prefs_util.php");
include_once("parsing_util.php");
include("yamlToJsonParser.php");
include("jsonToYamlParser.php");
include("case_replacer.php");

$source = "";
$result = "";
$comment = "";
$fileName = "";
$username = $_SESSION["user"];

$userPrefsUtil = new UserPrefsUtil();
$conversionUtil = new ConversionUtil();

if ($_POST) {
    if ($_FILES["file"]["error"] === UPLOAD_ERR_OK && is_uploaded_file($_FILES["file"]["tmp_name"])) {
        $source = file_get_contents($_FILES["file"]["tmp_name"]);
        $fileName = $_FILES["file"]["name"];
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

    $currentCase = "";
    $newCase = "";

    if (!empty($_POST["case-from"])) {
        $currentCase = $_POST["case-from"];
    }

    if (!empty($_POST["case-to"])) {
        $newCase = $_POST["case-to"];
    }

    if (!empty($_POST["comment-text"])) {
        $comment = $_POST["comment-text"];
    }

    $userPrefs = $userPrefsUtil->getUserPrefs($username);

    $conversion_type = $_POST["conversion-type"];

    if($userPrefs["auto_save_files"]==="true") {
        $conversionUtil->recordConversion($comment, $fileName, $conversion_type);
    }

    $result = $source;

    if(!empty($currentCase) && !empty($newCase)) {
        $result = performReplacement($currentCase, $newCase, $source);
    }

    if ($conversion_type === "yaml-to-json") {
        $result = getJsonFromYaml($result);
    } else if ($conversion_type === "json-to-yaml") {
        $result = getYamlFromJson($result);
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
    <header>
        <nav>
            <a href="/">Начало</a>
        </nav>
    </header>
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