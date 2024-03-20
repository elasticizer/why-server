<?php
$layout = './layout/layout.php';
require '../arranger.php';

header('Location: index.php');


$sn = isset($_GET['sn']) ? intval($_GET['sn']) : 0;

if (!empty($sn)) {
	$statement = connect()->prepare("DELETE FROM Article WHERE SN=?");
	$statement->execute([$_GET['sn']]);
}
