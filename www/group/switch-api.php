<?php

require '../arranger.php';

if (!isset($_GET['sn'])) {
	respond(400, [
		'code' => '0001',
		'summary' => '欄位未提供',
		'message' => '請再試一次。'
	]);
}

$sn = is_numeric($_GET['sn']) ? intval($_GET['sn']) : null;

$statement = connect()->prepare(
	'SELECT * FROM `Group` WHERE SN = ?'
);

$statement->execute([$sn]);

$row = $statement->fetch();
$beingImplicit = $row['Implicit'] === 0;

connect()->prepare(
	sprintf(
		'UPDATE `Group` SET Implicit = %s WHERE SN = ?',
		$beingImplicit ? 1 : 0
	)
)->execute([$sn]);

respond(200, [
	'code' => '0000',
	'summary' => $beingImplicit ? '群組已隱藏' : '群組已公開',
	'message' => sprintf(
		'%s %s 群組。',
		$beingImplicit ? '已隱藏' : '已公開',
		$row['Name']
	)
]);
