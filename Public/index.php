<?php

# REQUIRE THE DEPENDENCIES
require_once dirname(dirname(__FILE__)) . "/DualServer/Core/FileOperations.php";
require_once dirname(dirname(__FILE__)) . "/DualServer/Core/HTTPRequest.php";
require_once dirname(dirname(__FILE__)) . "/DualServer/Core/HTTPResponse.php";
require_once dirname(dirname(__FILE__)) . "/DualServer/Core/XTML.php";
require_once dirname(dirname(__FILE__)) . "/DualServer/Core/Handlers.php";


function endsWith($haystack, $needle)
{
	$length = strlen($needle);
	if ($length == 0)
	{
		return true;
	}

	return (substr($haystack, -$length) === $needle);
}


function GetSystemExtension()
{
	$out = array();
	$file = fopen("../DualServer/Core/mime.types", "r");

	while(($line = fgets($file)) !== false)
	{
		$line = trim(preg_replace('/#.*/', '', $line));

		if (!$line) continue;

		$parts = preg_split('/\s+/', $line);

		if (count($parts) == 1)
			continue;
		
		$type = array_shift($parts);

		foreach ($parts as $part)
			$out[$part] = $type;
	}

	fclose($file);

	return $out;
}

$request_uri = $_SERVER["REQUEST_URI"];

//Get the search subject
$config_path = "server-config.json";

if (file_exists($config_path) == true)
{
	$json_content = Read($config_path);
	//Decode the JSON out of the content
	$json = (json_decode($json_content, true));
	
	$key = $json["key"];
	$request = str_replace($json["server"], "" , $request_uri);

	//SET THE MIME TYPE FOR WORKABILITY
	$extension = GetSystemExtension();

	if (isset(pathinfo($request)["extension"]) && isset($extension[pathinfo($request)["extension"]]))
		header("Content-Type: " . $extension[pathinfo($request)["extension"]]);
	else
		header("Content-Type: text/html");

	$result_json = json_decode(ServerRequest($key, $request), true);
	$result = $result_json["content"];

	// Apply the template implementations
	$sections = XTML::GetSections($result);

	$result = XTML::ReplaceXTML($result, $sections, $request);

	$handle_request = "";

	if ($request == "")
	{
		$handle_request = "index.html";
	}

	//Handle via PHP
	$ResultResponse = new HTTPResponse($result);
	$result = Handlers::Handle(
		$handle_request, $ResultResponse
	)->getResponseText();

	switch ($result_json["error"])
	{
		case 1:
			echo Read("../DualServer/Docs/key-not-found.html");
		break;
		case 2:
			echo Read("../DualServer/Docs/file-not-found.html");
		break;
		case 0:
			echo $result;
		break;
		default:
			echo Read("../DualServer/Docs/file-not-found.html");
	}
}else{
	echo Read("../DualServer/Docs/config-not-found.html");
}
