<?php 

class HTTPResponse
{
	public $ResponseText = "";

	function __construct($ResponseText = "")
	{
		//Setup the HTTP Response
		$this->ResponseText = $ResponseText;
	}

	function getResponseText()
	{
		return $this->ResponseText;
	}

	function set($id, $value)
	{
		preg_match_all("/<#(.*?)#>/", $this->ResponseText, $matches);
		$to_replace = "";

		for ($i=0; $i < sizeof($matches[0]); $i++) { 
			if (ltrim(rtrim($matches[1][$i])) == $id)
			{
				$to_replace = $matches[0][$i];
			}
		}

		$this->ResponseText = str_replace($to_replace, $value, $this->ResponseText);

		return $this;
	}

}

 ?>