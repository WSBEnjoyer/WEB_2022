<?php

class UserValidation {
    private static $usernameMinLength = 3;
    private static $passwordMinLength = 3;
    private static $emailRegex = "/^[a-zA-Z0-9.-_]+@([a-zA-Z0-9-]+\.)+[a-zA-Z0-9]+$/";
    
    public static function validateUserData($username, $email, $password, $passwordRepeat) {
        $errors = array();

        if (mb_strlen($username) < UserValidation::$usernameMinLength) {
            array_push($errors, "Потребителското име трябва да бъде поне " . UserValidation::$usernameMinLength . " символа");
        }

        if (preg_match(UserValidation::$emailRegex, $email) === 0) {
            array_push($errors, "Невалиден email");
        }

        if (mb_strlen($password) < UserValidation::$passwordMinLength) {
            array_push($errors, "Паролата трябва да бъде поне " . UserValidation::$passwordMinLength . " символа");
        }

        if ($password !== $passwordRepeat) {
            array_push($errors, "Паролите трябва да съвпадат");
        }

        return $errors;
    }
}

?>