<?php
$layout = './layout/layout.php';
require '../arranger.php';

header('Location: view.php');


if (!empty($_GET['ArticleSN'])) {
	$statement = connect()->prepare("DELETE FROM ArticleTag WHERE ArticleSN=?");
	$statement->execute([$_GET['ArticleSN']]);
}
