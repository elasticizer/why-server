<?php
$layout = './layout/layout.php';
require '../arranger.php';

$statement = connect()->prepare("UPDATE `Domain` SET `Name`=?,`Intro`=?,`CreatorSN`=? WHERE `SN`=?");
$statement->execute([$_POST['Name'], $_POST['Intro'], $_SESSION['sn'], $_GET['sn']]);

header('Location: index.php');
