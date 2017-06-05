<?php 

require_once dirname(__FILE__) . "/HTTPResponse.php";

class Handlers
{
	public static $Handles = array();

	public static function AddHandler ($key, $value)
	{
		Handlers::$Handles[$key] = $value;
	}

	public static function Handle ($key, $Response)
	{
		return (Handlers::$Handles[$key])($key, $Response);
	}
}


// Add your handlers here
Handlers::AddHandler("index.html", function($request,HTTPResponse $response)
{
	return	$response
	     ->set("dateContainer", "The time is great")
	     ->set("titleSection", "This is the title");
});