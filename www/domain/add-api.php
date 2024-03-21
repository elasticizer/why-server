<?php
require '../arranger.php';

header('Location: index.php');

if (isset ($_POST['Name'])) {
	$statement = connect()->prepare("INSERT INTO `Domain` (`Name`, `Intro`, `CreatorSN`) VALUES (?, ?, 1)");
	$statement->execute([$_POST['Name'], $_POST['Intro']]);
}
