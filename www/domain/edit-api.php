<?php
$layout = './layout/layout.php';
require '../arranger.php';

$statement = connect()->prepare("UPDATE `Domain` SET `Name`=?,`Intro`=?,`CreatorSN`=1 WHERE `SN`=?");
$statement->execute([$_POST['Name'], $_POST['Intro'], $_GET['sn']]);

header('Location: view.php');
