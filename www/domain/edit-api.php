<?php
$layout = './layout/layout.php';
require '../arranger.php';

$statement = connect()->prepare("UPDATE `Domain` SET `Name`=?,`Intro`=? WHERE `SN`=?");
$statement->execute([$_POST['Name'], $_POST['Intro'], $_GET['sn']]);

header('Location: index.php');
