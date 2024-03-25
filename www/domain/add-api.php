<?php
require '../arranger.php';

header('Location: index.php');

if (isset ($_POST['Identifier'], $_POST['Name'], $_POST['Intro'], $_SESSION['sn'])) {
	$statement = connect()->prepare("INSERT INTO `Domain` (`Identifier`, `Name`, `Intro`, `CreatorSN`) VALUES (?, ?, ?, ?)");
	$statement->execute([$_POST['Identifier'], $_POST['Name'], $_POST['Intro'], $_SESSION['sn']]);
}
