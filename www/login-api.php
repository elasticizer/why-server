<?php

require './arranger.php';

header('Allow: POST');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	respond(405, [
		'summary' => '請求不正確',
		'message' => '請再試一次。'
	]);
}

if (!isset($_POST['username'])) {
	respond(400, [
		'summary' => '欄位未提供',
		'message' => '請再試一次。'
	]);
}

$statement = connect()->prepare(
	'SELECT * FROM Staff WHERE Username = ? OR `E-mail` = ?'
);
$statement->execute([$_POST['username'], $_POST['username']]);

$row = $statement->fetch();

if (!$row) {
	respond(401, [
		'code' => '0001',
		'summary' => '帳戶不存在',
		'message' => '請再試一次。'
	]);
}

if (isset($row['WhenDeactivated'])) {
	respond(403, [
		'code' => '0005',
		'summary' => '帳戶已停用',
		'message' => '請聯絡支援團隊尋求協助。'
	]);
}

if (is_null($row['PasswordHash'])) {
	pclose(
		popen(
			sprintf(
				'sh -c "php ./mailer.php %s %s &"',
				$row['E-mail'],
				$_SESSION['token'] = bin2hex(random_bytes(16))
			),
			'r'
		)
	);

	$_SESSION['username'] = $row['Username'];

	respond(200, [
		'code' => '0003',
		'summary' => '帳戶未啟用',
		'message' => '請前往電子信箱確認一封標題名為「帳戶啟用」的信件，並按照指示啟用帳戶。'
	]);
}

if (!isset($_POST['password'])) {
	respond(200, [
		'code' => '0002',
		'summary' => '帳戶已啟用',
		'message' => '請輸入密碼。'
	]);
}

if (!password_verify($_POST['password'], $row['PasswordHash'])) {
	respond(401, [
		'code' => '0004',
		'summary' => '密碼不正確',
		'message' => '請再試一次。'
	]);
}

$_SESSION['sn'] = $row['SN'];

respond(200, [
	'code' => '0000'
]);
