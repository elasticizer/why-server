<?php
$layout = './layout/layout.php';
require '../arranger.php';

// header('Location: view.php');




$statement = connect()->prepare("UPDATE `ArticleTag` SET `ArticleSN`=?, `TagSN`=? WHERE `ArticleSN`=?");
$statement->execute([$_POST['ArticleSN'], $_POST['TagSN'], $_POST['ArticleSN']]);
