<?php

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