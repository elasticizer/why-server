<?php

require '../arranger.php';

header('Location: ./');

if (
	!isset($_POST['sn'])
	|| !is_numeric($_POST['sn'])
	|| !isset($_POST['identifier'], $_POST['name'])
) {
	exit;
}

connect()->prepare(
	<<< 'END'
		UPDATE `Group`
		SET Identifier = ?,
			Name = ?,
			Description = ?,
			Implicit = ?,
			WhenLastEdited = CURRENT_TIMESTAMP
		WHERE SN = ?
		END
)->execute([
	$_POST['identifier'],
	$_POST['name'],
	$_POST['description'] ?? null,
	$_POST['implicit'] ?? 0,
	$_POST['sn']
]);

if (
	!isset($_POST['permissions'])
	|| !is_array($_POST['permissions'])
) {
	exit;
}

connect()->prepare(
	'DELETE FROM GroupPermission WHERE GroupSN = ?'
)->execute([$_POST['sn']]);

foreach ($_POST['permissions'] as $permission) {
	connect()->prepare(
		'INSERT INTO GroupPermission (GroupSN, PermissionSN) VALUES (?, ?)'
	)->execute([$_POST['sn'], $permission]);
}
