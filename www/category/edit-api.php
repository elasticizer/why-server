<?php
$layout = './layout/layout.php';
require '../arranger.php';

header('Location: view.php');

$statement = connect()->prepare("UPDATE `Category` SET `Name`=?,`Intro`=?,`Implicit`=? WHERE `SN`=?");
$statement->execute([$_POST['name'], $_POST['intro'],$_POST['checkbox'] === 'on' ? 1 : 0, $_POST['sn']]);
