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
		[PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT]
	);
}

function write($table, $data) {
	$i = array_search(0, array_keys($data));
	$data = array_filter($data, 'is_scalar');
	$list = [
		array_chunk(array_keys($data), $i)[0],
		array_chunk($data, $i)[0]
	];
	$stmt = sprintf(
		'INSERT OR FAIL INTO `%s` (`%s`) VALUES (%s)',
		$table,
		implode('`,`', array_keys($data)),
		implode(',', array_fill(0, count($data), '?')),
		implode('`,`', $list[0])
	);

	connect()
		->prepare($stmt)
		?->execute(array_map('trim', array_values($data)));

	$stmt = sprintf(
		'SELECT * FROM `%s` WHERE `%s` = ?',
		$table,
		implode('` = ? AND `', $list[0])
	);

	($sth = connect()->prepare($stmt))
		->execute($list[1]);

	return $sth->fetchColumn();
}
