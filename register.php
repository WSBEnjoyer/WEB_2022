<?php

include("util/auth_util.php");
include("util/user_validation.php");

session_start();

$errors = array();

if ($_POST) {
    $errors = UserValidation::validateUserData($_POST["username"], $_POST["email"], $_POST["password"], $_POST["password_repeat"]);
    
    if (!$errors) {
        $authUtil = new AuthUtil();
        $registrationResult = $authUtil->registerUser($_POST["username"], $_POST["email"], $_POST["password"]);

        if (is_array($registrationResult)) {
            // An array was returned and it contains registration errors
            $errors = $registrationResult;
        } else if ($registrationResult === true) {
            $_SESSION["just_registered"] = true;
            header("Location: login.php");
        } else if ($registrationResult === false) {
            $errors = array("Възникна грешка при регистрация");
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Регистриране</title>
    <link rel="stylesheet" href="css/global_style.css">
    <link rel="stylesheet" href="css/form_style.css">
</head>
<body>
    <main>
        <h1>Регистриране</h1>
        <form id="register-form" class="form" method="POST">
            <section class="messages-container">
                <?php foreach($errors as $error): ?>
                    <section class="message-container error-container">
                        <p class="error-text"><?= $error ?></p>
                    </section>
                <?php endforeach; ?>
            </section>

            <section class="input-group">
                <label>Потребителско име</label>
                <input type="text" name="username" value="<?= $_POST && $_POST["username"] ? $_POST["username"] : '' ?>" required />
            </section>

            <section class="input-group">
                <label>Имейл</label>
                <input type="email" name="email" value="<?= $_POST && $_POST["email"] ? $_POST["email"] : '' ?>" required />
            </section>

            <section class="input-group">
                <label>Парола</label>
                <input type="password" name="password" required />
            </section>

            <section class="input-group">
                <label>Потвърждаване на парола</label>
                <input type="password" name="password_repeat" required />
            </section>

            <button type="submit">Регистриране</button>
        </form>
        <p>Вече имате акаунт? <a href="login.php">Влизане</a></p>
    </main>
</body>
</html>