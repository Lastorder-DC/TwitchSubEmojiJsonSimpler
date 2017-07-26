<?php
require_once __DIR__ . '/JSONParser.php';
set_time_limit(0);

$emotes_start = false;
$emotes_body = false;
$emotes_data = false;
$emotes = array();
$temp_emotes_data = array();
$curkey = "";

function objStart($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $emotes, $temp_emotes_data, $curkey;
	
	if($emotes_body) {
		$emotes_data = true;
	}
}

function objEnd($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $emotes, $temp_emotes_data, $curkey;
	
	if($emotes_body) {
		$emotes_data = false;
		$emotes[$temp_emotes_data["code"]] = $temp_emotes_data;
		$curkey = "";
	}
}

function arrayStart($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $emotes, $temp_emotes_data, $curkey;
	
	// emotes array has started
	if($emotes_start) {
		$emotes_body = true;
		$emotes_data = false;
	}
}

function arrayEnd($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $emotes, $temp_emotes_data, $curkey;
	
	// emotes array has ended
	if($emotes_body) {
		$emotes_start = false;
		$emotes_body = false;
		$emotes_data = false;
	}
}

function property($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $emotes, $temp_emotes_data, $curkey;
	
	if($value == "emotes") {
		$emotes_start = true;
		$emotes_body = false;
		$emotes_data = false;
	} else {
		if($emotes_data) {
			$curkey = $value;
		}
	}
}

function scalar($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $emotes, $temp_emotes_data, $curkey;
	
	if($emotes_data) {
		$temp_emotes_data[$curkey] = $value;
	}
}

$parser = new JSONParser();

$parser->setArrayHandlers('arrayStart', 'arrayEnd');
$parser->setObjectHandlers('objStart', 'objEnd');
$parser->setPropertyHandler('property');
$parser->setScalarHandler('scalar');

echo "<pre>Parsing sub emoticon json...\n";
$parser->parseDocument(__DIR__ . '/json/subscriber.json');
echo "Parse done!\n";
$output = json_encode($emotes);

$fp = fopen(__DIR__ . '/json/sub.json', 'w');
if($fp === FALSE) echo "File save error!\n";
else {
    fwrite($fp, $output);
    fclose($fp);
    echo "Save done!\n";
}
echo "</pre>";