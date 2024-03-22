<?php

$db = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . '/../why.db');

connect()->exec('PRAGMA FOREIGN_KEYS=OFF');

function connect(): PDO {
	global $db;
	static $connection;

	return $connection ??= new PDO(
		"sqlite:{$db}",
		null,
		null,
		// [PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT]
	);
}

function write($table, $data) {
	$keys = array_keys($data);
	$i = array_search(0, array_keys($data));
	$data = array_filter($data, 'is_scalar');
	$keys = array_chunk($keys, $i)[0];

	$stmt = sprintf(
		'INSERT INTO `%s` (`%s`) VALUES (%s) ON CONFLICT (`%s`) DO NOTHING',
		$table,
		implode('`,`', array_keys($data)),
		implode(',', array_fill(0, count($data), '?')),
		implode('`,`', $keys)
	);

	connect()
		->prepare($stmt)
		->execute(array_values($data));

	$stmt = sprintf(
		'SELECT * FROM `%s` WHERE `%s` = ?',
		$table,
		array_key_first($data)
	);

	($sth = connect()->prepare($stmt))
		->execute([$data[array_key_first($data)]]);

	return $sth->fetchColumn();
}
