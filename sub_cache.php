<?php
require_once __DIR__ . '/JSONParser.php';
set_time_limit(0);

$emotes_start = false;
$emotes_body = false;
$emotes_data = false;
$temp_emotes_data = array();
$curkey = "";
$curcode = "";

function objStart($value, $property) {
	global $emotes_body, $emotes_data;
	
	if($emotes_body) {
		$emotes_data = true;
	}
}

function objEnd($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $temp_emotes_data, $curkey, $curcode, $fp;
	
	if($emotes_body) {
		$emotes_data = false;
		$emotes[$curcode] = $temp_emotes_data;
		$output = json_encode($emotes);
		$output = substr($output,1,strlen($output) - 2);
		fwrite($fp, $output . ",");
		//fclose($fp);
		//$fp = fopen(__DIR__ . '/json/sub.json', 'a');
		//if($fp === FALSE) die("File open error!\n");
		$curkey = "";
		$curcode = "";
	}
}

function arrayStart($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $temp_emotes_data, $curkey, $curcode;
	
	// emotes array has started
	if($emotes_start) {
		$emotes_body = true;
		$emotes_data = false;
	}
}

function arrayEnd($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $temp_emotes_data, $curkey, $curcode;
	
	// emotes array has ended
	if($emotes_body) {
		$emotes_start = false;
		$emotes_body = false;
		$emotes_data = false;
	}
}

function property($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $temp_emotes_data, $curkey, $curcode;
	
	if($value == "emotes") {
		$emotes_start = true;
		$emotes_body = false;
		$emotes_data = false;
	} else {
		if($emotes_data) {
			if($value == "id" || $value == "code") $curkey = $value;
			else $curkey = "SKIP";
		}
	}
}

function scalar($value, $property) {
	global $emotes_start, $emotes_body, $emotes_data, $temp_emotes_data, $curkey, $curcode;
	
	if($emotes_data) {
		if($curkey == "id") $temp_emotes_data[$curkey] = (int) $value;
		if($curkey == "code") $curcode = $value;
	}
}

$parser = new JSONParser();

$parser->setArrayHandlers('arrayStart', 'arrayEnd');
$parser->setObjectHandlers('objStart', 'objEnd');
$parser->setPropertyHandler('property');
$parser->setScalarHandler('scalar');

if(php_sapi_name() !== 'cli') echo "<pre>";

$output = "{";
$fp = fopen(__DIR__ . '/json/sub.json', 'w');
if($fp === FALSE) die("File clear error!\n");
fwrite($fp, $output);
fclose($fp);
echo "File clear done!\n";

$fp = fopen(__DIR__ . '/json/sub.json', 'a');
if($fp === FALSE) die("File open error!\n");

echo "Parsing sub emoticon json...\n";
$parser->parseDocument(__DIR__ . '/json/subscriber.json');
echo "Parse done!\n";

//$output = json_encode($emotes);
$output = '"EOF_NULL_NA":{"id":0}}';
fwrite($fp, $output);
echo "Save done!\n";
fclose($fp);

if(php_sapi_name() !== 'cli') echo "</pre>";
