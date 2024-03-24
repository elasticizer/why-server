<?php
$layout = './layout/layout.php';
require '../arranger.php';

session_start();


header('Location: index.php');




if (!empty($_POST['Title'])) {
	$statement = connect()->prepare("INSERT INTO `Article` (`Identifier`, `Title`, `Content`, `AuthorSN`) VALUES (?, ?, ?, ?)");
	$statement->execute([$_POST['Identifier'], $_POST['Title'], $POST['Content'], $_SESSION['sn']]);
}
