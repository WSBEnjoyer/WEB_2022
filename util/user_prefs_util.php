<?php

class UserPrefsUtil {
    private $availablePreferences = array(
        "auto_save_files" => "Автоматично запазване на файлове след конвертиране"
    );

    private $unableToOpenFileError = "Unable to open user preferences file";
    private $userPrefsSaveFolderName = "user_prefs";
    private $userPrefsSaveFolderPath;

    function __construct() {
        $this->userPrefsSaveFolderPath = __DIR__ . "/../" . $this->userPrefsSaveFolderName;
    }

    public function getAvailablePreferences() {
        return $this->availablePreferences;
    }

    public function saveUserPrefs($username, $prefs) {
        $prefs["username"] = $username;

        $this->writePrefsToUserFile($prefs);
    }

    public function getUserPrefs($username) {
        $userPrefsFilePath = $this->userPrefsSaveFolderPath . "/" . $username . ".prefs";

        if (!file_exists($userPrefsFilePath)) {
            return null;
        }

        $userPrefsFile = fopen($userPrefsFilePath, "r") or die($this->unableToOpenFileError);
        $userPrefs = json_decode(fread($userPrefsFile, filesize($userPrefsFilePath)), true /* associative */);
        fclose($userPrefsFile);

        return $userPrefs;
    }

    private function writePrefsToUserFile($prefs) {
        if (!is_dir($this->userPrefsSaveFolderPath)) {
            mkdir($this->userPrefsSaveFolderPath);
        }

        $username = $prefs["username"];
        
        $userPrefsFilePath = $this->userPrefsSaveFolderPath . "/" . $username . ".prefs";
        $userPrefsFile = fopen($userPrefsFilePath, "w") or die($this->unableToOpenFileError);

        fwrite($userPrefsFile, json_encode($prefs));
        fclose($userPrefsFile);
    }
}

?>