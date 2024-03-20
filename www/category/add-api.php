<?php
$layout = './layout/layout.php';
require '../arranger.php';

header('Location: index.php');

if (!empty($_POST['name'])) {
	$statement = connect()->prepare("INSERT INTO `Category` (`Name`, `Intro`,
	`Implicit`,`ParentSN`,`CreatorSN`) VALUES (?, ?, ?, ?, 1)");
	$statement->execute([$_POST['name'], $_POST['intro'], $_POST['checkbox'] === 'on' ? 1 : 0, $_POST['parent'],]);
}
