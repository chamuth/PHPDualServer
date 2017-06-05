<?php 

require_once "SimpleHTMLDOM.php";
require_once "HTTPRequest.php";

function PullDependencies($key, $result)
{
	//Get every hyper reference
	$html = str_get_html($result);
	foreach ($html->find('link') as $element) {
		if ($element->rel == "stylesheet")
		{
			//Get the style sheet to the head
			$content = Request($key, $element->href);
			$html->find("head", 0)->innertext = "<style>" . $content . "</style>" . $html->find("head", 0)->innertext;
		}
	}

	foreach ($html->find('script') as $element) {
		//Get the style sheet to the head
		$content = Request($key, $element->src);
		$html->find("body", 0)->innertext = $html->find("body", 0)->innertext . "<script type=\"text/javascript\">" . $content . "</script>" ;
	}

	return $html->__toString();
}

 ?>