<?php
$txt = 
"version: '3'
services:
  web1:
    image: nginx
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
format($lines, 0, 0);


function format($lines, $level, $from) {
	for ($i = $from; $i < count($lines); $i++) {
		$line = $lines[$i];
		$currLevel = count_level($line);
	
		if ($currLevel != $level) {
			$i--;
			break;
		}
		
		leave_blank_space($currLevel);
		
		$line = explode(":", $line);
		$key = trim(preg_replace('/\s\s+/', ' ', $line[0]));
		
		if (str_starts_with($key, "-")) {
			$key = str_replace('- ', '', $key);
		}
		
		if (!isset($line[1]) || empty($line[1]) || strlen($line[1]) == 1) {
			$nextLine = $lines[$i+1];
			$nextLine = trim(preg_replace('/\s\s+/', ' ', $nextLine));
			if ($i + 1 < count($lines) && count_level($lines[$i+1]) > $currLevel) {
				$array = false;
				if (str_starts_with($nextLine, "-") ) {
					$array = true;
					echo "\"$key\": [\n";
				} else {
					echo "\"$key\": {\n";
				}
				
				$endIn = format($lines, $currLevel + 2, $i + 1);
				leave_blank_space($currLevel);
				if($array) {
					echo "],\n";
				} else {
					echo "}\n";
				}
				
				$i = $endIn;
			} 
			else {
			echo "\"$key\" \n";
			}
		}
		else {
			$value = $line[1];
			$value = trim(preg_replace('/\s\s+/', ' ', $value));
			echo "\"$key\": ";
			if (is_numeric($value) || isBoolean($value)) {
				echo "$value";
			} else {
				echo "\"$value\"";
			}
			if ($i + 1 < count($lines) && count_level($lines[$i+1]) == $currLevel) {
				echo ",";
			}
			echo "\n";
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
	for ($i = 0; $i < $count; $i++) {
		echo " ";
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