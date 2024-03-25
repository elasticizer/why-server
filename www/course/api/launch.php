<?php
include __DIR__ . '/../../arranger.php';

$courseID = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;
$page = $_GET['page'] ?? 1;
$orderValue = $_GET['orderValue'] ?? 'SN ASC';
$limitPerpage = $_GET['limitPerpage'] ?? 10;

if (empty($courseID)) {
	header('Location:./index.php');
}
$r = connect()->query("SELECT * FROM course WHERE SN=$courseID")->fetch();
if (empty($r)) {
	header('Location:./index.php');
	exit;
}
$row = connect()->query("select WhenLaunched from Course where SN=$courseID")->fetch(PDO::FETCH_NUM);

$WhenLaunched = date('Y-m-d');

if (!empty($row[0])) {
	connect()->query("update Course set WhenLaunched = null where SN=$courseID");
} else {
	connect()->query("update Course set WhenLaunched = $WhenLaunched where SN = $courseID");
}

$backTo = "?page=$page&orderValue=$orderValue&limitPerpage=$limitPerpage";
if (!empty($_SERVER['HTTP_REFERER'])) {
	$REFERER = explode('?',$_SERVER['HTTP_REFERER']);
    $backTo = $REFERER[0].$backTo;
}
if (strpos($_SERVER['HTTP_REFERER'], 'edit') !== false) {
	header("Location: $backTo" . '&courseID=' . $courseID);
} else {
	header("Location: $backTo");
}
