<?php
$layout = './layout/layout.php';
require '../arranger.php';

header('Location: view.php');




if (!empty($_POST['Title'])) {
	$statement = connect()->prepare("INSERT INTO `Article` (`Identifier`, `Title`, `Content`, `AuthorSN`) VALUES (?, ?, ?, 1)");
	$statement->execute([$_POST['Identifier'], $_POST['Title'], $_POST['Content']]);
}