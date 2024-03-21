<?php

require '../arranger.php';

header('Location: index.php');

$statement = connect()->prepare("UPDATE `Category` SET `Name`=?,`Intro`=?,`Implicit`=?, `ParentSN`=?,`WhenLastEdited`=CURRENT_TIMESTAMP WHERE `SN`=?");
$statement->execute([$_POST['name'], $_POST['intro'], $_POST['checkbox'] ?? 0, $_POST['parent'], $_GET['sn']]);
