<?php

require '../arranger.php';

$sn = $_GET['sn'] ?? '0';
$table = '`Order`';
$statement = connect()->prepare("DELETE FROM {$table} WHERE SN = ?");

$statement->execute([$sn]);

header('Location: index.php');
