<?php

require '../arranger.php';

header('Location: ./');

if (
	!isset($_GET['sn'])
	|| !is_numeric($_GET['sn'])
) {
	exit;
}

connect()->prepare(
	'DELETE FROM `Group` WHERE SN = ?'
)->execute([$_GET['sn']]);

connect()->prepare(
	'DELETE FROM GroupPermission WHERE GroupSN = ?'
)->execute([$_POST['sn']]);
