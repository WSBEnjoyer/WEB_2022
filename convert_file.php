<?php

include("util/auth.php");
include("util/conversion_util.php");
include("util/user_prefs_util.php");
include_once("parsing_util.php");
include("yamlToJsonParser.php");
include("yamlToPropertiesParser.php");
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

    $sourceConverted = $source;

    if(!empty($currentCase) && !empty($newCase)) {
        $sourceConverted = performReplacement($currentCase, $newCase, $source);
    }

    if ($conversion_type === "yaml-to-json") {
        $result = getJsonFromYaml($sourceConverted);
    } else if ($conversion_type === "json-to-yaml") {
        $result = getYamlFromJson($sourceConverted);
    } else if ($conversion_type === "yaml-to-properties") {
        $result = getPropertiesFromYaml($sourceConverted);
    }

    if($userPrefs !== null && isset($userPrefs["auto_save_files"]) && $userPrefs["auto_save_files"]==="true") {
        $fileSaveDir = __DIR__ . "/saved_files/";
        $saveFileName = $username . "_" . time();

        if ($conversion_type === "yaml-to-json") {
            $saveFileName .= ".json";
        } else if ($conversion_type === "json-to-yaml") {
            $saveFileName .= ".yaml";
        } else if ($conversion_type === "yaml-to-properties") {
            $saveFileName .= ".properties";
        }

        $saveFilePath = $fileSaveDir . $saveFileName;

        $conversionUtil->recordConversion($comment, $fileName, $saveFileName, $conversion_type);

        // Save result to file
        if (!is_dir($fileSaveDir)) {
            mkdir($fileSaveDir);
        }

        $saveFile = fopen($saveFilePath, "w") or die("Unable to open or create file");

        fwrite($saveFile, $result);
        fclose($saveFile);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>???????????????? ???? ????????????????????????</title>
    <link rel="stylesheet" href="css/convert_result.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">????????????</a>
        </nav>
    </header>
    <main>
        <h1>???????????????? ???? ????????????????????????</h1>
        <section class="files">
            <section class="file-container">
                <h3>?????????????? ????????</h3>
                <section class="file-text"><?= $source ?></section>
            </section>
            <section class="file-container">
                <h3>????????????????</h3>
                <section class="file-text"><?= $result ?></section>
            </section>
        </section>
    </main>
</body>
</html>