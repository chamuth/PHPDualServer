<?php

	# CONTAINS FILE OPERATIONS FOR EASIER FUNCTION CALLS

	function Read($filepath)
	{
		try {
			
			$handle = fopen($filepath, "r");
			$content = fread($handle, filesize($filepath));
			fclose($handle);

			//Return the content
			return $content;

		} catch (Exception $e) {
			return false;
		}
	}

	function Create($file, $force)
	{
		if (!$force)
		{
			touch($file);
		}else{
			//indexer/index/index.html
			$separated_list = explode("/", $file);
			$cd = "";
			var_dump($separated_list);
			if (count($separated_list) > 1){
				for ($i=0; $i < sizeof($separated_list); $i++) { 
					$directory = $separated_list[$i];
					$cd = $directory . "/";

					if ($i != sizeof($separated_list) - 1){
						if (!file_exists($cd)){
							mkdir($cd);
						}
					}
				}
			}

			if (file_exists($file) == false)
			{
				//Create the file
				touch ($file);
			}	
		}
	}

	function Write($filepath, $content)
	{
		try{
			$handle = fopen($filepath, "w");
			fwrite($handle, $content);
			fclose($handle);

			return true;
		} catch (Exception $e)
		{
			return false;
		}
	}	

	function Append($filepath, $append)
	{
		try{
			$handle = fopen($filepath, "a");
			fwrite($handle, $append);
			fclose($handle);

			return true;
		} catch (Exception $e)
		{
			return false;
		}
	}
?>