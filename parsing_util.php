<?php

function leave_blank_space(&$value, $count) {
	for ($i = 0; $i < $count; $i++) {
		$value .=" ";
	}
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

?>