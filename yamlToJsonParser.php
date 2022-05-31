<!-- YAML to JSON Parser -->

<?php

include_once("parsing_util.php");

$jsonResult = "";

function getJsonFromYaml($text) {
	global $jsonResult;
	$jsonResult = "";

	$lines = explode("\n", $text);

	formatYamlToJson($lines, 0, 0);

	return $jsonResult;
}

function formatYamlToJson($lines, $level, $from) {
	global $jsonResult;
	global $replacementOptions;
	
	for ($i = $from; $i < count($lines); $i++) {
		$line = $lines[$i];
		
		$currLevel = count_level($line);
		if ($currLevel != $level) {
			$i--;
			break;
		}
		leave_blank_space($jsonResult, $currLevel);
		
		
		$line = explode(":", $line);
		
		$key = ltrim($line[0]);
		$key = rtrim($key);
		$value = ltrim($line[1]);
		$value = rtrim($value);
		
		if (str_starts_with($key, "-")) {
			$key = str_replace('-', '', $key);
			$key = ltrim($key);
		}

		if (isset($replacementOptions) && isset($replacementOptions[$key])) {
			if ($replacementOptions[$key][0] === "replace-tag") {
				$key = $replacementOptions[$key][1];
			} else if ($replacementOptions[$key][0] === "replace-value") {
				$value = $replacementOptions[$key][1];
			}
		}
		
		if (!isset($value) || empty($value)) {
			$nextLine = $lines[$i+1];
			if ($i + 1 < count($lines) && count_level($nextLine) > $currLevel) {
				$array = false;
                $nextLine = ltrim($nextLine);
				if (str_starts_with($nextLine, "-") ) {
					$array = true;
					$jsonResult .= "\"$key\": [\n";
				} else {
					$jsonResult .= "\"$key\": {\n";
				}
				
				$endIn = formatYamlToJson($lines, $currLevel + 2, $i + 1);
				leave_blank_space($jsonResult, $currLevel);
				if($array) {
					$jsonResult .= "],\n";
				} else {
					$jsonResult .= "}\n";
				}
				
				$i = $endIn;
			} 
			else {
				$jsonResult .= "\"$key\" \n";
			}
		}
		else {
			$jsonResult .= "\"$key\": ";
			if (is_numeric($value) || isBoolean($value)) {
				$jsonResult .= "$value";
			} else {
				$jsonResult .= "\"$value\"";
			}
			if ($i + 1 < count($lines) && count_level($lines[$i+1]) == $currLevel) {
				$jsonResult .= ",";
			}
			$jsonResult .= "\n";
		}
	}
	return $i;
}

function isBoolean($value) {
	$value = strtolower($value);
	if ($value == "true" || $value == 1 || $value == "false" || $value == 0) {
		return true;
	}
	
	return false;
}

?>