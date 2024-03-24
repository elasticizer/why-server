<?php

require '../arranger.php';

header('Location: ./');

if (!isset($_POST['email'], $_POST['firstname'], $_POST['lastname'])) {
	exit;
}

connect()->prepare(
	<<< 'END'
		INSERT INTO Staff (
			Username, `E-mail`, FirstName, LastName, CreatorSN
		) VALUES (
			?, ?, ?, ?, ?
		)
		END
)->execute([
	$_POST['email'],
	$_POST['email'],
	$_POST['firstname'],
	$_POST['lastname'],
	$_SESSION['sn']
]);

$sn = connect()->lastInsertId();

if (
	!isset($_POST['groups'])
	|| !is_array($_POST['groups'])
) {
	exit;
}

foreach ($_POST['groups'] as $group) {
	connect()->prepare(
		'INSERT INTO StaffGroup (StaffSN, GroupSN) VALUES (?, ?)'
	)->execute([$sn, $group]);
}
