<?php
$layout = './layout/layout.php';
require '../arranger.php';

header('Location: index.php');




$statement = connect()->prepare("UPDATE `ArticleCategory` SET `ArticleSN`=?, `CategorySN`=? WHERE `ArticleSN`=?");
$statement->execute([$_POST['ArticleSN'], $_POST['CategorySN'], $_GET['ArticleSN']]);
