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