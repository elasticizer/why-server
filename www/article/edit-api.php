<?php
$layout = './layout/layout.php';
require '../arranger.php';

header('Location: view.php');




$statement = connect()->prepare("UPDATE `Article` SET `Identifier`=?,`Title`=?,`Content`=?,`AuthorSN`=1 WHERE `SN`=?");
$statement->execute([$_POST['Identifier'], $_POST['Title'], $_POST['Content'], $_POST['sn']]);
