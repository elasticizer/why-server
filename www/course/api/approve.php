<?php
include __DIR__ . '/../../arranger.php';
$courseID = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;
$page = $_GET['page'] ?? 1;
$orderValue = $_GET['orderValue'] ?? 'SN ASC';
$limitPerpage = $_GET['limitPerpage'] ?? 5;

if (empty($courseID)) {
	header('Location:./index.php');
}
$r = connect()->query("SELECT * FROM Course WHERE SN=$courseID")->fetch();
if (empty($r)) {
	header('Location:./index.php');
	exit;
}
connect()->query("UPDATE `Course` SET ApproverSN = 2 where SN=$courseID");


$backTo = "?page=$page&orderValue=$orderValue&limitPerpage=$limitPerpage";
if (!empty($_SERVER['HTTP_REFERER'])) {
	$REFERER = explode('?', $_SERVER['HTTP_REFERER']);
	$backTo = $REFERER[0] . $backTo;
}
if (strpos($_SERVER['HTTP_REFERER'], 'edit') !== false) {
	header("Location: $backTo" . '&courseID=' . $courseID);
} else {
	header("Location: $backTo");
}
