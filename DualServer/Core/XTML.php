<?php 

require_once "FileOperations.php";

class XTML
{
	public static function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0)
		{
			return true;
		}

		return (substr($haystack, 0, $length) === $needle);
	}

	public static function GetSections($content_str)
	{	
		preg_match_all("/<#(.*?)#>/is", $content_str, $out, PREG_PATTERN_ORDER);

		for ($i=0; $i < sizeof($out[1]); $i++) { 
			$out[1][$i] = trim($out[1][$i]);
		}
		
		return array($out[0], $out[1]);
	}

	public static function ReplaceXTML($result, $sections, $request)
	{
		if ($request == "")
			$request = "index.html";

		$dots = explode(".", $request);
		$extension = $dots[sizeof($dots) - 1];
		unset($dots[sizeof($dots) - 1]);
		$filename = implode("", $dots);

		$resultant_string = $result;

		$matched_string = $sections[0];
		$section_string = $sections[1];

		for ($i=0; $i < sizeof($matched_string); $i++) { 
			$section_file = ("../" . $filename . "#" . $section_string[$i] . "." . $extension);

			if (file_exists($section_file))
			{
				$section_content= Read($section_file);

				if (!XTML::startsWith(trim($section_content), "<?php"))
				{
					$resultant_string = preg_replace("/" . $matched_string[$i] . "/", $section_content, $resultant_string);
				}
			}
		}

		return $resultant_string;
	}


}

 ?>