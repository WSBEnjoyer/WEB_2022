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
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Начало</a>
        </nav>
    </header>
    <main>
        <?php if (isset($success) && $success === true): ?>
        <section class="success">
            Your preferences have been updated successfully.
        </section>
        <?php endif ?>
        <section class="user-prefs">
            <form id="user-prefs-form" method="post">
                <?php foreach($availablePrefs as $prefName => $prefDescription): ?>
                    <section class="user-pref">
                        <label><?= $prefDescription ?></label>
                        <input type="checkbox" name="<?= $prefName ?>" <?= $userPrefs !== null && isset($userPrefs["auto_save_files"]) && $userPrefs["$prefName"] === "true" ? "checked" : "" ?> />
                    </section>
                <?php endforeach ?>

                <button type="submit">Запазване</button>
            </form>
        </section>
    </main>
</body>
</html>