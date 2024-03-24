<?php

ob_start();
session_start();

$sn = $_SESSION['sn'] ?? false;
$self = $_SERVER['PHP_SELF'];

if (
	!isset($_SESSION['sn'])
	&& str_ends_with($self, 'api.php')
	&& $self !== '/login-api.php'
) {
	http_response_code(403);

	respond(403, [
		'code' => '9999',
		'summary' => '身分未驗證',
		'message' => '請登入帳戶。'
	]);

	exit;
}

if (
	!isset($_SESSION['sn'])
	&& !in_array(
		$self,
		['/activate.php', '/login.php', '/login-api.php', '/setup.php']
	)
) {
	header('Location: /login.php');

	exit;
}

function find(string $path = '') {
	return realpath("{$_SERVER['DOCUMENT_ROOT']}/{$path}");
}

function authorize(string $code) {
	static $roll;

	if (!isset($roll)) {
		$statement = connect()->prepare(
			'SELECT * FROM StaffPermission WHERE SN = ?'
		);

		$statement->execute([$_SESSION['sn']]);

		$roll = array_column(
			$statement->fetchAll(PDO::FETCH_ASSOC),
			'Code'
		);
	}

	return in_array($code, $roll);
}

function arrange() {
	global $title;
	global $layout;

	$slot = ob_get_clean();

	if (!isset($layout)) {
		echo $slot;
		return;
	}

	include find($layout);
}

function connect(string $database = '../data/why.db') {
	static $connection;

	$database = find($database);

	return $connection ??= new PDO("sqlite:{$database}");
}

function respond(int $code, array $data = null) {
	http_response_code($code);
	header('Content-Type: application/json');

	if (isset($data)) {
		echo json_encode($data);
	}

	exit;
}

function localize(string $datetime) {
	return date_create($datetime, new DateTimeZone('UTC'))
		->setTimezone(new DateTimeZone('Asia/Taipei'))
		->format('Y-m-d H:i:s');
}

register_shutdown_function('arrange');
