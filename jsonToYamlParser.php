<!-- JSON to YAML Parser -->

<?php

include_once("parsing_util.php");

$yamlResult = "";

function getYamlFromJson($text) {
	global $yamlResult;
	$yamlResult = "";

	$text = cleanInput($text);
	$lines = explode("\n", $text);

	formatJsonToYaml($lines, 0, 0, false);

	return $yamlResult;
}

function formatJsonToYaml($lines, $level, $from, $isArray) {
	global $yamlResult;
	global $replacementOptions;

	$i = $from;
	$currLevel = $level;
	
	for ($i; $i < count($lines); $i++) {
		$line = $lines[$i];
		
		if ($currLevel < $level) {
			$i--;
			break;
		}
		if (is_numeric(strpos($line, "{")) || is_numeric(strpos($line, "["))) {
			$currLevel++;
			continue;
		}
		
		if (is_numeric(strpos($line, "}")) || is_numeric(strpos($line, "]"))) {
			$currLevel--;
			continue;
		}
		
		leave_blank_space($yamlResult, $level*2);

		$line = explode(":", $line);
	
		$key = ltrim($line[0]);
		$key = rtrim($key);
		
		$value = null;
		if (isset($line[1])) {
			$value = ltrim($line[1]);
			$value = rtrim($value);
		}

		if (isset($replacementOptions) && isset($replacementOptions[$key])) {
			if ($replacementOptions[$key][0] === "replace-tag") {
				$key = $replacementOptions[$key][1];
			} else if ($replacementOptions[$key][0] === "replace-value") {
				$value = $replacementOptions[$key][1];
			}
		}

		if ($isArray) {
			$key = "- ".$key;
		}
		
		if (is_null($value) || empty($value)) {
			if ($i + 1 < count($lines)) {
				$nextLine = $lines[$i+1];
				$nextLine = ltrim($nextLine);
		
				if (str_starts_with($nextLine, "[") ) {
					$yamlResult .= "$key: \n";
					$i = formatJsonToYaml($lines, $currLevel + 1, $i + 2, true);
				} else if (str_starts_with($nextLine, "{")){
					$yamlResult .= "$key: \n";
					$i = formatJsonToYaml($lines, $currLevel + 1, $i + 2, false);
				} else {
					$yamlResult .= "$key\n";
				}
			} 
			else {
				$yamlResult .= "$key\n";
			}
		}
		else {
			if ($value == "null") {
				$yamlResult .= "$key: \n";
				continue;
			}

			$yamlResult .= "$key: $value\n";
		}
	}
	return $i;
}

function cleanInput($txt) {
	$txt = ltrim($txt, '{');
	$txt = rtrim($txt, '}');
	$txt = str_replace('{', "\n{\n", $txt);
	$txt = str_replace('[', "\n[\n", $txt);
	$txt = str_replace(']', "\n]\n", $txt);
	$txt = str_replace('}', "\n}\n", $txt);
	$txt = str_replace(',', "\n", $txt);
	$txt = removeQuotes($txt);
	$txt = removeComma($txt);
	$txt = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $txt);
	return $txt;
}
function removeQuotes($string) {
	return str_replace('"', '', $string);
}

function removeComma($string) {
	return str_replace(',', '', $string);
}

?>