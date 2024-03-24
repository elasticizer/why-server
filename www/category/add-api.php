<?php

require '../arranger.php';

header('Location: index.php');

if (isset($_POST['identifier'], $_POST['name'], $_POST['intro'])) {
	$statement = connect()->prepare("INSERT INTO `Category` (`Identifier`, `Name`, `Intro`, `Implicit`, `ParentSN`, `CreatorSN`) VALUES (?, ?, ?, ?, ?, ?)");
	$statement->execute([$_POST['identifier'], $_POST['name'], $_POST['intro'], $_POST['checkbox'] ?? 0, $_POST['parent'], $_SESSION['sn']]);
}
