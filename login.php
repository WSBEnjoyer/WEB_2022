<?php

include("util/auth_util.php");

session_start();

$errors = array();

if ($_POST) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $authUtil = new AuthUtil();

    $authResult = $authUtil->authenticateUser($username, $password);

    if ($authResult) {
        header("Location: index.php");
    } else {
        $errors = array("Невалидно потребителско име или парола");
    }
}

if (isset($_SESSION["just_registered"])) {
    $justRegistered = true;
    unset($_SESSION["just_registered"]);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Влизане</title>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/global_style.css">
    <link rel="stylesheet" href="css/form_style.css">
</head>
<body>
    <main>
        <img class="login-img" src="./resources/login.png">
        <h1>Влизане</h1>
        <form id="login-form" class="form" method="POST">
            <section class="messages-container">
                <?php if(isset($justRegistered)): ?>
                    <section class="message-container success-container">
                        <p class="success-text">Регистрацията е успешна</p>
                    </section>
                <?php endif ?>

                <?php foreach($errors as $error): ?>
                    <section class="message-container error-container">
                        <p class="error-text"><?= $error ?></p>
                    </section>
                <?php endforeach ?>
            </section>

            <section class="input-group">
                <label>Потребителско име</label>
                <input type="text" name="username" required />
            </section>

            <section class="input-group">
                <label>Парола</label>
                <input type="password" name="password" required />
            </section>

            <button class="submit-btn" type="submit">Влизане</button>
        </form>
        <p>Нямате акаунт? <a href="register.php">Регистриране</a></p>
    </main>
</body>
</html>