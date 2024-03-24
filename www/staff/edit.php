<?php

require '../arranger.php';

header('Location: ./');

if (
	!isset($_POST['sn'])
	|| !is_numeric($_POST['sn'])
	|| !isset($_POST['email'], $_POST['firstname'], $_POST['lastname'])
) {
	exit;
}

connect()->prepare(
	<<< 'END'
		UPDATE Staff
		SET Username = ?,
			`E-mail` = ?,
			FirstName = ?,
			LastName = ?
		WHERE SN = ?
		END
)->execute([
	$_POST['email'],
	$_POST['email'],
	$_POST['firstname'],
	$_POST['lastname'],
	$_POST['sn']
]);

if (
	!isset($_POST['groups'])
	|| !is_array($_POST['groups'])
) {
	exit;
}

connect()->prepare(
	'DELETE FROM StaffGroup WHERE StaffSN = ?'
)->execute([$_POST['sn']]);

foreach ($_POST['groups'] as $group) {
	connect()->prepare(
		'INSERT INTO StaffGroup (StaffSN, GroupSN) VALUES (?, ?)'
	)->execute([$_POST['sn'], $group]);
}
