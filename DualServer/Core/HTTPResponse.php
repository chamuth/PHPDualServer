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

	function setSection($id, $value)
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

	function setBoolean($id, bool $boolean)
	{
		// Get everything between <= and =>
		preg_match_all("/<\=(.*?)\=>/si", $this->ResponseText, $matches);

		for ($i=0; $i < sizeof($matches[0]); $i++) 
		{
			$match = $matches[1][$i];
			$actual_text = $matches[0][$i];

			$match = ltrim($match);

			if (strpos($match, "if") == 0)
			{
				// This is a if statement
				$spaces = explode(" ", $match);
				$var = ltrim(rtrim($spaces[1]));

				$new_text = "";

				if ($var == $id)
				{
					//The correct thing is here
					if ($boolean)
					{
						//Remove variable name and if keyword
						unset($spaces[0]);
						unset($spaces[1]);

						$new_text = implode(" ", $spaces);
						$new_text = rtrim($new_text);
					}

					$this->ResponseText = str_replace($actual_text, $new_text, $this->ResponseText);
					
					echo "<script> console.log(`" . ($actual_text) . "`);</script>";
				}

				
				break;
			}
		}

		return $this;
	}

	function setLoop($id, $loopable)
	{
		// Get everything between <= and =>
		preg_match_all("/<\=(.*?)=>/si", $this->ResponseText, $matches);

		for ($i=0; $i < sizeof($matches[0]); $i++) { 
			$match = $matches[1][$i];
			$actual_text = $matches[0][$i];

			$match = ltrim($match);

			if (strpos($match, "foreach") == 0)
			{
				// This is a foreach statement
				$spaces = explode(" ", $match);
				$var = ltrim(rtrim($spaces[1]));

				$new_text = "";
				$final_text = "";

				if ($var == "$" . $id)
				{
					// This is the loop
					unset($spaces[0]);
					unset($spaces[1]);

					$new_text = implode(" ", $spaces);
					$new_text = rtrim($new_text);

					foreach ($loopable as $loop)
					{	
						$le_text = $new_text;
						preg_match_all("/{{(.*?)}}/si", $new_text, $variable_matches);

						for ($j=0; $j < sizeof($variable_matches[0]); $j++) { 
							$variable_match = $variable_matches[1][$j];
							$actual_match_var = $variable_matches[0][$j];

							if (ltrim(rtrim($variable_match)) == "$" . $id)
							{
								// this is the editing variable
								$le_text = str_replace($actual_match_var, strval($loop), $le_text);
								
							}
						}

						$final_text = $final_text . " " . $le_text;


					}

					$this->ResponseText = str_replace($actual_text, $final_text ,$this->ResponseText);
				}
			}
		}
		return $this;
	}
}

 