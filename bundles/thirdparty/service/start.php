<?php

Autoloader::map(array(
	'Service' => __DIR__ . DS . 'service' . EXT
));

function prettify_json($json) {
	$result = '';
	$pos = 0;
	$strLen = strlen($json);
	$indentStr = "\t";
	$newLine = "\n";
	$prevChar = '';
	$outOfQuotes = true;

	for($i = 0; $i <= $strLen; $i++)
	{
		// Grab the next character in the string
		$char = substr($json, $i, 1);

		// Are we inside a quoted string?
		if($char == '"' && $prevChar != '\\')
		{
			$outOfQuotes = !$outOfQuotes;
		}
		// If this character is the end of an element, 
		// output a new line and indent the next line
		else if(($char == '}' || $char == ']') && $outOfQuotes)
		{
			$result .= $newLine;
			$pos --;
			for ($j=0; $j<$pos; $j++)
			{
				$result .= $indentStr;
			}
		}
		// Add the character to the result string
		$result .= $char;

		// If the last character was the beginning of an element, 
		// output a new line and indent the next line
		if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes)
		{
			$result .= $newLine;
			if ($char == '{' || $char == '[')
			{
				$pos ++;
			}
			
			for ($j = 0; $j < $pos; $j++)
			{
				$result .= $indentStr;
			}
		}

		$prevChar = $char;
	}

	return $result;
}


/**
 * Register a JSON service
 * 
 * @param  Service $service
 * @return string
 */
Service::register('json', function(Service $service)
{
	$service->header('Content-Type', 'application/json');

	if(Input::get('pretty'))
	{
		return prettify_json(json_encode($service->data));
	}
	
	return json_encode($service->data);
});