<?php 

function ServerRequest($key, $request){
	$url = ("http://www.dualserver.tk/GET.php?key=$key&request=" . urlencode(str_replace("/", "#", $request)));

	$options = array(
		'http' => array(
			'method' => 'GET'
		)
	);

	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	return $result;
}

function PullXTML($key)
{
	$url = ("http://www.dualserver.tk/XTML.php?key=" . urlencode($key));
	$options = array(
		"http" => array(
			"method" => "GET"
		)
	);

	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	return $result;
}
?>