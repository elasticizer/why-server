<?php
$layout = './layout/layout.php';
require '../arranger.php';

header('Location: index.php');



if (!empty($_POST['ArticleSN'])) {
	$statement = connect()->prepare("INSERT INTO `ArticleCategory`(`ArticleSN`,`CategorySN`)VALUES(?,?)");
	$statement->execute([$_POST['ArticleSN'], $_POST['CategorySN']]);
}