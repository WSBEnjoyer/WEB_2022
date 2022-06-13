<?php

include("util/auth.php");
include("util/user_prefs_util.php");

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
        <nav>
            <a href="user_prefs.php">Предпочитания</a>
            <a href="conversion_history.php">История</a>
            <a href="logout.php">Изход</a>
        </nav>
    </header>
    <main>
        <img class="title-img" src="./resources/title-icon.png">
        <h1 class="title">
            JSON ⇆ YAML ⇆ Properties Parser
        </h1>
        <form id="file-upload-form" action="convert_file.php" method="POST" enctype="multipart/form-data">
            <label>Изберете тип на конвертиране:</label>
            <select name="conversion-type" id="conversion-type">
                <option value="yaml-to-json">YAML -> JSON</option>
                <option value="json-to-yaml">JSON -> YAML</option>
            </select>

            <label>Изберете файл:</label>
            <input type="file" name="file" />

            <h3>- ИЛИ -</h3>

            <label>Поставете съдържанието на файла:</label>
            <textarea id="file-text" name="file-text" rows="20" cols="50"></textarea>

            <label class="comments-title">Добавете коментар</label>
            <textarea id="comment-text" name="comment-text" rows="2" cols="50"></textarea>

            <h2>Опции</h2>
            <section class="options">
                <section class="replacement">
                    <label>Замяна на тагове и стойности</label>
                    <section class="replacement-option-creation">
                        <select class="options-value" id="replacement-type">
                            <option value="replace-tag">Замяна на таг</option>
                            <option value="replace-value">Замяна на стойност</option>
                        </select>
                        <button class="insert-btn" id="add-replacement-option-btn">Добавяне</button>
                    </section>
                    <section id="replacement-options-container">
                        <!-- Newly created replacement options will be added here -->
                    </section>
                </section>
                <section class="cases">
                    <label>(Оptional) Изберете case за смяна</label>
                    <section id="case-container">
                        <section>
                            <label>Oт</label>
                            <select name="case-from" id="case-from">
                                <option value="">None</option>
                                <option value="snake_case">snake_case</option>
                                <option value="kebab-case">kebab-case</option>
                                <option value="UPPER_CASE">UPPER_CASE</option>
                            </select>
                        </section>
                        <section>
                            <label>До</label>
                            <select name="case-to" id="case-to">
                                <option value="">None</option>
                                <option value="snake_case">snake_case</option>
                                <option value="kebab-case">kebab-case</option>
                                <option value="UPPER_CASE">UPPER_CASE</option>
                            </select>
                        </section>
                    </section>
                </section>
            </section>

            <button class="submit-btn" id="submit-btn" type="submit">Конвертиране</button>
        </form>
    </main>

    <script src="js/conversion_configuration.js"></script>
</body>

</html>