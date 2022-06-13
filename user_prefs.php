<?php

include("util/auth.php");
include("util/user_prefs_util.php");

$userPrefsUtil = new UserPrefsUtil();
$availablePrefs = $userPrefsUtil->getAvailablePreferences();
$username = $_SESSION["user"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newUserPrefs = array();

    foreach ($availablePrefs as $prefName => $prefDescription) {
        $newUserPrefs[$prefName] = (isset($_POST[$prefName]) && $_POST[$prefName] === "on") ? "true" : "false";
    }

    $userPrefsUtil->saveUserPrefs($username, $newUserPrefs);
    $success = true;
}

$userPrefs = $userPrefsUtil->getUserPrefs($username);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Предпочитания</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Назад</a>
        </nav>
    </header>
    <main>
        <?php if (isset($success) && $success === true): ?>
        <section class="success">
            Предпочитанията Ви бяха променени успешно.
        </section>
        <?php endif ?>

        <img class="settings-img" src="./resources/settings-icon.png">
        <h1>Предпочитания</h1>

        <section class="user-prefs">
            <form id="user-prefs-form" method="post">
                <?php foreach($availablePrefs as $prefName => $prefDescription): ?>
                    <section class="user-pref">
                        <label><?= $prefDescription ?></label>
                        <input type="checkbox" name="<?= $prefName ?>" <?= ($userPrefs !== null && isset($userPrefs["$prefName"]) && $userPrefs["$prefName"] === "true") ? "checked" : "" ?> />
                    </section>
                <?php endforeach ?>

                <button class="submit-btn" type="submit">Запазване</button>
            </form>
        </section>
    </main>
</body>
</html>