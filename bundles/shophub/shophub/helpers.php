<?php

function array_except($search, $array)
{
	$key = array_search($search, $array);
	if($key !== false) unset($array[$key]);

	return $array;
}