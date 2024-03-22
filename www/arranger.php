<?php

ob_start();

function arrange() {
	global $title;
	global $layout;

	$slot = ob_get_clean();

	if (empty($layout)) {
		echo $slot;
		return;
	}

	include find($layout);
}

function find(string $path = '') {
	return realpath("{$_SERVER['DOCUMENT_ROOT']}/{$path}");
}

function connect(string $database = '../data/why.db') {
	static $connection;

	$database = find($database);

	return $connection ??= new PDO("sqlite:{$database}");
}

function localize(string $datetime) {
	return date_create($datetime, new DateTimeZone('UTC'))
		->setTimezone(new DateTimeZone('Asia/Taipei'))
		->format('Y-m-d H:i:s');
}

register_shutdown_function('arrange');
