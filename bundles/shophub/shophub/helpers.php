<?php

function is_uuid($uuid)
{
     return (bool) preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $uuid);
}

function array_pluck($models, $value, $key = null)
{
	$result = array();
	$i = 0;
	foreach ($models as $model)
	{
		$result[is_null($key) ? $model->{$model::$key} : ($key instanceof Closure ? $key($model) : ($key == '' ? $i : $model->$key))] = $value instanceof Closure ? $value($model) : $model->$value;
		$i++;
	}

	return $result;
}

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