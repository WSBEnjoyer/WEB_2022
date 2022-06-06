<?php

class PropertiesUtil {
    private $propertiesFilePath = __DIR__ . "/../conf/properties.json";

    public function readProperties() {
        if (!file_exists($this->propertiesFilePath)) {
            var_dump($this->propertiesFilePath);
            return null;
        }

        $propertiesFile = fopen($this->propertiesFilePath, "r") or die($this->unableToOpenFileError);
        $properties = json_decode(fread($propertiesFile, filesize($this->propertiesFilePath)), true /* associative */);
        fclose($propertiesFile);

        return $properties;
    }
}

?>