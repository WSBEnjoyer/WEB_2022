<!-- YAML to JSON Parser -->

<?php
$txt = 
"version: '3'
services:
  web1:
    image: nginx
    blop: 
      idk: random
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf:ro
      - ./templates:/etc/nginx/templates
    port:
      - 80:80
  web2: 
    image: nginx
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf:ro
      - ./templates:/etc/nginx/templates
    port:
      - 81:80
  web3:
    image: nginx
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf:ro
      - ./templates:/etc/nginx/templates
    port:
      - 82:80";

$lines = explode("\n", $txt);
$jsonResult = "";
format($lines, 0, 0);

echo $jsonResult;


function format($lines, $level, $from) {
	global $jsonResult;
	
	for ($i = $from; $i < count($lines); $i++) {
		$line = $lines[$i];
		
		$currLevel = count_level($line);
		if ($currLevel != $level) {
			$i--;
			break;
		}
		leave_blank_space($currLevel);
		
		
		$line = explode(":", $line);
		
		$key = ltrim($line[0]);
		$key = rtrim($key);
		$value = ltrim($line[1]);
		$value = rtrim($value);
		
		if (str_starts_with($key, "-")) {
			$key = str_replace('-', '', $key);
			$key = ltrim($key);
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
				
				$endIn = format($lines, $currLevel + 2, $i + 1);
				leave_blank_space($currLevel);
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

function leave_blank_space($count) {
	global $jsonResult;
	for ($i = 0; $i < $count; $i++) {
		$jsonResult .=" ";
	}
}

function isBoolean($value) {
	$value = strtolower($value);
	if ($value == "true" || $value == 1 || $value == "false" || $value == 0) {
		return true;
	}
	
	return false;
}

?>