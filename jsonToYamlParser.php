<!-- JSON to YAML Parser -->

<?php
$txt = 
'{
  "version": "3",
"services": {
  "web1": {
    "image": "nginx",
    "blop": {
      "idk": "random"
    }
    "volumes": [
      "./nginx.conf": "/etc/nginx/nginx.conf",
      "./templates": "/etc/nginx/templates"
    ],
    "port": [
      "80": 80
    ],
  }
  "web2": {
    "image": "nginx",
    "volumes": [
      "./nginx.conf": "/etc/nginx/nginx.conf",
      "./templates": "/etc/nginx/templates"
    ],
    "port": [
      "81": 80
    ],
  }
  "web3": {
    "image": "nginx",
    "volumes": [
      "./nginx.conf": "/etc/nginx/nginx.conf",
      "./templates": "/etc/nginx/templates"
    ],
    "port": [
      "82": 80
    ],
  }
}
}';


$txt = cleanInput($txt);
$lines = explode("\n", $txt);

$yamlResult = "";
format($lines, 0, 0, false);

echo $yamlResult;


function format($lines, $level, $from, $isArray) {
	global $yamlResult;

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
		
		leave_blank_space($level*2);

		$line = explode(":", $line);
	
		$key = ltrim($line[0]);
		$key = rtrim($key);
		if ($isArray) {
			$key = "- ".$key;
		}
		
		$value = null;
		if (isset($line[1])) {
			$value = ltrim($line[1]);
			$value = rtrim($value);
		}
		
		if (is_null($value) || empty($value)) {
			if ($i + 1 < count($lines)) {
				$nextLine = $lines[$i+1];
				$nextLine = ltrim($nextLine);
		
				if (str_starts_with($nextLine, "[") ) {
					$yamlResult .= "$key: \n";
					$i = format($lines, $currLevel + 1, $i + 2, true);
				} else if (str_starts_with($nextLine, "{")){
					$yamlResult .= "$key: \n";
					$i = format($lines, $currLevel + 1, $i + 2, false);
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

function count_level($line) {
	$chars = str_split($line);
	$count = 0;
	
	foreach ($chars as $char) {
		if ($char == " ") {
			$count++;
		} else {
			break;
		}
	}
	return $count;
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

function leave_blank_space($count) {
	global $yamlResult;
	for ($i = 0; $i < $count; $i++) {
		$yamlResult .=" ";
	}
}

?>