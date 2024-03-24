<?php
include __DIR__ . '/../../arranger.php';
$page = $_GET['page'] ?? 1;
$orderValue = $_GET['orderValue'] ?? 'SN ASC';
$limitPerpage = $_GET['limitPerpage'] ?? 10;

$courseID = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;

connect()->query('DELETE FROM `course` WHERE SN = '.$courseID);

$backTo = "../index.php?page=$page&orderValue=$orderValue&limitPerpage=$limitPerpage?&d=1";

header("Location: $backTo");
