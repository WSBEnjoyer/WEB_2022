<?php

include_once("parsing_util.php");

function performReplacement($currentCase, $newCase, $source) {
    switch($currentCase) {
        case "UPPER_CASE": {
            $source = strtolower($source);
            if($newCase === "kebab-case") {
                $source = str_replace('_', '-', $source);
            }
            if($newCase === "snake_case") {
                $source = str_replace('-', '_', $source);
            }
        } break;
        case "snake_case": {
            if($newCase === "kebab-case") {
                $source = str_replace('_', '-', $source);
            }
            if($newCase === "UPPER_CASE") {
                $source = strtoupper($source);
            }
        } break;
        case "kebab-case": {
            if($newCase === "snake_case") {
                $source = str_replace('-', '_', $source);
            }
            if($newCase === "UPPER_CASE") {
                $source = str_replace('-', '_', $source);
                $source = strtolower($source);
            }
        } break;
        default: break;
    }

    return $source;
}
?>