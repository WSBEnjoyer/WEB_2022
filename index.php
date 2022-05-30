<?php

include("util/auth.php");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Конвертиране</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <header>
        <a href="logout.php">Изход</a>
    </header>
    <main>
        <form id="file-upload-form" action="convert_file.php" method="POST" enctype="multipart/form-data">
            <label>Изберете тип на конвертиране:</label>
            <select name="conversion-type" id="conversion-type">
                <option value="yaml-to-json">YAML -> JSON</option>
                <option value="json-to-yaml">JSON -> YAML</option>
            </select>

            <label>Изберете файл:</label>
            <input type="file" name="file" />

            <h2>ИЛИ</h2>

            <label>Поставете съдържанието на файла:</label>
            <textarea id="file-text" name="file-text" rows="20" cols="50"></textarea>

            <button type="submit">Конвертиране</button>
        </form>
    </main>
</body>
</html>