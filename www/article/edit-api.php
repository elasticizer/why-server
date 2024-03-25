<?php


$layout = './layout/layout.php';
require '../arranger.php';


header('Location: index.php');

$statement = connect()->prepare("UPDATE `Article` SET `Identifier`=?,`Title`=?,`Content`=?,`WhenLastEdited`=CURRENT_TIMESTAMP,`AuthorSN`=1 WHERE `SN`=?");
$statement->execute([$_POST['Identifier'], $_POST['Title'], $_POST['Content'], $_GET['sn']]);
