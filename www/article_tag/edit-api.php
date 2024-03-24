<?php
$layout = './layout/layout.php';
require '../arranger.php';

header('Location: index.php');




$statement = connect()->prepare("UPDATE `ArticleTag` SET `ArticleSN`=?, `TagSN`=? WHERE `ArticleSN`=?");
$statement->execute([$_POST['ArticleSN'], $_POST['TagSN'], $_GET['ArticleSN']]);
