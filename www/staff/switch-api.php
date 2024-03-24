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

if ($sn === $_SESSION['sn']) {
	respond(403, [
		'code' => '0002',
		'summary' => '別開玩笑了',
		'message' => '您不能停用自己的帳戶。'
	]);
}

$statement = connect()->prepare(
	'SELECT * FROM Staff WHERE SN = ?'
);

$statement->execute([$sn]);

$row = $statement->fetch();
$deactivating = is_null($row['WhenDeactivated']);

connect()->prepare(
	sprintf(
		'UPDATE Staff SET WhenDeactivated = %s WHERE SN = ?',
		$deactivating ? 'CURRENT_TIMESTAMP' : 'NULL'
	)
)->execute([$sn]);

respond(200, [
	'code' => '0000',
	'summary' => $deactivating ? '帳戶已停用' : '帳戶已啟用',
	'message' => sprintf(
		'%s %s 進入系統。',
		$deactivating ? '已禁止' : '已允許',
		$row['FirstName']
	)
]);
