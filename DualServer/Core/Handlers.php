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
		->setBoolean("boolean1", true)
	     ->setSection("dateContainer", "The time is great")
	     ->setSection("titleSection", "This is the title")

		->setLoop("items", array(1,2,3,4,5,6,7,8))
		->setLoop("rows", array("Chamuth Chamandana", "Nigga nigga", "Framwork of niggerS"));
});