<!-- YAML to Properties Parser -->

<?php

include_once("parsing_util.php");

$propertiesResult = "";

function getPropertiesFromYaml($text) {
	global $propertiesResult;
	$propertiesResult = "";

	$lines = explode("\n", $text);

	formatYamlToProperties($lines, 0, 0, "", -1);

	return $propertiesResult;
}

function formatYamlToProperties($lines, $level, $from, $keyPrefix, $arrayCounter) {
	global $propertiesResult;
	global $replacementOptions;
	
	for ($i = $from; $i < count($lines); $i++) {
		$line = $lines[$i];
		
		$currLevel = count_level($line);
		if ($currLevel != $level) {
			$i--;
			break;
		}
		
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
				}
				
                $newKeyPrefix = (!empty($keyPrefix) ? $keyPrefix . "." : "") . $key;

				$endIn = formatYamlToProperties($lines, $currLevel + 2, $i + 1, $newKeyPrefix, ($array === true) ? 0 : -1);
				
				$i = $endIn;
			} 
			else {
				$propertiesResult .= ((!empty($keyPrefix)) ? "$keyPrefix." : "") . "$key \n";
			}
		}
		else {
            $propertiesResult .= (!empty($keyPrefix)) ? "$keyPrefix." : "";

            if ($arrayCounter >= 0) {
                $propertiesResult .= "$arrayCounter=$key\n";

                $arrayCounter++;
            } else {
                $propertiesResult .= "$key=$value\n";
            }
		}
	}
	return $i;
}

?>