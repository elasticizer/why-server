<?php
include __DIR__ . '/../../arranger.php';
$orderValue = $_GET['orderValue'];
$limitPerpage = $_GET['limitPerpage'];
$courseID = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;
if (empty($courseID)) {
	header('Location:./view.php');
}
$r = connect()->query("SELECT * FROM course WHERE SN=$courseID")->fetch();
if (empty($r)) {
	header('Location:./view.php');
	exit;
}
$row = connect()->query("select WhenLaunched from Course where SN=$courseID")->fetch(PDO::FETCH_NUM);

$WhenLaunched = date('Y-m-d');

if (!empty($row[0])) {
	connect()->query("update Course set WhenLaunched = null where SN=$courseID");
} else {
	connect()->query("update Course set WhenLaunched = $WhenLaunched where SN = $courseID");
}

$backTo = './edit.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
	$backTo = $_SERVER['HTTP_REFERER'];
}
header("Location: $backTo");
