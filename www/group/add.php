<?php

require '../arranger.php';

header('Location: ./');

if (!isset($_POST['identifier'], $_POST['name'])) {
	exit;
}

connect()->prepare(
	<<<'END'
		INSERT INTO `Group` (
			Identifier, Name, Description, Implicit, CreatorSN
		) VALUES (
			?, ?, ?, ?, ?
		)
		END
)->execute([
	$_POST['identifier'],
	$_POST['name'],
	$_POST['description'] ?? null,
	$_POST['implicit'] ?? 0,
	$_SESSION['sn']
]);

$sn = connect()->lastInsertId();

if (
	!isset($_POST['permissions'])
	|| !is_array($_POST['permissions'])
) {
	exit;
}

foreach ($_POST['permissions'] as $permission) {
	connect()->prepare(
		'INSERT INTO GroupPermission (GroupSN, PermissionSN) VALUES (?, ?)'
	)->execute([$sn, $permission]);
}
